@component('mail::message')
Incoming contact us request from a customer via homepage contact us form.

# Customer Information: 
First name: {{ $input->name }} <br>
Last name: {{ $input->lastname }}

# Customer Contacts: <br>
Email: {{ $input->email }} <br>
Phone: {{ $input->phone }}

# Customer message: <br>
{{ $input->message }}


Thanks,<br>
{{ config('app.name') }}
@endcomponent
