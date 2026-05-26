@extends('layouts.store')
@section('title', 'Herbal Roots — Pure & Natural Herbal Products')
@section('meta', 'Shop premium organic herbal supplements, oils and remedies at Herbal Roots — pure, natural, lab-tested and ethically sourced. Cash on Delivery across Pakistan.')

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'Herbal Roots',
    'url' => url('/'),
    'logo' => asset('images/logo.svg'),
    'description' => 'Premium organic herbal supplements, oils and remedies — pure, natural and ethically sourced.',
    'contactPoint' => [
        '@type' => 'ContactPoint',
        'telephone' => '+92-300-1234567',
        'contactType' => 'customer service',
        'areaServed' => 'PK',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
<!-- HERO -->
@php($heroProduct = $bestSellers->first() ?? $featured->first())
<section class="hero">
    <div class="hero-bg"></div>
    <div class="container hero-inner">
        <div class="reveal">
            <span class="hero-rating"><span class="s">★★★★★</span> Trusted by <b>50,000+</b> happy customers</span>
            <h1>Pure Herbal Wellness,<br><span class="accent">Delivered to You</span></h1>
            <p>Premium organic supplements, oils and remedies — ethically sourced, lab-tested and crafted to help you feel your best, naturally.</p>
            <div class="hero-cta">
                <a href="{{ route('shop.index') }}" class="btn btn-gold">Shop Best Sellers →</a>
                <a href="{{ route('about') }}" class="play"><span class="pi">▶</span> Our Story</a>
            </div>
            <div class="trust-mini">
                <span><span class="tk">✓</span> Free shipping over Rs.5,000</span>
                <span><span class="tk">✓</span> 100% lab-tested</span>
                <span><span class="tk">✓</span> Cash on delivery</span>
            </div>
        </div>

        <div class="hero-art reveal d2">
            <div class="showcase">
                @if($heroProduct)
                    @if($heroProduct->on_sale)
                        <div class="showcase-badge"><b>-{{ $heroProduct->discount_percent }}%</b><small>OFF</small></div>
                    @else
                        <div class="showcase-badge"><b>★</b><small>BEST</small></div>
                    @endif
                    <a href="{{ route('shop.show', $heroProduct->slug) }}" class="showcase-card">
                        <div class="img-wrap"><img src="{{ $heroProduct->image_url }}" alt="{{ $heroProduct->name }}"></div>
                        <div class="showcase-info">
                            <span class="sc-cat">{{ $heroProduct->category->name ?? 'Best Seller' }}</span>
                            <h3>{{ $heroProduct->name }}</h3>
                            <div class="stars">{!! str_repeat('★', (int) round($heroProduct->rating)) !!} <span class="muted" style="font-size:.78rem">({{ $heroProduct->rating }})</span></div>
                            <div class="sc-row">
                                <div class="sc-price">Rs. {{ number_format($heroProduct->current_price) }}@if($heroProduct->on_sale)<span class="w">Rs. {{ number_format($heroProduct->price) }}</span>@endif</div>
                                <span class="btn btn-primary btn-sm">View →</span>
                            </div>
                        </div>
                    </a>
                @endif
                <div class="mini-stat m1"><span class="mi">🌿</span><div>100% Organic<small>Pure extracts</small></div></div>
                <div class="mini-stat m2"><span class="mi">🚚</span><div>Fast Delivery<small>2–4 days nationwide</small></div></div>
            </div>
        </div>
    </div>
</section>

<!-- TRUST STRIP -->
<section class="trust">
    <div class="container row">
        <div class="item"><span class="ico">🚚</span> Free Shipping over Rs.5000</div>
        <div class="item"><span class="ico">🌿</span> 100% Organic Herbs</div>
        <div class="item"><span class="ico">🔬</span> Lab-Tested Purity</div>
        <div class="item"><span class="ico">↩️</span> 7-Day Easy Returns</div>
    </div>
</section>

<!-- CATEGORIES -->
<section class="section">
    <div class="container">
        <div class="text-center reveal">
            <span class="eyebrow">Browse by need</span>
            <h2 class="section-title">Shop by Category</h2>
            <p class="section-sub">From immunity boosters to calming herbs — find the right remedy for your wellness goal.</p>
        </div>
        <div class="cat-grid">
            @foreach($categories->take(6) as $cat)
                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="cat-card reveal">
                    <img src="{{ $cat->image ? asset($cat->image) : asset('images/categories/immunity.svg') }}" alt="{{ $cat->name }}">
                    <div class="ov">
                        <h3>{{ $cat->name }}</h3>
                        <span>{{ $cat->products_count }} products</span>
                        <span class="go">Explore →</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- FEATURED -->
<section class="section" style="background:#fff">
    <div class="container">
        <div class="text-center reveal">
            <span class="eyebrow">Hand-picked</span>
            <h2 class="section-title">Featured Products</h2>
            <p class="section-sub">Our most-loved herbal blends, trusted by thousands for everyday wellbeing.</p>
        </div>
        <div class="prod-grid">
            @foreach($featured as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div class="text-center" style="margin-top:2.5rem">
            <a href="{{ route('shop.index') }}" class="btn btn-outline">View All Products</a>
        </div>
    </div>
</section>

<!-- ABOUT SPLIT -->
<section class="section">
    <div class="container split">
        <div class="reveal"><img src="{{ asset('images/about.svg') }}" alt="About Herbal Roots"></div>
        <div class="reveal d2">
            <span class="eyebrow">Our Promise</span>
            <h2 class="section-title">Rooted in Nature, Backed by Science</h2>
            <p class="muted">For generations, herbs have been nature's medicine cabinet. We bring that ancient wisdom to your home — purified, standardized and rigorously tested so you get nothing but the good stuff.</p>
            <ul class="feature-list">
                <li><span class="fi">🌱</span><div><h4>Ethically Sourced</h4><span class="muted">Directly from organic farms, no middlemen.</span></div></li>
                <li><span class="fi">🔬</span><div><h4>Lab Verified</h4><span class="muted">Every batch tested for purity &amp; potency.</span></div></li>
                <li><span class="fi">♻️</span><div><h4>Eco Packaging</h4><span class="muted">Recyclable, plastic-free wherever possible.</span></div></li>
            </ul>
            <a href="{{ route('about') }}" class="btn btn-primary" style="margin-top:1.6rem">Learn More</a>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section" style="background:var(--beige)">
    <div class="container">
        <div class="text-center reveal">
            <span class="eyebrow">Simple &amp; quick</span>
            <h2 class="section-title">How It Works</h2>
        </div>
        <div class="steps">
            <div class="step reveal d1"><div class="num">1</div><h4 style="color:var(--green-800)">Browse &amp; Choose</h4><p class="muted">Explore our curated range of herbal products by your wellness goal.</p></div>
            <div class="step reveal d2"><div class="num">2</div><h4 style="color:var(--green-800)">Order Securely</h4><p class="muted">Add to cart and checkout with Cash on Delivery or bank transfer.</p></div>
            <div class="step reveal d3"><div class="num">3</div><h4 style="color:var(--green-800)">Wellness Delivered</h4><p class="muted">Fast, careful delivery straight to your doorstep.</p></div>
        </div>
    </div>
</section>

<!-- NEW ARRIVALS -->
<section class="section" style="background:#fff">
    <div class="container">
        <div class="text-center reveal">
            <span class="eyebrow">Fresh on the shelf</span>
            <h2 class="section-title">New Arrivals</h2>
        </div>
        <div class="prod-grid">
            @foreach($newArrivals->take(4) as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="section">
    <div class="container">
        <div class="text-center reveal">
            <span class="eyebrow">Loved by many</span>
            <h2 class="section-title">What Our Customers Say</h2>
        </div>
        <div class="t-grid">
            <div class="t-card reveal d1"><p>The Ashwagandha really helped my sleep and stress levels. Quality you can feel — will reorder for sure!</p><div class="t-author"><span class="av">A</span><div><b>Ayesha K.</b><div class="muted" style="font-size:.82rem">Verified Buyer</div></div></div></div>
            <div class="t-card reveal d2"><p>Finally a herbal brand that's transparent about sourcing. The turmeric capsules are top-notch.</p><div class="t-author"><span class="av">B</span><div><b>Bilal R.</b><div class="muted" style="font-size:.82rem">Verified Buyer</div></div></div></div>
            <div class="t-card reveal d3"><p>Fast delivery, beautiful packaging and the neem oil cleared my skin. Highly recommended!</p><div class="t-author"><span class="av">S</span><div><b>Sana M.</b><div class="muted" style="font-size:.82rem">Verified Buyer</div></div></div></div>
        </div>
    </div>
</section>

<!-- CTA / NEWSLETTER -->
<section class="section" style="padding-top:0">
    <div class="container">
        <div class="cta-band reveal">
            <span class="eyebrow" style="color:var(--gold)">Join the family</span>
            <h2>Get 10% Off Your First Order</h2>
            <p style="opacity:.9;max-width:480px;margin:.6rem auto 0">Subscribe for herbal wellness tips, exclusive offers and early access to new products.</p>
            <form class="newsletter" onsubmit="event.preventDefault(); this.reset(); toast('Subscribed! Check your inbox 🌿');">
                <input type="email" placeholder="Enter your email" required>
                <button class="btn btn-gold" type="submit">Subscribe</button>
            </form>
        </div>
    </div>
</section>
@endsection
