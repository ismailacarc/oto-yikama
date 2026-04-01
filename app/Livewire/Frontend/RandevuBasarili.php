<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Appointment;

class RandevuBasarili extends Component
{
    public Appointment $appointment;

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function render()
    {
        return view('livewire.frontend.randevu-basarili')->layout('components.layouts.app');
    }
}