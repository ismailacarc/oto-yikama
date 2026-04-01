<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\AppointmentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MaliBilgiler extends Component
{
    public string $dateFrom = '';
    public string $dateTo = '';

    public function mount()
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    public function filter()
    {
        // triggers re-render
    }

    public function render()
    {
        $from = Carbon::parse($this->dateFrom)->startOfDay();
        $to = Carbon::parse($this->dateTo)->endOfDay();

        $appointments = Appointment::where('status', 'onaylandi')
            ->whereBetween('appointment_date', [$from, $to])
            ->get();

        $totalRevenue = $appointments->sum('total_price');
        $dayCount = max(1, Carbon::parse($this->dateFrom)->diffInDays(Carbon::parse($this->dateTo)) + 1);
        $dailyAverage = $totalRevenue / $dayCount;

        // Daily breakdown
        $dailyRevenue = $appointments->groupBy(function ($a) {
            return $a->appointment_date->format('d.m.Y');
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('total_price'),
            ];
        });

        // Service breakdown
        $serviceRevenue = DB::table('appointment_services')
            ->join('appointments', 'appointments.id', '=', 'appointment_services.appointment_id')
            ->join('services', 'services.id', '=', 'appointment_services.service_id')
            ->where('appointments.status', 'onaylandi')
            ->whereBetween('appointments.appointment_date', [$from, $to])
            ->select('services.name', DB::raw('COUNT(*) as count'), DB::raw('SUM(appointment_services.price) as total'))
            ->groupBy('services.name')
            ->get();

        // Heatmap data (day of week x hour)
        $heatmapData = [];
        $allAppointments = Appointment::where('status', '!=', 'iptal')
            ->whereBetween('appointment_date', [$from, $to])
            ->get();

        foreach ($allAppointments as $apt) {
            $dayOfWeek = $apt->appointment_date->dayOfWeekIso; // 1=Mon, 7=Sun
            $hour = $apt->appointment_date->format('H');
            $key = $dayOfWeek . '-' . $hour;
            $heatmapData[$key] = ($heatmapData[$key] ?? 0) + 1;
        }

        return view('livewire.admin.mali-bilgiler', [
            'totalRevenue' => $totalRevenue,
            'dayCount' => $dayCount,
            'dailyAverage' => $dailyAverage,
            'dailyRevenue' => $dailyRevenue,
            'serviceRevenue' => $serviceRevenue,
            'heatmapData' => $heatmapData,
        ])->layout('components.layouts.admin');
    }
}
