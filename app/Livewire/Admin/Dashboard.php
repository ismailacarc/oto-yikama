<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\WorkOrder;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Staff;
use App\Models\StaffTimer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public string $selectedDate = '';
    public string $reportFrom   = '';
    public string $reportTo     = '';

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->reportFrom   = now()->startOfMonth()->format('Y-m-d');
        $this->reportTo     = now()->format('Y-m-d');
    }

    public function render()
    {
        $monthStart = Carbon::now()->startOfMonth();
        $date       = Carbon::parse($this->selectedDate);

        // ── KPI cards ──────────────────────────────────────────
        $todayOrderCount = WorkOrder::whereDate('created_at', $date)->count();

        $monthRevenue = Payment::where('paid_at', '>=', $monthStart)->sum('amount');

        $pendingCount    = WorkOrder::where('status', 'bekleyen')->count();
        $inProgressCount = WorkOrder::where('status', 'devam_eden')->count();
        $doneCount       = WorkOrder::where('status', 'tamamlandi')
                            ->whereMonth('completed_at', now()->month)
                            ->whereYear('completed_at', now()->year)
                            ->count();

        // Cari alacak: sum of remaining balances
        $cariAlacak = WorkOrder::selectRaw(
            'SUM(total_amount - discount_amount - paid_amount) as remaining'
        )->having('remaining', '>', 0)->value('remaining') ?? 0;

        // ── Günlük iş emirleri ─────────────────────────────────
        $dailyOrders = WorkOrder::with(['customer', 'vehicle.brand', 'vehicle.model', 'staff'])
            ->whereDate('created_at', $date)
            ->latest()
            ->get();

        // ── Aktif personel ─────────────────────────────────────
        $activeTimers = StaffTimer::with([
            'staff',
            'workOrder.customer',
            'workOrder.vehicle.brand',
            'workOrder.vehicle.model',
        ])->whereNull('ended_at')->get();

        // ── Bu ayın günlük ciro (bar chart) ───────────────────
        $daysInMonth = now()->daysInMonth;
        $dailyRevenue = Payment::where('paid_at', '>=', $monthStart)
            ->selectRaw('DATE(paid_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $chartData = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dayStr = now()->format('Y-m-') . str_pad($i, 2, '0', STR_PAD_LEFT);
            $chartData[] = [
                'label' => $i,
                'value' => (float) ($dailyRevenue[$dayStr] ?? 0),
            ];
        }
        $chartMax = max(array_column($chartData, 'value') ?: [1]);

        // ── Ödeme tipi dağılımı ────────────────────────────────
        $paymentBreakdown = Payment::where('paid_at', '>=', $monthStart)
            ->selectRaw('payment_type, SUM(amount) as total')
            ->groupBy('payment_type')
            ->pluck('total', 'payment_type');

        $nakitTotal  = (float) ($paymentBreakdown['nakit']  ?? 0);
        $kartTotal   = (float) ($paymentBreakdown['kart']   ?? 0);
        $havaleTotal = (float) ($paymentBreakdown['havale'] ?? 0);
        $payTotal    = $nakitTotal + $kartTotal + $havaleTotal ?: 1;

        // ── Düşük stok ─────────────────────────────────────────
        $lowStock = Product::where('is_active', true)
            ->whereRaw('stock_quantity <= min_stock_alert AND min_stock_alert > 0')
            ->orderBy('stock_quantity')
            ->get();

        // ── Müşteri sayısı ─────────────────────────────────────
        $totalCustomers    = Customer::count();
        $newCustomersMonth = Customer::where('created_at', '>=', $monthStart)->count();

        return view('livewire.admin.dashboard', compact(
            'todayOrderCount',
            'monthRevenue',
            'pendingCount',
            'inProgressCount',
            'doneCount',
            'cariAlacak',
            'dailyOrders',
            'date',
            'activeTimers',
            'chartData',
            'chartMax',
            'nakitTotal',
            'kartTotal',
            'havaleTotal',
            'payTotal',
            'lowStock',
            'totalCustomers',
            'newCustomersMonth',
        ))->layout('components.layouts.admin');
    }
}
