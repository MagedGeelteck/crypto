<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-container {
            padding: 0;
        }
        .email-header {
            background: linear-gradient(135deg, {{ $statusColor }} 0%, {{ $statusColor }}dd 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .email-header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .status-icon {
            width: 80px;
            height: 80px;
            margin: 20px auto;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .status-icon svg {
            width: 40px;
            height: 40px;
            fill: {{ $statusColor }};
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #1f2937;
        }
        .message {
            font-size: 16px;
            color: #4b5563;
            margin-bottom: 30px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin: 20px 0;
            background-color: {{ $statusColor }};
            color: white;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .info-table tr {
            border-bottom: 1px solid #e5e7eb;
        }
        .info-table td {
            padding: 12px 0;
        }
        .info-table td:first-child {
            color: #6b7280;
            width: 40%;
        }
        .info-table td:last-child {
            font-weight: 600;
            color: #1f2937;
            text-align: right;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: #f9fafb;
            border-radius: 8px;
            overflow: hidden;
        }
        .products-table thead {
            background-color: #f3f4f6;
        }
        .products-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }
        .products-table td {
            padding: 12px;
            border-top: 1px solid #e5e7eb;
            color: #1f2937;
        }
        .products-table .total-row {
            background-color: #f3f4f6;
            font-weight: 600;
        }
        .info-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 8px 0;
            font-size: 14px;
            color: #92400e;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .support-button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s;
        }
        .support-button:hover {
            transform: translateY(-2px);
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            margin: 5px 0;
            font-size: 14px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <!-- Logo -->
            <div style="margin-bottom: 20px;">
                <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ gs()->site_name }}" style="max-width: 150px; height: auto;">
            </div>
            <h1>Order Status Update</h1>
            <p>Your order #{{ $deposit->code }} has been updated</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <!-- Status Icon -->
            <div class="status-icon">
                @if($statusText == 'Approved')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                @elseif($statusText == 'Rejected')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                @endif
            </div>

            <!-- Greeting -->
            <p class="greeting">Hello @if(isset($deposit->user)) {{ $deposit->user->username }} @else Customer @endif,</p>

            <!-- Message -->
            <p class="message">
                Your order status has been updated. Here are the details:
            </p>

            <!-- Status Badge -->
            <div style="text-align: center;">
                <span class="status-badge">{{ $statusText }}</span>
            </div>

            <!-- Order Summary -->
            <h2 class="section-title">Order Summary</h2>
            <table class="info-table">
                <tr>
                    <td>Order Code:</td>
                    <td>#{{ $deposit->code }}</td>
                </tr>
                <tr>
                    <td>Transaction ID:</td>
                    <td>{{ $deposit->trx }}</td>
                </tr>
                <tr>
                    <td>Order Date:</td>
                    <td>{{ showDateTime($deposit->created_at, 'd M Y, h:i A') }}</td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>{{ $statusText }}</td>
                </tr>
            </table>

            <!-- Products -->
            <h2 class="section-title">Order Items</h2>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Price</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sells as $sell)
                    <tr>
                        <td>{{ $sell->product->name }}</td>
                        <td style="text-align: center;">{{ $sell->qty }}</td>
                        <td style="text-align: right;">{{ showAmount($sell->product_price) }}</td>
                        <td style="text-align: right;">{{ showAmount($sell->total_price) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">Total Amount:</td>
                        <td style="text-align: right;">{{ showAmount($totalAmount) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Status Specific Messages -->
            @if($statusText == 'Approved')
            <div class="info-box" style="background-color: #d1fae5; border-left-color: #10b981;">
                <p style="color: #065f46; font-weight: 600;">✓ Your order has been approved!</p>
                <p style="color: #065f46;">• Your payment will be processed and transferred to your PayPal account within 2 hours maximum.</p>
                <p style="color: #065f46;">• PayPal Email: <strong>{{ $deposit->shipping['email'] ?? 'N/A' }}</strong></p>
                <p style="color: #065f46;">• You will receive another notification once the payment is completed.</p>
            </div>
            @elseif($statusText == 'Rejected')
            <div class="info-box" style="background-color: #fee2e2; border-left-color: #ef4444;">
                <p style="color: #991b1b; font-weight: 600;">✗ Your order has been rejected</p>
                <p style="color: #991b1b;">• Unfortunately, we were unable to process your order.</p>
                <p style="color: #991b1b;">• If you believe this is an error, please contact our support team for assistance.</p>
                <p style="color: #991b1b;">• We apologize for any inconvenience caused.</p>
            </div>
            @else
            <div class="info-box">
                <p style="font-weight: 600;">ℹ Order Status: {{ $statusText }}</p>
                <p>• Your order is being processed.</p>
                <p>• You'll receive another email once there are further updates.</p>
                <p>• PayPal Email: <strong>{{ $deposit->shipping['email'] ?? 'N/A' }}</strong></p>
            </div>
            @endif

            <!-- Support Button -->
            <div class="button-container">
                <a href="{{ route('contact') }}" class="support-button">Contact Support</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ gs()->site_name }}</strong></p>
            <p>This is an automated email. Please do not reply directly to this message.</p>
        </div>
    </div>
    </div>
</body>
</html>
