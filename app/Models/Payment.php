<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $fillable = ['work_order_id', 'customer_id', 'amount', 'payment_type', 'paid_at', 'notes'];
    protected $casts = ['amount' => 'decimal:2', 'paid_at' => 'date'];

    public function workOrder() { return $this->belongsTo(WorkOrder::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
}
