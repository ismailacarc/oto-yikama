<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = ['name', 'color', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // Tailwind renk → bg/text sınıfları
    public function getBadgeClassAttribute(): string
    {
        return match($this->color) {
            'red'    => 'bg-red-100 text-red-700',
            'orange' => 'bg-orange-100 text-orange-700',
            'amber'  => 'bg-amber-100 text-amber-700',
            'yellow' => 'bg-yellow-100 text-yellow-700',
            'green'  => 'bg-green-100 text-green-700',
            'teal'   => 'bg-teal-100 text-teal-700',
            'blue'   => 'bg-blue-100 text-blue-700',
            'indigo' => 'bg-indigo-100 text-indigo-700',
            'purple' => 'bg-purple-100 text-purple-700',
            'pink'   => 'bg-pink-100 text-pink-700',
            default  => 'bg-slate-100 text-slate-700',
        };
    }
}
