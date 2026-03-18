<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Invoice Email Template</title>
    <style>
        /* Email-safe fonts and reset */
        body {
            margin: 0;
            padding: 24px;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f1f5f9;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Main Container */
        .email-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        
        /* Top Gradient Border */
        .gradient-border {
            height: 4px;
            background: linear-gradient(90deg, #2563eb 0%, #4f46e5 100%);
        }
        
        /* Main Content */
        .content {
            padding: 40px;
        }
        
        /* Header Section */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }
        
        .header-left {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        
        .header-right {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        
        .invoice-title {
            font-size: 36px;
            font-weight: bold;
            color: #2563eb;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
        }
        
        .template-badge {
            font-size: 11px;
            color: #94a3b8;
            margin: 0 0 16px 0;
        }
        
        /* Info Rows */
        .info-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 13px;
            color: #4b5563;
        }
        
        .icon {
            width: 16px;
            height: 16px;
            display: inline-block;
            color: #6b7280;
        }
        
        .copy-badge {
            font-size: 11px;
            color: #10b981;
            margin-left: 4px;
        }
        
        /* Customer Card */
        .customer-card {
            background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px 24px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .customer-title {
            font-size: 12px;
            font-weight: 600;
            color: #4b5563;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .customer-detail {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            margin-bottom: 4px;
            font-size: 13px;
            color: #4b5563;
        }
        
        /* Company Info */
        .company-section {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 40px;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: 600;
            color: #4b5563;
            margin: 0 0 16px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .company-grid {
            display: table;
            width: 100%;
        }
        
        .company-row {
            display: table-row;
        }
        
        .company-cell {
            display: table-cell;
            padding-bottom: 8px;
            font-size: 13px;
            color: #4b5563;
        }
        
        .company-cell:first-child {
            width: 50%;
        }
        
        .company-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Items Table */
        .table-container {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 40px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items-table th {
            background: linear-gradient(90deg, #2563eb 0%, #4f46e5 100%);
            color: white;
            font-size: 13px;
            font-weight: 600;
            padding: 16px;
            text-align: left;
        }
        
        .items-table th:not(:first-child) {
            text-align: right;
        }
        
        .items-table td {
            padding: 16px;
            font-size: 13px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .items-table td:not(:first-child) {
            text-align: right;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .items-table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        .item-name {
            font-weight: 500;
            color: #374151;
        }
        
        .item-amount {
            font-weight: 600;
            color: #2563eb;
        }
        
        /* Bottom Grid */
        .bottom-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        .grid-left {
            display: table-cell;
            width: 50%;
            padding-right: 20px;
            vertical-align: top;
        }
        
        .grid-right {
            display: table-cell;
            width: 50%;
            padding-left: 20px;
            vertical-align: top;
        }
        
        /* Payment Card */
        .payment-card {
            background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 24px;
            height: 100%;
        }
        
        .payment-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 16px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .payment-icon {
            color: #2563eb;
        }
        
        /* QR Section */
        .qr-section {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .qr-code {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .qr-placeholder {
            text-align: center;
            color: white;
        }
        
        .qr-placeholder small {
            font-size: 8px;
            display: block;
            margin: 2px 0;
        }
        
        .qr-placeholder .qr-pattern {
            font-size: 16px;
            line-height: 1;
        }
        
        .qr-details {
            flex: 1;
        }
        
        .upi-label {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin: 0 0 8px 0;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .upi-row {
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .label {
            font-size: 11px;
            color: #6b7280;
        }
        
        .value {
            font-size: 12px;
            font-weight: 500;
            color: #1f2937;
        }
        
        .copy-btn {
            color: #94a3b8;
            font-size: 12px;
            margin-left: 4px;
            cursor: pointer;
        }
        
        /* Bank Grid */
        .bank-grid {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }
        
        .bank-row {
            display: table-row;
        }
        
        .bank-cell {
            display: table-cell;
            width: 50%;
            padding-right: 4px;
        }
        
        .bank-card {
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 8px;
        }
        
        .bank-label {
            font-size: 11px;
            color: #6b7280;
            margin: 0 0 2px 0;
        }
        
        .bank-value-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .bank-value {
            font-size: 12px;
            font-weight: 500;
            color: #1f2937;
        }
        
        .full-width-card {
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 12px;
        }
        
        /* Payment Footer */
        .payment-footer {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            border-top: 1px solid #e2e8f0;
            margin-top: 16px;
            padding-top: 12px;
            font-size: 11px;
            color: #94a3b8;
        }
        
        .razorpay-logo {
            font-weight: 600;
            color: #2563eb;
        }
        
        .shield {
            color: #10b981;
        }
        
        /* Totals Card */
        .totals-card {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 24px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .total-label {
            color: #6b7280;
        }
        
        .total-value {
            font-weight: 500;
            color: #1f2937;
        }
        
        .discount-value {
            font-weight: 500;
            color: #16a34a;
        }
        
        .border-top {
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            margin-top: 12px;
        }
        
        .grand-total {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 12px;
        }
        
        .grand-total-label {
            color: #374151;
        }
        
        .grand-total-value {
            color: #2563eb;
        }
        
        .status-badge {
            padding: 8px;
            border-radius: 6px;
            text-align: center;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #2563eb;
        }
        
        .status-paid {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }
        
        /* Footer */
        .invoice-footer {
            border-top: 1px solid #e2e8f0;
            margin-top: 40px;
            padding-top: 24px;
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
        }
        
        /* Responsive */
        @media screen and (max-width: 600px) {
            .content {
                padding: 20px;
            }
            
            .header-left,
            .header-right,
            .grid-left,
            .grid-right,
            .company-cell {
                display: block;
                width: 100%;
            }
            
            .header-right {
                margin-top: 20px;
            }
            
            .customer-card {
                text-align: left;
            }
            
            .customer-detail {
                justify-content: flex-start;
            }
            
            .grid-left {
                padding-right: 0;
                margin-bottom: 20px;
            }
            
            .grid-right {
                padding-left: 0;
            }
            
            .qr-section {
                flex-direction: column;
            }
            
            .bank-grid {
                display: block;
            }
            
            .bank-cell {
                display: block;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Top Gradient Border -->
        <div class="gradient-border"></div>
        
        <div class="content">
            
            <!-- HEADER -->
            <div class="header">
                <div class="header-left">
                    <h1 class="invoice-title">INVOICE</h1>
                    <p class="template-badge">Modern Billing Template</p>
                    
                    <div style="margin-top: 16px;">
                        <!-- Invoice Number -->
                        <div class="info-row">
                            <span style="color: #6b7280;">#</span>
                            <span>Invoice #{{ $invoice->invoice_no }}</span>
                            <span style="color: #94a3b8; margin-left: 4px;">📋</span>
                        </div>
                        
                        <!-- Invoice Date -->
                        <div class="info-row">
                            <span style="color: #6b7280;">📅</span>
                            <span>Invoice Date: {{ $invoice->invoice_date->format('F d, Y') }}</span>
                        </div>
                        
                        <!-- Due Date -->
                        <div class="info-row">
                            <span style="color: #6b7280;">⏰</span>
                            <span>Due Date: {{ $invoice->due_date->format('F d, Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="header-right">
                    <div class="customer-card">
                        <h2 class="customer-title">Bill To</h2>
                        
                        <div class="customer-detail">
                            <span style="color: #6b7280;">🏢</span>
                            <span>{{ $invoice->customer->name }}</span>
                        </div>
                        
                        <div class="customer-detail">
                            <span style="color: #6b7280;">✉️</span>
                            <span>{{ $invoice->customer->email }}</span>
                        </div>
                        
                        <div class="customer-detail">
                            <span style="color: #6b7280;">📞</span>
                            <span>{{ $invoice->customer->phone }}</span>
                        </div>
                        
                        <div class="customer-detail">
                            <span style="color: #6b7280;">📍</span>
                            <span>{{ $invoice->customer->billing_address }}</span>
                        </div>
                        
                        <div class="customer-detail">
                            <span style="color: #6b7280;">#</span>
                            <span>GST: {{ $invoice->customer->gstin }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- COMPANY INFO -->
            <div class="company-section">
                <h3 class="section-title">Company Information</h3>
                
                <div class="company-grid">
                    <div class="company-row">
                        <div class="company-cell">
                            <div class="company-item">
                                <span style="color: #6b7280;">🏢</span>
                                <span>{{ $invoice->organization->company_name }}</span>
                            </div>
                        </div>
                        <div class="company-cell">
                            <div class="company-item">
                                <span style="color: #6b7280;">✉️</span>
                                <span>{{ $invoice->organization->email }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="company-row">
                        <div class="company-cell">
                            <div class="company-item">
                                <span style="color: #6b7280;">📞</span>
                                <span>{{ $invoice->organization->phone }}</span>
                            </div>
                        </div>
                        <div class="company-cell">
                            <div class="company-item">
                                <span style="color: #6b7280;">📍</span>
                                <span>{{ $invoice->organization->address }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="company-row">
                        <div class="company-cell" colspan="2">
                            <div class="company-item" style="margin-top: 8px;">
                                <span style="color: #6b7280;">#</span>
                                <span>GSTIN: {{ $invoice->organization->gstin }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ITEMS TABLE -->
            <div class="table-container">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Tax %</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr>
                            <td class="item-name">{{ $item->item_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->tax_percent }}%</td>
                            <td class="item-amount">₹{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- BOTTOM SECTION -->
            <div class="bottom-grid">
                <!-- PAYMENT INFORMATION -->
                <div class="grid-left">
                    <div class="payment-card">
                        <h3 class="payment-title">
                            <span class="payment-icon">💳</span>
                            Payment Information
                        </h3>
                        
                        <!-- QR Code Section -->
                        <div class="qr-section">
                            <div class="qr-code">
                                <div class="qr-placeholder">
                                    <small>Scan to</small>
                                    @if($invoice->organization->defaultBankAccount?->qr_code_url)
                                        <img src="{{ $invoice->organization->defaultBankAccount->qr_code_url }}" width="80">
                                    @else
                                        <small>Scan to</small>
                                        <div class="qr-pattern">⬛⬛</div>
                                    @endif
                                    <small>Pay</small>
                                </div>
                            </div>
                            
                            <div class="qr-details">
                                <p class="upi-label">
                                    <span style="color: #2563eb;">📱</span> 
                                    Scan with UPI app
                                </p>
                                
                                <div class="upi-row">
                                    <span class="label">UPI ID:</span>
                                    <div>
                                        <span class="value">
                                        {{ $invoice->organization->defaultBankAccount->upi_id ?? '-' }}
                                        </span>
                                        <span class="copy-btn">📋</span>
                                    </div>
                                </div>
                                
                                <div class="upi-row">
                                    <span class="label">Merchant:</span>
                                    <span class="value">
                                    {{ $invoice->organization->company_name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bank Details - Grid -->
                        <div class="bank-grid">
                            <div class="bank-row">
                                <div class="bank-cell">
                                    <div class="bank-card">
                                        <p class="bank-label">Account</p>
                                        <div class="bank-value-wrapper">
                                            <span class="bank-value">
                                            {{ $invoice->organization->defaultBankAccount->account_number ?? '-' }}
                                            </span>
                                            <span class="copy-btn">📋</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bank-cell">
                                    <div class="bank-card">
                                        <p class="bank-label">IFSC</p>
                                        <div class="bank-value-wrapper">
                                            <span class="bank-value">
                                            {{ $invoice->organization->defaultBankAccount->ifsc_code ?? '-' }}
                                            </span>
                                            <span class="copy-btn">📋</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="full-width-card">
                            <p class="bank-label">Bank</p>
                            <span class="bank-value">
                            {{ $invoice->organization->defaultBankAccount->bank_name ?? '-' }}
                            </span>
                        </div>
                        
                        <!-- Powered by Razorpay -->
                        <div class="payment-footer">
                            <span>Powered by</span>
                            <span class="razorpay-logo">Razorpay</span>
                            <span class="shield">✓</span>
                        </div>
                    </div>
                </div>
                
                <!-- TOTALS -->
                <div class="grid-right">
                    <div class="totals-card">
                        <div style="flex: 1;">
                            <div class="total-row">
                                <span class="total-label">Subtotal</span>
                                <span class="total-value">
                                ₹{{ number_format($invoice->subtotal, 2) }}
                                </span>
                            </div>
                            
                            <div class="total-row">
                                <span class="total-label">Tax</span>
                                <span class="total-value">
                                ₹{{ number_format($invoice->tax_amount, 2) }}
                                </span>
                            </div>
                            
                            <div class="total-row">
                                <span class="total-label">Discount</span>
                                <span class="discount-value">
                                -₹{{ number_format($invoice->discount_amount, 2) }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <div class="border-top"></div>
                            
                            <div class="grand-total">
                                <span class="grand-total-label">Total</span>
                                <span class="grand-total-value">
                                ₹{{ number_format($invoice->total_amount, 2) }}
                                </span>
                            </div>
                            
                            <!-- Payment Status -->
                            <div class="status-badge status-pending">
                                Complete payment using QR or bank transfer
                            </div>
                            
                            <!-- Paid Status (commented out)
                            <div class="status-badge status-paid">
                                ✓ Payment Received
                            </div>
                            -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FOOTER -->
            <div class="invoice-footer">
                Thank you for your business!
            </div>
            
        </div>
    </div>
</body>
</html>