<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\ProductSearch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request, ProductSearch $searchService): View
    {
        // --- DEBUG POINT 1: Cek semua input request yang diterima controller ---
        // Ini akan menunjukkan persis apa yang dikirim dari browser Anda.
        // dd($request->all());

        $sortBy = $request->query('sort_by', 'newest');
        $limit = $request->query('limit', 12);

        $filters = $request->only([
            'category_id', 'price_min', 'price_max',
            'style', 'material', 'color_tone', 'fit', 'pattern', 'occasion',
            'neckline', 'sleeve_length',
            'general_tags', 'origin',
        ]);

        foreach ([
            'style', 'material', 'color_tone', 'fit', 'pattern', 'occasion',
            'neckline', 'sleeve_length', 'general_tags'
        ] as $key) {
            if ($request->has($key) && is_string($filters[$key])) {
                $filters[$key] = explode(',', $filters[$key]);
            }
        }

        // --- DEBUG POINT 2: Cek array $filters setelah diproses di controller ---
        // Ini akan menunjukkan array filter yang akan diteruskan ke ProductSearch service.
        // dd($filters);


        if ($request->has('vibe_name')) {
            $searchService->applyVibe($request->input('vibe_name'));
        } else {
            $searchService->applyFilters($filters);
        }

        $productsQuery = $searchService->getQueryBuilder();

        switch ($sortBy) {
            case 'price_asc':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $productsQuery->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $productsQuery->orderBy('created_at', 'desc');
                break;
        }

        // --- DEBUG POINT 3: Cek Query SQL final sebelum dieksekusi ---
        // Ini adalah debug point paling penting untuk masalah database.
        dd($productsQuery->toSql(), $productsQuery->getBindings());

        $products = $productsQuery->paginate($limit);

        $categories = Category::orderBy('name')->get();
        $allVibeDefinitions = config('vibe.definitions');

        $availableVibeAttributes = [];
        foreach ($allVibeDefinitions as $vibeKey => $vibeCriteria) {
            foreach (['occasion', 'style', 'material', 'color_tone', 'fit', 'pattern', 'neckline', 'sleeve_length'] as $attr) {
                if (isset($vibeCriteria[$attr])) {
                    foreach ($vibeCriteria[$attr] as $value) {
                        if (!isset($availableVibeAttributes[$attr]) || !in_array($value, $availableVibeAttributes[$attr])) {
                            $availableVibeAttributes[$attr][] = $value;
                        }
                    }
                }
            }
            if (isset($vibeCriteria['general_tags'])) {
                foreach ($vibeCriteria['general_tags'] as $value) {
                    if (!isset($availableVibeAttributes['general_tags']) || !in_array($value, $availableVibeAttributes['general_tags'])) {
                        $availableVibeAttributes['general_tags'][] = $value;
                    }
                }
            }
        }
        foreach ($availableVibeAttributes as $key => $values) {
            sort($availableVibeAttributes[$key]);
        }

        $availableOrigins = ['Indonesia', 'China', 'Vietnam', 'India', 'USA'];

        return view('products.index', [
            'products' => $products,
            'sortBy' => $sortBy,
            'limit' => $limit,
            'categories' => $categories,
            'availableVibeAttributes' => $availableVibeAttributes,
            'availableGeneralTags' => $availableGeneralTags ?? [],
            'availableOrigins' => $availableOrigins,
            'request' => $request
        ]);
    }
}