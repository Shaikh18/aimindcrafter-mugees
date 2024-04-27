<x-mail::message>

{!! $email->message !!}

<a href="{{ config('app.url') }}/?ref={{ auth()->user()->referral_id }}">Register Now</a>


{!! $email->footer !!}
</x-mail::message>
