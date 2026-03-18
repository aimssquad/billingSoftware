<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimal Invoice Email Template</title>
    <style>
        /* Email-safe fonts and reset */
        body {
            margin: 0;
            padding: 24px;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f5f6f8;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Main Container */
        .email-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        
        .invoice-title {
            font-size: 36px;
            font-weight: 500;
            letter-spacing: 0.5px;
            color: #1f2937;
            margin: 0;
        }
        
        .invoice-number {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            color: #4b5563;
        }
        
        .copy-indicator {
            font-size: 11px;
            color: #10b981;
            margin-left: 4px;
        }
        
        /* Company + Customer Grid */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 32px;
        }
        
        .info-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }
        
        .info-title {
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 8px 0;
            font-size: 14px;
        }
        
        .info-text {
            color: #4b5563;
            font-size: 13px;
            line-height: 1.5;
            margin: 0 0 4px 0;
        }
        
        /* Items Table */
        .table-container {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 32px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        
        .items-table th {
            background-color: #f9fafb;
            color: #374151;
            font-weight: 600;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .items-table th:not(:first-child) {
            text-align: right;
        }
        
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #4b5563;
        }
        
        .items-table td:not(:first-child) {
            text-align: right;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .items-table tbody tr:hover {
            background-color: #f9fafb;
        }
        
        .item-name {
            color: #374151;
        }
        
        .tax-percent {
            color: #6b7280;
        }
        
        /* Payment + Total Grid */
        .bottom-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        .payment-section {
            display: table-cell;
            width: 50%;
            padding-right: 20px;
            vertical-align: top;
        }
        
        .total-section {
            display: table-cell;
            width: 50%;
            padding-left: 20px;
            vertical-align: top;
        }
        
        /* Payment Card */
        .payment-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #374151;
            margin: 0 0 16px 0;
            font-size: 14px;
        }
        
        .payment-icon {
            color: #2563eb;
        }
        
        /* QR Code */
        .qr-container {
            margin-bottom: 16px;
        }
        
        .qr-box {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px;
            display: inline-block;
        }
        
        .qr-placeholder {
            width: 96px;
            height: 96px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }
        
        .qr-placeholder span {
            font-size: 10px;
            color: #6b7280;
        }
        
        .scan-text {
            font-size: 11px;
            color: #6b7280;
            margin: 4px 0 0 0;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        /* Payment Details */
        .payment-detail {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .detail-label {
            font-size: 12px;
            color: #6b7280;
        }
        
        .detail-value {
            font-size: 13px;
            font-weight: 500;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .copy-btn {
            color: #9ca3af;
            font-size: 12px;
            cursor: pointer;
        }
        
        /* Bank Section */
        .bank-section {
            border-top: 1px solid #e5e7eb;
            margin-top: 16px;
            padding-top: 12px;
        }
        
        .bank-title {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin: 0 0 8px 0;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .bank-icon {
            color: #16a34a;
        }
        
        /* Payment Footer */
        .payment-footer {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            border-top: 1px solid #e5e7eb;
            margin-top: 16px;
            padding-top: 12px;
            font-size: 11px;
            color: #9ca3af;
        }
        
        .razorpay-logo {
            font-weight: 600;
            color: #2563eb;
        }
        
        /* Totals Card */
        .totals-container {
            width: 240px;
            margin-left: auto;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
            color: #4b5563;
        }
        
        .total-border {
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            margin-top: 8px;
        }
        
        .discount-row {
            display: flex;
            justify-content: space-between;
            color: #16a34a;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            margin-top: 8px;
        }
        
        .grand-total {
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            font-size: 18px;
            background-color: #f3f4f6;
            padding: 8px 12px;
            border-radius: 6px;
            margin-top: 8px;
        }
        
        .grand-total-value {
            color: #2563eb;
        }
        
        .status-badge {
            margin-top: 16px;
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
        
        /* Responsive */
        @media screen and (max-width: 600px) {
            body {
                padding: 12px;
            }
            
            .email-container {
                padding: 20px;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .info-left,
            .info-right,
            .payment-section,
            .total-section {
                display: block;
                width: 100%;
            }
            
            .info-right {
                text-align: left;
                margin-top: 20px;
            }
            
            .payment-section {
                padding-right: 0;
                margin-bottom: 24px;
            }
            
            .total-section {
                padding-left: 0;
            }
            
            .totals-container {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        
        <!-- HEADER -->
        <div class="header">
            <h1 class="invoice-title">INVOICE</h1>
            
            <div class="invoice-number">
                <span style="color: #6b7280;">#</span>
                {{-- <span>INV-MIN-2024-001</span> --}}
                <span>{{ $invoice->invoice_no }}</span>
                <span style="color: #9ca3af; margin-left: 4px;">📋</span>
            </div>
        </div>
        
        <!-- COMPANY + CUSTOMER -->
        <div class="info-grid">
            <div class="info-left">
                {{-- <h3 class="info-title">Company Name</h3>
                <p class="info-text">123 Business Street</p>
                <p class="info-text">City, Business Street</p>
                <p class="info-text">City, State ZIP Code</p> --}}
                <h3 class="info-title">{{ $invoice->organization->company_name }}</h3>
                <p class="info-text">{{ $invoice->organization->address }}</p>
                <p class="info-text">{{ $invoice->organization->email }}</p>
                <p class="info-text">Ph: {{ $invoice->organization->phone }}</p>
            </div>
            
            <div class="info-right">
                {{-- <h3 class="info-title">TechCorp Solutions Inc.</h3>
                <p class="info-text">789 Innovation Drive</p>
                <p class="info-text">San Francisco, CA 94107</p>
                <p class="info-text">Ph: +1 (555) 987-6543</p> --}}
                <h3 class="info-title">{{ $invoice->customer->name }}</h3>
                <p class="info-text">{{ $invoice->customer->billing_address }}</p>
                <p class="info-text">{{ $invoice->customer->email }}</p>
                <p class="info-text">Ph: {{ $invoice->customer->phone }}</p>
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
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr>
                        <td class="item-name">{{ $item->item_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->price, 2) }}</td>
                        <td class="tax-percent">{{ $item->tax_percent }}%</td>
                        <td>₹{{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- PAYMENT + TOTAL SECTION -->
        <div class="bottom-grid">
            <!-- PAYMENT INFO -->
            <div class="payment-section">
                <div class="payment-title">
                    <span class="payment-icon">💳</span>
                    Payment Information
                </div>
                
                <!-- QR Code -->
                <div class="qr-container">
                    <div class="qr-box">
                        <div class="qr-placeholder">
                            <span>QR Code</span>
                        </div>
                    </div>
                    <p class="scan-text">
                        <span style="color: #2563eb;">📱</span>
                        Scan with any UPI app
                    </p>
                </div>
                
                <!-- Payment Details -->
                <div class="payment-detail">
                    <span class="detail-label">UPI ID:</span>
                    {{-- <span class="detail-value">
                        techcorp@okhdfcbank
                        <span class="copy-btn">📋</span>
                    </span> --}}
                    <span class="detail-value">
                    {{ $invoice->organization->defaultBankAccount->upi_id ?? '-' }}
                    </span>
                </div>
                
                <div class="payment-detail">
                    <span class="detail-label">Merchant:</span>
                    {{-- <span class="detail-value">TechCorp Solutions</span> --}}
                    <span class="detail-value">
                    {{ $invoice->organization->company_name }}
                    </span>
                </div>
                
                <!-- Bank Transfer Details -->
                <div class="bank-section">
                    <h4 class="bank-title">
                        <span class="bank-icon">🏦</span>
                        Bank Transfer
                    </h4>
                    
                    <div class="payment-detail">
                        <span class="detail-label">Account:</span>
                        {{-- <span class="detail-value">
                            12345678901
                            <span class="copy-btn">📋</span>
                        </span> --}}
                        <span class="detail-value">
                        {{ $invoice->organization->defaultBankAccount->account_number ?? '-' }}
                        </span>
                    </div>
                    
                    <div class="payment-detail">
                        <span class="detail-label">IFSC:</span>
                        {{-- <span class="detail-value">
                            HDFC0001234
                            <span class="copy-btn">📋</span>
                        </span> --}}
                        <span class="detail-value">
                        {{ $invoice->organization->defaultBankAccount->ifsc_code ?? '-' }}
                        </span>
                    </div>
                    
                    <div class="payment-detail">
                        <span class="detail-label">Bank:</span>
                        <span class="detail-value">
                        {{ $invoice->organization->defaultBankAccount->bank_name ?? '-' }}
                        </span>
                    </div>
                </div>
                
                <!-- Powered by Razorpay -->
                <div class="payment-footer">
                    <span>Powered by</span>
                    <span class="razorpay-logo">Razorpay</span>
                    <span style="color: #10b981;">✓</span>
                </div>
            </div>
            
            <!-- TOTALS -->
            <div class="total-section">
                <div class="totals-container">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span class="detail-value">₹{{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    
                    <div class="total-row total-border">
                        <span>Tax Amount</span>
                        <span class="detail-value">₹{{ number_format($invoice->tax_amount, 2) }}</span>
                    </div>
                    
                    <div class="discount-row">
                        <span>Discount</span>
                        <span>-₹{{ number_format($invoice->discount_amount, 2) }}</span>
                    </div>
                    
                    <div class="grand-total">
                        <span>Total</span>
                        <span class="grand-total-value">₹{{ number_format($invoice->total_amount, 2) }}</span>
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
</body>
</html>