<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Service;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Payment;
use App\Models\StaffTimer;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class IsEmirleri extends Component
{
    use WithPagination;

    public string $activeTab   = 'bekleyen';
    public string $search      = '';

    // ── Form ──────────────────────────────────────────────
    public bool  $showForm        = false;
    public ?int  $editingId       = null;

    // Adım 1: Müşteri & Araç
    public ?int  $customerId      = null;
    public ?int  $vehicleId       = null;
    public ?int  $staffId         = null;
    public string $notes          = '';
    public string $scheduledDate  = '';
    public string $scheduledTime  = '';

    // Adım 2: Kalemler
    public array $items = [];
    // item yapısı: [type, item_id, name, quantity, unit, unit_price, total_price]

    // Geçici seçim
    public string $itemType      = 'service'; // service | product
    public ?int   $itemServiceId = null;
    public ?int   $itemProductId = null;
    public string $itemQty       = '1';
    public string $itemPrice     = '';

    // Ödeme (yeni iş emri)
    public bool   $takePayment   = false;
    public string $paymentAmount = '';
    public string $paymentType   = 'nakit';
    public string $paymentDiscount = '';

    // Detay paneli
    public ?int  $viewingId = null;

    // Detay paneli - kalem ekle
    public bool   $showDetailItemForm = false;
    public string $detailItemType     = 'service';
    public ?int   $detailServiceId    = null;
    public ?int   $detailProductId    = null;
    public string $detailItemQty      = '1';
    public string $detailItemPrice    = '';

    // Detay paneli - ödeme ekle
    public bool   $showPaymentForm    = false;
    public string $detailPayAmount    = '';
    public string $detailPayType      = 'nakit';
    public string $detailPayDiscount  = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingActiveTab() { $this->resetPage(); $this->viewingId = null; $this->showDetailItemForm = false; $this->showPaymentForm = false; }

    // ── Müşteri seçince araçları getir ────────────────────
    public function updatedCustomerId()
    {
        $this->vehicleId = null;
    }

    // ── Ürün seçince fiyat otomatik gelsin ────────────────
    public function updatedItemProductId()
    {
        if ($this->itemProductId) {
            $p = Product::find($this->itemProductId);
            $this->itemPrice = $p ? (string) $p->unit_price : '';
        }
    }

    public function updatedItemServiceId()
    {
        if ($this->itemServiceId) {
            $s = Service::find($this->itemServiceId);
            $this->itemPrice = $s ? (string) $s->price : '';
        }
    }

    public function updatedDetailProductId()
    {
        if ($this->detailProductId) {
            $p = Product::find($this->detailProductId);
            $this->detailItemPrice = $p ? (string) $p->unit_price : '';
        }
    }

    public function updatedDetailServiceId()
    {
        if ($this->detailServiceId) {
            $s = Service::find($this->detailServiceId);
            $this->detailItemPrice = $s ? (string) $s->price : '';
        }
    }

    // ── Kaleme ekle ───────────────────────────────────────
    public function addItem()
    {
        $this->validate([
            'itemQty'   => 'required|numeric|min:0.001',
            'itemPrice' => 'required|numeric|min:0',
        ], [
            'itemQty.required'   => 'Miktar giriniz.',
            'itemPrice.required' => 'Fiyat giriniz.',
        ]);

        if ($this->itemType === 'service') {
            if (!$this->itemServiceId) { $this->addError('itemServiceId', 'Hizmet seçiniz.'); return; }
            $s = Service::findOrFail($this->itemServiceId);
            $this->items[] = [
                'type'       => 'service',
                'item_id'    => $s->id,
                'name'       => $s->name,
                'quantity'   => (float) $this->itemQty,
                'unit'       => 'adet',
                'unit_price' => (float) $this->itemPrice,
                'total_price'=> round((float) $this->itemQty * (float) $this->itemPrice, 2),
            ];
        } else {
            if (!$this->itemProductId) { $this->addError('itemProductId', 'Ürün seçiniz.'); return; }
            $p = Product::findOrFail($this->itemProductId);
            $this->items[] = [
                'type'       => 'product',
                'item_id'    => $p->id,
                'name'       => $p->name,
                'quantity'   => (float) $this->itemQty,
                'unit'       => $p->unit,
                'unit_price' => (float) $this->itemPrice,
                'total_price'=> round((float) $this->itemQty * (float) $this->itemPrice, 2),
            ];
        }

        $this->reset(['itemServiceId','itemProductId','itemQty','itemPrice']);
        $this->itemType  = 'service';
    }

    public function removeItem(int $index)
    {
        array_splice($this->items, $index, 1);
        $this->items = array_values($this->items);
    }

    public function getTotalProperty(): float
    {
        return array_sum(array_column($this->items, 'total_price'));
    }

    // ── Form aç ───────────────────────────────────────────
    public function openForm()
    {
        $this->reset(['editingId','customerId','vehicleId','staffId','notes','items',
                      'itemType','itemServiceId','itemProductId','itemQty','itemPrice',
                      'takePayment','paymentAmount','paymentType']);
        $this->itemType   = 'service';
        $this->itemQty    = '1';
        $this->paymentType = 'nakit';
        $this->showForm   = true;
        $this->viewingId  = null;
        $this->showDetailItemForm = false;
        $this->showPaymentForm    = false;
    }

    // ── Kaydet ────────────────────────────────────────────
    public function save()
    {
        $this->validate([
            'customerId' => 'required|exists:customers,id',
        ], ['customerId.required' => 'Müşteri seçiniz.']);

        if (empty($this->items)) {
            $this->addError('items', 'En az bir hizmet veya ürün ekleyin.');
            return;
        }

        $payAmt      = 0;
        $discountAmt = 0;
        if ($this->takePayment && $this->paymentAmount) {
            $this->validate([
                'paymentAmount' => 'required|numeric|min:0.01',
            ], ['paymentAmount.required' => 'Ödeme tutarı giriniz.']);
            $payAmt      = (float) $this->paymentAmount;
            $discountAmt = (float) ($this->paymentDiscount ?: 0);
        }

        DB::transaction(function () use ($payAmt, $discountAmt) {
            $total = $this->total;

            $scheduledAt = null;
            if ($this->scheduledDate) {
                $scheduledAt = \Carbon\Carbon::parse(
                    $this->scheduledDate . ($this->scheduledTime ? ' ' . $this->scheduledTime : ' 08:00')
                );
            }

            $order = WorkOrder::create([
                'order_no'        => WorkOrder::generateOrderNo(),
                'customer_id'     => $this->customerId,
                'vehicle_id'      => $this->vehicleId ?: null,
                'staff_id'        => $this->staffId ?: null,
                'status'          => 'bekleyen',
                'total_amount'    => $total,
                'paid_amount'     => $payAmt,
                'discount_amount' => $discountAmt,
                'notes'           => $this->notes ?: null,
                'scheduled_at'    => $scheduledAt,
            ]);

            foreach ($this->items as $item) {
                WorkOrderItem::create([
                    'work_order_id' => $order->id,
                    'type'          => $item['type'],
                    'item_id'       => $item['item_id'],
                    'name'          => $item['name'],
                    'quantity'      => $item['quantity'],
                    'unit'          => $item['unit'],
                    'unit_price'    => $item['unit_price'],
                    'total_price'   => $item['total_price'],
                ]);

                // Stok düş + hareket kaydı
                if ($item['type'] === 'product') {
                    $p = Product::find($item['item_id']);
                    if ($p) {
                        $before = (float) $p->stock_quantity;
                        $p->decrement('stock_quantity', $item['quantity']);
                        StockMovement::create([
                            'product_id'    => $p->id,
                            'work_order_id' => $order->id,
                            'type'          => 'out',
                            'quantity'      => $item['quantity'],
                            'before'        => $before,
                            'after'         => $before - $item['quantity'],
                            'notes'         => 'İş emri: '.$order->order_no,
                        ]);
                    }
                }
            }

            if ($payAmt > 0) {
                Payment::create([
                    'work_order_id' => $order->id,
                    'customer_id'   => $this->customerId,
                    'amount'        => $payAmt,
                    'payment_type'  => $this->paymentType,
                    'paid_at'       => now(),
                ]);
            }
        });

        $this->showForm = false;
        $this->activeTab = 'bekleyen';
        $this->reset(['customerId','vehicleId','staffId','notes','scheduledDate','scheduledTime','items','takePayment','paymentAmount','paymentType']);
    }

    // ── Durum geçiş ───────────────────────────────────────
    public function moveToDevamEden(int $id)
    {
        WorkOrder::findOrFail($id)->update([
            'status'     => 'devam_eden',
            'started_at' => now(),
        ]);
    }

    public function moveToTamamlandi(int $id)
    {
        WorkOrder::findOrFail($id)->update([
            'status'       => 'tamamlandi',
            'completed_at' => now(),
        ]);
    }

    public function moveToBekleyen(int $id)
    {
        WorkOrder::findOrFail($id)->update([
            'status'     => 'bekleyen',
            'started_at' => null,
        ]);
    }

    // ── Detay ─────────────────────────────────────────────
    public function toggleDetail(int $id)
    {
        $this->viewingId = $this->viewingId === $id ? null : $id;
        $this->showDetailItemForm = false;
        $this->showPaymentForm    = false;
    }

    // ── Detay paneli - kalem ekle ─────────────────────────
    public function addItemToOrder(int $orderId)
    {
        $this->validate([
            'detailItemQty'   => 'required|numeric|min:0.001',
            'detailItemPrice' => 'required|numeric|min:0',
        ], [
            'detailItemQty.required'   => 'Miktar giriniz.',
            'detailItemPrice.required' => 'Fiyat giriniz.',
        ]);

        $order = WorkOrder::findOrFail($orderId);
        $totalPrice = round((float) $this->detailItemQty * (float) $this->detailItemPrice, 2);

        if ($this->detailItemType === 'service') {
            if (!$this->detailServiceId) { $this->addError('detailServiceId', 'Hizmet seçiniz.'); return; }
            $s = Service::findOrFail($this->detailServiceId);
            WorkOrderItem::create([
                'work_order_id' => $orderId,
                'type'          => 'service',
                'item_id'       => $s->id,
                'name'          => $s->name,
                'quantity'      => (float) $this->detailItemQty,
                'unit'          => 'adet',
                'unit_price'    => (float) $this->detailItemPrice,
                'total_price'   => $totalPrice,
            ]);
        } else {
            if (!$this->detailProductId) { $this->addError('detailProductId', 'Ürün seçiniz.'); return; }
            $p = Product::findOrFail($this->detailProductId);
            WorkOrderItem::create([
                'work_order_id' => $orderId,
                'type'          => 'product',
                'item_id'       => $p->id,
                'name'          => $p->name,
                'quantity'      => (float) $this->detailItemQty,
                'unit'          => $p->unit,
                'unit_price'    => (float) $this->detailItemPrice,
                'total_price'   => $totalPrice,
            ]);
            $before = (float) $p->stock_quantity;
            $qty = (float) $this->detailItemQty;
            $p->decrement('stock_quantity', $qty);
            StockMovement::create([
                'product_id'    => $p->id,
                'work_order_id' => $orderId,
                'type'          => 'out',
                'quantity'      => $qty,
                'before'        => $before,
                'after'         => $before - $qty,
                'notes'         => 'İş emri kalemine eklendi',
            ]);
        }

        $order->update(['total_amount' => $order->items()->sum('total_price')]);
        $this->reset(['detailServiceId','detailProductId','detailItemQty','detailItemPrice']);
        $this->detailItemType = 'service';
        $this->detailItemQty  = '1';
        $this->showDetailItemForm = false;
    }

    public function removeItemFromOrder(int $itemId)
    {
        $item = WorkOrderItem::findOrFail($itemId);
        $order = WorkOrder::findOrFail($item->work_order_id);

        if ($item->type === 'product') {
            $p = Product::find($item->item_id);
            if ($p) {
                $before = (float) $p->stock_quantity;
                $p->increment('stock_quantity', $item->quantity);
                StockMovement::create([
                    'product_id'    => $p->id,
                    'work_order_id' => $item->work_order_id,
                    'type'          => 'in',
                    'quantity'      => $item->quantity,
                    'before'        => $before,
                    'after'         => $before + $item->quantity,
                    'notes'         => 'İş emri kalemi silindi (iade)',
                ]);
            }
        }

        $item->delete();
        $order->update(['total_amount' => $order->items()->sum('total_price')]);
    }

    // ── Detay paneli - ödeme ekle ─────────────────────────
    public function addPaymentToOrder(int $orderId)
    {
        $this->validate([
            'detailPayAmount' => 'required|numeric|min:0.01',
        ], ['detailPayAmount.required' => 'Tutar giriniz.']);

        $order = WorkOrder::findOrFail($orderId);

        Payment::create([
            'work_order_id' => $orderId,
            'customer_id'   => $order->customer_id,
            'amount'        => (float) $this->detailPayAmount,
            'payment_type'  => $this->detailPayType,
            'paid_at'       => now(),
        ]);

        $discount = (float) ($this->detailPayDiscount ?: 0);
        $newPaid  = $order->paid_amount + (float) $this->detailPayAmount;
        $newDiscount = $order->discount_amount + $discount;
        $effectiveTotal = $order->total_amount - $newDiscount;
        $order->update([
            'paid_amount'     => min($newPaid, $effectiveTotal),
            'discount_amount' => $newDiscount,
        ]);

        $this->showPaymentForm   = false;
        $this->detailPayAmount   = '';
        $this->detailPayType     = 'nakit';
        $this->detailPayDiscount = '';
    }

    // ── Personel Sayacı ───────────────────────────────────
    public function startTimer(int $orderId)
    {
        $order = WorkOrder::findOrFail($orderId);
        if (!$order->staff_id) return;

        // Açık timer varsa önce kapat
        StaffTimer::where('work_order_id', $orderId)->whereNull('ended_at')
            ->each(fn($t) => $this->stopTimer($t->id));

        StaffTimer::create([
            'work_order_id' => $orderId,
            'staff_id'      => $order->staff_id,
            'started_at'    => now(),
        ]);
    }

    public function stopTimer(int $timerId)
    {
        $timer = StaffTimer::findOrFail($timerId);
        $ended = now();
        $timer->update([
            'ended_at'         => $ended,
            'duration_minutes' => (int) $timer->started_at->diffInMinutes($ended),
        ]);
    }

    // ── Sil ───────────────────────────────────────────────
    public function delete(int $id)
    {
        $order = WorkOrder::with('items')->findOrFail($id);

        // Stoku geri yükle
        foreach ($order->items as $item) {
            if ($item->type === 'product') {
                Product::where('id', $item->item_id)->increment('stock_quantity', $item->quantity);
            }
        }

        $order->delete();
        if ($this->viewingId === $id) $this->viewingId = null;
    }

    // ── Render ────────────────────────────────────────────
    public function render()
    {
        $query = WorkOrder::with(['customer','vehicle.brand','vehicle.model','staff','items'])
            ->where('status', $this->activeTab);

        if ($this->search) {
            $query->whereHas('customer', fn($q) =>
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('phone', 'like', '%'.$this->search.'%')
            )->orWhere('order_no', 'like', '%'.$this->search.'%');
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        $counts = [
            'bekleyen'   => WorkOrder::where('status','bekleyen')->count(),
            'devam_eden' => WorkOrder::where('status','devam_eden')->count(),
            'tamamlandi' => WorkOrder::where('status','tamamlandi')->count(),
        ];

        $customers = Customer::orderBy('name')->get();
        $vehicles  = $this->customerId
            ? Vehicle::with(['brand','model'])->where('customer_id', $this->customerId)->get()
            : collect();
        $services  = Service::where('is_active', true)->orderBy('name')->get();
        $products  = Product::where('is_active', true)->orderBy('name')->get();
        $staffList = Staff::where('is_active', true)->orderBy('name')->get();

        $viewingOrder = $this->viewingId
            ? WorkOrder::with(['customer','vehicle.brand','vehicle.model','staff','items','payments','timers.staff'])->find($this->viewingId)
            : null;

        return view('livewire.admin.is-emirleri', compact(
            'orders','counts','customers','vehicles','services','products','staffList','viewingOrder'
        ))->layout('components.layouts.admin');
    }
}
