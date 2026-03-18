<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Services\PaymentGatewayService;
use App\Models\Invoice;
use App\Models\Payment;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;

use Razorpay\Api\Api;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentGatewayService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /*
    |--------------------------------------------------------------------------
    | Create Payment (Main Entry)
    |--------------------------------------------------------------------------
    */

    public function pay(Request $request)
    {   
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
        ]);
        
        $oid = $request->attributes->get('organization_id');
        
        $invoice = Invoice::where('organization_id', $oid)
            ->where('id', $request->invoice_id)
            ->where('status', 'sent')
            ->firstOrFail();
        //dd(invoice);
        $gateway = $this->paymentService->getActiveGateway($oid);
        //dd($gateway);
        switch ($gateway->gateway) {

            case 'stripe':
                return $this->payWithStripe($gateway, $invoice);

            case 'razorpay':
                //dd('okhhh');
                return $this->payWithRazorpay($gateway, $invoice);

            case 'paypal':
                return $this->payWithPaypal($gateway, $invoice);

            default:
                return response()->json(['message' => 'Gateway not supported'], 400);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STRIPE
    |--------------------------------------------------------------------------
    */

    private function payWithStripe($gateway, $invoice)
    {
        $secret = Crypt::decryptString($gateway->secret_key);
        Stripe::setApiKey($secret);

        $intent = PaymentIntent::create([
            'amount' => $invoice->total_amount * 100,
            'currency' => $invoice->currency ?? 'usd',
            'metadata' => [
                'invoice_id' => $invoice->id,
                'organization_id' => $invoice->organization_id
            ]
        ]);

        Payment::create([
            'organization_id' => $invoice->organization_id,
            'invoice_id' => $invoice->id,
            'gateway' => 'stripe',
            'transaction_id' => $intent->id,
            'amount' => $invoice->total_amount,
            'status' => 'pending',
            'response' => json_encode($intent)
        ]);

        return response()->json([
            'gateway' => 'stripe',
            'client_secret' => $intent->client_secret,
            'public_key' => $gateway->public_key
        ]);
    }

    public function stripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        $event = Webhook::constructEvent(
            $payload,
            $signature,
            config('services.stripe.webhook_secret')
        );

        if ($event->type === 'payment_intent.succeeded') {

            $intent = $event->data->object;

            $payment = Payment::where('transaction_id', $intent->id)->first();

            if ($payment) {
                DB::transaction(function () use ($payment) {
                    $payment->update(['status' => 'success']);
                    Invoice::where('id', $payment->invoice_id)
                        ->update(['status' => 'paid']);
                });
            }
        }

        return response()->json(['status' => 'success']);
    }

    /*
    |--------------------------------------------------------------------------
    | RAZORPAY
    |--------------------------------------------------------------------------
    */

    private function payWithRazorpay($gateway, $invoice)
    {
        //$secret = Crypt::decryptString($gateway->secret_key);
        $secret = $gateway->secret_key;
        //dd($secret);
        $api = new Api($gateway->public_key, $secret);

        $order = $api->order->create([
            'receipt' => 'inv_' . $invoice->id,
            'amount' => $invoice->total_amount * 100,
            'currency' => 'INR'
        ]);

        Payment::create([
            'organization_id' => $invoice->organization_id,
            'invoice_id' => $invoice->id,
            'gateway' => 'razorpay',
            'transaction_id' => $order['id'],
            'amount' => $invoice->total_amount,
            'status' => 'pending',
            'response' => json_encode($order)
        ]);

        return response()->json([
            'gateway' => 'razorpay',
            'order_id' => $order['id'],
            'key' => $gateway->public_key,
            'amount' => $invoice->total_amount * 100
        ]);
    }

    // private function payWithRazorpay($gateway, $invoice)
    // {
    //     try {

    //         dd('Gateway secret before use:', $gateway->secret_key);

    //         $api = new \Razorpay\Api\Api(
    //             $gateway->public_key,
    //             $gateway->secret_key
    //         );

    //         $order = $api->order->create([
    //             'receipt' => 'inv_' . $invoice->id,
    //             'amount' => $invoice->total_amount * 100,
    //             'currency' => 'INR'
    //         ]);

    //         dd($order);

    //     } catch (\Exception $e) {
    //         dd($e->getMessage(), $e->getFile(), $e->getLine());
    //     }
    // }

    public function razorpayWebhook(Request $request)
    {
        $data = $request->all();

        if (isset($data['event']) && $data['event'] === 'payment.captured') {

            $orderId = $data['payload']['payment']['entity']['order_id'] ?? null;

            $payment = Payment::where('transaction_id', $orderId)->first();

            if ($payment) {
                DB::transaction(function () use ($payment) {
                    $payment->update(['status' => 'success']);
                    Invoice::where('id', $payment->invoice_id)
                        ->update(['status' => 'paid']);
                });
            }
        }

        return response()->json(['status' => 'success']);
    }

    /*
    |--------------------------------------------------------------------------
    | PAYPAL
    |--------------------------------------------------------------------------
    */

    private function payWithPaypal($gateway, $invoice)
    {
        $clientId = $gateway->public_key;
        $secret = Crypt::decryptString($gateway->secret_key);

        $environment = $gateway->mode === 'live'
            ? new ProductionEnvironment($clientId, $secret)
            : new SandboxEnvironment($clientId, $secret);

        $client = new PayPalHttpClient($environment);

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');

        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => "inv_" . $invoice->id,
                "amount" => [
                    "currency_code" => $invoice->currency ?? "USD",
                    "value" => $invoice->total_amount
                ]
            ]]
        ];

        $response = $client->execute($request);

        Payment::create([
            'organization_id' => $invoice->organization_id,
            'invoice_id' => $invoice->id,
            'gateway' => 'paypal',
            'transaction_id' => $response->result->id,
            'amount' => $invoice->total_amount,
            'status' => 'pending',
            'response' => json_encode($response->result)
        ]);

        $approveLink = collect($response->result->links)
            ->where('rel', 'approve')
            ->first()->href ?? null;

        return response()->json([
            'gateway' => 'paypal',
            'order_id' => $response->result->id,
            'approve_link' => $approveLink
        ]);
    }

    public function paypalCapture(Request $request)
    {
        $request->validate([
            'order_id' => 'required'
        ]);

        $payment = Payment::where('transaction_id', $request->order_id)->firstOrFail();

        $gateway = $this->paymentService->getActiveGateway($payment->organization_id);

        $clientId = $gateway->public_key;
        $secret = Crypt::decryptString($gateway->secret_key);

        $environment = $gateway->mode === 'live'
            ? new ProductionEnvironment($clientId, $secret)
            : new SandboxEnvironment($clientId, $secret);

        $client = new PayPalHttpClient($environment);

        $captureRequest = new OrdersCaptureRequest($request->order_id);
        $captureRequest->prefer('return=representation');

        $response = $client->execute($captureRequest);

        if ($response->result->status === "COMPLETED") {

            DB::transaction(function () use ($payment) {
                $payment->update(['status' => 'success']);
                Invoice::where('id', $payment->invoice_id)
                    ->update(['status' => 'paid']);
            });
        }

        return response()->json(['status' => 'success']);
    }


    private function createRazorpayPaymentLink($gateway, $invoice)
    {
        $api = new Api(
            $gateway->public_key,
            $gateway->secret_key
        );

        $paymentLink = $api->paymentLink->create([
            'amount' => $invoice->total_amount * 100,
            'currency' => 'INR',
            'description' => 'Invoice #' . $invoice->invoice_no,

            'customer' => [
                'name' => $invoice->customer->name,
                'email' => $invoice->customer->email,
                'contact' => $invoice->customer->phone,
            ],

            'notify' => [
                'sms' => true,
                'email' => true
            ],

            'callback_url' => config('app.url') . '/payment-success',
            'callback_method' => 'get'
        ]);

        return $paymentLink;
    }
}