<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model {
    protected $fillable = ['order_no', 'customer_id', 'vehicle_id', 'staff_id', 'status', 'total_amount', 'paid_amount', 'discount_amount', 'notes', 'scheduled_at', 'started_at', 'completed_at'];
    protected $casts = ['total_amount' => 'decimal:2', 'paid_amount' => 'decimal:2', 'discount_amount' => 'decimal:2', 'scheduled_at' => 'datetime', 'started_at' => 'datetime', 'completed_at' => 'datetime'];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function staff() { return $this->belongsTo(Staff::class); }
    public function items() { return $this->hasMany(WorkOrderItem::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function timers() { return $this->hasMany(StaffTimer::class); }

    public function getEffectiveTotalAttribute(): float {
        return (float) $this->total_amount - (float) $this->discount_amount;
    }

    public function getRemainingAmountAttribute(): float {
        return $this->effective_total - (float) $this->paid_amount;
    }

    public function getStatusLabelAttribute(): string {
        return match($this->status) {
            'bekleyen'    => 'Bekleyen',
            'devam_eden'  => 'Devam Eden',
            'tamamlandi'  => 'Tamamlandı',
            default       => $this->status,
        };
    }

    public static function generateOrderNo(): string {
        $year = date('Y');
        $last = static::whereYear('created_at', $year)->max('id') ?? 0;
        return 'İE-' . $year . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
