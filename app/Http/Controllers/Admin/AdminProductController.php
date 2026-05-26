<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    /**
     * Generate a product short description, full description and benefits with AI.
     * Returns JSON consumed by the "✨ Generate with AI" button on the product form.
     */
    public function aiGenerate(Request $request, AiService $ai)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        if (! $ai->enabled()) {
            return response()->json(['error' => 'AI is not configured. Add OPENAI_API_KEY to .env.'], 422);
        }

        $category = $data['category'] ?: 'herbal product';

        $reply = $ai->chat([
            ['role' => 'system', 'content' =>
                'You are an e-commerce copywriter for a Pakistani herbal-products store. '.
                'Return ONLY valid JSON (no markdown) with exactly these keys: '.
                '"short_description" (one catchy sentence, max 140 chars), '.
                '"description" (2-3 informative sentences, no medical claims), '.
                '"benefits" (3-5 short benefit phrases separated by commas). '.
                'Keep wellness claims general and safe.',
            ],
            ['role' => 'user', 'content' => "Product name: {$data['name']}\nCategory: {$category}"],
        ], temperature: 0.7, maxTokens: 400);

        if (! $reply) {
            return response()->json(['error' => 'Could not generate text. Please try again.'], 502);
        }

        // Strip accidental code fences, then decode.
        $json = preg_replace('/^```(?:json)?|```$/m', '', trim($reply));
        $parsed = json_decode(trim($json), true);

        if (! is_array($parsed)) {
            return response()->json(['error' => 'AI returned an unexpected format. Please try again.'], 502);
        }

        return response()->json([
            'short_description' => $parsed['short_description'] ?? '',
            'description'       => $parsed['description'] ?? '',
            'benefits'          => $parsed['benefits'] ?? '',
        ]);
    }

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
