<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Order Submitted</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; }
        .content { background-color: #f9f9f9; padding: 20px; margin-top: 20px; }
        .info-row { margin: 10px 0; padding: 10px; background-color: white; border-left: 4px solid #4CAF50; }
        .label { font-weight: bold; color: #555; }
        .value { color: #333; }
        .footer { margin-top: 20px; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <!-- Logo -->
            <div style="margin-bottom: 15px;">
                <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ gs()->site_name }}" style="max-width: 120px; height: auto;">
            </div>
            <h1>🛒 New Order Submitted</h1>
        </div>
        
        <div class="content">
            <p><strong>A new order has been submitted and is awaiting payment confirmation.</strong></p>
            
            <div class="info-row">
                <span class="label">Order Code:</span>
                <span class="value">{{ $deposit->code ?? 'Pending' }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Transaction ID:</span>
                <span class="value">{{ $deposit->trx }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Customer:</span>
                <span class="value">{{ $deposit->user->username }} ({{ $deposit->user->email ?? 'No email' }})</span>
            </div>
            
            <div class="info-row">
                <span class="label">Amount:</span>
                <span class="value">{{ showAmount($deposit->amount) }} {{ gs('cur_text') }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Payment Method:</span>
                <span class="value">{{ $deposit->method_currency }} ({{ $deposit->gatewayCurrency()->name }})</span>
            </div>
            
            <div class="info-row">
                <span class="label">Final Amount:</span>
                <span class="value">{{ showAmount($deposit->final_amount, currencyFormat: false) }} {{ $deposit->method_currency }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="value">{{ $deposit->status == 2 ? 'Pending Payment Confirmation' : 'Payment Initiated' }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Date:</span>
                <span class="value">{{ $deposit->created_at->format('M d, Y - h:i A') }}</span>
            </div>
            
            <p style="margin-top: 20px;">
                <a href="{{ route('admin.deposit.details', $deposit->id) }}" 
                   style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">
                    View Order Details
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from {{ gs('site_name') }}</p>
            <p>{{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
