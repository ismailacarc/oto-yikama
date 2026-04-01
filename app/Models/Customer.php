<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'notes'];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
