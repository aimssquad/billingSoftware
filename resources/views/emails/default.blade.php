<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice</title>
</head>

<body style="margin:0;padding:0;background:#f8fafc;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;padding:20px;">
<tr>
<td align="center">

<table width="650" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;border:1px solid #e2e8f0;overflow:hidden;">

<!-- Header -->
<tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
<td style="padding:30px;">
<h2 style="margin:0;color:#0f172a;">INVOICE</h2>

<p style="margin:6px 0;color:#64748b;">
Invoice #: {{ $invoice->invoice_no }}
</p>

<p style="margin:6px 0;color:#64748b;">
Invoice Date: {{ $invoice->invoice_date->format('d F Y') }}
</p>

<p style="margin:6px 0;color:#64748b;">
Due Date: {{ $invoice->due_date->format('d F Y') }}
</p>

</td>
</tr>

<!-- Customer -->
<tr>
<td style="padding:30px;border-bottom:1px solid #e2e8f0;">

<h3 style="margin-bottom:10px;color:#64748b;">Bill To</h3>

<p style="margin:4px 0;color:#0f172a;font-weight:bold;">
{{ $invoice->customer->name }}
</p>

<p style="margin:4px 0;color:#64748b;">
{{ $invoice->customer->email }}
</p>

<p style="margin:4px 0;color:#64748b;">
{{ $invoice->customer->phone }}
</p>

<p style="margin:4px 0;color:#64748b;">
{{ $invoice->customer->billing_address }}
</p>

<p style="margin:4px 0;color:#64748b;">
GSTIN: {{ $invoice->customer->gstin }}
</p>

</td>
</tr>

<!-- Items -->
<tr>
<td style="padding:30px;">

<table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;">

<thead style="background:#f8fafc;">
<tr>
<th align="left" style="font-size:12px;color:#64748b;border-bottom:1px solid #e2e8f0;">Item</th>
<th align="right" style="font-size:12px;color:#64748b;border-bottom:1px solid #e2e8f0;">Qty</th>
<th align="right" style="font-size:12px;color:#64748b;border-bottom:1px solid #e2e8f0;">Price</th>
<th align="right" style="font-size:12px;color:#64748b;border-bottom:1px solid #e2e8f0;">Tax</th>
<th align="right" style="font-size:12px;color:#64748b;border-bottom:1px solid #e2e8f0;">Total</th>
</tr>
</thead>

<tbody>

@foreach($invoice->items as $item)
<tr>
<td>{{ $item->item_name }}</td>
<td align="right">{{ $item->quantity }}</td>
<td align="right">₹{{ number_format($item->price, 2) }}</td>
<td align="right">{{ $item->tax_percent }}%</td>
<td align="right">₹{{ number_format($item->total_amount, 2) }}</td>
</tr>
@endforeach

</tbody>

</table>

</td>
</tr>

<!-- Summary -->
<tr>
<td style="padding:30px;border-top:1px solid #e2e8f0;">

<table width="100%" cellpadding="0" cellspacing="0">

<tr>
<td align="right">

<table width="250">

<tr>
<td style="color:#64748b;">Subtotal</td>
<td align="right">₹{{ number_format($invoice->subtotal, 2) }}</td>
</tr>

<tr>
<td style="color:#64748b;">Tax</td>
<td align="right">₹{{ number_format($invoice->tax_amount, 2) }}</td>
</tr>

<tr>
<td style="color:#64748b;">Discount</td>
<td align="right">₹{{ number_format($invoice->discount_amount, 2) }}</td>
</tr>

<tr>
<td style="font-weight:bold;border-top:1px solid #e2e8f0;padding-top:10px;">
Total
</td>

<td align="right" style="font-weight:bold;color:#2563eb;border-top:1px solid #e2e8f0;padding-top:10px;">
₹{{ number_format($invoice->total_amount, 2) }}
</td>
</tr>

</table>

</td>
</tr>

</table>

</td>
</tr>

<!-- Payment QR -->
<tr>
<td align="center" style="padding:30px;border-top:1px solid #e2e8f0;">

<p style="font-weight:bold;color:#0f172a;">Quick Payment</p>

<img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=upi://pay?pa=test@upi&pn=Demo&am=6844&cu=INR" width="160"/>

<p style="font-size:12px;color:#64748b;">
Scan QR using any UPI app
</p>

</td>
</tr>

<!-- Footer -->
<tr>
<td align="center" style="padding:20px;border-top:1px solid #e2e8f0;color:#64748b;font-size:13px;">

<p>Thank you for your business!</p>
<p>This is a computer generated invoice.</p>

</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>