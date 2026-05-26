@extends('layouts.store')
@section('title', 'My Orders — Herbal Roots')

@section('content')
<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container"><h1>My Account</h1>
        <div class="crumbs">Welcome back, {{ auth()->user()->name }} 🌿</div></div>
</div>

<section class="section">
    <div class="container">
        <div style="display:flex;gap:1rem;margin-bottom:1.6rem;flex-wrap:wrap">
            <a href="{{ route('orders.index') }}" class="btn btn-primary btn-sm">My Orders</a>
            <a href="{{ route('profile.edit') }}" class="btn btn-outline btn-sm">Edit Profile</a>
            <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn btn-outline btn-sm">Logout</button></form>
        </div>

        @forelse($orders as $order)
            <div class="form-card reveal" style="margin-bottom:1.2rem">
                <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:.5rem;align-items:center">
                    <div><b style="color:var(--green-800)">{{ $order->order_number }}</b>
                        <div class="muted" style="font-size:.85rem">{{ $order->created_at->format('d M Y, h:i A') }}</div></div>
                    <span class="status-pill status-{{ $order->status == 'completed' ? 'completed' : 'pending' }}">{{ ucfirst($order->status) }}</span>
                </div>
                <hr style="border:none;border-top:1px solid var(--beige);margin:.9rem 0">
                @foreach($order->items as $item)
                    <div class="sum-row"><span>{{ $item->product_name }} × {{ $item->quantity }}</span><b>Rs. {{ number_format($item->price * $item->quantity) }}</b></div>
                @endforeach
                <div class="sum-row total"><span>Total</span><span>Rs. {{ number_format($order->total) }}</span></div>
            </div>
        @empty
            <div class="empty-cart" style="background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:3rem">
                <div class="e">📦</div><h3 style="color:var(--green-800)">No orders yet</h3>
                <p>Your placed orders will appear here.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="margin-top:1rem">Start Shopping</a>
            </div>
        @endforelse
    </div>
</section>
@endsection
