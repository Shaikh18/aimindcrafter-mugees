<x-mail::message>

{!! $email->message !!}

Order ID: <span style="font-weight: 600">{{ $order->order_id }}</span>

{!! $email->footer !!}
</x-mail::message>
