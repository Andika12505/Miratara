<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'stock_before',
        'stock_after',
        'reason',
        'reference_type',
        'reference_id',
        'notes',
        'unit_cost',
        'total_cost',
        'batch_number',
        'from_location',
        'to_location',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship ke product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship ke user who made the movement
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Polymorphic relationship untuk reference (Order, Return, dll)
    public function reference()
    {
        return $this->morphTo();
    }

    // === HELPER METHODS ===

    /**
     * Get formatted quantity with sign
     */
    public function getFormattedQuantityAttribute()
    {
        $sign = '';
        if ($this->type === 'in') {
            $sign = '+';
        } elseif ($this->type === 'out') {
            $sign = '-';
        }
        
        return $sign . number_format($this->quantity);
    }

    /**
     * Get movement description
     */
    public function getDescriptionAttribute()
    {
        $descriptions = [
            'in' => 'Stock In',
            'out' => 'Stock Out',
            'adjustment' => 'Stock Adjustment',
            'reserved' => 'Stock Reserved',
            'released' => 'Stock Released',
        ];

        return $descriptions[$this->type] ?? 'Unknown Movement';
    }

    /**
     * Get reason description
     */
    public function getReasonDescriptionAttribute()
    {
        $reasons = [
            'purchase' => 'Purchase/Procurement',
            'sale' => 'Sale/Order',
            'return' => 'Return/Refund',
            'damaged' => 'Damaged/Defective',
            'expired' => 'Expired Product',
            'adjustment' => 'Manual Adjustment',
            'transfer' => 'Transfer/Move',
            'reservation' => 'Order Reservation',
            'cancellation' => 'Order Cancellation',
        ];

        return $reasons[$this->reason] ?? 'Other';
    }

    /**
     * Get stock difference
     */
    public function getStockDifferenceAttribute()
    {
        return $this->stock_after - $this->stock_before;
    }

    /**
     * Check if movement increases stock
     */
    public function isIncrease()
    {
        return $this->stock_after > $this->stock_before;
    }

    /**
     * Check if movement decreases stock
     */
    public function isDecrease()
    {
        return $this->stock_after < $this->stock_before;
    }

    // === SCOPES ===

    /**
     * Scope for stock in movements
     */
    public function scopeStockIn($query)
    {
        return $query->where('type', 'in');
    }

    /**
     * Scope for stock out movements
     */
    public function scopeStockOut($query)
    {
        return $query->where('type', 'out');
    }

    /**
     * Scope for adjustments
     */
    public function scopeAdjustments($query)
    {
        return $query->where('type', 'adjustment');
    }

    /**
     * Scope for reservations
     */
    public function scopeReservations($query)
    {
        return $query->where('type', 'reserved');
    }

    /**
     * Scope for movements by reason
     */
    public function scopeByReason($query, $reason)
    {
        return $query->where('reason', $reason);
    }

    /**
     * Scope for movements within date range
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for movements by product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope for movements by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // === STATIC METHODS ===

    /**
     * Get stock movements summary for a product
     */
    public static function getProductSummary($productId, $startDate = null, $endDate = null)
    {
        $query = self::where('product_id', $productId);
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return [
            'total_in' => $query->clone()->where('type', 'in')->sum('quantity'),
            'total_out' => $query->clone()->where('type', 'out')->sum('quantity'),
            'total_adjustments' => $query->clone()->where('type', 'adjustment')->count(),
            'total_value_in' => $query->clone()->where('type', 'in')->sum('total_cost'),
            'total_value_out' => $query->clone()->where('type', 'out')->sum('total_cost'),
            'last_movement' => $query->clone()->orderBy('created_at', 'desc')->first(),
        ];
    }

    /**
     * Get daily stock movements
     */
    public static function getDailyMovements($date = null)
    {
        $date = $date ?? now()->format('Y-m-d');
        
        return self::whereDate('created_at', $date)
            ->with(['product:id,name', 'user:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get movements by type for analytics
     */
    public static function getMovementsByType($startDate = null, $endDate = null)
    {
        $query = self::query();
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->selectRaw('type, reason, COUNT(*) as count, SUM(quantity) as total_quantity, SUM(total_cost) as total_value')
            ->groupBy('type', 'reason')
            ->orderBy('count', 'desc')
            ->get();
    }
}