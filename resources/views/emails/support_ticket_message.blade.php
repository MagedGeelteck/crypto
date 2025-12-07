<div style="font-family: sans-serif; font-size: 14px; color: #111">
    <!-- Logo -->
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ gs()->site_name }}" style="max-width: 150px; height: auto;">
    </div>
    
    <h2>New Message on Support Ticket</h2>
    <p><strong>Ticket ID:</strong> {{ $ticket->ticket }}</p>
    <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
    <p><strong>From:</strong>
        @if($message->admin_id)
            Admin (ID: {{ $message->admin_id }})
        @else
            {{ $ticket->name }} @if($ticket->username) ({{ $ticket->username }}) @endif
        @endif
    </p>
    <hr>
    <div>{!! nl2br(e($message->message)) !!}</div>
    <hr>
    <p>View ticket: <a href="{{ route('admin.ticket.view', $ticket->id) }}">Ticket #{{ $ticket->ticket }}</a></p>
</div>
