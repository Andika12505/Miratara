<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'question',
        'answer',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the parent article.
     */
    public function parent()
    {
        return $this->belongsTo(CsArticle::class, 'parent_id');
    }

    /**
     * Get the child articles.
     */
    public function children()
    {
        return $this->hasMany(CsArticle::class, 'parent_id')->orderBy('order');
    }

    /**
     * Scope a query to only include root articles (without parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id')->where('is_active', true);
    }
}
