<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\WorkOrder;
use App\Models\Staff;
use Carbon\Carbon;

class Takvim extends Component
{
    public string $currentMonth = '';
    public int    $staffFilter  = 0;
    public string $selectedDay  = '';

    public function mount()
    {
        $this->currentMonth = now()->format('Y-m');
        $this->selectedDay  = now()->format('Y-m-d');
    }

    public function previousMonth()
    {
        $this->currentMonth = Carbon::parse($this->currentMonth . '-01')->subMonth()->format('Y-m');
    }

    public function nextMonth()
    {
        $this->currentMonth = Carbon::parse($this->currentMonth . '-01')->addMonth()->format('Y-m');
    }

    public function goToday()
    {
        $this->currentMonth = now()->format('Y-m');
        $this->selectedDay  = now()->format('Y-m-d');
    }

    public function selectDay(string $day)
    {
        $this->selectedDay = $day;
    }

    public function render()
    {
        $monthStart = Carbon::parse($this->currentMonth . '-01')->startOfMonth();
        $monthEnd   = $monthStart->copy()->endOfMonth();

        $gridStart = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd   = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);

        $ordersQuery = WorkOrder::with(['customer', 'vehicle.brand', 'staff'])
            ->whereNotNull('scheduled_at')
            ->whereBetween('scheduled_at', [$gridStart, $gridEnd->copy()->endOfDay()]);

        if ($this->staffFilter > 0) {
            $ordersQuery->where('staff_id', $this->staffFilter);
        }

        $ordersByDay = $ordersQuery->orderBy('scheduled_at')->get()
            ->groupBy(fn($o) => $o->scheduled_at->format('Y-m-d'));

        $days = [];
        $cur  = $gridStart->copy();
        while ($cur <= $gridEnd) {
            $days[] = $cur->copy();
            $cur->addDay();
        }

        $dayOrders = collect();
        if ($this->selectedDay) {
            $dayQuery = WorkOrder::with(['customer', 'vehicle.brand', 'vehicle.model', 'staff'])
                ->whereDate('scheduled_at', $this->selectedDay);
            if ($this->staffFilter > 0) {
                $dayQuery->where('staff_id', $this->staffFilter);
            }
            $dayOrders = $dayQuery->orderBy('scheduled_at')->get();
        }

        // scheduled_at olmayan ama bugün oluşturulan emirleri de göster
        $todayUnscheduled = collect();
        if ($this->selectedDay === now()->format('Y-m-d')) {
            $todayUnscheduled = WorkOrder::with(['customer', 'vehicle.brand', 'staff'])
                ->whereNull('scheduled_at')
                ->whereDate('created_at', now())
                ->orderBy('created_at')
                ->get();
        }

        $staffList = Staff::where('is_active', true)->orderBy('name')->get();

        return view('livewire.admin.takvim', compact(
            'days', 'ordersByDay', 'monthStart', 'dayOrders', 'todayUnscheduled', 'staffList'
        ))->layout('components.layouts.admin');
    }
}
