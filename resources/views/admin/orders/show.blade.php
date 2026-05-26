@extends('layouts.store')
@section('title', 'Order '.$order->order_number.' — Admin')

@section('content')
<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container"><h1>Order {{ $order->order_number }}</h1>
        <div class="crumbs"><a href="{{ route('admin.orders.index') }}">Admin</a> / Orders / {{ $order->order_number }}</div></div>
</div>

<section class="section">
    <div class="container checkout-grid">
        <!-- Items + customer -->
        <div class="reveal">
            <div class="form-card" style="margin-bottom:1.4rem">
                <h3>Items Ordered</h3>
                <table class="cart-table">
                    <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead>
                    <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td><b style="color:var(--green-800)">{{ $item->product_name }}</b></td>
                            <td>Rs. {{ number_format($item->price) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td><b>Rs. {{ number_format($item->price * $item->quantity) }}</b></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="sum-row" style="margin-top:1rem"><span>Subtotal</span><b>Rs. {{ number_format($order->subtotal) }}</b></div>
                <div class="sum-row"><span>Shipping</span><b>{{ $order->shipping == 0 ? 'Free' : 'Rs. '.number_format($order->shipping) }}</b></div>
                <div class="sum-row total"><span>Total</span><span>Rs. {{ number_format($order->total) }}</span></div>
            </div>

            <div class="form-card">
                <h3>Customer &amp; Shipping</h3>
                <div class="sum-row"><span>Name</span><b>{{ $order->name }}</b></div>
                <div class="sum-row"><span>Email</span><b>{{ $order->email }}</b></div>
                <div class="sum-row"><span>Phone</span><b>{{ $order->phone }}</b></div>
                <div class="sum-row"><span>City</span><b>{{ $order->city }}</b></div>
                <div class="sum-row"><span>Address</span><b style="text-align:right;max-width:60%">{{ $order->address }}</b></div>
                @if($order->notes)<div class="sum-row"><span>Notes</span><b style="text-align:right;max-width:60%">{{ $order->notes }}</b></div>@endif
                <div class="sum-row"><span>Account</span><b>{{ $order->user ? $order->user->email : 'Guest checkout' }}</b></div>
            </div>
        </div>

        <!-- Status control -->
        <div class="reveal d2">
            <div class="summary">
                <h3>Order Status</h3>
                <p style="margin-bottom:1rem"><span class="status-pill status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></p>
                <div class="sum-row"><span>Placed</span><b>{{ $order->created_at->format('d M Y, h:i A') }}</b></div>
                <div class="sum-row"><span>Payment</span><b>{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : ucfirst($order->card_brand ?? 'Card').' •••• '.($order->card_last4 ?? '') }}</b></div>
                <div class="sum-row"><span>Paid</span><b>{{ $order->is_paid ? '✓ Yes' : '— No (COD)' }}</b></div>
                @if($order->payment_intent_id)<div class="sum-row"><span>Txn ID</span><b style="font-size:.78rem">{{ $order->payment_intent_id }}</b></div>@endif

                <form action="{{ route('admin.orders.status', $order) }}" method="POST" style="margin-top:1.2rem">
                    @csrf @method('PATCH')
                    <div class="field">
                        <label>Update Status</label>
                        <select name="status">
                            @foreach(['pending','processing','completed','cancelled'] as $s)
                                <option value="{{ $s }}" @selected($order->status==$s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Save Status</button>
                </form>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-block" style="margin-top:.6rem">← Back to Orders</a>
            </div>
        </div>
    </div>
</section>
@endsection
