<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockAlert;
use App\Models\StockMovement;
use App\Services\StockManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StockManagementController extends Controller
{
    protected $stockService;

    public function __construct(StockManagementService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display stock management overview
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $category = $request->get('category');

        $query = Product::with(['category', 'activeStockAlerts']);

        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($status) {
            if ($status === 'low_stock') {
                $query->lowStock();
            } elseif ($status === 'out_of_stock') {
                $query->outOfStock();
            } elseif ($status === 'overstock') {
                $query->overStocked();
            } else {
                $query->where('stock_status', $status);
            }
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        $products = $query->orderBy('stock', 'asc')->paginate(20);

        // Get categories for filter
        $categories = \App\Models\Category::orderBy('name')->get();

        // Summary data
        $summary = $this->stockService->getStockSummary();

        return view('admin.stock.index', compact('products', 'categories', 'summary', 'search', 'status', 'category'));
    }

    /**
     * Show stock management form for a product
     */
    public function show(Product $product)
    {
        $product->load(['category', 'stockMovements.user', 'stockAlerts']);
        
        // Get stock movements with pagination
        $movements = $product->stockMovements()->with('user')->paginate(20);
        
        // Get stock summary for this product
        $summary = StockMovement::getProductSummary($product->id);

        return view('admin.stock.show', compact('product', 'movements', 'summary'));
    }

    /**
     * Add stock to product
     */
    public function addStock(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|in:purchase,return,adjustment,transfer',
            'unit_cost' => 'nullable|numeric|min:0',
            'batch_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500'
        ]);

        $result = $this->stockService->addStock(
            $product->id,
            $request->quantity,
            $request->reason,
            $request->notes,
            $request->unit_cost,
            $request->batch_number
        );

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * Remove stock from product
     */
    public function removeStock(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|in:sale,damaged,expired,transfer,adjustment',
            'notes' => 'nullable|string|max:500'
        ]);

        $result = $this->stockService->removeStock(
            $product->id,
            $request->quantity,
            $request->reason,
            $request->notes
        );

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * Adjust stock to specific quantity
     */
    public function adjustStock(Request $request, Product $product)
    {
        $request->validate([
            'new_quantity' => 'required|integer|min:0',
            'reason' => 'required|string|in:adjustment,correction,audit',
            'notes' => 'nullable|string|max:500'
        ]);

        $result = $this->stockService->adjustStock(
            $product->id,
            $request->new_quantity,
            $request->reason,
            $request->notes
        );

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * Update product stock settings
     */
    public function updateSettings(Request $request, Product $product)
    {
        $request->validate([
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0|gt:min_stock',
            'stock_unit' => 'required|string|max:50',
            'cost_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products')->ignore($product->id)
            ],
            'location' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date|after:today'
        ]);

        $product->update($request->only([
            'min_stock', 'max_stock', 'stock_unit', 'cost_price',
            'supplier', 'sku', 'location', 'expiry_date'
        ]));

        // Update stock status and check alerts
        $product->updateStockStatus();
        $product->checkStockAlerts();
        $product->save();

        return redirect()->back()->with('success', 'Stock settings updated successfully');
    }

    /**
     * Bulk stock operations
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'action' => 'required|in:add,remove,adjust',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:0',
            'reason' => 'required|string',
            'notes' => 'nullable|string|max:500'
        ]);

        $updates = [];
        foreach ($request->products as $productData) {
            $updates[] = [
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
                'type' => $request->action === 'adjust' ? 'adjustment' : $request->action
            ];
        }

        $result = $this->stockService->bulkUpdateStock(
            $updates,
            $request->reason,
            $request->notes
        );

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * Stock movements history
     */
    public function movements(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        // Filters
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->reason) {
            $query->where('reason', $request->reason);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $movements = $query->orderBy('created_at', 'desc')->paginate(30);

        // Get products and filter options
        $products = Product::select('id', 'name')->orderBy('name')->get();
        $types = ['in', 'out', 'adjustment', 'reserved', 'released'];
        $reasons = ['purchase', 'sale', 'return', 'damaged', 'expired', 'adjustment', 'transfer', 'reservation', 'cancellation'];

        return view('admin.stock.movements', compact('movements', 'products', 'types', 'reasons'));
    }

    /**
     * Stock alerts management
     */
    public function alerts(Request $request)
    {
        $query = StockAlert::with(['product.category', 'acknowledgedBy', 'resolvedBy']);

        // Filters
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->priority) {
            $query->where('priority', $request->priority);
        }

        $alerts = $query->orderBy('priority', 'desc')
                       ->orderBy('created_at', 'desc')
                       ->paginate(20);

        // Summary data
        $summary = StockAlert::getSummary();
        $alertsByType = StockAlert::getAlertsByType();
        $alertsByPriority = StockAlert::getAlertsByPriority();

        return view('admin.stock.alerts', compact('alerts', 'summary', 'alertsByType', 'alertsByPriority'));
    }

    /**
     * Acknowledge alert
     */
    public function acknowledgeAlert(Request $request, StockAlert $alert)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        $alert->acknowledge(auth()->id(), $request->notes);

        return redirect()->back()->with('success', 'Alert acknowledged successfully');
    }

    /**
     * Resolve alert
     */
    public function resolveAlert(Request $request, StockAlert $alert)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        $alert->resolve(auth()->id(), $request->notes);

        return redirect()->back()->with('success', 'Alert resolved successfully');
    }

    /**
     * Stock reports
     */
    public function reports(Request $request)
    {
        $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date) : now()->subMonth();
        $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date) : now();

        $report = $this->stockService->generateStockReport($startDate, $endDate);

        return view('admin.stock.reports', compact('report', 'startDate', 'endDate'));
    }

    /**
     * Export stock data
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'current_stock');

        switch ($type) {
            case 'current_stock':
                return $this->exportCurrentStock();
            case 'movements':
                return $this->exportMovements($request);
            case 'alerts':
                return $this->exportAlerts($request);
            default:
                abort(400, 'Invalid export type');
        }
    }

    /**
     * Export current stock levels
     */
    private function exportCurrentStock()
    {
        $products = Product::with('category')->get();

        $csvData = "Product Name,SKU,Category,Current Stock,Min Stock,Max Stock,Reserved Stock,Available Stock,Status,Location,Supplier,Cost Price,Stock Value\n";

        foreach ($products as $product) {
            $csvData .= sprintf(
                '"%s","%s","%s",%d,%d,%d,%d,%d,"%s","%s","%s",%.2f,%.2f' . "\n",
                $product->name,
                $product->sku ?? '',
                $product->category->name ?? '',
                $product->stock,
                $product->min_stock,
                $product->max_stock,
                $product->reserved_stock,
                $product->available_stock,
                $product->stock_status,
                $product->location ?? '',
                $product->supplier ?? '',
                $product->cost_price ?? 0,
                $product->stock_value ?? 0
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="stock_levels_' . now()->format('Y-m-d') . '.csv"');
    }

    /**
     * Export stock movements
     */
    private function exportMovements(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        if ($request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('created_at', '<=', $request->end_date);
        }

        $movements = $query->orderBy('created_at', 'desc')->get();

        $csvData = "Date,Product,Type,Reason,Quantity,Stock Before,Stock After,User,Notes\n";

        foreach ($movements as $movement) {
            $csvData .= sprintf(
                '"%s","%s","%s","%s",%d,%d,%d,"%s","%s"' . "\n",
                $movement->created_at->format('Y-m-d H:i:s'),
                $movement->product->name,
                $movement->description,
                $movement->reason_description,
                $movement->quantity,
                $movement->stock_before,
                $movement->stock_after,
                $movement->user->name ?? '',
                str_replace('"', '""', $movement->notes ?? '')
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="stock_movements_' . now()->format('Y-m-d') . '.csv"');
    }

    /**
     * Export stock alerts
     */
    private function exportAlerts(Request $request)
    {
        $query = StockAlert::with(['product', 'acknowledgedBy', 'resolvedBy']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $alerts = $query->orderBy('created_at', 'desc')->get();

        $csvData = "Date,Product,Type,Priority,Status,Current Stock,Threshold,Message,Acknowledged By,Resolved By\n";

        foreach ($alerts as $alert) {
            $csvData .= sprintf(
                '"%s","%s","%s","%s","%s",%d,%d,"%s","%s","%s"' . "\n",
                $alert->created_at->format('Y-m-d H:i:s'),
                $alert->product->name,
                $alert->type_description,
                $alert->priority_description,
                $alert->status_description,
                $alert->current_stock,
                $alert->threshold_value ?? 0,
                str_replace('"', '""', $alert->message),
                $alert->acknowledgedBy->name ?? '',
                $alert->resolvedBy->name ?? ''
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="stock_alerts_' . now()->format('Y-m-d') . '.csv"');
    }
}