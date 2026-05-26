<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin + demo user
        User::updateOrCreate(['email' => 'admin@herbalroots.pk'], [
            'name' => 'Admin', 'password' => Hash::make('password'),
            'is_admin' => true, 'phone' => '+92 300 1234567', 'city' => 'Lahore',
            'email_verified_at' => now(),
        ]);
        User::updateOrCreate(['email' => 'demo@herbalroots.pk'], [
            'name' => 'Demo Customer', 'password' => Hash::make('password'),
            'phone' => '+92 311 7654321', 'city' => 'Karachi', 'address' => '12 Garden Road',
            'email_verified_at' => now(),
        ]);

        $categories = [
            ['Immunity', 'immunity', 'Strengthen your natural defenses with potent herbal boosters.'],
            ['Digestion', 'digestion', 'Soothe and support your gut with time-tested herbs.'],
            ['Skin & Hair', 'skin-hair', 'Nourish skin and hair naturally from root to glow.'],
            ['Stress & Sleep', 'stress-sleep', 'Calm the mind and rest deeply with adaptogenic herbs.'],
            ['Energy & Vitality', 'energy', 'Recharge naturally and feel revitalized every day.'],
            ['Detox & Cleanse', 'detox', 'Gently flush out toxins and restore balance.'],
        ];

        $catModels = [];
        foreach ($categories as [$name, $slug, $desc]) {
            $catModels[$slug] = Category::updateOrCreate(['slug' => $slug], [
                'name' => $name, 'description' => $desc,
                'image' => "images/categories/{$slug}.svg",
            ]);
        }

        // [name, category, price, sale_price, img, benefits, featured, rating]
        $products = [
            ['Ashwagandha Capsules', 'stress-sleep', 1499, 1199, 'p1', 'Reduces stress, Boosts energy, Better sleep', true, 4.9],
            ['Organic Turmeric Curcumin', 'immunity', 1299, null, 'p2', 'Anti-inflammatory, Joint support, Immunity', true, 4.8],
            ['Pure Neem Capsules', 'skin-hair', 999, 799, 'p3', 'Clear skin, Blood purifier, Detox', true, 4.7],
            ['Holy Basil (Tulsi) Extract', 'immunity', 1099, null, 'p4', 'Respiratory health, Stress relief, Immunity', true, 4.8],
            ['Aloe Vera Gel Capsules', 'digestion', 899, 699, 'p5', 'Gut health, Hydration, Skin glow', true, 4.6],
            ['Moringa Leaf Powder', 'energy', 1199, null, 'p6', 'Rich in nutrients, Energy boost, Antioxidants', true, 4.9],
            ['Ginger Root Extract', 'digestion', 849, null, 'p7', 'Digestion, Nausea relief, Anti-inflammatory', true, 4.5],
            ['Brahmi Brain Tonic', 'stress-sleep', 1399, 1149, 'p8', 'Memory, Focus, Calm mind', true, 4.7],
            ['Amla (Indian Gooseberry)', 'skin-hair', 999, null, 'p9', 'Vitamin C, Hair growth, Immunity', false, 4.6],
            ['Triphala Cleanse Formula', 'detox', 1099, 899, 'p10', 'Gentle detox, Digestion, Regularity', false, 4.7],
            ['Shatavari Wellness Capsules', 'energy', 1499, null, 'p11', 'Vitality, Hormonal balance, Strength', false, 4.8],
            ['Herbal Honey Immunity Tonic', 'immunity', 1799, 1499, 'p12', 'Immunity, Throat soothing, Natural energy', false, 4.9],
        ];

        foreach ($products as [$name, $cat, $price, $sale, $img, $benefits, $featured, $rating]) {
            Product::updateOrCreate(['slug' => Str::slug($name)], [
                'category_id' => $catModels[$cat]->id,
                'name' => $name,
                'short_description' => 'Premium '.$name.' — pure, organic and lab-tested for everyday wellness.',
                'description' => 'Our '.$name.' is crafted from carefully sourced organic herbs, standardized for potency and rigorously tested for purity. Free from fillers, artificial colors and preservatives. Each batch is processed to preserve the natural goodness of the herb, giving you a clean, effective supplement you can trust as part of your daily wellness routine.',
                'benefits' => $benefits,
                'price' => $price,
                'sale_price' => $sale,
                'stock' => rand(15, 80),
                'image' => "images/products/{$img}.svg",
                'is_featured' => $featured,
                'rating' => $rating,
            ]);
        }
    }
}
