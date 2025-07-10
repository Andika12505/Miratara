<?php
// app/Models/Size.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get products that have this size
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sizes')
                    ->withPivot('stock', 'is_available')
                    ->withTimestamps();
    }

    /**
     * Scope to get only active sizes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order sizes by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the display name or fall back to name
     */
    public function getDisplayAttribute()
    {
        return $this->display_name ?: $this->name;
    }
}