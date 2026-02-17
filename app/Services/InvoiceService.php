<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function __construct(
        protected UsageTrackingService $usageTrackingService,
        protected SubscriptionService $subscriptionService
    ) {}

    /**
     * Create invoice with items. Enforces invoice limit from subscription.
     */
    public function create(Organization $organization, array $data): Invoice
    {
        if (! $this->usageTrackingService->canCreateInvoice($organization)) {
            throw new \RuntimeException(
                'Invoice limit reached for this month. Upgrade your plan or wait for next billing cycle.'
            );
        }

        $activeSub = $this->subscriptionService->getActiveSubscription($organization);
        if (! $activeSub) {
            throw new \RuntimeException('No active subscription. Please subscribe to a plan.');
        }

        return DB::transaction(function () use ($organization, $data, $activeSub) {
            $invoice = Invoice::create([
                'organization_id' => $organization->id,
                'customer_id' => $data['customer_id'],
                'invoice_no' => $data['invoice_no'],
                'invoice_type' => $data['invoice_type'] ?? 'gst',
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'] ?? null,
                'subtotal' => $data['subtotal'] ?? 0,
                'tax_amount' => $data['tax_amount'] ?? 0,
                'discount_amount' => $data['discount_amount'] ?? 0,
                'total_amount' => $data['total_amount'] ?? 0,
                'status' => $data['status'] ?? 'draft',
            ]);

            if (! empty($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'item_name' => $item['item_name'],
                        'quantity' => $item['quantity'] ?? 1,
                        'price' => $item['price'] ?? 0,
                        'tax_percent' => $item['tax_percent'] ?? 0,
                        'tax_amount' => $item['tax_amount'] ?? 0,
                        'total_amount' => $item['total_amount'] ?? 0,
                    ]);
                }
            }

            $this->usageTrackingService->record(
                $organization->id,
                $activeSub->id,
                'invoice',
                $invoice->id
            );

            return $invoice->load('customer', 'items');
        });
    }

    /**
     * Update invoice (no new usage record).
     */
    public function update(Invoice $invoice, array $data): Invoice
    {
        $invoice->update($data);
        if (! empty($data['items']) && is_array($data['items'])) {
            $invoice->items()->delete();
            foreach ($data['items'] as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'] ?? 1,
                    'price' => $item['price'] ?? 0,
                    'tax_percent' => $item['tax_percent'] ?? 0,
                    'tax_amount' => $item['tax_amount'] ?? 0,
                    'total_amount' => $item['total_amount'] ?? 0,
                ]);
            }
        }
        return $invoice->fresh(['customer', 'items']);
    }
}
