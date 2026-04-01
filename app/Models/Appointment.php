<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['customer_id', 'staff_id', 'appointment_date', 'note', 'total_price', 'status'];

    protected $casts = [
        'appointment_date' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_services')->withPivot('price')->withTimestamps();
    }

    public function appointmentServices()
    {
        return $this->hasMany(AppointmentService::class);
    }
}
