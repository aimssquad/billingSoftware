<h2>Invoice Details</h2>

<p>Dear {{ $invoice->customer->name }},</p>

<p>
    Your invoice <strong>#{{ $invoice->invoice_no }}</strong> 
    dated {{ $invoice->invoice_date->format('d M Y') }} 
    has been generated.
</p>

<p><strong>Total Amount:</strong> ₹{{ number_format($invoice->total_amount, 2) }}</p>

<p><strong>Due Date:</strong> 
    {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : 'N/A' }}
</p>

<br>

<p>You can view your invoice using the link below:</p>

<p>
    <a href="{{ $siteUrl }}/invoices/{{ $invoice->id }}">
        View Invoice
    </a>
</p>

<br>

<p>Thank you for your business.</p>