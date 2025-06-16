<?php

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
}