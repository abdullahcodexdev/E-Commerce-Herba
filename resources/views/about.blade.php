@extends('layouts.store')
@section('title', 'About Us — Herbal Roots')

@section('content')
<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container"><h1>Our Story</h1>
        <div class="crumbs"><a href="{{ route('home') }}">Home</a> / About</div></div>
</div>

<section class="section">
    <div class="container split">
        <div class="reveal"><img src="{{ asset('images/about.svg') }}" alt="About"></div>
        <div class="reveal d2">
            <span class="eyebrow">Who we are</span>
            <h2 class="section-title">Bringing Nature's Wisdom to Modern Wellness</h2>
            <p class="muted">Herbal Roots was born from a simple belief — that nature already holds the answers to a healthier life. We blend centuries-old herbal traditions with modern science to craft pure, potent and trustworthy remedies.</p>
            <p class="muted" style="margin-top:1rem">From farm to bottle, every product is ethically sourced, carefully processed and rigorously lab-tested. No fillers. No shortcuts. Just nature, perfected.</p>
            <ul class="feature-list">
                <li><span class="fi">🌱</span><div><h4>100% Natural</h4><span class="muted">Plant-based, no synthetic additives.</span></div></li>
                <li><span class="fi">🤝</span><div><h4>Fair &amp; Ethical</h4><span class="muted">Supporting local organic farmers.</span></div></li>
                <li><span class="fi">🔬</span><div><h4>Science-Backed</h4><span class="muted">Every claim tested, every batch verified.</span></div></li>
            </ul>
        </div>
    </div>
</section>

<section class="section" style="background:var(--beige)">
    <div class="container">
        <div class="text-center reveal"><span class="eyebrow">What drives us</span><h2 class="section-title">Our Values</h2></div>
        <div class="steps">
            <div class="step reveal d1"><div class="num">🌿</div><h4 style="color:var(--green-800)">Purity</h4><p class="muted">Clean, honest ingredients you can trust completely.</p></div>
            <div class="step reveal d2"><div class="num">💚</div><h4 style="color:var(--green-800)">Wellbeing</h4><p class="muted">Your health is the heart of everything we make.</p></div>
            <div class="step reveal d3"><div class="num">🌍</div><h4 style="color:var(--green-800)">Sustainability</h4><p class="muted">Caring for the planet that nourishes us all.</p></div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0;margin-top:4rem">
    <div class="container"><div class="cta-band reveal">
        <h2>Ready to Begin Your Wellness Journey?</h2>
        <p style="opacity:.9;margin-top:.5rem">Explore our full range of natural herbal products today.</p>
        <a href="{{ route('shop.index') }}" class="btn btn-gold" style="margin-top:1.4rem">Shop Now</a>
    </div></div>
</section>
@endsection
