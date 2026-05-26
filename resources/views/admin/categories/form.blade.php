@extends('layouts.store')
@section('title', ($category->exists ? 'Edit' : 'New').' Category | Herbal Roots')

@section('content')
<div class="page-head">
    <div class="hero-bg"></div>
    <div class="container"><h1>{{ $category->exists ? '✏️ Edit Category' : '➕ New Category' }}</h1>
        <div class="crumbs"><a href="{{ route('admin.categories.index') }}">Categories</a> / {{ $category->exists ? 'Edit' : 'Create' }}</div></div>
</div>

<section class="section">
    <div class="container" style="max-width:620px">
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0;padding-left:1.1rem">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="form-card reveal">
            <form action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST">
                @csrf
                @if($category->exists) @method('PUT') @endif

                <div class="field">
                    <label>Name *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
                </div>

                <div class="field">
                    <label>Description</label>
                    <textarea name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="field">
                    <label>Image path (optional)</label>
                    <input type="text" name="image" value="{{ old('image', $category->image) }}" placeholder="images/categories/yourfile.jpg">
                </div>

                <div style="display:flex;gap:.75rem;margin-top:1rem">
                    <button type="submit" class="btn btn-primary">{{ $category->exists ? 'Update' : 'Create' }} Category</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
