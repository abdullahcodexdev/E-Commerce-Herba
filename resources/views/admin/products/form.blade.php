@extends('layouts.store')
@section('title', ($product->exists ? 'Edit' : 'New').' Product | Herbal Roots')

@section('content')
<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container"><h1>{{ $product->exists ? '✏️ Edit Product' : '➕ New Product' }}</h1>
        <div class="crumbs"><a href="{{ route('admin.products.index') }}">Products</a> / {{ $product->exists ? 'Edit' : 'Create' }}</div></div>
</div>

<section class="section">
    <div class="container" style="max-width:760px">
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0;padding-left:1.1rem">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="form-card reveal">
            <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if($product->exists) @method('PUT') @endif

                <div class="grid-2">
                    <div class="field">
                        <label>Name *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
                    </div>
                    <div class="field">
                        <label>Category *</label>
                        <select name="category_id" required>
                            <option value="">— Select —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id)==$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label>Short description</label>
                    <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" maxlength="500">
                </div>

                <div class="field">
                    <label>Full description</label>
                    <textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="field">
                    <label>Benefits (one per line or comma-separated)</label>
                    <textarea name="benefits" rows="3">{{ old('benefits', $product->benefits) }}</textarea>
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label>Price (Rs.) *</label>
                        <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
                    </div>
                    <div class="field">
                        <label>Sale price (optional)</label>
                        <input type="number" name="sale_price" step="0.01" min="0" value="{{ old('sale_price', $product->sale_price) }}">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label>Stock *</label>
                        <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required>
                    </div>
                    <div class="field">
                        <label>Rating (0–5)</label>
                        <input type="number" name="rating" step="0.1" min="0" max="5" value="{{ old('rating', $product->rating ?? 4.5) }}">
                    </div>
                </div>

                <div class="field">
                    <label>Image path (e.g. images/products/p1.svg)</label>
                    <input type="text" name="image" value="{{ old('image', $product->image) }}" placeholder="images/products/yourfile.jpg">
                </div>

                <div class="field">
                    <label>…or upload an image (jpg, png, webp, svg — max 2MB)</label>
                    <input type="file" name="image_file" accept="image/*">
                    @if($product->exists && $product->image)
                        <div style="margin-top:.5rem"><img src="{{ $product->image_url }}" alt="" style="width:80px;height:80px;object-fit:cover;border-radius:8px"></div>
                    @endif
                </div>

                <div class="field">
                    <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer">
                        <input type="checkbox" name="is_featured" value="1" style="width:auto" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        Show on homepage (Featured)
                    </label>
                </div>

                <div style="display:flex;gap:.75rem;margin-top:1rem">
                    <button type="submit" class="btn btn-primary">{{ $product->exists ? 'Update' : 'Create' }} Product</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
