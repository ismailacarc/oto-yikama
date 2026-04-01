<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\CarBrand;
use App\Models\CarModel;
use App\Models\WorkOrder;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class Uyeler extends Component
{
    use WithPagination;

    public string $search = '';
    public bool   $filterDebtor = false;
    public ?int $activeCustomerId = null; // detay paneli açık olan müşteri

    // Müşteri form
    public bool $showCustomerForm = false;
    public ?int $editingCustomerId = null;
    public string $customerName  = '';
    public string $customerPhone = '';
    public string $customerEmail = '';
    public string $customerNotes = '';

    // Cari ödeme formu
    public ?int   $cariPayOrderId  = null;
    public string $cariPayAmount   = '';
    public string $cariPayType     = 'nakit';
    public string $cariPayDiscount = '';

    // Araç form
    public bool $showVehicleForm = false;
    public ?int $editingVehicleId = null;
    public ?int $vehicleCustomerId = null;
    public ?int $selectedBrandId  = null;
    public ?int $selectedModelId  = null;
    public string $vehiclePlate   = '';
    public string $vehicleColor   = '';
    public string $vehicleYear    = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterDebtor() { $this->resetPage(); }

    // ── Müşteri ──────────────────────────────────────────────
    public function openCustomerForm(?int $id = null)
    {
        $this->reset(['editingCustomerId','customerName','customerPhone','customerEmail','customerNotes']);
        if ($id) {
            $c = Customer::findOrFail($id);
            $this->editingCustomerId = $c->id;
            $this->customerName  = $c->name;
            $this->customerPhone = $c->phone;
            $this->customerEmail = $c->email ?? '';
            $this->customerNotes = $c->notes ?? '';
        }
        $this->showCustomerForm = true;
        $this->showVehicleForm  = false;
    }

    public function saveCustomer()
    {
        $this->validate([
            'customerName'  => 'required|string|max:255',
            'customerPhone' => 'required|string|max:20',
            'customerEmail' => 'nullable|email|max:255',
        ], [
            'customerName.required'  => 'Ad soyad zorunludur.',
            'customerPhone.required' => 'Telefon zorunludur.',
            'customerEmail.email'    => 'Geçerli bir e-posta girin.',
        ]);

        $data = [
            'name'  => $this->customerName,
            'phone' => $this->customerPhone,
            'email' => $this->customerEmail ?: null,
            'notes' => $this->customerNotes ?: null,
        ];

        if ($this->editingCustomerId) {
            Customer::findOrFail($this->editingCustomerId)->update($data);
            $this->activeCustomerId = $this->editingCustomerId;
        } else {
            $c = Customer::create($data);
            $this->activeCustomerId = $c->id;
        }

        $this->showCustomerForm = false;
        $this->reset(['editingCustomerId','customerName','customerPhone','customerEmail','customerNotes']);
    }

    public function deleteCustomer(int $id)
    {
        Customer::findOrFail($id)->delete();
        if ($this->activeCustomerId === $id) $this->activeCustomerId = null;
    }

    public function toggleCustomer(int $id)
    {
        $this->activeCustomerId = $this->activeCustomerId === $id ? null : $id;
        $this->showVehicleForm  = false;
        $this->showCustomerForm = false;
        $this->cariPayOrderId   = null;
        $this->cariPayAmount    = '';
    }

    public function openCariPay(int $orderId)
    {
        $this->cariPayOrderId = $this->cariPayOrderId === $orderId ? null : $orderId;
        $this->cariPayAmount  = '';
        $this->cariPayType    = 'nakit';
    }

    public function saveCariPayment()
    {
        $this->validate([
            'cariPayAmount' => 'required|numeric|min:0.01',
        ], ['cariPayAmount.required' => 'Tutar giriniz.']);

        $order = WorkOrder::findOrFail($this->cariPayOrderId);
        $amount = (float) $this->cariPayAmount;

        $discount = (float) ($this->cariPayDiscount ?: 0);

        DB::transaction(function () use ($order, $amount, $discount) {
            Payment::create([
                'work_order_id' => $order->id,
                'customer_id'   => $order->customer_id,
                'amount'        => $amount,
                'payment_type'  => $this->cariPayType,
                'paid_at'       => now(),
            ]);
            $newDiscount    = $order->discount_amount + $discount;
            $effectiveTotal = $order->total_amount - $newDiscount;
            $newPaid        = $order->paid_amount + $amount;
            $order->update([
                'paid_amount'     => min($newPaid, $effectiveTotal),
                'discount_amount' => $newDiscount,
            ]);
        });

        $this->cariPayOrderId  = null;
        $this->cariPayAmount   = '';
        $this->cariPayType     = 'nakit';
        $this->cariPayDiscount = '';
    }

    // ── Araç ──────────────────────────────────────────────
    public function openVehicleForm(int $customerId, ?int $vehicleId = null)
    {
        $this->reset(['editingVehicleId','selectedBrandId','selectedModelId','vehiclePlate','vehicleColor','vehicleYear']);
        $this->vehicleCustomerId = $customerId;
        $this->showCustomerForm  = false;

        if ($vehicleId) {
            $v = Vehicle::findOrFail($vehicleId);
            $this->editingVehicleId = $v->id;
            $this->selectedBrandId  = $v->car_brand_id;
            $this->selectedModelId  = $v->car_model_id;
            $this->vehiclePlate     = $v->plate ?? '';
            $this->vehicleColor     = $v->color ?? '';
            $this->vehicleYear      = $v->year ?? '';
        }

        $this->showVehicleForm = true;
    }

    public function updatedSelectedBrandId()
    {
        $this->selectedModelId = null; // marka değişince model sıfırla
    }

    public function saveVehicle()
    {
        $this->validate([
            'vehicleCustomerId' => 'required|exists:customers,id',
            'selectedBrandId'   => 'required|exists:car_brands,id',
            'selectedModelId'   => 'required|exists:car_models,id',
            'vehiclePlate'      => 'nullable|string|max:20',
            'vehicleColor'      => 'nullable|string|max:50',
            'vehicleYear'       => 'nullable|integer|min:1980|max:' . (date('Y') + 1),
        ], [
            'selectedBrandId.required' => 'Marka seçiniz.',
            'selectedModelId.required' => 'Model seçiniz.',
        ]);

        $brand = CarBrand::find($this->selectedBrandId);
        $model = CarModel::find($this->selectedModelId);

        $data = [
            'customer_id'  => $this->vehicleCustomerId,
            'car_brand_id' => $this->selectedBrandId,
            'car_model_id' => $this->selectedModelId,
            'brand_name'   => $brand?->name,
            'model_name'   => $model?->name,
            'plate'        => strtoupper($this->vehiclePlate) ?: null,
            'color'        => $this->vehicleColor ?: null,
            'year'         => $this->vehicleYear ?: null,
        ];

        if ($this->editingVehicleId) {
            Vehicle::findOrFail($this->editingVehicleId)->update($data);
        } else {
            Vehicle::create($data);
        }

        $this->showVehicleForm = false;
        $this->reset(['editingVehicleId','selectedBrandId','selectedModelId','vehiclePlate','vehicleColor','vehicleYear']);
    }

    public function deleteVehicle(int $id)
    {
        Vehicle::findOrFail($id)->delete();
    }

    public function render()
    {
        $query = Customer::withCount('vehicles')
            ->withSum('workOrders as total_amount_sum', 'total_amount')
            ->withSum('workOrders as discount_amount_sum', 'discount_amount')
            ->withSum('workOrders as paid_amount_sum', 'paid_amount');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterDebtor) {
            $query->whereHas('workOrders', fn($q) =>
                $q->whereRaw('(total_amount - discount_amount) > paid_amount')
            );
        }

        $customers = $query->orderBy('name')->paginate(20);

        // Genel istatistikler
        $totalDebt = WorkOrder::whereRaw('(total_amount - discount_amount) > paid_amount')
            ->selectRaw('SUM(total_amount - discount_amount - paid_amount) as debt')
            ->value('debt') ?? 0;
        $debtorCount = Customer::whereHas('workOrders', fn($q) =>
            $q->whereRaw('(total_amount - discount_amount) > paid_amount')
        )->count();

        $activeCustomer = null;
        $vehicles = collect();
        $customerDebt = 0;
        $openOrders = collect();
        if ($this->activeCustomerId) {
            $activeCustomer = Customer::find($this->activeCustomerId);
            $vehicles = Vehicle::with(['brand', 'model'])
                ->where('customer_id', $this->activeCustomerId)
                ->orderBy('created_at', 'desc')
                ->get();
            $customerDebt = WorkOrder::where('customer_id', $this->activeCustomerId)
                ->whereRaw('(total_amount - discount_amount) > paid_amount')
                ->selectRaw('SUM(total_amount - discount_amount - paid_amount) as debt')
                ->value('debt') ?? 0;
            $openOrders = WorkOrder::with('vehicle.brand')
                ->where('customer_id', $this->activeCustomerId)
                ->whereRaw('(total_amount - discount_amount) > paid_amount')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $brands = CarBrand::orderBy('name')->get();
        $models = $this->selectedBrandId
            ? CarModel::where('car_brand_id', $this->selectedBrandId)->orderBy('name')->get()
            : collect();

        return view('livewire.admin.uyeler', compact(
            'customers','activeCustomer','vehicles','brands','models',
            'totalDebt','debtorCount','customerDebt','openOrders'
        ))->layout('components.layouts.admin');
    }
}
