<x-mail::message>

{!! $email->message !!}

Order ID: <span style="font-weight: 600">{{ $order->order_id }}</span>

<x-mail::panel>
# Order Details: <br>
Plan Type: {{ ucfirst($order->frequency) }} <br>
Plan Name: {{ $order->plan_name }} <br>
</x-mail::panel>



{!! $email->footer !!}
</x-mail::message>
