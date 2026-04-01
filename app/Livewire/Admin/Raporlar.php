<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Payment;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\StaffTimer;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Raporlar extends Component
{
    public string $period = 'month'; // today | week | month | year | custom
    public string $dateFrom = '';
    public string $dateTo   = '';

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo   = now()->format('Y-m-d');
    }

    public function updatedPeriod()
    {
        match($this->period) {
            'today' => [$this->dateFrom, $this->dateTo] = [now()->format('Y-m-d'), now()->format('Y-m-d')],
            'week'  => [$this->dateFrom, $this->dateTo] = [now()->startOfWeek()->format('Y-m-d'), now()->format('Y-m-d')],
            'month' => [$this->dateFrom, $this->dateTo] = [now()->startOfMonth()->format('Y-m-d'), now()->format('Y-m-d')],
            'year'  => [$this->dateFrom, $this->dateTo] = [now()->startOfYear()->format('Y-m-d'), now()->format('Y-m-d')],
            default => null,
        };
    }

    private function range(): array
    {
        return [
            Carbon::parse($this->dateFrom)->startOfDay(),
            Carbon::parse($this->dateTo)->endOfDay(),
        ];
    }

    public function render()
    {
        [$from, $to] = $this->range();

        // ── Ciro ─────────────────────────────────────────
        $payments = Payment::whereBetween('paid_at', [$from, $to]);
        $totalRevenue  = (clone $payments)->sum('amount');
        $revenueByType = (clone $payments)->select('payment_type', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_type')->pluck('total','payment_type');

        // Günlük ciro (grafik için)
        $days = collect();
        $current = $from->copy();
        while ($current <= $to) {
            $days[$current->format('Y-m-d')] = 0;
            $current->addDay();
        }
        $dailyRevenue = (clone $payments)
            ->select(DB::raw('DATE(paid_at) as day'), DB::raw('SUM(amount) as total'))
            ->groupBy('day')->pluck('total','day');
        $chartData = $days->merge($dailyRevenue);
        $chartMax  = $chartData->max() ?: 1;

        // ── İş Emirleri ──────────────────────────────────
        $orders = WorkOrder::whereBetween('created_at', [$from, $to]);
        $totalOrders     = (clone $orders)->count();
        $completedOrders = (clone $orders)->where('status','tamamlandi')->count();
        $pendingRevenue  = WorkOrder::whereRaw('(total_amount - discount_amount) > paid_amount')->sum(DB::raw('total_amount - discount_amount - paid_amount'));

        // ── En Çok Yapılan Hizmet/Ürün ───────────────────
        $topItems = WorkOrderItem::whereHas('workOrder', fn($q) =>
            $q->whereBetween('created_at', [$from, $to])
        )->select('name','type', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(total_price) as revenue'))
         ->groupBy('name','type')
         ->orderByDesc('revenue')
         ->limit(6)->get();

        // ── Personel Verimliliği ──────────────────────────
        $staffStats = StaffTimer::with('staff')
            ->whereBetween('started_at', [$from, $to])
            ->whereNotNull('ended_at')
            ->select('staff_id',
                DB::raw('COUNT(*) as job_count'),
                DB::raw('SUM(duration_minutes) as total_minutes'))
            ->groupBy('staff_id')
            ->orderByDesc('total_minutes')
            ->get();

        // ── Stok Durumu ───────────────────────────────────
        $lowStock    = Product::where('is_active',true)->whereRaw('min_stock_alert > 0')->whereRaw('stock_quantity <= min_stock_alert')->orderBy('stock_quantity')->get();
        $stockValue  = Product::where('is_active',true)->sum(DB::raw('stock_quantity * unit_price'));
        $recentMoves = StockMovement::with('product')->whereBetween('created_at', [$from, $to])
            ->orderByDesc('created_at')->limit(8)->get();

        return view('livewire.admin.raporlar', compact(
            'totalRevenue','revenueByType','chartData','chartMax',
            'totalOrders','completedOrders','pendingRevenue',
            'topItems','staffStats',
            'lowStock','stockValue','recentMoves'
        ))->layout('components.layouts.admin');
    }
}
