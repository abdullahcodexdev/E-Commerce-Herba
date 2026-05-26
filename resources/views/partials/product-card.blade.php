@php($p = $product)
<div class="prod-card reveal">
    <div class="prod-media">
        @if($p->on_sale)<span class="prod-tag">-{{ $p->discount_percent }}%</span>@endif
        @if($p->created_at && $p->created_at->gt(now()->subDays(30)))<span class="prod-tag new">New</span>@endif
        <a href="{{ route('shop.show', $p->slug) }}"><img src="{{ $p->image_url }}" alt="{{ $p->name }}"></a>
        <div class="quick-add">
            <form action="{{ route('cart.add', $p) }}" method="POST" class="ajax-add">
                @csrf
                <button type="submit" class="btn btn-gold btn-block btn-sm">Add to Cart</button>
            </form>
        </div>
    </div>
    <div class="prod-body">
        <span class="prod-cat">{{ $p->category->name ?? 'Herbal' }}</span>
        <h3 class="prod-name"><a href="{{ route('shop.show', $p->slug) }}">{{ $p->name }}</a></h3>
        <div class="stars">{!! str_repeat('★', (int) round($p->rating)) . str_repeat('☆', 5 - (int) round($p->rating)) !!}
            <span class="muted" style="font-size:.78rem">({{ $p->rating }})</span></div>
        <div class="price-row">
            <div class="price">
                <span class="now">Rs. {{ number_format($p->current_price) }}</span>
                @if($p->on_sale)<span class="was">Rs. {{ number_format($p->price) }}</span>@endif
            </div>
            <a href="{{ route('shop.show', $p->slug) }}" class="icon-btn" title="View">→</a>
        </div>
    </div>
</div>
