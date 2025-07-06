<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB; // Pastikan ini di-import untuk DB::raw()

class ProductSearch
{
    protected Builder $query;

    public function __construct()
    {
        $this->query = Product::query(); // Inisialisasi query Eloquent untuk model Product
    }

    /**
     * Menerapkan filter berdasarkan array kriteria.
     * Metode ini akan digunakan oleh Faceted Search (Tier 1) dan logika Vibe Search (Tier 2).
     *
     * @param array $filters Contoh: ['category_id' => 1, 'price_min' => 100, 'style' => ['casual', 'bohemian']]
     * @return $this
     */
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
        // Ini adalah atribut seperti style, material, occasion, dll. yang disimpan sebagai ARRAY di JSON
        $vibeAttributesKeys = [
            'occasion', 'style', 'material', 'color_tone', 'fit', 'pattern',
            'neckline', 'sleeve_length'
        ];

        foreach ($vibeAttributesKeys as $attr) {
            if (isset($filters[$attr]) && is_array($filters[$attr]) && !empty($filters[$attr])) {
                $this->query->where(function (Builder $q) use ($attr, $filters) {
                    foreach ($filters[$attr] as $value) {
                        // Ini akan menjadi nilai yang dicari di JSON_SEARCH
                        // Kita ingin mencari string literal "value" (misal "cotton")
                        // Yang perlu dibungkus dengan wildcard untuk JSON_SEARCH
                        $search_value_for_json_search = "%" . $value . "%";

                        // Kita perlu memastikan bahwa $search_value_for_json_search di-encode ke JSON
                        // agar MySQL melihatnya sebagai string JSON literal (e.g., "\"%cotton%\"")
                        // Ini akan menjadi argumen kedua untuk JSON_SEARCH
                        $json_encoded_search_value = json_encode($search_value_for_json_search);

                        // --- PERBAIKAN DI SINI: Gunakan JSON_SEARCH dengan binding yang di-json_encode ---
                        $q->orWhereRaw(
                            "JSON_SEARCH(metadata->'$.vibe_attributes.{$attr}', 'one', ?) IS NOT NULL",
                            [$json_encoded_search_value]
                        );
                    }
                });
            }
        }

        // --- Filter Atribut JSON (general_tags) ---
        if (isset($filters['general_tags']) && is_array($filters['general_tags']) && !empty($filters['general_tags'])) {
            $this->query->where(function (Builder $q) use ($filters) {
                foreach ($filters['general_tags'] as $tag) {
                    $search_value_for_json_search = "%" . $tag . "%";
                    $json_encoded_search_value = json_encode($search_value_for_json_search);

                    // --- PERBAIKAN DI SINI: Gunakan JSON_SEARCH dengan binding yang di-json_encode ---
                    $q->orWhereRaw(
                        "JSON_SEARCH(metadata->'$.general_tags', 'one', ?) IS NOT NULL",
                        [$json_encoded_search_value]
                    );
                }
            });
        }

        // --- Filter Atribut JSON Lainnya (non-array, e.g., 'origin') ---
        // Untuk atribut yang tidak disimpan sebagai array di JSON, bisa pakai operator -> langsung
        if (isset($filters['origin']) && $filters['origin']) {
            $this->query->where('metadata->origin', $filters['origin']);
        }

        return $this;
    }

    /**
     * Menerapkan kriteria spesifik "vibe" ke query.
     * Metode ini akan menginterpretasikan 'nama vibe' menjadi filter spesifik.
     *
     * @param string $vibeName Nama vibe yang telah didefinisikan (misal: 'beach_getaway')
     * @return $this
     */
    public function applyVibe(string $vibeName): self
    {
        $vibeMappings = config('vibe.definitions');

        if (isset($vibeMappings[$vibeName])) {
            $vibeCriteria = $vibeMappings[$vibeName];
            $this->applyFilters($vibeCriteria); // Gunakan kembali applyFilters
        }
        return $this;
    }

    /**
     * Mendapatkan instance query builder Eloquent yang sudah dibangun.
     * Berguna untuk melakukan chaining lebih lanjut (misal: ordering, pagination).
     *
     * @return Builder
     */
    public function getQueryBuilder(): Builder
    {
        return $this->query;
    }

    /**
     * Mengeksekusi query dan mendapatkan hasil yang dipaginasi.
     *
     * @param int $perPage Jumlah item per halaman
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query->paginate($perPage);
    }

    /**
     * Mengeksekusi query dan mendapatkan semua hasil.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query->get();
    }
}