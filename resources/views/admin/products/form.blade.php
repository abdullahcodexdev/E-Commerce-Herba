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

                <div style="margin:.25rem 0 1rem">
                    <button type="button" id="aiGenBtn" class="btn btn-outline btn-sm">✨ Generate description with AI</button>
                    <span id="aiGenMsg" class="muted" style="font-size:.82rem;margin-left:.5rem"></span>
                </div>

                <div class="field">
                    <label>Short description</label>
                    <input type="text" name="short_description" id="f_short" value="{{ old('short_description', $product->short_description) }}" maxlength="500">
                </div>

                <div class="field">
                    <label>Full description</label>
                    <textarea name="description" id="f_desc" rows="4">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="field">
                    <label>Benefits (one per line or comma-separated)</label>
                    <textarea name="benefits" id="f_benefits" rows="3">{{ old('benefits', $product->benefits) }}</textarea>
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

                <fieldset style="border:1px solid #e3e9e0;border-radius:10px;padding:1rem;margin:0 0 1rem">
                    <legend style="padding:0 .5rem;color:var(--green-700);font-weight:600">🔎 SEO (search engines)</legend>
                    <div class="field">
                        <label>Meta title <span class="muted" style="font-size:.78rem">(max ~60 chars)</span></label>
                        <input type="text" name="meta_title" id="f_metatitle" value="{{ old('meta_title', $product->meta_title) }}" maxlength="255">
                    </div>
                    <div class="field">
                        <label>Meta description <span class="muted" style="font-size:.78rem">(max ~155 chars)</span></label>
                        <textarea name="meta_description" id="f_metadesc" rows="2" maxlength="320">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>
                    <p class="muted" style="font-size:.8rem;margin:0">Leave blank to auto-use the product name &amp; short description.</p>
                </fieldset>

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

@push('scripts')
<script>
(function () {
    const btn = document.getElementById('aiGenBtn'),
          msg = document.getElementById('aiGenMsg'),
          nameEl = document.querySelector('[name="name"]'),
          catEl = document.querySelector('[name="category_id"]');

    btn.addEventListener('click', async () => {
        const name = nameEl.value.trim();
        if (!name) { msg.textContent = 'Enter a product name first.'; nameEl.focus(); return; }

        const category = catEl.options[catEl.selectedIndex]?.text || '';
        btn.disabled = true;
        msg.textContent = '✨ Generating…';

        try {
            const res = await fetch("{{ route('admin.products.ai') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}", 'Accept': 'application/json' },
                body: JSON.stringify({ name, category })
            });
            const data = await res.json();
            if (!res.ok) { msg.textContent = data.error || 'Failed. Try again.'; return; }

            if (data.short_description) document.getElementById('f_short').value = data.short_description;
            if (data.description)       document.getElementById('f_desc').value = data.description;
            if (data.benefits)          document.getElementById('f_benefits').value = data.benefits;
            if (data.meta_title)        document.getElementById('f_metatitle').value = data.meta_title;
            if (data.meta_description)  document.getElementById('f_metadesc').value = data.meta_description;
            msg.textContent = '✓ Done — review & edit before saving.';
        } catch (e) {
            msg.textContent = 'Connection error. Try again.';
        } finally {
            btn.disabled = false;
        }
    });
})();
</script>
@endpush
@endsection
