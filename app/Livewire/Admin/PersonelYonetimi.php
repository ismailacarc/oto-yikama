<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Staff;
use App\Models\StaffTimer;

class PersonelYonetimi extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $phone = '';
    public bool $is_active = true;

    public ?int $viewingStaffId = null;

    public function toggleStaff(int $id)
    {
        $this->viewingStaffId = $this->viewingStaffId === $id ? null : $id;
    }

    public function create()
    {
        $this->reset(['editingId', 'name', 'phone', 'is_active']);
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $staff = Staff::findOrFail($id);
        $this->editingId = $staff->id;
        $this->name = $staff->name;
        $this->phone = $staff->phone ?? '';
        $this->is_active = $staff->is_active;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            Staff::findOrFail($this->editingId)->update($data);
        } else {
            Staff::create($data);
        }

        $this->showForm = false;
        $this->reset(['editingId', 'name', 'phone', 'is_active']);
    }

    public function delete(int $id)
    {
        Staff::findOrFail($id)->delete();
    }

    public function render()
    {
        $staffList = Staff::orderBy('sort_order')->get();

        // Aktif timerlar (şu an çalışan)
        $activeTimers = StaffTimer::with([
            'workOrder.customer',
            'workOrder.vehicle.brand',
            'workOrder.vehicle.model',
        ])->whereNull('ended_at')->get()->keyBy('staff_id');

        // Seçili personelin timer geçmişi
        $timerHistory = collect();
        if ($this->viewingStaffId) {
            $timerHistory = StaffTimer::with([
                'workOrder.customer',
                'workOrder.vehicle.brand',
                'workOrder.vehicle.model',
            ])->where('staff_id', $this->viewingStaffId)
              ->whereNotNull('ended_at')
              ->orderBy('started_at', 'desc')
              ->limit(20)
              ->get();
        }

        return view('livewire.admin.personel-yonetimi', compact('staffList','activeTimers','timerHistory'))
            ->layout('components.layouts.admin');
    }
}
