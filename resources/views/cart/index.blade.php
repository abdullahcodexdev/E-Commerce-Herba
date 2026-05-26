@extends('layouts.store')
@section('title', 'Your Cart — Herbal Roots')

@section('content')
{{-- Hidden blocks consumed by the slide-out drawer (JS scrapes these) --}}
<div style="display:none">
    <div id="drawerLines">
        @forelse($lines as $line)
            <div class="mini-line">
                <button class="mini-x" data-remove="{{ $line->product->id }}" title="Remove this item" aria-label="Remove">&times;</button>
                <img src="{{ $line->product->image_url }}" alt="">
                <div class="info">
                    <b>{{ $line->product->name }}</b>
                    <span class="muted" style="font-size:.85rem">{{ $line->quantity }} × Rs. {{ number_format($line->product->current_price) }}</span>
                    <div><button class="rm" data-remove="{{ $line->product->id }}">Remove</button></div>
                </div>
                <b style="color:var(--green-700)">Rs. {{ number_format($line->line_total) }}</b>
            </div>
        @empty
            <div class="empty-cart"><div class="e">🛒</div><p>Your cart is empty</p></div>
        @endforelse
    </div>
    <div id="drawerFootInner">
        @if($lines->count())
            <div class="sum-row"><span>Subtotal</span><b>Rs. {{ number_format($subtotal) }}</b></div>
            <div class="sum-row"><span>Shipping</span><b>{{ $shipping == 0 ? 'Free' : 'Rs. '.number_format($shipping) }}</b></div>
            <div class="sum-row total"><span>Total</span><span>Rs. {{ number_format($total) }}</span></div>
            <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-block" style="margin-top:.8rem">Checkout</a>
            <a href="{{ route('shop.index') }}" class="btn btn-outline btn-block" style="margin-top:.5rem">Continue Shopping</a>
        @endif
    </div>
</div>

<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container"><h1>Shopping Cart</h1>
        <div class="crumbs"><a href="{{ route('home') }}">Home</a> / Cart</div></div>
</div>

<section class="section">
    <div class="container">
        @if($lines->count())
        <div class="checkout-grid">
            <div class="reveal">
                <table class="cart-table">
                    <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th><th></th></tr></thead>
                    <tbody>
                    @foreach($lines as $line)
                        <tr>
                            <td>
                                <div class="cart-prod">
                                    <img src="{{ $line->product->image_url }}" alt="">
                                    <div><a href="{{ route('shop.show', $line->product->slug) }}"><b style="color:var(--green-800)">{{ $line->product->name }}</b></a>
                                        <div class="muted" style="font-size:.82rem">{{ $line->product->category->name ?? '' }}</div></div>
                                </div>
                            </td>
                            <td>Rs. {{ number_format($line->product->current_price) }}</td>
                            <td>
                                <form action="{{ route('cart.update', $line->product) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="qty">
                                        <button type="submit" name="quantity" value="{{ $line->quantity - 1 }}">−</button>
                                        <input type="text" value="{{ $line->quantity }}" readonly style="width:40px">
                                        <button type="submit" name="quantity" value="{{ $line->quantity + 1 }}">+</button>
                                    </div>
                                </form>
                            </td>
                            <td><b style="color:var(--green-700)">Rs. {{ number_format($line->line_total) }}</b></td>
                            <td>
                                <form action="{{ route('cart.remove', $line->product) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="icon-btn" title="Remove">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="reveal d2">
                <div class="summary">
                    <h3>Order Summary</h3>
                    <div class="sum-row"><span>Subtotal</span><b>Rs. {{ number_format($subtotal) }}</b></div>
                    <div class="sum-row"><span>Shipping</span><b>{{ $shipping == 0 ? 'Free 🎉' : 'Rs. '.number_format($shipping) }}</b></div>
                    @if($shipping > 0)<p class="muted" style="font-size:.82rem">Add Rs. {{ number_format(5000 - $subtotal) }} more for free shipping!</p>@endif
                    <div class="sum-row total"><span>Total</span><span>Rs. {{ number_format($total) }}</span></div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-block" style="margin-top:1rem">Proceed to Checkout →</a>
                    <a href="{{ route('shop.index') }}" class="btn btn-outline btn-block" style="margin-top:.6rem">Continue Shopping</a>
                </div>
            </div>
        </div>
        @else
            <div class="empty-cart" style="background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:4rem 2rem">
                <div class="e">🛒</div>
                <h3 style="color:var(--green-800)">Your cart is empty</h3>
                <p>Looks like you haven't added any herbal goodness yet.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary" style="margin-top:1rem">Start Shopping</a>
            </div>
        @endif
    </div>
</section>
@endsection
