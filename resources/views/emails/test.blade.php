@component('mail::message')

{{ $input->message }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
