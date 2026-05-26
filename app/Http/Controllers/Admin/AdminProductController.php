<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%{$s}%");
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        return view('admin.products.form', [
            'product' => new Product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['image'] = $this->handleImage($request, $data['image'] ?? null);
        $data['is_featured'] = $request->boolean('is_featured');
        unset($data['image_file']);

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request);

        if ($data['name'] !== $product->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }
        $data['image'] = $this->handleImage($request, $product->image);
        $data['is_featured'] = $request->boolean('is_featured');
        unset($data['image_file']);

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    /* ---------------------------------------------------------------- */
    /*  Helpers                                                          */
    /* ---------------------------------------------------------------- */
    protected function validateProduct(Request $request): array
    {
        return $request->validate([
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'benefits'          => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0|lt:price',
            'stock'             => 'required|integer|min:0',
            'rating'            => 'nullable|numeric|min:0|max:5',
            'is_featured'       => 'nullable|boolean',
            'image'             => 'nullable|string|max:255',
            'image_file'        => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);
    }

    /** Store an uploaded image in public/images/products, else keep the existing path. */
    protected function handleImage(Request $request, ?string $current): ?string
    {
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $name = 'p-'.Str::random(10).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $name);

            return 'images/products/'.$name;
        }

        return $current;
    }

    /** Generate a slug that is unique across products. */
    protected function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (Product::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
