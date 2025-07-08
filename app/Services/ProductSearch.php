<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductSearch
{
    protected Builder $query;

    public function __construct()
    {
        // CRITICAL: Filter active products (this was the key fix!)
        $this->query = Product::where('is_active', true);
    }

    public function applyFilters(array $filters): self
    {
        // --- Filter Kolom Standar ---
        if (isset($filters['category_id']) && $filters['category_id']) {
            $this->query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['price_min']) && is_numeric($filters['price_min'])) {
            $this->query->where('price', '>=', $filters['price_min']);
        }
        if (isset($filters['price_max']) && is_numeric($filters['price_max'])) {
            $this->query->where('price', '<=', $filters['price_max']);
        }

        if (isset($filters['is_active']) && in_array($filters['is_active'], [0, 1])) {
             $this->query->where('is_active', (bool) $filters['is_active']);
        }

        // --- Filter Atribut JSON (metadata->vibe_attributes) ---
        $vibeAttributesKeys = [
            'occasion', 'style', 'material', 'color_tone', 'fit', 'pattern',
            'neckline', 'sleeve_length'
        ];

        foreach ($vibeAttributesKeys as $attr) {
            if (isset($filters[$attr]) && is_array($filters[$attr]) && !empty($filters[$attr])) {
                $this->query->where(function (Builder $q) use ($attr, $filters) {
                    foreach ($filters[$attr] as $value) {
                        $q->orWhereJsonContains("metadata->vibe_attributes->{$attr}", $value);
                    }
                });
            }
        }

        // --- Filter general_tags ---
        if (isset($filters['general_tags']) && is_array($filters['general_tags']) && !empty($filters['general_tags'])) {
            $this->query->where(function (Builder $q) use ($filters) {
                foreach ($filters['general_tags'] as $tag) {
                    $q->orWhereJsonContains('metadata->general_tags', $tag);
                }
            });
        }

        // --- Filter origin ---
        if (isset($filters['origin']) && !empty($filters['origin'])) {
            if (is_array($filters['origin'])) {
                $this->query->where(function (Builder $q) use ($filters) {
                    foreach ($filters['origin'] as $origin) {
                        $q->orWhere('metadata->origin', $origin);
                    }
                });
            } else {
                $this->query->where('metadata->origin', $filters['origin']);
            }
        }

        return $this;
    }

    public function applyVibe(string $vibeName): self
    {
        $vibeMappings = config('vibe.definitions');

        if (isset($vibeMappings[$vibeName])) {
            $vibeCriteria = $vibeMappings[$vibeName];
            $this->applyFilters($vibeCriteria);
        }
        return $this;
    }

    public function getQueryBuilder(): Builder
    {
        return $this->query;
    }

    public function paginate(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query->paginate($perPage);
    }

    public function get(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query->get();
    }
}