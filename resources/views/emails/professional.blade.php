<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Invoice Email Template</title>
    <style>
        /* Email-safe fonts and reset */
        body {
            margin: 0;
            padding: 24px;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f3f4f6;
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
            border: 1px solid #e5e7eb;
        }
        
        /* Header with Angled Block */
        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(90deg, #f1f5f9 0%, #e2e8f0 100%);
            padding: 24px 32px;
            overflow: hidden;
            min-height: 100px;
        }
        
        .company-info {
            position: relative;
            z-index: 10;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
        }
        
        .template-badge {
            font-size: 10px;
            color: #6b7280;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Angled Block */
        .angled-block {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 320px;
            background: linear-gradient(90deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 32px;
            clip-path: polygon(20% 0, 100% 0, 100% 100%, 0% 100%);
            z-index: 5;
        }
        
        .invoice-details {
            text-align: right;
            font-size: 13px;
            line-height: 1.6;
        }
        
        .invoice-details p {
            margin: 2px 0;
        }
        
        .invoice-number {
            font-weight: 600;
        }
        
        .copy-indicator {
            color: #86efac;
            font-size: 11px;
            margin-left: 4px;
        }
        
        /* Main Content */
        .content {
            padding: 32px;
        }
        
        /* Company + Customer Grid */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 32px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 24px;
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
        }
        
        .section-header {
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 12px 0;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .section-icon {
            color: #2563eb;
        }
        
        .company-detail {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.5;
            margin: 0 0 4px 0;
        }
        
        .company-name-bold {
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 4px 0;
        }
        
        .detail-with-icon {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 4px;
            font-size: 12px;
            color: #6b7280;
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
            background-color: #1e3a8a;
            color: white;
            font-weight: 600;
            padding: 12px;
            text-align: left;
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
            font-weight: 500;
            color: #374151;
        }
        
        .item-amount {
            font-weight: 500;
            color: #2563eb;
        }
        
        /* Payment + Totals Grid */
        .bottom-grid {
            display: table;
            width: 100%;
            margin-top: 24px;
        }
        
        .payment-section {
            display: table-cell;
            width: 50%;
            padding-right: 16px;
            vertical-align: top;
        }
        
        .total-section {
            display: table-cell;
            width: 50%;
            padding-left: 16px;
            vertical-align: top;
        }
        
        /* Payment Card */
        .payment-card {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 24px;
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
        
        /* QR Section */
        .qr-section {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .qr-code {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2563eb 0%, #1e3a8a 100%);
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
        
        .qr-pattern {
            font-size: 16px;
            line-height: 1;
        }
        
        .qr-details {
            flex: 1;
        }
        
        .quick-pay-label {
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
        
        .bank-grid {
            display: table;
            width: 100%;
            margin-bottom: 8px;
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
            border: 1px solid #e5e7eb;
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
            margin-top: 2px;
        }
        
        .bank-value {
            font-size: 12px;
            font-weight: 500;
            color: #1f2937;
        }
        
        .full-width-card {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px;
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
        .totals-card {
            width: 300px;
            margin-left: auto;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .total-label {
            color: #4b5563;
        }
        
        .total-value {
            font-weight: 500;
            color: #1f2937;
        }
        
        .discount-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
            margin-bottom: 8px;
            color: #16a34a;
        }
        
        .total-due {
            display: flex;
            justify-content: space-between;
            background-color: #eff6ff;
            padding: 16px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 18px;
            margin-top: 16px;
        }
        
        .total-due-label {
            color: #374151;
        }
        
        .total-due-value {
            color: #2563eb;
        }
        
        .status-badge {
            margin-top: 16px;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
        }
        
        .status-pending {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
        }
        
        .status-pending-text {
            font-size: 12px;
            color: #2563eb;
        }
        
        .status-paid {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
        }
        
        .status-paid-text {
            font-size: 13px;
            font-weight: 500;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        /* Footer */
        .invoice-footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }
        
        .footer-note {
            font-size: 13px;
            color: #6b7280;
            margin: 0 0 4px 0;
        }
        
        .footer-thanks {
            font-size: 12px;
            color: #9ca3af;
            margin: 0;
        }
        
        /* Responsive */
        @media screen and (max-width: 600px) {
            .content {
                padding: 16px;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                padding: 16px;
            }
            
            .angled-block {
                position: relative;
                width: 100%;
                clip-path: none;
                padding: 12px 16px;
                margin-top: 16px;
                justify-content: flex-start;
            }
            
            .info-left,
            .info-right,
            .payment-section,
            .total-section,
            .bank-cell {
                display: block;
                width: 100%;
            }
            
            .info-right {
                margin-top: 24px;
            }
            
            .payment-section {
                padding-right: 0;
                margin-bottom: 24px;
            }
            
            .total-section {
                padding-left: 0;
            }
            
            .totals-card {
                width: 100%;
            }
            
            .qr-section {
                flex-direction: column;
            }
            
            .bank-grid {
                display: block;
            }
            
            .bank-cell {
                padding-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        
        <!-- HEADER with Angled Block -->
        <div class="header">
            <div class="company-info">
                <h1 class="company-name">YOUR COMPANY NAME</h1>
                <p class="template-badge">PROFESSIONAL INVOICE TEMPLATE</p>
            </div>
            
            <div class="angled-block">
                <div class="invoice-details">
                    <p>
                        Invoice #: <span class="invoice-number">INV-PRO-2024-001</span>
                        <span style="margin-left: 4px;">📋</span>
                    </p>
                    <p>Invoice Date: March 28, 2024</p>
                    <p>Due Date: April 27, 2024</p>
                </div>
            </div>
        </div>
        
        <div class="content">
            
            <!-- COMPANY + CUSTOMER -->
            <div class="info-grid">
                <!-- FROM (Company) -->
                <div class="info-left">
                    <h3 class="section-header">
                        <span class="section-icon">🏢</span>
                        From
                    </h3>
                    <p class="company-name-bold">Your Company Name</p>
                    <p class="company-detail">123 Business Street</p>
                    <p class="company-detail">City, State Code</p>
                    
                    <div class="detail-with-icon">
                        <span style="color: #9ca3af;">✉️</span>
                        <span>info@company.com</span>
                    </div>
                    
                    <div class="detail-with-icon">
                        <span style="color: #9ca3af;">🌐</span>
                        <span>www.company.com</span>
                    </div>
                    
                    <div class="detail-with-icon">
                        <span style="color: #9ca3af;">#</span>
                        <span>GST: 22AAAAA0000A1Z5</span>
                    </div>
                </div>
                
                <!-- BILL TO (Customer) -->
                <div class="info-right">
                    <h3 class="section-header">
                        <span class="section-icon">🏢</span>
                        Bill To
                    </h3>
                    <p class="company-name-bold">TechCorp Solutions Inc.</p>
                    <p class="company-detail">789 Innovation Drive</p>
                    <p class="company-detail">San Francisco, CA 94107</p>
                    
                    <div class="detail-with-icon">
                        <span style="color: #9ca3af;">✉️</span>
                        <span>accounts@techcorp.com</span>
                    </div>
                    
                    <div class="detail-with-icon">
                        <span style="color: #9ca3af;">📞</span>
                        <span>+1 (555) 987-6543</span>
                    </div>
                    
                    <div class="detail-with-icon">
                        <span style="color: #9ca3af;">#</span>
                        <span>GST: 36AABCT1234F1Z8</span>
                        <span style="margin-left: 4px;">📋</span>
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
                            <th>Unit Price</th>
                            <th>Tax %</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="item-name">Enterprise Software License</td>
                            <td>5</td>
                            <td>$299.00</td>
                            <td>18%</td>
                            <td class="item-amount">$1,764.10</td>
                        </tr>
                        <tr>
                            <td class="item-name">Cloud Hosting Services (Annual)</td>
                            <td>2</td>
                            <td>$599.00</td>
                            <td>18%</td>
                            <td class="item-amount">$1,413.64</td>
                        </tr>
                        <tr>
                            <td class="item-name">Premium Support Package</td>
                            <td>1</td>
                            <td>$499.00</td>
                            <td>18%</td>
                            <td class="item-amount">$588.82</td>
                        </tr>
                        <tr>
                            <td class="item-name">API Integration Services</td>
                            <td>8</td>
                            <td>$125.00</td>
                            <td>18%</td>
                            <td class="item-amount">$1,180.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- PAYMENT + TOTALS -->
            <div class="bottom-grid">
                <!-- PAYMENT INFORMATION -->
                <div class="payment-section">
                    <div class="payment-card">
                        <h3 class="payment-title">
                            <span style="color: #2563eb;">💳</span>
                            Payment Information
                        </h3>
                        
                        <!-- QR Code Section -->
                        <div class="qr-section">
                            <div class="qr-code">
                                <div class="qr-placeholder">
                                    <small>Scan to</small>
                                    <div class="qr-pattern">⬛⬛</div>
                                    <div class="qr-pattern">⬛⬛</div>
                                    <small>Pay</small>
                                </div>
                            </div>
                            
                            <div class="qr-details">
                                <p class="quick-pay-label">
                                    <span style="color: #2563eb;">📱</span>
                                    Quick UPI Payment
                                </p>
                                
                                <div class="upi-row">
                                    <span class="label">UPI ID:</span>
                                    <span class="value">
                                        techcorp@okhdfcbank
                                        <span class="copy-btn">📋</span>
                                    </span>
                                </div>
                                
                                <div class="upi-row">
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
                            
                            <div class="bank-grid">
                                <div class="bank-row">
                                    <div class="bank-cell">
                                        <div class="bank-card">
                                            <p class="bank-label">Account</p>
                                            <div class="bank-value-wrapper">
                                                <span class="bank-value">12345678901</span>
                                                <span class="copy-btn">📋</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bank-cell">
                                        <div class="bank-card">
                                            <p class="bank-label">IFSC</p>
                                            <div class="bank-value-wrapper">
                                                <span class="bank-value">HDFC0001234</span>
                                                <span class="copy-btn">📋</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="full-width-card">
                                <p class="bank-label">Bank</p>
                                <span class="bank-value">HDFC Bank - Corporate Branch</span>
                            </div>
                        </div>
                        
                        <!-- Powered by Razorpay -->
                        <div class="payment-footer">
                            <span>Secure payments by</span>
                            <span class="razorpay-logo">Razorpay</span>
                            <span style="color: #10b981;">✓</span>
                        </div>
                    </div>
                </div>
                
                <!-- TOTALS -->
                <div class="total-section">
                    <div class="totals-card">
                        <div class="total-row">
                            <span class="total-label">Subtotal</span>
                            <span class="total-value">$4,446.56</span>
                        </div>
                        
                        <div class="total-row">
                            <span class="total-label">Tax Amount</span>
                            <span class="total-value">$800.00</span>
                        </div>
                        
                        <div class="discount-row">
                            <span>Discount</span>
                            <span>-$300.00</span>
                        </div>
                        
                        <div class="total-due">
                            <span class="total-due-label">Total Due</span>
                            <span class="total-due-value">$4,946.56</span>
                        </div>
                        
                        <!-- Payment Status -->
                        <div class="status-badge status-pending">
                            <p class="status-pending-text">
                                Complete payment using QR code or bank transfer
                            </p>
                        </div>
                        
                        <!-- Paid Status (commented out)
                        <div class="status-badge status-paid">
                            <p class="status-paid-text">
                                <span style="color: #16a34a;">✓</span>
                                Payment Received
                            </p>
                        </div>
                        -->
                    </div>
                </div>
            </div>
            
            <!-- FOOTER -->
            <div class="invoice-footer">
                <p class="footer-note">This is a computer generated invoice. No signature required.</p>
                <p class="footer-thanks">Thank you for your business!</p>
            </div>
            
        </div>
    </div>
    
    <!-- Alternative Paid Version -->
    <div class="email-container" style="margin-top: 24px;">
        <div class="header">
            <div class="company-info">
                <h1 class="company-name">YOUR COMPANY NAME</h1>
                <p class="template-badge">PROFESSIONAL INVOICE TEMPLATE</p>
            </div>
            <div class="angled-block">
                <div class="invoice-details">
                    <p>Invoice #: <span class="invoice-number">INV-PRO-2024-002</span></p>
                    <p>Invoice Date: March 25, 2024</p>
                    <p>Due Date: April 24, 2024</p>
                </div>
            </div>
        </div>
        
        <div class="content">
            <div class="info-grid">
                <div class="info-left">
                    <h3 class="section-header"><span>🏢</span>From</h3>
                    <p class="company-name-bold">Your Company Name</p>
                    <p class="company-detail">123 Business Street, City</p>
                    <div class="detail-with-icon"><span>✉️</span>info@company.com</div>
                </div>
                <div class="info-right">
                    <h3 class="section-header"><span>🏢</span>Bill To</h3>
                    <p class="company-name-bold">Startup Innovations LLC</p>
                    <p class="company-detail">456 Tech Boulevard, Austin, TX</p>
                    <div class="detail-with-icon"><span>✉️</span>finance@startup.io</div>
                </div>
            </div>
            
            <div class="table-container">
                <table class="items-table">
                    <thead><tr><th>Description</th><th>Qty</th><th>Price</th><th>Tax</th><th>Amount</th></tr></thead>
                    <tbody>
                        <tr><td>Consulting Services</td><td>15</td><td>$200.00</td><td>10%</td><td class="item-amount">$3,300.00</td></tr>
                        <tr><td>Software License</td><td>3</td><td>$150.00</td><td>10%</td><td class="item-amount">$495.00</td></tr>
                    </tbody>
                </table>
            </div>
            
            <div class="bottom-grid">
                <div class="payment-section">
                    <div class="payment-card">
                        <h3 class="payment-title">💳 Payment Information</h3>
                        <div style="background: white; border-radius: 6px; padding: 16px; text-align: center;">
                            <p style="margin: 0; color: #16a34a; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <span style="font-size: 16px;">✓</span> Payment Received
                            </p>
                            <p style="margin: 8px 0 0; font-size: 12px; color: #6b7280;">Transaction ID: TXN987654321</p>
                            <p style="margin: 4px 0 0; font-size: 11px; color: #9ca3af;">Paid on: March 27, 2024</p>
                        </div>
                    </div>
                </div>
                <div class="total-section">
                    <div class="totals-card">
                        <div class="total-row"><span>Subtotal</span><span>$3,795.00</span></div>
                        <div class="total-row"><span>Tax</span><span>$379.50</span></div>
                        <div class="total-due"><span>Total</span><span class="total-due-value">$4,174.50</span></div>
                        <div class="status-badge status-paid">
                            <p class="status-paid-text"><span>✓</span> Payment Received</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="invoice-footer">
                <p class="footer-note">This is a computer generated invoice. No signature required.</p>
                <p class="footer-thanks">Thank you for your business!</p>
            </div>
        </div>
    </div>
</body>
</html>