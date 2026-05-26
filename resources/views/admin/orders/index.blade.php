@extends('layouts.store')
@section('title', 'Admin — Orders | Herbal Roots')

@section('content')
<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container"><h1>⚙ Admin Dashboard</h1>
        <div class="crumbs">Manage customer orders &amp; track sales</div></div>
</div>

<section class="section">
    <div class="container">
        <!-- Stats -->
        <div class="stat-grid reveal">
            <div class="stat-card accent"><div class="sv">{{ $stats['total'] }}</div><div class="sl">Total Orders</div></div>
            <div class="stat-card"><div class="sv">{{ $stats['pending'] }}</div><div class="sl">Pending</div></div>
            <div class="stat-card"><div class="sv">{{ $stats['completed'] }}</div><div class="sl">Completed</div></div>
            <div class="stat-card"><div class="sv">Rs. {{ number_format($stats['revenue']) }}</div><div class="sl">Revenue</div></div>
            <div class="stat-card"><div class="sv">{{ $stats['products'] }}</div><div class="sl">Products</div></div>
            <div class="stat-card"><div class="sv">{{ $stats['customers'] }}</div><div class="sl">Customers</div></div>
        </div>

        <!-- Toolbar -->
        <div class="admin-toolbar">
            <div class="admin-tabs">
                <a href="{{ route('admin.orders.index') }}" class="{{ !request('status') ? 'active' : '' }}">All</a>
                <a href="{{ route('admin.orders.index', ['status'=>'pending']) }}" class="{{ request('status')=='pending' ? 'active' : '' }}">Pending</a>
                <a href="{{ route('admin.orders.index', ['status'=>'processing']) }}" class="{{ request('status')=='processing' ? 'active' : '' }}">Processing</a>
                <a href="{{ route('admin.orders.index', ['status'=>'completed']) }}" class="{{ request('status')=='completed' ? 'active' : '' }}">Completed</a>
                <a href="{{ route('admin.orders.index', ['status'=>'cancelled']) }}" class="{{ request('status')=='cancelled' ? 'active' : '' }}">Cancelled</a>
            </div>
            <form action="{{ route('admin.orders.index') }}" method="GET" class="search-box">
                <input type="text" name="search" placeholder="Search order #, name, phone…" value="{{ request('search') }}">
                <button type="submit">🔍</button>
            </form>
        </div>

        @if($orders->count())
            <table class="cart-table reveal">
                <thead><tr><th>Order #</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr></thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td><b style="color:var(--green-800)">{{ $order->order_number }}</b></td>
                        <td>{{ $order->name }}<div class="muted" style="font-size:.8rem">{{ $order->phone }}</div></td>
                        <td>{{ $order->items_count }}</td>
                        <td><b style="color:var(--green-700)">Rs. {{ number_format($order->total) }}</b></td>
                        <td>{{ $order->payment_method == 'cod' ? 'COD' : 'Card' }}</td>
                        <td><span class="status-pill status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        <td class="muted" style="font-size:.82rem">{{ $order->created_at->format('d M Y') }}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline btn-sm">View</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div>{{ $orders->links() }}</div>
        @else
            <div class="empty-cart" style="background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:3rem">
                <div class="e">📭</div><h3 style="color:var(--green-800)">No orders found</h3>
                <p>When customers place orders, they'll appear here.</p>
            </div>
        @endif
    </div>
</section>
@endsection
