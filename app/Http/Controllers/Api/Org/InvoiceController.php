<?php

namespace App\Http\Controllers\Api\Org;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Mail\InvoiceSentMail;
use App\Services\TenantMailService;
use App\Services\PaymentGatewayService;
use Razorpay\Api\Api;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {   
        $organizationId = $request->attributes->get('organization_id');
        $invoices = Invoice::where('organization_id', $organizationId)
            ->with('customer')
            ->latest()
            ->paginate(15);
        return InvoiceResource::collection($invoices);
    }

    public function invoiceList(Request $request)
    {
        $organizationId = $request->attributes->get('organization_id');
        $invoices = Invoice::with(['organization', 'customer', 'items'])->where('organization_id', $organizationId)->get();
        return response()->json(['data' => $invoices]);
    }

    public function store(Request $request): JsonResponse
    {
        $organization = $request->attributes->get('organization');
        $organizationId = $organization->id;
        $valid = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_no' => 'required|string|max:50',
            'invoice_type' => 'in:gst,non_gst',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'subtotal' => 'numeric',
            'tax_amount' => 'numeric',
            'discount_amount' => 'numeric',
            'total_amount' => 'numeric',
            'status' => 'in:draft,sent,partial,paid,overdue',
            'items' => 'nullable|array',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'numeric',
            'items.*.price' => 'numeric',
            'items.*.tax_percent' => 'numeric',
            'items.*.tax_amount' => 'numeric',
            'items.*.total_amount' => 'numeric',
        ]);
        $valid['customer_id'] = (int) $valid['customer_id'];
        if (\App\Models\Customer::where('id', $valid['customer_id'])->where('organization_id', $organizationId)->doesntExist()) {
            return response()->json(['message' => 'Customer not found or does not belong to your organization.'], 422);
        }
        try {
            $invoice = app(InvoiceService::class)->create($organization, $valid);
            return (new InvoiceResource($invoice))->response()->setStatusCode(201);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show(Request $request, Invoice $invoice)
    {   
        $this->ensureSameOrg($request, $invoice);
        $invoice->load('customer', 'items','organization');
        //dd($invoice);
        return new InvoiceResource($invoice);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $this->ensureSameOrg($request, $invoice);
        $valid = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'invoice_no' => 'sometimes|string|max:50',
            'invoice_type' => 'in:gst,non_gst',
            'invoice_date' => 'date',
            'due_date' => 'nullable|date',
            'subtotal' => 'numeric',
            'tax_amount' => 'numeric',
            'discount_amount' => 'numeric',
            'total_amount' => 'numeric',
            'status' => 'in:draft,sent,partial,paid,overdue',
            'items' => 'nullable|array',
        ]);
        $invoice = app(InvoiceService::class)->update($invoice, $valid);
        return new InvoiceResource($invoice);
    }

    public function destroy(Request $request, Invoice $invoice)
    {
        $this->ensureSameOrg($request, $invoice);
        $invoice->delete();
        return response()->json(null, 204);
    }

    public function items(Request $request, Invoice $invoice)
    {
        $this->ensureSameOrg($request, $invoice);
        return response()->json(['data' => $invoice->items]);
    }

    private function ensureSameOrg(Request $request, Invoice $invoice): void
    {
        if ($invoice->organization_id !== $request->attributes->get('organization_id')) {
            abort(404);
        }
    }

    // public function sendEmail(Request $request, Invoice $invoice)
    // {   
    //     $this->ensureSameOrg($request, $invoice);
    //     //dd('okk');
    //     //$data = $invoice->load('customer', 'items');
    //     $invoice->load(
    //         'customer',
    //         'items',
    //         'organization.invoiceSetting.template',
    //         'organization.defaultBankAccount'
    //     );
    //     //dd($invoice);
    //     $organizationId = $request->attributes->get('organization_id');

    //     try {
    //         TenantMailService::send(
    //             $organizationId,
    //             $invoice,
    //             $invoice->customer->email
    //         );

    //         $invoice->update([
    //             'status' => 'sent'
    //         ]);

    //         return response()->json([
    //             'message' => 'Invoice email sent successfully'
    //         ]);

    //     } catch (\Exception $e) {

    //         return response()->json([
    //             'message' => 'Failed to send invoice email',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    // public function sendEmail(Request $request, Invoice $invoice)
    // {
    //     $this->ensureSameOrg($request, $invoice);

    //     $invoice->load(
    //         'customer',
    //         'items',
    //         'organization.invoiceSetting.template',
    //         'organization.defaultBankAccount'
    //     );

    //     $organizationId = $request->attributes->get('organization_id');

    //     try {

    //         // ✅ Get active gateway
    //         $gateway = app(PaymentGatewayService::class)
    //             ->getActiveGateway($organizationId);

    //         $paymentLink = null;

    //         // ✅ Only for Razorpay
    //         if ($gateway && $gateway->isRazorpay()) {

    //             $razor = new Api(
    //                 $gateway->public_key,
    //                 $gateway->secret_key
    //             );

    //             $link = $razor->paymentLink->create([
    //                 'amount' => $invoice->total_amount * 100,
    //                 'currency' => 'INR',
    //                 'description' => 'Invoice #' . $invoice->invoice_no,

    //                 'customer' => [
    //                     'name' => $invoice->customer->name,
    //                     'email' => $invoice->customer->email,
    //                     'contact' => $invoice->customer->phone,
    //                 ]
    //             ]);

    //             $paymentLink = $link['short_url'];

    //             // ✅ Save link in DB
    //             $invoice->update([
    //                 'payment_link' => $paymentLink
    //             ]);
    //         }

    //         // ✅ Send email with link
    //         TenantMailService::send(
    //             $organizationId,
    //             $invoice,
    //             $invoice->customer->email,
    //             $paymentLink
    //         );

    //         $invoice->update(['status' => 'sent']);

    //         return response()->json([
    //             'message' => 'Invoice email sent successfully'
    //         ]);

    //     } catch (\Exception $e) {

    //         return response()->json([
    //             'message' => 'Failed to send invoice email',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function sendEmail(Request $request, Invoice $invoice)
    {
        $this->ensureSameOrg($request, $invoice);

        $invoice->load(
            'customer',
            'items',
            'organization.invoiceSetting.template',
            'organization.defaultBankAccount'
        );

        $organizationId = $request->attributes->get('organization_id');

        try {

            $gateway = app(PaymentGatewayService::class)
                ->getActiveGateway($organizationId);

            $paymentLink = $invoice->payment_link ?? null;

            if (!$paymentLink && $gateway && $gateway->isRazorpay()) {

                $razor = new Api(
                    $gateway->public_key,
                    $gateway->secret_key
                );

                $link = $razor->paymentLink->create([
                    'amount' => $invoice->total_amount * 100,
                    'currency' => 'INR',
                    'description' => 'Invoice #' . $invoice->invoice_no,

                    'customer' => [
                        'name' => $invoice->customer->name,
                        'email' => $invoice->customer->email,
                        'contact' => $invoice->customer->phone,
                    ],

                    'callback_url' => config('app.frontend_url') . '/payment-success',
                    'callback_method' => 'get'
                ]);

                $paymentLink = $link['short_url'];

                // ✅ Save payment
                Payment::create([
                    'organization_id' => $invoice->organization_id,
                    'invoice_id' => $invoice->id,
                    'gateway' => 'razorpay',
                    'transaction_id' => $link['id'], // plink_xxx
                    'amount' => $invoice->total_amount,
                    'status' => 'pending',
                    'response' => json_encode($link)
                ]);

                // ✅ Save link in invoice
                $invoice->update([
                    'payment_link' => $paymentLink,
                    'payment_link_id' => $link['id']
                ]);
            }

            TenantMailService::send(
                $organizationId,
                $invoice,
                $invoice->customer->email,
                $paymentLink
            );

            $invoice->update(['status' => 'sent']);

            return response()->json([
                'message' => 'Invoice email sent successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to send invoice email',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function orgLastInvoiceNo(Request $request)
    {
        $organizationId = $request->attributes->get('organization_id');

        $lastInvoice = Invoice::where('organization_id', $organizationId)
                        ->orderBy('id','desc')
                        ->first();

        if ($lastInvoice) {

            $lastInvoiceNo = $lastInvoice->invoice_no;

            // extract number part (last 4 digits)
            $number = (int) substr($lastInvoiceNo, -4);

            // increase number
            $number++;

            // rebuild invoice number
            $prefix = substr($lastInvoiceNo, 0, -4);

            $invoiceNo = $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);

        } else {

            $year  = date('Y');
            $month = date('m');

            $invoiceNo = 'INV' . $year . $month . '0001';
        }

        return response()->json([
            'last_invoice_no' => $invoiceNo
        ]);
    }
}
