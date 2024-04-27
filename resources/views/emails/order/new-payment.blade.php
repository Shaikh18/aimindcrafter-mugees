<x-mail::message>

There was a new successful payment with {{ ucfirst($order->gateway) }}

Order ID: <span style="font-weight: 600">{{ $order->order_id }}</span>

<x-mail::panel>
# Order Details: <br>
Plan Type: {{ ucfirst($order->frequency) }} <br>
Plan Name: {{ $order->plan_name }} <br>
Total: {{ $order->price }}{{ $order->currency }}
</x-mail::panel>



Thank you,<br>
{{ config('app.name') }}
</x-mail::message>
