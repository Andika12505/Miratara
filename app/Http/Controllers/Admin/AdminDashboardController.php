<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\StockAlert;
use App\Models\StockMovement;
use App\Services\StockManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    protected $stockService;

    public function __construct(StockManagementService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display admin dashboard with stock management overview
     */
    public function index()
    {
        // Basic counts
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        
        // Stock management data
        $stockSummary = $this->stockService->getStockSummary();
        
        // Recent stock alerts
        $recentAlerts = StockAlert::active()
            ->with(['product:id,name', 'product.category:id,name'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent stock movements
        $recentMovements = StockMovement::with(['product:id,name', 'user:id,name'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Low stock products
        $lowStockProducts = Product::lowStock()
            ->with('category:id,name')
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();

        // Out of stock products
        $outOfStockProducts = Product::outOfStock()
            ->with('category:id,name')
            ->limit(10)
            ->get();

        // Stock status distribution
        $stockStatusDistribution = Product::select('stock_status', DB::raw('count(*) as count'))
            ->groupBy('stock_status')
            ->pluck('count', 'stock_status')
            ->toArray();

        // Monthly stock movements chart data
        $monthlyMovements = StockMovement::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as stock_in'),
                DB::raw('SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock_out')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top categories by stock value
        $topCategoriesByValue = Category::select('categories.name')
            ->selectRaw('SUM(products.stock * COALESCE(products.cost_price, 0)) as total_value')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_value', 'desc')
            ->limit(5)
            ->get();

        // Alert distribution by type
        $alertDistribution = StockAlert::active()
            ->select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts', 
            'totalCategories',
            'stockSummary',
            'recentAlerts',
            'recentMovements',
            'lowStockProducts',
            'outOfStockProducts',
            'stockStatusDistribution',
            'monthlyMovements',
            'topCategoriesByValue',
            'alertDistribution'
        ));
    }

    /**
     * Get stock data for AJAX requests
     */
    public function getStockData(Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'summary':
                return response()->json($this->stockService->getStockSummary());

            case 'alerts':
                $alerts = StockAlert::active()
                    ->with(['product:id,name'])
                    ->orderBy('priority', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->limit(20)
                    ->get();
                return response()->json($alerts);

            case 'movements':
                $movements = StockMovement::with(['product:id,name', 'user:id,name'])
                    ->orderBy('created_at', 'desc')
                    ->limit(20)
                    ->get();
                return response()->json($movements);

            case 'low_stock':
                $products = Product::lowStock()
                    ->with('category:id,name')
                    ->orderBy('stock', 'asc')
                    ->get();
                return response()->json($products);

            case 'out_of_stock':
                $products = Product::outOfStock()
                    ->with('category:id,name')
                    ->get();
                return response()->json($products);

            case 'chart_data':
                return response()->json([
                    'monthly_movements' => StockMovement::select(
                            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                            DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as stock_in'),
                            DB::raw('SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock_out')
                        )
                        ->where('created_at', '>=', now()->subMonths(12))
                        ->groupBy('month')
                        ->orderBy('month')
                        ->get(),
                    
                    'stock_status_distribution' => Product::select('stock_status', DB::raw('count(*) as count'))
                        ->groupBy('stock_status')
                        ->get(),
                    
                    'alert_distribution' => StockAlert::active()
                        ->select('type', DB::raw('count(*) as count'))
                        ->groupBy('type')
                        ->get()
                ]);

            default:
                return response()->json(['error' => 'Invalid data type'], 400);
        }
    }

    /**
     * Quick stock actions
     */
    public function quickStockAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:acknowledge_alert,resolve_alert,quick_adjust',
            'id' => 'required|integer',
            'notes' => 'nullable|string|max:500'
        ]);

        $action = $request->input('action');
        $id = $request->input('id');
        $notes = $request->input('notes');

        try {
            switch ($action) {
                case 'acknowledge_alert':
                    $alert = StockAlert::findOrFail($id);
                    $alert->acknowledge(auth()->id(), $notes);
                    return response()->json([
                        'success' => true,
                        'message' => 'Alert acknowledged successfully'
                    ]);

                case 'resolve_alert':
                    $alert = StockAlert::findOrFail($id);
                    $alert->resolve(auth()->id(), $notes);
                    return response()->json([
                        'success' => true,
                        'message' => 'Alert resolved successfully'
                    ]);

                case 'quick_adjust':
                    $request->validate([
                        'quantity' => 'required|integer|min:0'
                    ]);
                    
                    $quantity = $request->input('quantity');
                    $result = $this->stockService->adjustStock($id, $quantity, 'adjustment', $notes);
                    
                    return response()->json($result);

                default:
                    return response()->json(['error' => 'Invalid action'], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Action failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Stock analytics data for charts
     */
    public function stockAnalytics(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays($period);

        $analytics = [
            // Stock movements trend
            'movements_trend' => StockMovement::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as stock_in'),
                    DB::raw('SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock_out'),
                    DB::raw('COUNT(*) as total_movements')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get(),

            // Top products by movement
            'top_moving_products' => StockMovement::select('product_id')
                ->selectRaw('SUM(ABS(quantity)) as total_movement')
                ->with('product:id,name')
                ->where('created_at', '>=', $startDate)
                ->groupBy('product_id')
                ->orderBy('total_movement', 'desc')
                ->limit(10)
                ->get(),

            // Stock value by category
            'stock_value_by_category' => Category::select('categories.name')
                ->selectRaw('SUM(products.stock * COALESCE(products.cost_price, 0)) as total_value')
                ->selectRaw('SUM(products.stock) as total_quantity')
                ->join('products', 'categories.id', '=', 'products.category_id')
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('total_value', 'desc')
                ->get(),

            // Alert trends
            'alert_trends' => StockAlert::select(
                    DB::raw('DATE(created_at) as date'),
                    'type',
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy('date', 'type')
                ->orderBy('date')
                ->get(),

            // Stock turnover
            'stock_turnover' => Product::select('id', 'name', 'stock')
                ->selectRaw('COALESCE((
                    SELECT SUM(quantity) 
                    FROM stock_movements 
                    WHERE stock_movements.product_id = products.id 
                    AND stock_movements.type = "out" 
                    AND stock_movements.created_at >= ?
                ), 0) as sold_quantity', [$startDate])
                ->selectRaw('CASE 
                    WHEN stock > 0 THEN COALESCE((
                        SELECT SUM(quantity) 
                        FROM stock_movements 
                        WHERE stock_movements.product_id = products.id 
                        AND stock_movements.type = "out" 
                        AND stock_movements.created_at >= ?
                    ), 0) / stock 
                    ELSE 0 
                END as turnover_ratio', [$startDate])
                ->having('sold_quantity', '>', 0)
                ->orderBy('turnover_ratio', 'desc')
                ->limit(15)
                ->get()
        ];

        return response()->json($analytics);
    }
}