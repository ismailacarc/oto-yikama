<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\Appointment;
use App\Models\Setting;
use Carbon\Carbon;

class RandevuAl extends Component
{
    public int $step = 1;
    public array $selectedServices = [];
    public ?int $selectedStaff = null;
    public bool $staffGeneral = true;
    public string $selectedDate = '';
    public string $selectedTime = '';
    public string $customerName = '';
    public string $customerPhone = '';
    public string $note = '';

    public function mount()
    {
        $this->selectedDate = Carbon::tomorrow()->format('Y-m-d');
    }

    public function toggleService(int $id)
    {
        if (in_array($id, $this->selectedServices)) {
            $this->selectedServices = array_values(array_diff($this->selectedServices, [$id]));
        } else {
            $this->selectedServices[] = $id;
        }
    }

    public function selectStaff(?int $id)
    {
        if ($id === null) {
            $this->staffGeneral = true;
            $this->selectedStaff = null;
        } else {
            $this->staffGeneral = false;
            $this->selectedStaff = $id;
        }
    }

    public function nextStep()
    {
        if ($this->step === 1 && empty($this->selectedServices)) {
            $this->addError('services', 'En az bir işlem seçmelisiniz.');
            return;
        }

        if ($this->step === 2 && (!$this->selectedDate || !$this->selectedTime)) {
            $this->addError('datetime', 'Tarih ve saat seçmelisiniz.');
            return;
        }

        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function submit()
    {
        $this->validate([
            'customerName' => 'required|string|max:255',
            'customerPhone' => 'required|string|max:20',
        ]);

        $customer = Customer::firstOrCreate(
            ['phone' => $this->customerPhone],
            ['name' => $this->customerName]
        );
        $customer->update(['name' => $this->customerName]);

        $services = Service::whereIn('id', $this->selectedServices)->get();
        $totalPrice = $services->sum('price');

        $allAuto = $services->every(fn($s) => $s->approval_type === 'auto');

        $appointment = Appointment::create([
            'customer_id' => $customer->id,
            'staff_id' => $this->staffGeneral ? null : $this->selectedStaff,
            'appointment_date' => Carbon::parse($this->selectedDate . ' ' . $this->selectedTime),
            'note' => $this->note,
            'total_price' => $totalPrice,
            'status' => $allAuto ? 'onaylandi' : 'bekliyor',
        ]);

        foreach ($services as $service) {
            $appointment->services()->attach($service->id, ['price' => $service->price]);
        }

        return redirect('/randevu/basarili/' . $appointment->id);
    }

    public function render()
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();
        $staffList = Staff::where('is_active', true)->orderBy('sort_order')->get();

        $timeSlots = [];
        if ($this->selectedDate) {
            $start = Setting::get('working_hours_start', '09:00');
            $end = Setting::get('working_hours_end', '21:00');
            $interval = (int) Setting::get('slot_interval', 30);

            $current = Carbon::parse($this->selectedDate . ' ' . $start);
            $endTime = Carbon::parse($this->selectedDate . ' ' . $end);

            while ($current < $endTime) {
                $timeSlots[] = $current->format('H:i');
                $current->addMinutes($interval);
            }
        }

        $selectedServiceDetails = Service::whereIn('id', $this->selectedServices)->get();

        return view('livewire.frontend.randevu-al', [
            'services' => $services,
            'staffList' => $staffList,
            'timeSlots' => $timeSlots,
            'selectedServiceDetails' => $selectedServiceDetails,
        ])->layout('components.layouts.app');
    }
}
