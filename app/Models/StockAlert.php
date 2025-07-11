<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'priority',
        'status',
        'current_stock',
        'threshold_value',
        'message',
        'acknowledged_by',
        'acknowledged_at',
        'resolved_by',
        'resolved_at',
        'resolution_notes',
        'metadata',
        'expires_at',
    ];

    protected $casts = [
        'current_stock' => 'integer',
        'threshold_value' => 'integer',
        'metadata' => 'array',
        'acknowledged_at' => 'datetime',
        'resolved_at' => 'datetime',
        'expires_at' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship ke product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship ke user who acknowledged
    public function acknowledgedBy()
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    // Relationship ke user who resolved
    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // === HELPER METHODS ===

    /**
     * Get alert type description
     */
    public function getTypeDescriptionAttribute()
    {
        $types = [
            'low_stock' => 'Low Stock',
            'out_of_stock' => 'Out of Stock',
            'overstock' => 'Overstock',
            'expiring_soon' => 'Expiring Soon',
        ];

        return $types[$this->type] ?? 'Unknown';
    }

    /**
     * Get priority description
     */
    public function getPriorityDescriptionAttribute()
    {
        $priorities = [
            'low' => 'Low Priority',
            'medium' => 'Medium Priority',
            'high' => 'High Priority',
            'critical' => 'Critical Priority',
        ];

        return $priorities[$this->priority] ?? 'Unknown';
    }

    /**
     * Get status description
     */
    public function getStatusDescriptionAttribute()
    {
        $statuses = [
            'active' => 'Active',
            'acknowledged' => 'Acknowledged',
            'resolved' => 'Resolved',
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    /**
     * Get priority color for UI
     */
    public function getPriorityColorAttribute()
    {
        $colors = [
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'critical' => 'dark',
        ];

        return $colors[$this->priority] ?? 'secondary';
    }

    /**
     * Get type color for UI
     */
    public function getTypeColorAttribute()
    {
        $colors = [
            'low_stock' => 'warning',
            'out_of_stock' => 'danger',
            'overstock' => 'info',
            'expiring_soon' => 'warning',
        ];

        return $colors[$this->type] ?? 'secondary';
    }

    /**
     * Get alert icon
     */
    public function getIconAttribute()
    {
        $icons = [
            'low_stock' => 'fas fa-exclamation-triangle',
            'out_of_stock' => 'fas fa-times-circle',
            'overstock' => 'fas fa-arrow-up',
            'expiring_soon' => 'fas fa-clock',
        ];

        return $icons[$this->type] ?? 'fas fa-bell';
    }

    /**
     * Check if alert is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if alert is acknowledged
     */
    public function isAcknowledged()
    {
        return $this->status === 'acknowledged';
    }

    /**
     * Check if alert is resolved
     */
    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    /**
     * Check if alert is critical
     */
    public function isCritical()
    {
        return $this->priority === 'critical';
    }

    /**
     * Check if alert is high priority
     */
    public function isHighPriority()
    {
        return in_array($this->priority, ['high', 'critical']);
    }

    /**
     * Get days since alert was created
     */
    public function getDaysSinceCreatedAttribute()
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Get time until expiry (for expiring products)
     */
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expires_at) {
            return null;
        }

        return now()->diffInDays($this->expires_at, false);
    }

    // === ACTION METHODS ===

    /**
     * Acknowledge alert
     */
    public function acknowledge($userId = null, $notes = null)
    {
        $this->update([
            'status' => 'acknowledged',
            'acknowledged_by' => $userId ?? auth()->id(),
            'acknowledged_at' => now(),
            'resolution_notes' => $notes,
        ]);

        return $this;
    }

    /**
     * Resolve alert
     */
    public function resolve($userId = null, $notes = null)
    {
        $this->update([
            'status' => 'resolved',
            'resolved_by' => $userId ?? auth()->id(),
            'resolved_at' => now(),
            'resolution_notes' => $notes,
        ]);

        return $this;
    }

    /**
     * Reactivate alert
     */
    public function reactivate()
    {
        $this->update([
            'status' => 'active',
            'acknowledged_by' => null,
            'acknowledged_at' => null,
            'resolved_by' => null,
            'resolved_at' => null,
            'resolution_notes' => null,
        ]);

        return $this;
    }

    // === SCOPES ===

    /**
     * Scope for active alerts
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for acknowledged alerts
     */
    public function scopeAcknowledged($query)
    {
        return $query->where('status', 'acknowledged');
    }

    /**
     * Scope for resolved alerts
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Scope for alerts by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for alerts by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for high priority alerts
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'critical']);
    }

    /**
     * Scope for critical alerts
     */
    public function scopeCritical($query)
    {
        return $query->where('priority', 'critical');
    }

    /**
     * Scope for alerts expiring soon
     */
    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->where('expires_at', '<=', now()->addDays($days));
    }

    /**
     * Scope for recent alerts
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // === STATIC METHODS ===

    /**
     * Get alerts summary
     */
    public static function getSummary()
    {
        return [
            'total_active' => self::active()->count(),
            'total_acknowledged' => self::acknowledged()->count(),
            'total_resolved' => self::resolved()->count(),
            'critical_alerts' => self::active()->critical()->count(),
            'high_priority_alerts' => self::active()->highPriority()->count(),
            'recent_alerts' => self::recent()->count(),
        ];
    }

    /**
     * Get alerts by type count
     */
    public static function getAlertsByType()
    {
        return self::active()
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }

    /**
     * Get alerts by priority count
     */
    public static function getAlertsByPriority()
    {
        return self::active()
            ->selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();
    }

    /**
     * Get top products with most alerts
     */
    public static function getTopProductsWithAlerts($limit = 10)
    {
        return self::active()
            ->with('product:id,name')
            ->selectRaw('product_id, COUNT(*) as alert_count')
            ->groupBy('product_id')
            ->orderBy('alert_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Auto-resolve alerts based on current stock
     */
    public static function autoResolveAlerts()
    {
        $resolvedCount = 0;

        // Auto-resolve low stock alerts
        $lowStockAlerts = self::active()->byType('low_stock')->get();
        foreach ($lowStockAlerts as $alert) {
            if ($alert->product->stock > $alert->product->min_stock) {
                $alert->resolve(null, 'Auto-resolved: Stock level restored');
                $resolvedCount++;
            }
        }

        // Auto-resolve out of stock alerts
        $outOfStockAlerts = self::active()->byType('out_of_stock')->get();
        foreach ($outOfStockAlerts as $alert) {
            if ($alert->product->stock > 0) {
                $alert->resolve(null, 'Auto-resolved: Stock replenished');
                $resolvedCount++;
            }
        }

        // Auto-resolve overstock alerts
        $overstockAlerts = self::active()->byType('overstock')->get();
        foreach ($overstockAlerts as $alert) {
            if ($alert->product->stock <= $alert->product->max_stock) {
                $alert->resolve(null, 'Auto-resolved: Stock level normalized');
                $resolvedCount++;
            }
        }

        return $resolvedCount;
    }
}