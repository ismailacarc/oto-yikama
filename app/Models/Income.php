<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['title', 'category', 'amount', 'income_date', 'source', 'note'];
    protected $casts    = ['amount' => 'decimal:2', 'income_date' => 'date'];

    // Kategori etiket rengi
    public function getCategoryBadgeAttribute(): string
    {
        return match($this->category) {
            'is_emri' => 'bg-cyan-100 text-cyan-700',
            'sigorta' => 'bg-blue-100 text-blue-700',
            'satis'   => 'bg-emerald-100 text-emerald-700',
            default   => 'bg-slate-100 text-slate-700',
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'is_emri' => 'İş Emri',
            'sigorta' => 'Sigorta',
            'satis'   => 'Satış',
            default   => 'Diğer',
        };
    }
}
