<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', // Ini yang baru, menggantikan 'category'
        'name',
        'slug',
        'description',
        'image', // Ubah dari image_url_1/2 menjadi satu kolom image saja (jika di migrasi begitu)
        'price',
        'stock',
        'metadata', // Kolom JSON baru kita
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer', // Pastikan stock di-cast sebagai integer
        'is_active' => 'boolean',
        'metadata' => 'array', // Ini adalah cast penting untuk kolom JSON
    ];

    // Definisikan relasi ke model Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * NEW: Get sizes available for this product
     */
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes')
                    ->withPivot('stock', 'is_available')
                    ->withTimestamps()
                    ->orderBy('sort_order');
    }

    /**
     * NEW: Get only available sizes (with stock > 0 and is_available = true)
     */
    public function availableSizes()
    {
        return $this->sizes()
                    ->wherePivot('is_available', true)
                    ->wherePivot('stock', '>', 0);
    }

    /**
     * NEW: Get total stock across all sizes
     */
    public function getTotalSizeStockAttribute()
    {
        return $this->sizes()->sum('product_sizes.stock');
    }

    /**
     * NEW: Check if product has any sizes configured
     */
    public function hasSizes()
    {
        return $this->sizes()->count() > 0;
    }

    /**
     * NEW: Get stock for a specific size
     */
    public function getStockForSize($sizeId)
    {
        $size = $this->sizes()->where('size_id', $sizeId)->first();
        return $size ? $size->pivot->stock : 0;
    }

    /**
     * NEW: Check if a specific size is available
     */
    public function isSizeAvailable($sizeId)
    {
        $size = $this->sizes()->where('size_id', $sizeId)->first();
        return $size && $size->pivot->is_available && $size->pivot->stock > 0;
    }

    /**
     * NEW: Get the stock to display (uses size-based stock if sizes exist, otherwise regular stock)
     */
    public function getDisplayStockAttribute()
    {
        if ($this->hasSizes()) {
            return $this->total_size_stock;
        }
        return $this->stock;
    }

    /**
     * NEW: Check if product is in stock (considering sizes if they exist)
     */
    public function isInStock()
    {
        if ($this->hasSizes()) {
            return $this->availableSizes()->count() > 0;
        }
        return $this->stock > 0;
    }

    /**
     * NEW: Scope to get products that are in stock
     */
    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->whereHas('sizes', function ($sizeQuery) {
                $sizeQuery->where('product_sizes.is_available', true)
                         ->where('product_sizes.stock', '>', 0);
            })
            ->orWhere(function ($noSizeQuery) {
                $noSizeQuery->whereDoesntHave('sizes')
                           ->where('stock', '>', 0);
            });
        });
    }
}