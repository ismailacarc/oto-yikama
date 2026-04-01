<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\WorkOrder;
use App\Models\Customer;

class Odemeler extends Component
{
    use WithPagination;

    public string $activeTab  = 'odemeler'; // odemeler | cari
    public string $search     = '';
    public string $filterType = ''; // nakit | kart | havale

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterType() { $this->resetPage(); }
    public function updatingActiveTab() { $this->resetPage(); $this->search = ''; $this->filterType = ''; }

    public function render()
    {
        // Ödemeler sekmesi
        $paymentsQuery = Payment::with(['workOrder','customer'])
            ->when($this->filterType, fn($q) => $q->where('payment_type', $this->filterType))
            ->when($this->search, fn($q) =>
                $q->whereHas('customer', fn($c) =>
                    $c->where('name','like','%'.$this->search.'%')
                      ->orWhere('phone','like','%'.$this->search.'%')
                )
            );

        $payments = $this->activeTab === 'odemeler'
            ? $paymentsQuery->orderBy('paid_at','desc')->paginate(20)
            : collect();

        $totalByType = Payment::selectRaw('payment_type, SUM(amount) as total')
            ->groupBy('payment_type')->pluck('total','payment_type');

        // Cari sekmesi
        $cariQuery = Customer::withSum('workOrders as total_sum','total_amount')
            ->withSum('workOrders as discount_sum','discount_amount')
            ->withSum('workOrders as paid_sum','paid_amount')
            ->whereHas('workOrders', fn($q) => $q->whereRaw('(total_amount - discount_amount) > paid_amount'))
            ->when($this->search, fn($q) =>
                $q->where('name','like','%'.$this->search.'%')
                  ->orWhere('phone','like','%'.$this->search.'%')
            );

        $cariList = $this->activeTab === 'cari'
            ? $cariQuery->orderBy('name')->paginate(20)
            : collect();

        $totalCari = WorkOrder::whereRaw('(total_amount - discount_amount) > paid_amount')
            ->selectRaw('SUM(total_amount - discount_amount - paid_amount) as debt')->value('debt') ?? 0;

        return view('livewire.admin.odemeler', compact(
            'payments','totalByType','cariList','totalCari'
        ))->layout('components.layouts.admin');
    }
}
