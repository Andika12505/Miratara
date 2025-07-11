<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'price',
        'stock',
        'min_stock',
        'max_stock',
        'reserved_stock',
        'stock_unit',
        'cost_price',
        'supplier',
        'sku',
        'location',
        'batch_number',
        'expiry_date',
        'metadata',
        'is_active',
        'last_stock_update'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'max_stock' => 'integer',
        'reserved_stock' => 'integer',
        'is_active' => 'boolean',
        'metadata' => 'array',
        'expiry_date' => 'date',
        'last_stock_update' => 'datetime'
    ];

    // Relationship ke model Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship ke stock movements
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class)->orderBy('created_at', 'desc');
    }

    // Relationship ke stock alerts
    public function stockAlerts()
    {
        return $this->hasMany(StockAlert::class);
    }

    // Relationship ke active stock alerts
    public function activeStockAlerts()
    {
        return $this->hasMany(StockAlert::class)->where('status', 'active');
    }

    // Relationship ke product sizes (many-to-many)
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes')->withPivot('stock', 'price')->withTimestamps();
    }

    // === STOCK MANAGEMENT METHODS ===

    /**
     * Get available stock (stock - reserved_stock)
     */
    public function getAvailableStockAttribute()
    {
        return $this->stock - $this->reserved_stock;
    }

    /**
     * Check if product is in stock
     */
    public function isInStock($quantity = 1)
    {
        return $this->available_stock >= $quantity;
    }

    /**
     * Check if stock is low
     */
    public function isLowStock()
    {
        return $this->stock <= $this->min_stock && $this->stock > 0;
    }

    /**
     * Check if product is out of stock
     */
    public function isOutOfStock()
    {
        return $this->stock <= 0;
    }

    /**
     * Check if product is overstocked
     */
    public function isOverstocked()
    {
        return $this->stock > $this->max_stock;
    }

    /**
     * Update stock and create movement record
     */
    public function updateStock($quantity, $type = 'adjustment', $reason = 'adjustment', $notes = null, $referenceType = null, $referenceId = null)
    {
        $stockBefore = $this->stock;
        
        // Update stock based on type
        if ($type === 'in') {
            $this->stock += abs($quantity);
        } elseif ($type === 'out') {
            $this->stock -= abs($quantity);
        } else {
            $this->stock = $quantity; // For adjustments, set exact quantity
        }

        // Ensure stock doesn't go negative
        $this->stock = max(0, $this->stock);
        
        // Update stock status
        $this->updateStockStatus();
        
        // Update last stock update timestamp
        $this->last_stock_update = now();
        
        $this->save();

        // Create stock movement record
        $this->createStockMovement($type, $quantity, $stockBefore, $reason, $notes, $referenceType, $referenceId);

        // Check and create alerts if needed
        $this->checkStockAlerts();

        return $this;
    }

    /**
     * Reserve stock (for pending orders)
     */
    public function reserveStock($quantity, $notes = null, $referenceType = null, $referenceId = null)
    {
        if ($this->available_stock >= $quantity) {
            $this->reserved_stock += $quantity;
            $this->save();

            $this->createStockMovement('reserved', $quantity, $this->stock, 'reservation', $notes, $referenceType, $referenceId);
            
            return true;
        }
        return false;
    }

    /**
     * Release reserved stock
     */
    public function releaseStock($quantity, $notes = null, $referenceType = null, $referenceId = null)
    {
        $this->reserved_stock = max(0, $this->reserved_stock - $quantity);
        $this->save();

        $this->createStockMovement('released', $quantity, $this->stock, 'release', $notes, $referenceType, $referenceId);

        return $this;
    }

    /**
     * Update stock status based on current stock levels
     */
    public function updateStockStatus()
    {
        if ($this->isOutOfStock()) {
            $this->stock_status = 'out_of_stock';
        } elseif ($this->isLowStock()) {
            $this->stock_status = 'low_stock';
        } else {
            $this->stock_status = 'in_stock';
        }
    }

    /**
     * Create stock movement record
     */
    private function createStockMovement($type, $quantity, $stockBefore, $reason, $notes = null, $referenceType = null, $referenceId = null)
    {
        return StockMovement::create([
            'product_id' => $this->id,
            'user_id' => Auth::id() ?? 1, // Default to admin if no user
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $this->stock,
            'reason' => $reason,
            'notes' => $notes,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'unit_cost' => $this->cost_price,
            'total_cost' => $this->cost_price ? $this->cost_price * abs($quantity) : null,
            'batch_number' => $this->batch_number,
            'to_location' => $this->location,
        ]);
    }

    /**
     * Check stock levels and create alerts if needed
     */
    public function checkStockAlerts()
    {
        // Check for low stock
        if ($this->isLowStock() && !$this->hasActiveAlert('low_stock')) {
            $this->createStockAlert('low_stock', 'medium', "Product {$this->name} is running low on stock. Current: {$this->stock}, Minimum: {$this->min_stock}");
        }

        // Check for out of stock
        if ($this->isOutOfStock() && !$this->hasActiveAlert('out_of_stock')) {
            $this->createStockAlert('out_of_stock', 'high', "Product {$this->name} is out of stock!");
        }

        // Check for overstock
        if ($this->isOverstocked() && !$this->hasActiveAlert('overstock')) {
            $this->createStockAlert('overstock', 'low', "Product {$this->name} is overstocked. Current: {$this->stock}, Maximum: {$this->max_stock}");
        }

        // Check for expiring products
        if ($this->expiry_date && $this->expiry_date->diffInDays(now()) <= 30 && !$this->hasActiveAlert('expiring_soon')) {
            $this->createStockAlert('expiring_soon', 'medium', "Product {$this->name} expires on {$this->expiry_date->format('Y-m-d')}");
        }
    }

    /**
     * Check if product has active alert of specific type
     */
    private function hasActiveAlert($type)
    {
        return $this->activeStockAlerts()->where('type', $type)->exists();
    }

    /**
     * Create stock alert
     */
    private function createStockAlert($type, $priority, $message)
    {
        return StockAlert::create([
            'product_id' => $this->id,
            'type' => $type,
            'priority' => $priority,
            'current_stock' => $this->stock,
            'threshold_value' => $type === 'low_stock' ? $this->min_stock : ($type === 'overstock' ? $this->max_stock : null),
            'message' => $message,
            'expires_at' => $this->expiry_date,
        ]);
    }

    /**
     * Get stock value (stock * cost_price)
     */
    public function getStockValueAttribute()
    {
        return $this->stock * ($this->cost_price ?? 0);
    }

    /**
     * Get profit margin
     */
    public function getProfitMarginAttribute()
    {
        if (!$this->cost_price || $this->cost_price == 0) {
            return 0;
        }
        return (($this->price - $this->cost_price) / $this->cost_price) * 100;
    }

    /**
     * Scope for low stock products
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
    }

    /**
     * Scope for out of stock products
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    /**
     * Scope for overstocked products
     */
    public function scopeOverstocked($query)
    {
        return $query->whereColumn('stock', '>', 'max_stock');
    }

    /**
     * Scope for products with active alerts
     */
    public function scopeWithActiveAlerts($query)
    {
        return $query->whereHas('activeStockAlerts');
    }
}