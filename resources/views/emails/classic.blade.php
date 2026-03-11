<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Email Template</title>
    <style>
        /* Email-safe fonts and reset */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f3f4f6;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Container */
        .email-container {
            max-width: 750px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        /* Header Section */
        .invoice-header {
            background-color: #eff6ff;
            border-bottom: 1px solid #e5e7eb;
            padding: 32px 24px;
        }
        
        .header-content {
            display: table;
            width: 100%;
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
            text-align: right;
        }
        
        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #2563eb;
            margin: 0 0 16px 0;
            letter-spacing: 0.5px;
        }
        
        /* Info rows */
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
            vertical-align: middle;
        }
        
        .copy-badge {
            font-size: 11px;
            color: #10b981;
            margin-left: 4px;
        }
        
        /* Customer section */
        .customer-title {
            font-weight: 600;
            color: #374151;
            margin: 0 0 12px 0;
            font-size: 14px;
        }
        
        .customer-detail {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            margin-bottom: 8px;
            font-size: 13px;
            color: #4b5563;
        }
        
        /* Items Table */
        .items-section {
            padding: 32px 24px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .items-table th {
            background-color: #2563eb;
            color: white;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 16px;
            text-align: left;
        }
        
        .items-table th:not(:first-child) {
            text-align: right;
        }
        
        .items-table td {
            padding: 12px 16px;
            font-size: 13px;
            border-bottom: 1px solid #e5e7eb;
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
            font-weight: 500;
            color: #374151;
        }
        
        /* Payment and Summary Grid */
        .grid-container {
            display: table;
            width: 100%;
            margin-top: 32px;
        }
        
        .grid-left {
            display: table-cell;
            width: 50%;
            padding-right: 16px;
            vertical-align: top;
        }
        
        .grid-right {
            display: table-cell;
            width: 50%;
            padding-left: 16px;
            vertical-align: top;
        }
        
        /* Payment Card */
        .payment-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            background-color: #f9fafb;
        }
        
        .payment-title {
            font-weight: 600;
            color: #2563eb;
            margin: 0 0 16px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }
        
        /* QR Code Section */
        .qr-section {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .qr-code {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            text-align: center;
        }
        
        .qr-details {
            flex: 1;
        }
        
        .upi-info {
            background-color: white;
            border: 1px solid #e5e7eb;
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
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 12px;
            padding: 2px 6px;
        }
        
        /* Bank Details */
        .bank-section {
            border-top: 1px solid #e5e7eb;
            padding-top: 16px;
            margin-top: 16px;
        }
        
        .bank-title {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin: 0 0 12px 0;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .bank-detail {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
            color: #2563eb;
            font-weight: 600;
        }
        
        /* Totals Card */
        .totals-card {
            width: 280px;
            margin-left: auto;
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
        
        .border-top {
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
            margin-top: 12px;
        }
        
        .grand-total {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .status-badge {
            margin-top: 16px;
            padding: 8px;
            border-radius: 8px;
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
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding: 20px;
            font-size: 13px;
            color: #6b7280;
        }
        
        /* Responsive */
        @media screen and (max-width: 600px) {
            .header-left, .header-right {
                display: block;
                width: 100%;
                text-align: left;
            }
            
            .header-right {
                margin-top: 24px;
                text-align: left;
            }
            
            .customer-detail {
                justify-content: flex-start;
            }
            
            .grid-left, .grid-right {
                display: block;
                width: 100%;
                padding: 0;
            }
            
            .grid-left {
                margin-bottom: 24px;
            }
            
            .totals-card {
                width: 100%;
            }
            
            .qr-section {
                flex-direction: column;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 24px; background-color: #f3f4f6;">
    <div class="email-container">
        
        <!-- HEADER -->
        <div class="invoice-header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="invoice-title">INVOICE</h1>
                    
                    <div style="margin-top: 16px;">
                        <!-- Invoice Number -->
                        <div class="info-row">
                            <span style="color: #6b7280;">#</span>
                            <span style="color: #4b5563;">Invoice #INV-2024-001</span>
                            <span style="color: #9ca3af; margin-left: 4px;">📋</span>
                        </div>
                        
                        <!-- Invoice Date -->
                        <div class="info-row">
                            <span style="color: #6b7280;">📅</span>
                            <span style="color: #4b5563;">Invoice Date: March 15, 2024</span>
                        </div>
                        
                        <!-- Due Date -->
                        <div class="info-row">
                            <span style="color: #6b7280;">⏰</span>
                            <span style="color: #4b5563;">Due Date: April 14, 2024</span>
                        </div>
                    </div>
                </div>
                
                <div class="header-right">
                    <h3 class="customer-title">Bill To</h3>
                    
                    <!-- Customer Details -->
                    <div class="customer-detail">
                        <span style="color: #6b7280;">🏢</span>
                        <span style="color: #4b5563;">TechCorp Solutions</span>
                    </div>
                    
                    <div class="customer-detail">
                        <span style="color: #6b7280;">✉️</span>
                        <span style="color: #4b5563;">billing@techcorp.com</span>
                    </div>
                    
                    <div class="customer-detail">
                        <span style="color: #6b7280;">📞</span>
                        <span style="color: #4b5563;">+1 (555) 123-4567</span>
                    </div>
                    
                    <div class="customer-detail">
                        <span style="color: #6b7280;">📍</span>
                        <span style="color: #4b5563;">123 Business Ave, Suite 100, San Francisco, CA 94105</span>
                    </div>
                    
                    <div class="customer-detail">
                        <span style="color: #6b7280;">#</span>
                        <span style="color: #4b5563;">GST: 27AAECS1234F1Z5</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ITEMS TABLE -->
        <div class="items-section">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Tax</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="item-name">Web Development Services</td>
                        <td>40</td>
                        <td>$75.00</td>
                        <td>10%</td>
                        <td>$3,300.00</td>
                    </tr>
                    <tr>
                        <td class="item-name">UI/UX Design Package</td>
                        <td>20</td>
                        <td>$95.00</td>
                        <td>10%</td>
                        <td>$2,090.00</td>
                    </tr>
                    <tr>
                        <td class="item-name">Hosting Setup & Configuration</td>
                        <td>1</td>
                        <td>$250.00</td>
                        <td>18%</td>
                        <td>$295.00</td>
                    </tr>
                    <tr>
                        <td class="item-name">SSL Certificate (Annual)</td>
                        <td>2</td>
                        <td>$99.00</td>
                        <td>10%</td>
                        <td>$217.80</td>
                    </tr>
                </tbody>
            </table>
            
            <!-- SUMMARY + PAYMENT -->
            <div class="grid-container">
                <!-- PAYMENT INFORMATION -->
                <div class="grid-left">
                    <div class="payment-card">
                        <h3 class="payment-title">
                            <span style="color: #2563eb;">💳</span>
                            Payment Information
                        </h3>
                        
                        <!-- QR Code Section -->
                        <div class="qr-section">
                            <div class="qr-code">
                                <div style="text-align: center;">
                                    <div style="font-size: 8px; margin-bottom: 2px;">Scan to Pay</div>
                                    <div style="font-size: 16px;">⬛⬛</div>
                                    <div style="font-size: 16px;">⬛⬛</div>
                                    <div style="font-size: 8px; margin-top: 2px;">UPI QR</div>
                                </div>
                            </div>
                            <div class="qr-details">
                                <p style="font-size: 13px; font-weight: 500; color: #374151; margin: 0 0 8px 0; display: flex; align-items: center; gap: 4px;">
                                    <span style="color: #2563eb;">📱</span> Scan with UPI app
                                </p>
                                
                                <div class="upi-info">
                                    <span class="label">UPI ID:</span>
                                    <div>
                                        <span class="value">techcorp@okhdfcbank</span>
                                        <span class="copy-btn" style="margin-left: 8px;">📋</span>
                                    </div>
                                </div>
                                
                                <div class="upi-info">
                                    <span class="label">Merchant:</span>
                                    <span class="value">TechCorp Solutions</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bank Transfer Details -->
                        <div class="bank-section">
                            <h4 class="bank-title">
                                <span style="color: #16a34a;">🏦</span>
                                Bank Transfer
                            </h4>
                            
                            <div class="bank-detail">
                                <span class="label">Account:</span>
                                <div>
                                    <span class="value">12345678901</span>
                                    <span class="copy-btn" style="margin-left: 8px;">📋</span>
                                </div>
                            </div>
                            
                            <div class="bank-detail">
                                <span class="label">IFSC:</span>
                                <div>
                                    <span class="value">HDFC0001234</span>
                                    <span class="copy-btn" style="margin-left: 8px;">📋</span>
                                </div>
                            </div>
                            
                            <div class="bank-detail">
                                <span class="label">Bank:</span>
                                <span class="value">HDFC Bank</span>
                            </div>
                        </div>
                        
                        <!-- Powered by -->
                        <div class="payment-footer">
                            <span>Powered by</span>
                            <span class="razorpay-logo">Razorpay</span>
                        </div>
                    </div>
                </div>
                
                <!-- TOTALS -->
                <div class="grid-right">
                    <div class="totals-card">
                        <div class="total-row">
                            <span class="total-label">Subtotal</span>
                            <span class="total-value">$5,302.80</span>
                        </div>
                        
                        <div class="total-row">
                            <span class="total-label">Tax</span>
                            <span class="total-value">$600.00</span>
                        </div>
                        
                        <div class="total-row" style="color: #16a34a;">
                            <span class="total-label">Discount</span>
                            <span class="total-value">-$250.00</span>
                        </div>
                        
                        <div class="border-top"></div>
                        
                        <div class="grand-total">
                            <span>Total</span>
                            <span>$5,652.80</span>
                        </div>
                        
                        <!-- Payment Status -->
                        <div class="status-badge status-pending">
                            Complete payment using QR or bank transfer
                        </div>
                        
                        <!-- Paid Status (alternative) 
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
    
    <!-- Alternative Version (Paid Status) - Uncomment to use
    <div class="email-container" style="margin-top: 24px;">
        <div class="invoice-header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="invoice-title">INVOICE</h1>
                    <div style="margin-top: 16px;">
                        <div class="info-row"><span style="color: #6b7280;">#</span><span>Invoice #INV-2024-002</span></div>
                        <div class="info-row"><span style="color: #6b7280;">📅</span><span>Invoice Date: March 10, 2024</span></div>
                        <div class="info-row"><span style="color: #6b7280;">⏰</span><span>Due Date: April 9, 2024</span></div>
                    </div>
                </div>
                <div class="header-right">
                    <h3 class="customer-title">Bill To</h3>
                    <div class="customer-detail"><span>🏢</span><span>Startup Innovations</span></div>
                    <div class="customer-detail"><span>✉️</span><span>accounts@startup.io</span></div>
                    <div class="customer-detail"><span>📍</span><span>456 Market Street, Austin, TX</span></div>
                </div>
            </div>
        </div>
        
        <div class="items-section">
            <table class="items-table">
                <thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Tax</th><th>Total</th></tr></thead>
                <tbody>
                    <tr><td class="item-name">Consulting Services</td><td>10</td><td>$150.00</td><td>10%</td><td>$1,650.00</td></tr>
                    <tr><td class="item-name">Software License</td><td>5</td><td>$99.00</td><td>10%</td><td>$544.50</td></tr>
                </tbody>
            </table>
            
            <div class="grid-container">
                <div class="grid-left">
                    <div class="payment-card">
                        <h3 class="payment-title"><span>💳</span>Payment Information</h3>
                        <div style="padding: 10px; background: white; border-radius: 6px; text-align: center;">
                            <p style="margin: 0; color: #16a34a; font-weight: 500;">✓ Payment Completed</p>
                            <p style="margin: 5px 0 0; font-size: 12px; color: #6b7280;">Transaction ID: TXN123456789</p>
                        </div>
                    </div>
                </div>
                <div class="grid-right">
                    <div class="totals-card">
                        <div class="total-row"><span class="total-label">Subtotal</span><span>$1,995.00</span></div>
                        <div class="total-row"><span class="total-label">Tax</span><span>$199.50</span></div>
                        <div class="border-top"></div>
                        <div class="grand-total"><span>Total</span><span>$2,194.50</span></div>
                        <div class="status-badge status-paid">✓ Payment Received</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="invoice-footer">Thank you for your business!</div>
    </div>
    -->
</body>
</html>