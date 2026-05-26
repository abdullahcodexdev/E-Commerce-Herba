<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'meta_title', 'meta_description',
        'short_description', 'description',
        'benefits', 'price', 'sale_price', 'stock', 'image', 'is_featured', 'rating',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'rating' => 'decimal:1',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getCurrentPriceAttribute(): float
    {
        return (float) ($this->sale_price ?? $this->price);
    }

    public function getOnSaleAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->on_sale) {
            return 0;
        }
        return (int) round(100 - ($this->sale_price / $this->price * 100));
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        return asset('images/placeholder-product.svg');
    }

    /** SEO <title>: stored meta_title, else a sensible default built from the name. */
    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: ($this->name.' — Herbal Roots');
    }

    /** SEO meta description: stored value, else short_description, else a generic line. */
    public function getSeoDescriptionAttribute(): string
    {
        $text = $this->meta_description ?: ($this->short_description ?: $this->description);

        return \Illuminate\Support\Str::limit(strip_tags((string) $text) ?: 'Premium organic herbal product from Herbal Roots.', 160);
    }
}
