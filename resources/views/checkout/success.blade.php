@extends('layouts.store')
@section('title', 'Order Confirmed — Herbal Roots')

@section('content')
<section class="section">
    <div class="container">
        <div class="success-box reveal">
            <div class="check">✓</div>
            <h2 class="section-title" style="margin-bottom:.3rem">Thank You for Your Order!</h2>
            <p class="muted">Your order has been placed successfully. A confirmation has been sent to <b>{{ $order->email }}</b>.</p>

            <div class="order-card">
                <div class="sum-row"><span>Order Number</span><b style="color:var(--green-800)">{{ $order->order_number }}</b></div>
                <div class="sum-row"><span>Name</span><b>{{ $order->name }}</b></div>
                <div class="sum-row"><span>Payment</span><b>
                    @if($order->payment_method == 'cod')
                        Cash on Delivery
                    @else
                        {{ ucfirst($order->card_brand ?? 'Card') }} •••• {{ $order->card_last4 ?? '' }}
                    @endif
                </b></div>
                @if($order->is_paid)
                    <div class="sum-row"><span>Payment Status</span><span class="status-pill status-completed">✓ Paid</span></div>
                @endif
                <div class="sum-row"><span>Status</span><span class="status-pill status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></div>
                <hr style="border:none;border-top:1px solid var(--beige);margin:.6rem 0">
                @foreach($order->items as $item)
                    <div class="sum-row"><span>{{ $item->product_name }} × {{ $item->quantity }}</span><b>Rs. {{ number_format($item->price * $item->quantity) }}</b></div>
                @endforeach
                <div class="sum-row total"><span>Total</span><span>Rs. {{ number_format($order->total) }}</span></div>
            </div>

            <a href="{{ route('shop.index') }}" class="btn btn-primary">Continue Shopping</a>
            @auth<a href="{{ route('orders.index') }}" class="btn btn-outline">View My Orders</a>@endauth
        </div>
    </div>
</section>
@endsection
