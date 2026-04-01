<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Service;

class Islemler extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $description = '';
    public int $duration = 30;
    public float $price = 0;
    public string $approval_type = 'manual';
    public bool $is_active = true;

    public function create()
    {
        $this->reset(['editingId', 'name', 'description', 'duration', 'price', 'approval_type', 'is_active']);
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $service = Service::findOrFail($id);
        $this->editingId = $service->id;
        $this->name = $service->name;
        $this->description = $service->description ?? '';
        $this->duration = $service->duration;
        $this->price = $service->price;
        $this->approval_type = $service->approval_type;
        $this->is_active = $service->is_active;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:5',
            'price' => 'required|numeric|min:0',
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'duration' => $this->duration,
            'price' => $this->price,
            'approval_type' => $this->approval_type,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            Service::findOrFail($this->editingId)->update($data);
        } else {
            Service::create($data);
        }

        $this->showForm = false;
        $this->reset(['editingId', 'name', 'description', 'duration', 'price', 'approval_type', 'is_active']);
    }

    public function delete(int $id)
    {
        Service::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.admin.islemler', [
            'services' => Service::orderBy('sort_order')->get(),
        ])->layout('components.layouts.admin');
    }
}
