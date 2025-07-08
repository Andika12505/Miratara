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
        // ===== COMPREHENSIVE DEBUG BLOCK =====
        if ($request->has('material')) {
            
            // 1. Check what we received
            $receivedMaterial = $request->input('material');
            
            // 2. Check how it's processed
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
            
            // 3. Check actual products in database
            $totalProducts = Product::count();
            $sampleMetadata = Product::select('metadata')->first();
            
            // 4. Test different query approaches
            $testQueries = [
                'direct_cotton' => Product::whereJsonContains('metadata->vibe_attributes->material', 'cotton')->count(),
                'array_cotton' => Product::whereJsonContains('metadata->vibe_attributes->material', ['cotton'])->count(),
                'like_cotton' => Product::where('metadata', 'LIKE', '%cotton%')->count(),
                'case_insensitive' => Product::whereRaw('LOWER(JSON_EXTRACT(metadata, "$.vibe_attributes.material")) LIKE ?', ['%cotton%'])->count(),
            ];
            
            // 5. Test with ProductSearch service
            $searchService->applyFilters($filters);
            $queryBuilder = $searchService->getQueryBuilder();
            $resultCount = $queryBuilder->count();
            $sqlQuery = $queryBuilder->toSql();
            $bindings = $queryBuilder->getBindings();
            
            // 6. Get actual products that should match
            $productsWithMaterial = Product::whereNotNull('metadata->vibe_attributes->material')->get();
            $allMaterials = [];
            foreach ($productsWithMaterial as $product) {
                $materials = data_get($product->metadata, 'vibe_attributes.material', []);
                $allMaterials = array_merge($allMaterials, is_array($materials) ? $materials : [$materials]);
            }
            $uniqueMaterials = array_unique($allMaterials);
            
            /*dd([
                'step1_received_input' => $receivedMaterial,
                'step2_processed_filters' => $filters,
                'step3_total_products' => $totalProducts,
                'step4_sample_metadata' => $sampleMetadata,
                'step5_test_queries' => $testQueries,
                'step6_search_service_result_count' => $resultCount,
                'step7_sql_query' => $sqlQuery,
                'step8_bindings' => $bindings,
                'step9_all_materials_in_db' => $uniqueMaterials,
                'step10_products_with_material_count' => $productsWithMaterial->count(),
            ]); */
        }
        // ===== END DEBUG BLOCK =====

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