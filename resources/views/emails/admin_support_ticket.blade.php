<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Support Ticket</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #FF9800; color: white; padding: 20px; text-align: center; }
        .content { background-color: #f9f9f9; padding: 20px; margin-top: 20px; }
        .info-row { margin: 10px 0; padding: 10px; background-color: white; border-left: 4px solid #FF9800; }
        .label { font-weight: bold; color: #555; }
        .value { color: #333; }
        .message-box { background-color: white; padding: 15px; margin: 15px 0; border: 1px solid #ddd; border-radius: 5px; }
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
            <h1>🎫 New Support Ticket Submitted</h1>
        </div>
        
        <div class="content">
            <p><strong>A new support ticket has been submitted and requires attention.</strong></p>
            
            <div class="info-row">
                <span class="label">Ticket ID:</span>
                <span class="value">#{{ $ticket->ticket }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Subject:</span>
                <span class="value">{{ $ticket->subject }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">User:</span>
                <span class="value">{{ $ticket->user->username }} ({{ $ticket->user->email ?? 'No email' }})</span>
            </div>
            
            <div class="info-row">
                <span class="label">Priority:</span>
                <span class="value">{{ ucfirst($ticket->priority) }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="value">{{ $ticket->status == 0 ? 'Open' : ($ticket->status == 1 ? 'Answered' : 'Closed') }}</span>
            </div>
            
            <div class="info-row">
                <span class="label">Date:</span>
                <span class="value">{{ $ticket->created_at->format('M d, Y - h:i A') }}</span>
            </div>
            
            <div class="message-box">
                <strong>Message:</strong>
                <p style="margin-top: 10px;">{{ $ticket->last_reply }}</p>
            </div>
            
            <p style="margin-top: 20px;">
                <a href="{{ route('admin.ticket.view', $ticket->id) }}" 
                   style="display: inline-block; padding: 10px 20px; background-color: #FF9800; color: white; text-decoration: none; border-radius: 5px;">
                    View & Respond to Ticket
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
