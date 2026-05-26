@extends('layouts.store')
@section('title', 'Admin — Products | Herbal Roots')

@section('content')
<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container"><h1>📦 Manage Products</h1>
        <div class="crumbs">Add, edit &amp; remove products in your store</div></div>
</div>

<section class="section">
    <div class="container">
        @include('admin.partials.nav')

        <div class="admin-toolbar">
            <form action="{{ route('admin.products.index') }}" method="GET" class="search-box" style="display:flex;gap:.5rem;flex-wrap:wrap">
                <input type="text" name="search" placeholder="Search products…" value="{{ request('search') }}">
                <select name="category" onchange="this.form.submit()">
                    <option value="">All categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category')==$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit">🔍</button>
            </form>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ New Product</a>
        </div>

        @if($products->count())
            <table class="cart-table reveal">
                <thead><tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Featured</th><th></th></tr></thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td><img src="{{ $product->image_url }}" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:8px"></td>
                        <td><b style="color:var(--green-800)">{{ $product->name }}</b></td>
                        <td class="muted">{{ $product->category->name ?? '—' }}</td>
                        <td>
                            <b style="color:var(--green-700)">Rs. {{ number_format($product->current_price) }}</b>
                            @if($product->on_sale)<div class="muted" style="font-size:.78rem;text-decoration:line-through">Rs. {{ number_format($product->price) }}</div>@endif
                        </td>
                        <td>{!! $product->stock > 0 ? $product->stock : '<span style="color:#c0392b">Out</span>' !!}</td>
                        <td>{{ $product->is_featured ? '⭐' : '—' }}</td>
                        <td style="white-space:nowrap">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline btn-sm">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#c0392b;color:#fff">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div>{{ $products->links() }}</div>
        @else
            <div class="empty-cart" style="background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:3rem">
                <div class="e">📦</div><h3 style="color:var(--green-800)">No products yet</h3>
                <p>Click "New Product" to add your first item.</p>
            </div>
        @endif
    </div>
</section>
@endsection
