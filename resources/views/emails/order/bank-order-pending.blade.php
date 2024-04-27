<x-mail::message>

New Bank Transfer order has been placed and pending your approval

Order ID: <span style="font-weight: 600">{{ $order->order_id }}</span>

<x-mail::panel>
Plan Type: {{ ucfirst($order->frequency) }} <br>
Plan Name: {{ $order->plan_name }} <br>
Total Cost: {{ $order->price }}{{ $order->currency }}
</x-mail::panel>



Thank you,<br>
{{ config('app.name') }}
</x-mail::message>
