<div style="font-family: sans-serif; font-size: 14px; color: #111">
    <!-- Logo -->
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ gs()->site_name }}" style="max-width: 150px; height: auto;">
    </div>
    
    <h2>New Support Ticket Opened</h2>
    <p><strong>Ticket ID:</strong> {{ $ticket->ticket }}</p>
    <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
    <p><strong>Opened by:</strong> {{ $ticket->name }} @if($ticket->username) ({{ $ticket->username }}) @endif</p>
    <p><strong>Email:</strong> {{ $ticket->email ?? 'N/A' }}</p>
    <hr>
    <h3>Initial Message</h3>
    <div>{!! nl2br(e($message->message)) !!}</div>
    <hr>
    <p>To view the ticket in the admin panel, visit: <a href="{{ route('admin.ticket.view', $ticket->id) }}">Ticket #{{ $ticket->ticket }}</a></p>
</div>
