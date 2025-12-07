<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Ticket Reply</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .support-icon {
            width: 80px;
            height: 80px;
            margin: 20px auto;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .support-icon svg {
            width: 40px;
            height: 40px;
            fill: #667eea;
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
        .ticket-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin: 20px 0;
            background-color: #667eea;
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
        }
        .reply-box {
            background-color: #f9fafb;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .reply-box .reply-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .reply-box .reply-content {
            font-size: 15px;
            color: #1f2937;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .info-box {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 8px 0;
            font-size: 14px;
            color: #1e40af;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .view-button {
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
        .view-button:hover {
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
            <h1>Support Ticket Reply</h1>
            <p>Our admin has replied to your ticket</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <!-- Support Icon -->
            <div class="support-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
            </div>

            <!-- Greeting -->
            <p class="greeting">Hello {{ $user->username }},</p>

            <!-- Message -->
            <p class="message">
                We have received a new reply to your support ticket. Here are the details:
            </p>

            <!-- Ticket Badge -->
            <div style="text-align: center;">
                <span class="ticket-badge">Ticket #{{ $ticket->ticket }}</span>
            </div>

            <!-- Ticket Information -->
            <h2 class="section-title">Ticket Information</h2>
            <table class="info-table">
                <tr>
                    <td>Ticket ID:</td>
                    <td>#{{ $ticket->ticket }}</td>
                </tr>
                <tr>
                    <td>Subject:</td>
                    <td>{{ $ticket->subject }}</td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>
                        @if($ticket->status == 0)
                            <span style="color: #f59e0b;">Open</span>
                        @elseif($ticket->status == 1)
                            <span style="color: #10b981;">Answered</span>
                        @elseif($ticket->status == 2)
                            <span style="color: #3b82f6;">Customer Reply</span>
                        @else
                            <span style="color: #6b7280;">Closed</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Last Updated:</td>
                    <td>{{ showDateTime($ticket->last_reply, 'd M Y, h:i A') }}</td>
                </tr>
            </table>

            <!-- Admin Reply -->
            <h2 class="section-title">Admin's Reply</h2>
            <div class="reply-box">
                <div class="reply-label">Reply from Support Team:</div>
                <div class="reply-content">{{ $message->message }}</div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <p style="font-weight: 600;">📌 Next Steps:</p>
                <p>• Click the button below to view the full conversation</p>
                <p>• You can reply to this ticket by logging into your account</p>
                <p>• If your issue is resolved, you can close the ticket</p>
            </div>

            <!-- View Button -->
            <div class="button-container">
                <a href="{{ route('ticket.view', $ticket->ticket) }}" class="view-button">View Ticket & Reply</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ gs()->site_name }}</strong></p>
            <p>This is an automated email notification. Please do not reply directly to this message.</p>
            <p>For support, please log in to your account and reply to the ticket.</p>
        </div>
    </div>
    </div>
</body>
</html>
