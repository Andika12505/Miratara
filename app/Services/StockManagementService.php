<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockManagementService
{
    /**
     * Add stock to product
     */
    public function addStock($productId, $quantity, $reason = 'purchase', $notes = null, $unitCost = null, $batchNumber = null)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($productId);
            
            // Update cost price if provided
            if ($unitCost) {
                $product->cost_price = $unitCost;
            }
            
            // Update batch number if provided
            if ($batchNumber) {
                $product->batch_number = $batchNumber;
            }

            // Add stock
            $product->updateStock($quantity, 'in', $reason, $notes);

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully added {$quantity} units to {$product->name}",
                'product' => $product,
                'new_stock' => $product->stock
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error adding stock: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to add stock: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Remove stock from product
     */
    public function removeStock($productId, $quantity, $reason = 'sale', $notes = null, $referenceType = null, $referenceId = null)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($productId);
            
            // Check if enough stock available
            if ($product->available_stock < $quantity) {
                return [
                    'success' => false,
                    'message' => "Insufficient stock. Available: {$product->available_stock}, Requested: {$quantity}"
                ];
            }

            // Remove stock
            $product->updateStock(-$quantity, 'out', $reason, $notes, $referenceType, $referenceId);

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully removed {$quantity} units from {$product->name}",
                'product' => $product,
                'new_stock' => $product->stock
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error removing stock: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to remove stock: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Adjust stock to specific quantity
     */
    public function adjustStock($productId, $newQuantity, $reason = 'adjustment', $notes = null)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($productId);
            $oldStock = $product->stock;
            $difference = $newQuantity - $oldStock;

            // Update stock to new quantity
            $product->updateStock($newQuantity, 'adjustment', $reason, $notes);

            DB::commit();

            $action = $difference > 0 ? 'increased' : 'decreased';
            $diffAmount = abs($difference);

            return [
                'success' => true,
                'message' => "Stock {$action} by {$diffAmount} units. New stock: {$newQuantity}",
                'product' => $product,
                'old_stock' => $oldStock,
                'new_stock' => $newQuantity,
                'difference' => $difference
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error adjusting stock: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to adjust stock: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reserve stock for order
     */
    public function reserveStock($productId, $quantity, $referenceType = null, $referenceId = null, $notes = null)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($productId);
            
            if (!$product->reserveStock($quantity, $notes, $referenceType, $referenceId)) {
                return [
                    'success' => false,
                    'message' => "Insufficient stock to reserve. Available: {$product->available_stock}, Requested: {$quantity}"
                ];
            }

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully reserved {$quantity} units of {$product->name}",
                'product' => $product,
                'reserved_stock' => $product->reserved_stock,
                'available_stock' => $product->available_stock
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error reserving stock: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to reserve stock: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Release reserved stock
     */
    public function releaseStock($productId, $quantity, $referenceType = null, $referenceId = null, $notes = null)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($productId);
            $product->releaseStock($quantity, $notes, $referenceType, $referenceId);

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully released {$quantity} units of {$product->name}",
                'product' => $product,
                'reserved_stock' => $product->reserved_stock,
                'available_stock' => $product->available_stock
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error releasing stock: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to release stock: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Transfer stock between locations
     */
    public function transferStock($productId, $quantity, $fromLocation, $toLocation, $notes = null)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($productId);
            
            // Create stock movement record for transfer
            StockMovement::create([
                'product_id' => $productId,
                'user_id' => auth()->id() ?? 1,
                'type' => 'adjustment',
                'quantity' => 0, // No quantity change, just location change
                'stock_before' => $product->stock,
                'stock_after' => $product->stock,
                'reason' => 'transfer',
                'notes' => $notes,
                'from_location' => $fromLocation,
                'to_location' => $toLocation,
                'unit_cost' => $product->cost_price,
                'batch_number' => $product->batch_number,
            ]);

            // Update product location
            $product->update(['location' => $toLocation]);

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully transferred {$quantity} units from {$fromLocation} to {$toLocation}",
                'product' => $product
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error transferring stock: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to transfer stock: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Bulk update stock for multiple products
     */
    public function bulkUpdateStock($updates, $reason = 'bulk_adjustment', $notes = null)
    {
        try {
            DB::beginTransaction();

            $results = [];
            $successCount = 0;
            $errorCount = 0;

            foreach ($updates as $update) {
                try {
                    $productId = $update['product_id'];
                    $quantity = $update['quantity'];
                    $type = $update['type'] ?? 'adjustment';
                    
                    if ($type === 'adjustment') {
                        $result = $this->adjustStock($productId, $quantity, $reason, $notes);
                    } elseif ($type === 'add') {
                        $result = $this->addStock($productId, $quantity, $reason, $notes);
                    } elseif ($type === 'remove') {
                        $result = $this->removeStock($productId, $quantity, $reason, $notes);
                    }

                    if ($result['success']) {
                        $successCount++;
                    } else {
                        $errorCount++;
                    }

                    $results[] = array_merge($result, ['product_id' => $productId]);

                } catch (\Exception $e) {
                    $errorCount++;
                    $results[] = [
                        'success' => false,
                        'message' => 'Error updating product ' . $productId . ': ' . $e->getMessage(),
                        'product_id' => $productId
                    ];
                }
            }

            DB::commit();

            return [
                'success' => $errorCount === 0,
                'message' => "Bulk update completed. Success: {$successCount}, Errors: {$errorCount}",
                'results' => $results,
                'summary' => [
                    'total' => count($updates),
                    'success' => $successCount,
                    'errors' => $errorCount
                ]
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in bulk stock update: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to perform bulk update: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get stock summary for dashboard
     */
    public function getStockSummary()
    {
        try {
            return [
                'total_products' => Product::count(),
                'total_stock_value' => Product::sum(DB::raw('stock * COALESCE(cost_price, 0)')),
                'low_stock_count' => Product::lowStock()->count(),
                'out_of_stock_count' => Product::outOfStock()->count(),
                'overstock_count' => Product::overStocked()->count(),
                'total_reserved' => Product::sum('reserved_stock'),
                'active_alerts' => StockAlert::active()->count(),
                'critical_alerts' => StockAlert::active()->critical()->count(),
                'recent_movements' => StockMovement::where('created_at', '>=', now()->subDays(7))->count(),
                'top_moving_products' => $this->getTopMovingProducts(5),
                'expiring_soon' => Product::whereNotNull('expiry_date')
                    ->where('expiry_date', '<=', now()->addDays(30))
                    ->count(),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting stock summary: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get top moving products
     */
    public function getTopMovingProducts($limit = 10, $days = 30)
    {
        return StockMovement::select('product_id', DB::raw('SUM(ABS(quantity)) as total_movement'))
            ->where('created_at', '>=', now()->subDays($days))
            ->with('product:id,name,stock')
            ->groupBy('product_id')
            ->orderBy('total_movement', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Run daily stock maintenance
     */
    public function runDailyMaintenance()
    {
        try {
            $results = [];

            // Auto-resolve alerts
            $resolvedAlerts = StockAlert::autoResolveAlerts();
            $results['resolved_alerts'] = $resolvedAlerts;

            // Update stock statuses
            $products = Product::all();
            foreach ($products as $product) {
                $product->updateStockStatus();
                $product->checkStockAlerts();
                $product->save();
            }
            $results['updated_products'] = $products->count();

            // Clean old stock movements (optional - keep last 2 years)
            $cleanedMovements = StockMovement::where('created_at', '<', now()->subYears(2))->delete();
            $results['cleaned_movements'] = $cleanedMovements;

            return [
                'success' => true,
                'message' => 'Daily maintenance completed successfully',
                'results' => $results
            ];

        } catch (\Exception $e) {
            Log::error('Error in daily maintenance: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Daily maintenance failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate stock report
     */
    public function generateStockReport($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? now()->subMonth()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        return [
            'period' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d')
            ],
            'movements_summary' => StockMovement::getMovementsByType($startDate, $endDate),
            'current_stock_levels' => Product::select('id', 'name', 'stock', 'min_stock', 'max_stock', 'reserved_stock', 'stock_status')
                ->with('category:id,name')
                ->get(),
            'alerts_summary' => StockAlert::getSummary(),
            'stock_value' => Product::sum(DB::raw('stock * COALESCE(cost_price, 0)')),
            'low_stock_products' => Product::lowStock()->with('category:id,name')->get(),
            'out_of_stock_products' => Product::outOfStock()->with('category:id,name')->get(),
            'top_moving_products' => $this->getTopMovingProducts(10, $startDate->diffInDays($endDate)),
        ];
    }
}