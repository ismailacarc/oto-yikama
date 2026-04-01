<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model {
    protected $fillable = ['customer_id', 'car_brand_id', 'car_model_id', 'brand_name', 'model_name', 'plate', 'color', 'year'];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function brand() { return $this->belongsTo(CarBrand::class, 'car_brand_id'); }
    public function model() { return $this->belongsTo(CarModel::class, 'car_model_id'); }
    public function workOrders() { return $this->hasMany(WorkOrder::class); }

    public function getDisplayNameAttribute(): string {
        $brand = $this->brand_name ?? $this->brand?->name ?? '';
        $model = $this->model_name ?? $this->model?->name ?? '';
        $plate = $this->plate ?? '';
        return trim("$brand $model") . ($plate ? " ($plate)" : '');
    }
}
