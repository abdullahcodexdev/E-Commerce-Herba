@extends('layouts.store')
@section('title', 'Admin — Categories | Herbal Roots')

@section('content')
<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container"><h1>🗂 Manage Categories</h1>
        <div class="crumbs">Organize your products into categories</div></div>
</div>

<section class="section">
    <div class="container">
        @include('admin.partials.nav')

        <div class="admin-toolbar">
            <div></div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ New Category</a>
        </div>

        @if($categories->count())
            <table class="cart-table reveal">
                <thead><tr><th>Name</th><th>Slug</th><th>Products</th><th></th></tr></thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td><b style="color:var(--green-800)">{{ $category->name }}</b></td>
                        <td class="muted">{{ $category->slug }}</td>
                        <td>{{ $category->products_count }}</td>
                        <td style="white-space:nowrap">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline btn-sm">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#c0392b;color:#fff">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div>{{ $categories->links() }}</div>
        @else
            <div class="empty-cart" style="background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:3rem">
                <div class="e">🗂</div><h3 style="color:var(--green-800)">No categories yet</h3>
                <p>Click "New Category" to create one.</p>
            </div>
        @endif
    </div>
</section>
@endsection
