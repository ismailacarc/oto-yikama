<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model {
    protected $fillable = ['product_id','work_order_id','type','quantity','before','after','notes'];
    protected $casts = ['quantity'=>'decimal:3','before'=>'decimal:3','after'=>'decimal:3'];

    public function product() { return $this->belongsTo(Product::class); }
    public function workOrder() { return $this->belongsTo(WorkOrder::class); }
}
