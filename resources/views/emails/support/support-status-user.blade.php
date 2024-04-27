<x-mail::message>

{!! $email->message !!}


<x-mail::panel>
Ticket ID: <br>
<span style="font-weight: 700;"> {{ $ticket->ticket_id }} </span>
</x-mail::panel>

{!! $email->footer !!}
</x-mail::message>

