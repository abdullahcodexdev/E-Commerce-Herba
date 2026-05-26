<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class SeoController extends Controller
{
    /** Dynamic XML sitemap for search engines (/sitemap.xml). */
    public function sitemap()
    {
        $urls = [];

        // Static pages
        foreach (['home', 'shop.index', 'about', 'contact'] as $name) {
            $urls[] = ['loc' => route($name), 'priority' => $name === 'home' ? '1.0' : '0.7'];
        }

        // Categories
        foreach (Category::all() as $cat) {
            $urls[] = ['loc' => route('shop.index', ['category' => $cat->slug]), 'priority' => '0.6'];
        }

        // Products
        foreach (Product::select('slug', 'updated_at')->get() as $product) {
            $urls[] = [
                'loc'     => route('shop.show', $product->slug),
                'lastmod' => optional($product->updated_at)->toAtomString(),
                'priority' => '0.8',
            ];
        }

        $xml = view('seo.sitemap', compact('urls'))->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
