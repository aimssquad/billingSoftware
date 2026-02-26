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
            ->where('status', 'unpaid')
            ->firstOrFail();

        $gateway = $this->paymentService->getActiveGateway($oid);

        switch ($gateway->gateway) {

            case 'stripe':
                return $this->payWithStripe($gateway, $invoice);

            case 'razorpay':
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
        $secret = Crypt::decryptString($gateway->secret_key);
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
}