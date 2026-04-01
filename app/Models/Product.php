<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $fillable = ['name', 'category', 'unit', 'stock_quantity', 'unit_price', 'min_stock_alert', 'is_active', 'notes'];
    protected $casts = ['stock_quantity' => 'decimal:3', 'unit_price' => 'decimal:2', 'min_stock_alert' => 'decimal:3', 'is_active' => 'boolean'];

    public function isLowStock(): bool {
        return $this->min_stock_alert > 0 && $this->stock_quantity <= $this->min_stock_alert;
    }
}
