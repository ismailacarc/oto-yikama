<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Setting;
use Carbon\Carbon;

class Randevular extends Component
{
    use WithPagination;

    public string $status = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    // Yeni randevu formu
    public bool $showCreateForm = false;
    public string $newCustomerName = '';
    public string $newCustomerPhone = '';
    public array $newSelectedServices = [];
    public ?int $newStaffId = null;
    public string $newDate = '';
    public string $newTime = '';
    public string $newNote = '';
    public string $newStatus = 'onaylandi';

    public function updatingStatus() { $this->resetPage(); }
    public function updatingDateFrom() { $this->resetPage(); }
    public function updatingDateTo() { $this->resetPage(); }

    public function openCreateForm()
    {
        $this->reset(['newCustomerName', 'newCustomerPhone', 'newSelectedServices', 'newStaffId', 'newDate', 'newTime', 'newNote', 'newStatus']);
        $this->newStatus = 'onaylandi';
        $this->newDate = Carbon::today()->format('Y-m-d');
        $this->showCreateForm = true;
    }

    public function createAppointment()
    {
        $this->validate([
            'newCustomerName' => 'required|string|max:255',
            'newCustomerPhone' => 'required|string|max:20',
            'newSelectedServices' => 'required|array|min:1',
            'newDate' => 'required|date',
            'newTime' => 'required',
        ], [
            'newCustomerName.required' => 'Müşteri adı zorunludur.',
            'newCustomerPhone.required' => 'Telefon zorunludur.',
            'newSelectedServices.required' => 'En az bir işlem seçmelisiniz.',
            'newSelectedServices.min' => 'En az bir işlem seçmelisiniz.',
            'newDate.required' => 'Tarih zorunludur.',
            'newTime.required' => 'Saat zorunludur.',
        ]);

        $customer = Customer::firstOrCreate(
            ['phone' => $this->newCustomerPhone],
            ['name' => $this->newCustomerName]
        );
        $customer->update(['name' => $this->newCustomerName]);

        $services = Service::whereIn('id', $this->newSelectedServices)->get();
        $totalPrice = $services->sum('price');

        $appointment = Appointment::create([
            'customer_id' => $customer->id,
            'staff_id' => $this->newStaffId,
            'appointment_date' => Carbon::parse($this->newDate . ' ' . $this->newTime),
            'note' => $this->newNote,
            'total_price' => $totalPrice,
            'status' => $this->newStatus,
        ]);

        foreach ($services as $service) {
            $appointment->services()->attach($service->id, ['price' => $service->price]);
        }

        $this->showCreateForm = false;
        $this->reset(['newCustomerName', 'newCustomerPhone', 'newSelectedServices', 'newStaffId', 'newDate', 'newTime', 'newNote', 'newStatus']);
    }

    public function approve(int $id)
    {
        Appointment::findOrFail($id)->update(['status' => 'onaylandi']);
    }

    public function cancel(int $id)
    {
        Appointment::findOrFail($id)->update(['status' => 'iptal']);
    }

    public function filter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Appointment::with(['customer', 'services', 'staff']);

        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->dateFrom) {
            $query->whereDate('appointment_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('appointment_date', '<=', $this->dateTo);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(20);

        // Form için veriler
        $allServices = Service::where('is_active', true)->orderBy('sort_order')->get();
        $allStaff = Staff::where('is_active', true)->orderBy('sort_order')->get();

        $timeSlots = [];
        $start = Setting::get('working_hours_start', '09:00');
        $end = Setting::get('working_hours_end', '21:00');
        $interval = (int) Setting::get('slot_interval', 30);
        $current = Carbon::parse('2026-01-01 ' . $start);
        $endTime = Carbon::parse('2026-01-01 ' . $end);
        while ($current < $endTime) {
            $timeSlots[] = $current->format('H:i');
            $current->addMinutes($interval);
        }

        return view('livewire.admin.randevular', [
            'appointments' => $appointments,
            'allServices' => $allServices,
            'allStaff' => $allStaff,
            'timeSlots' => $timeSlots,
        ])->layout('components.layouts.admin');
    }
}
