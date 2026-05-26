@extends('layouts.store')
@section('title', 'Shop — Herbal Roots')

@section('content')
<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container">
        <h1>Our Herbal Collection</h1>
        <div class="crumbs"><a href="{{ route('home') }}">Home</a> / Shop</div>
    </div>
</div>

<section class="section">
    <div class="container shop-wrap">
        <aside>
            <div class="filter-card reveal">
                <h4>Search</h4>
                <form action="{{ route('shop.index') }}" method="GET" class="search-box" style="margin-bottom:1.4rem">
                    <input type="text" name="search" placeholder="Search herbs…" value="{{ request('search') }}">
                    <button type="submit">🔍</button>
                </form>
                <h4>Categories</h4>
                <ul class="filter-list">
                    <li><a href="{{ route('shop.index') }}" class="{{ !request('category') ? 'active' : '' }}">All Products <span class="c">{{ $categories->sum('products_count') }}</span></a></li>
                    @foreach($categories as $cat)
                        <li><a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="{{ request('category') == $cat->slug ? 'active' : '' }}">{{ $cat->name }} <span class="c">{{ $cat->products_count }}</span></a></li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <div>
            <div class="shop-toolbar reveal">
                <span class="muted">Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products</span>
                <form method="GET" action="{{ route('shop.index') }}">
                    @foreach(request()->except('sort','page') as $k => $v)<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endforeach
                    <select name="sort" onchange="this.form.submit()">
                        <option value="">Newest</option>
                        <option value="price_low" @selected(request('sort')=='price_low')>Price: Low to High</option>
                        <option value="price_high" @selected(request('sort')=='price_high')>Price: High to Low</option>
                        <option value="rating" @selected(request('sort')=='rating')>Top Rated</option>
                    </select>
                </form>
            </div>

            @if($products->count())
                <div class="prod-grid cols-3">
                    @foreach($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
                <div>{{ $products->links() }}</div>
            @else
                <div class="empty-cart"><div class="e">🌿</div><h3>No products found</h3><p>Try a different search or category.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary" style="margin-top:1rem">Reset Filters</a></div>
            @endif
        </div>
    </div>
</section>
@endsection
