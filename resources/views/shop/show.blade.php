@extends('layouts.store')
@section('title', $product->name.' — Herbal Roots')
@section('meta', $product->short_description)

@section('content')
<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container">
        <h1>{{ $product->name }}</h1>
        <div class="crumbs"><a href="{{ route('home') }}">Home</a> / <a href="{{ route('shop.index') }}">Shop</a> / {{ $product->name }}</div>
    </div>
</div>

<section class="section">
    <div class="container pd-grid">
        <div class="pd-media reveal">
            @if($product->on_sale)<span class="prod-tag" style="font-size:.9rem">-{{ $product->discount_percent }}% OFF</span>@endif
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
        </div>
        <div class="reveal d2">
            <span class="pd-cat">{{ $product->category->name ?? 'Herbal' }}</span>
            <h1 class="pd-title">{{ $product->name }}</h1>
            <div class="stars">{!! str_repeat('★', (int) round($product->rating)) . str_repeat('☆', 5 - (int) round($product->rating)) !!}
                <span class="muted" style="font-size:.85rem">{{ $product->rating }} / 5.0</span></div>
            <div class="pd-price">Rs. {{ number_format($product->current_price) }}
                @if($product->on_sale)<span class="was">Rs. {{ number_format($product->price) }}</span>@endif
            </div>
            <p class="pd-desc">{{ $product->short_description }}</p>

            @if($product->stock > 0)
                <p class="in-stock">● In Stock ({{ $product->stock }} available)</p>
            @else
                <p class="out-stock">● Out of Stock</p>
            @endif

            <form action="{{ route('cart.add', $product) }}" method="POST" class="ajax-add">
                @csrf
                <div class="pd-actions">
                    <div class="qty">
                        <button type="button" data-dec>−</button>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                        <button type="button" data-inc>+</button>
                    </div>
                    <button type="submit" class="btn btn-primary" {{ $product->stock < 1 ? 'disabled' : '' }}>🛒 Add to Cart</button>
                    <a href="{{ route('cart.index') }}" class="btn btn-gold">Buy Now</a>
                </div>
            </form>

            @if($product->benefits)
                <h4 style="color:var(--green-800);margin-top:.5rem">Key Benefits</h4>
                <div class="benefit-tags">
                    @foreach(explode(',', $product->benefits) as $b)
                        <span>✓ {{ trim($b) }}</span>
                    @endforeach
                </div>
            @endif

            <div class="pd-meta">
                <span>🚚 Free delivery on orders over Rs. 5,000</span>
                <span>🔬 Lab-tested for purity &amp; potency</span>
                <span>↩️ 7-day easy returns</span>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top:3rem">
        <div class="form-card reveal">
            <h3>Description</h3>
            <p class="muted">{{ $product->description ?: $product->short_description }}</p>
        </div>
    </div>

    @if($related->count())
    <div class="container" style="margin-top:3.5rem">
        <div class="text-center reveal"><span class="eyebrow">You may also like</span><h2 class="section-title">Related Products</h2></div>
        <div class="prod-grid">
            @foreach($related as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
    @endif
</section>
@endsection
