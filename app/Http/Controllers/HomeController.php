<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::where('is_featured', true)->latest()->take(8)->get();
        $newArrivals = Product::latest()->take(8)->get();
        $categories = Category::withCount('products')->get();
        $bestSellers = Product::orderByDesc('rating')->take(4)->get();

        return view('home', compact('featured', 'newArrivals', 'categories', 'bestSellers'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSubmit(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactMessage::create($data);

        return back()->with('success', 'Thank you! Your message has been received. We will get back to you soon.');
    }
}
