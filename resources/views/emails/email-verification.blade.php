<x-mail::message>

{!! $email->message !!}

<x-mail::panel>
<span style="font-weight: 700; letter-spacing: 5px"> {{ $code }} </span>
</x-mail::panel>


{!! $email->footer !!}
</x-mail::message>
