<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\StockMovement;

class Urunler extends Component
{
    use WithPagination;

    public string $search    = '';
    public string $filterCat = '';

    // Ürün formu
    public bool    $showForm         = false;
    public ?int    $editingId        = null;
    public string  $name             = '';
    public string  $category         = '';
    public string  $unit             = 'adet';
    public string  $unit_price       = '';
    public string  $stock_quantity   = '';
    public string  $min_stock_alert  = '0';
    public string  $notes            = '';
    public string  $customCategory   = '';

    // Stok giriş formu
    public bool   $showStockForm  = false;
    public ?int   $stockProductId = null;
    public string $stockAmount    = '';
    public string $stockNote      = '';

    public array $categories = [
        'ppf'      => 'PPF Film',
        'seramik'  => 'Seramik',
        'kimyasal' => 'Kimyasal',
        'alet'     => 'Alet & Ekipman',
        'diger'    => 'Diğer',
    ];

    public array $units = ['adet', 'm²', 'ml', 'litre', 'kg', 'gram', 'rulo'];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterCat() { $this->resetPage(); }

    // ── Ürün CRUD ──────────────────────────────────────────
    public function openForm(?int $id = null)
    {
        $this->reset(['editingId','name','category','customCategory','unit','unit_price','stock_quantity','min_stock_alert','notes']);
        $this->unit = 'adet';
        if ($id) {
            $p = Product::findOrFail($id);
            $this->editingId       = $p->id;
            $this->name            = $p->name;
            // Kayıtlı kategori sabit listede yoksa manuel giriş moduna al
            $knownCats = array_keys($this->categories);
            if ($p->category && !in_array($p->category, $knownCats)) {
                $this->category       = '__manuel__';
                $this->customCategory = $p->category;
            } else {
                $this->category = $p->category ?? '';
            }
            $this->unit            = $p->unit;
            $this->unit_price      = (string) $p->unit_price;
            $this->stock_quantity  = (string) $p->stock_quantity;
            $this->min_stock_alert = (string) $p->min_stock_alert;
            $this->notes           = $p->notes ?? '';
        }
        $this->showStockForm = false;
        $this->showForm      = true;
    }

    public function save()
    {
        $this->validate([
            'name'           => 'required|string|max:255',
            'unit'           => 'required|string',
            'unit_price'     => 'required|numeric|min:0',
            'stock_quantity' => 'required|numeric|min:0',
            'min_stock_alert'=> 'nullable|numeric|min:0',
        ], [
            'name.required'       => 'Ürün adı zorunludur.',
            'unit_price.required' => 'Birim fiyat zorunludur.',
            'unit_price.numeric'  => 'Geçerli bir fiyat girin.',
            'stock_quantity.required' => 'Stok miktarı zorunludur.',
        ]);

        $finalCategory = $this->category === '__manuel__'
            ? (trim($this->customCategory) ?: null)
            : ($this->category ?: null);

        $data = [
            'name'            => $this->name,
            'category'        => $finalCategory,
            'unit'            => $this->unit,
            'unit_price'      => $this->unit_price,
            'stock_quantity'  => $this->stock_quantity,
            'min_stock_alert' => $this->min_stock_alert ?: 0,
            'notes'           => $this->notes ?: null,
            'is_active'       => true,
        ];

        if ($this->editingId) {
            Product::findOrFail($this->editingId)->update($data);
        } else {
            Product::create($data);
        }

        $this->showForm = false;
        $this->reset(['editingId','name','category','customCategory','unit','unit_price','stock_quantity','min_stock_alert','notes']);
    }

    public function delete(int $id)
    {
        Product::findOrFail($id)->delete();
    }

    public function toggleActive(int $id)
    {
        $p = Product::findOrFail($id);
        $p->update(['is_active' => !$p->is_active]);
    }

    // ── Stok Girişi ────────────────────────────────────────
    public function openStockForm(int $id)
    {
        $this->stockProductId = $id;
        $this->stockAmount    = '';
        $this->stockNote      = '';
        $this->showForm       = false;
        $this->showStockForm  = true;
    }

    public function addStock()
    {
        $this->validate([
            'stockAmount' => 'required|numeric|min:0.001',
        ], ['stockAmount.required' => 'Miktar giriniz.', 'stockAmount.min' => 'Miktar 0\'dan büyük olmalı.']);

        $p = Product::findOrFail($this->stockProductId);
        $before = (float) $p->stock_quantity;
        $qty = (float) $this->stockAmount;
        $p->increment('stock_quantity', $qty);
        StockMovement::create([
            'product_id' => $p->id,
            'type'       => 'in',
            'quantity'   => $qty,
            'before'     => $before,
            'after'      => $before + $qty,
            'notes'      => 'Manuel stok girişi',
        ]);

        $this->showStockForm  = false;
        $this->stockProductId = null;
        $this->stockAmount    = '';
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        if ($this->filterCat) {
            $query->where('category', $this->filterCat);
        }

        $products   = $query->orderBy('name')->paginate(20);
        $lowStock   = Product::where('is_active', true)
                        ->whereRaw('min_stock_alert > 0 AND stock_quantity <= min_stock_alert')
                        ->count();
        $totalValue = Product::where('is_active', true)
                        ->selectRaw('SUM(stock_quantity * unit_price) as total')
                        ->value('total') ?? 0;

        return view('livewire.admin.urunler', compact('products', 'lowStock', 'totalValue'))
            ->layout('components.layouts.admin');
    }
}
