<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .email-header p {
            margin: 10px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 40px 30px;
        }
        .success-icon {
            text-align: center;
            margin-bottom: 20px;
        }
        .success-icon svg {
            width: 60px;
            height: 60px;
            fill: #28a745;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 15px;
        }
        .message {
            font-size: 15px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .order-details {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .order-details h2 {
            font-size: 18px;
            color: #333;
            margin: 0 0 15px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #555;
        }
        .detail-value {
            color: #333;
            text-align: right;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .products-table th {
            background-color: #667eea;
            color: #ffffff;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        .products-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            color: #555;
        }
        .products-table tr:last-child td {
            border-bottom: none;
        }
        .total-row {
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 16px;
        }
        .payment-info {
            background: linear-gradient(135deg, #e8eef5 0%, #f8fafc 100%);
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 5px;
        }
        .payment-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        .payment-info strong {
            color: #333;
        }
        .info-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 5px;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 14px;
            color: #856404;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 15px;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            color: #666;
            font-size: 13px;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 8px;
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <!-- Logo -->
            <div style="margin-bottom: 20px;">
                <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ gs()->site_name }}" style="max-width: 150px; height: auto;">
            </div>
            <h1>✓ Order Confirmed</h1>
            <p>Thank you for your purchase!</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <!-- Success Icon -->
            <div class="success-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>

            <!-- Greeting -->
            <p class="greeting">Hello @if(isset($deposit->user)) {{ $deposit->user->username }} @else Customer @endif,</p>

            <!-- Message -->
            <p class="message">
                We're pleased to confirm that we've received your order! Your order is currently <strong>pending</strong> and will be processed by our admin team. Once approved, your payment will be transferred to your PayPal account within <strong>2 hours</strong>.
            </p>

            <!-- Order Details -->
            <div class="order-details">
                <h2>Order Summary</h2>
                <div class="detail-row">
                    <span class="detail-label">Order Code:</span>
                    <span class="detail-value"><strong>{{ $deposit->code }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Transaction ID:</span>
                    <span class="detail-value">{{ $deposit->trx }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Order Date:</span>
                    <span class="detail-value">{{ $deposit->created_at->format('F d, Y h:i A') }}</span>
                </div>
            </div>

            <!-- Products Table -->
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
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->product->name }}</td>
                        <td style="text-align: center;">{{ $order->qty }}</td>
                        <td style="text-align: right;">{{ showAmount($order->product_price) }}</td>
                        <td style="text-align: right;">{{ showAmount($order->total_price) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">Total Amount:</td>
                        <td style="text-align: right;">{{ showAmount($totalAmount) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Payment Information -->
            <div class="payment-info">
                <p><strong>Payment Details:</strong></p>
                <p>• Amount Paid: <strong>₿{{ $btcAmount }} BTC</strong></p>
                <p>• Payment Method: <strong>Bitcoin (BTC)</strong></p>
                <p>• Status: <strong>Pending Approval</strong></p>
            </div>

            <!-- Important Information -->
            <div class="info-box">
                <p><strong>⚠️ Important:</strong></p>
                <p>• Your order is currently pending and awaiting admin approval.</p>
                <p>• Once approved by our admin team, your payment will be transferred to your PayPal account within 2 hours maximum.</p>
                <p>• You'll receive another email once the order status is updated.</p>
                <p>• Please ensure your PayPal email address is correct: <strong>{{ $deposit->shipping['email'] ?? 'N/A' }}</strong></p>
            </div>

            <!-- Support Button -->
            <div class="button-container">
                <a href="{{ route('contact') }}" class="button">Need Help? Contact Support</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ gs('site_name') }}</strong></p>
            <p>Thank you for choosing our service!</p>
            <p style="margin-top: 15px; color: #999; font-size: 12px;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
