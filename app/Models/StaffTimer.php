<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StaffTimer extends Model {
    protected $fillable = ['work_order_id', 'staff_id', 'started_at', 'ended_at', 'duration_minutes'];
    protected $casts = ['started_at' => 'datetime', 'ended_at' => 'datetime'];

    public function workOrder() { return $this->belongsTo(WorkOrder::class); }
    public function staff() { return $this->belongsTo(Staff::class); }
}
