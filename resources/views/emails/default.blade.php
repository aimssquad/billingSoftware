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

<p style="margin:6px 0;color:#64748b;">Invoice #: INV-2025-001</p>
<p style="margin:6px 0;color:#64748b;">Invoice Date: 20 March 2025</p>
<p style="margin:6px 0;color:#64748b;">Due Date: 30 March 2025</p>

</td>
</tr>

<!-- Customer -->
<tr>
<td style="padding:30px;border-bottom:1px solid #e2e8f0;">

<h3 style="margin-bottom:10px;color:#64748b;">Bill To</h3>

<p style="margin:4px 0;color:#0f172a;font-weight:bold;">
Aminul Islam
</p>

<p style="margin:4px 0;color:#64748b;">
aminul@example.com
</p>

<p style="margin:4px 0;color:#64748b;">
+91 9876543210
</p>

<p style="margin:4px 0;color:#64748b;">
Kolkata, West Bengal, India
</p>

<p style="margin:4px 0;color:#64748b;">
GSTIN: 22AAAAA0000A1Z5
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

<tr>
<td>Website Development</td>
<td align="right">1</td>
<td align="right">₹3000</td>
<td align="right">18%</td>
<td align="right">₹3540</td>
</tr>

<tr>
<td>Domain Registration</td>
<td align="right">1</td>
<td align="right">₹800</td>
<td align="right">18%</td>
<td align="right">₹944</td>
</tr>

<tr>
<td>Hosting (1 Year)</td>
<td align="right">1</td>
<td align="right">₹2000</td>
<td align="right">18%</td>
<td align="right">₹2360</td>
</tr>

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
<td align="right">₹5800</td>
</tr>

<tr>
<td style="color:#64748b;">Tax</td>
<td align="right">₹1044</td>
</tr>

<tr>
<td style="color:#64748b;">Discount</td>
<td align="right">₹0</td>
</tr>

<tr>
<td style="font-weight:bold;border-top:1px solid #e2e8f0;padding-top:10px;">
Total
</td>

<td align="right" style="font-weight:bold;color:#2563eb;border-top:1px solid #e2e8f0;padding-top:10px;">
₹6844
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