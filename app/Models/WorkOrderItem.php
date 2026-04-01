<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WorkOrderItem extends Model {
    protected $fillable = ['work_order_id', 'type', 'item_id', 'name', 'quantity', 'unit', 'unit_price', 'total_price'];
    protected $casts = ['quantity' => 'decimal:3', 'unit_price' => 'decimal:2', 'total_price' => 'decimal:2'];

    public function workOrder() { return $this->belongsTo(WorkOrder::class); }
}
