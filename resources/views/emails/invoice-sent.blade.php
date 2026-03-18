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

@if($paymentLink)
<br>

<p>
    <a href="{{ $paymentLink }}"
       style="background:#2563eb;
              color:white;
              padding:12px 20px;
              border-radius:6px;
              text-decoration:none;
              display:inline-block;
              font-weight:bold;">
        💳 Pay Now
    </a>
</p>

@endif

<p>
    <a href="{{ $siteUrl }}/invoices/{{ $invoice->id }}">
        View Invoice
    </a>
</p>

<br>

<p>Thank you for your business.</p>