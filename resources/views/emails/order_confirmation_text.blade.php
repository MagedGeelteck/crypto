ORDER CONFIRMED
================

Thank you for your purchase!

Hello,

Your order has been successfully placed and is awaiting confirmation.

ORDER SUMMARY
-------------
Order Code: #{{ $deposit->code }}
Transaction ID: {{ $deposit->trx }}
Order Date: {{ showDateTime($deposit->created_at, 'd M Y, h:i A') }}

ORDER ITEMS
-----------
@foreach($orders as $order)
{{ $order->product->name }}
Quantity: {{ $order->qty }}
Price: {{ showAmount($order->product_price) }}
Total: {{ showAmount($order->total_price) }}

@endforeach

Total Amount: {{ showAmount($totalAmount) }}

PAYMENT DETAILS
---------------
BTC Amount: ₿{{ $btcAmount }}
Payment Method: Bitcoin (BTC)
Status: Pending Approval

IMPORTANT INFORMATION
---------------------
• Your order is currently pending and awaiting admin approval.
• Once approved by our admin team, your payment will be transferred to your PayPal account within 2 hours maximum.
• You'll receive another email once the order status is updated.
• Please ensure your PayPal email address is correct: {{ $deposit->shipping['email'] ?? 'N/A' }}

Need help? Contact our support team: {{ route('contact') }}

---
{{ gs()->site_name }}
This is an automated email. Please do not reply directly to this message.
