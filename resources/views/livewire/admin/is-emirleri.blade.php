<div>

{{-- ── BAŞLIK ───────────────────────────────────────────── --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">İş Emirleri</h1>
        <p class="text-sm text-gray-500 mt-0.5">Tüm iş takibi burada</p>
    </div>
    <button wire:click="openForm" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yeni İş Emri
    </button>
</div>

{{-- ── YENİ İŞ EMRİ MODAL ──────────────────────────────── --}}
@if($showForm)
<div class="fixed inset-0 z-50 flex items-start justify-center p-4 pt-6 overflow-y-auto" style="background:rgba(0,0,0,0.6)">
<div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl my-4" wire:click.stop>

    {{-- Modal başlık --}}
    <div class="flex items-center justify-between p-5 border-b border-gray-100">
        <h2 class="text-lg font-bold text-gray-900">Yeni İş Emri</h2>
        <button wire:click="$set('showForm',false)" class="text-gray-400 hover:text-gray-600 p-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <div class="p-5 space-y-5">

        {{-- 1. Müşteri & Araç --}}
        <div class="bg-slate-50 rounded-xl p-4 space-y-3">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Müşteri & Araç</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Müşteri *</label>
                    <select wire:model.live="customerId" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none bg-white">
                        <option value="">-- Müşteri Seçin --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                        @endforeach
                    </select>
                    @error('customerId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Araç</label>
                    <select wire:model="vehicleId" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none bg-white" {{ !$customerId ? 'disabled' : '' }}>
                        <option value="">{{ $customerId ? '-- Araç Seçin --' : 'Önce müşteri seçin' }}</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}">{{ $v->brand?->name }} {{ $v->model?->name }} @if($v->plate)({{ $v->plate }})@endif</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Personel</label>
                    <select wire:model="staffId" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none bg-white">
                        <option value="">-- Personel Seçin --</option>
                        @foreach($staffList as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Not</label>
                    <input type="text" wire:model="notes" placeholder="İş emri notu..."
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
                </div>
            </div>

            {{-- Opsiyonel Tarih & Saat --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Planlanan Tarih
                        <span class="text-xs font-normal text-gray-400 ml-1">(opsiyonel)</span>
                    </label>
                    <input type="date" wire:model="scheduledDate"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Planlanan Saat
                        <span class="text-xs font-normal text-gray-400 ml-1">(opsiyonel)</span>
                    </label>
                    <input type="time" wire:model="scheduledTime"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
                </div>
            </div>
        </div>

        {{-- 2. Hizmet / Ürün Ekle --}}
        <div class="bg-slate-50 rounded-xl p-4 space-y-3">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Hizmet & Ürün Ekle</p>

            {{-- Tip seçici --}}
            <div class="flex rounded-xl border border-gray-200 overflow-hidden bg-white">
                <button type="button" wire:click="$set('itemType','service')"
                    class="flex-1 py-2 text-sm font-semibold transition {{ $itemType === 'service' ? 'bg-cyan-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                    Hizmet
                </button>
                <button type="button" wire:click="$set('itemType','product')"
                    class="flex-1 py-2 text-sm font-semibold transition {{ $itemType === 'product' ? 'bg-cyan-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                    Ürün
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                {{-- Seçim --}}
                <div class="sm:col-span-1">
                    @if($itemType === 'service')
                    <select wire:model.live="itemServiceId" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none bg-white">
                        <option value="">-- Hizmet --</option>
                        @foreach($services as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('itemServiceId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @else
                    <select wire:model.live="itemProductId" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none bg-white">
                        <option value="">-- Ürün --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->stock_quantity+0 }} {{ $p->unit }})</option>
                        @endforeach
                    </select>
                    @error('itemProductId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @endif
                </div>

                {{-- Miktar --}}
                <div>
                    <input type="number" wire:model="itemQty" placeholder="Miktar" min="0.001" step="0.001"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
                    @error('itemQty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Fiyat + Ekle --}}
                <div class="flex gap-2">
                    <div class="flex-1">
                        <input type="number" wire:model="itemPrice" placeholder="Fiyat ₺" min="0" step="0.01"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
                        @error('itemPrice') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="button" wire:click="addItem"
                        class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 rounded-xl font-bold text-lg transition flex-shrink-0">+</button>
                </div>
            </div>
        </div>

        {{-- 3. Eklenen Kalemler --}}
        @if(!empty($items))
        <div class="border border-gray-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500">Kalem</th>
                        <th class="px-3 py-2.5 text-center text-xs font-semibold text-gray-500">Miktar</th>
                        <th class="px-3 py-2.5 text-right text-xs font-semibold text-gray-500">Birim Fiyat</th>
                        <th class="px-3 py-2.5 text-right text-xs font-semibold text-gray-500">Toplam</th>
                        <th class="px-3 py-2.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($items as $i => $item)
                    <tr>
                        <td class="px-4 py-2.5">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="text-xs px-1.5 py-0.5 rounded font-medium {{ $item['type'] === 'service' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $item['type'] === 'service' ? 'Hizmet' : 'Ürün' }}
                                </span>
                                {{ $item['name'] }}
                            </span>
                        </td>
                        <td class="px-3 py-2.5 text-center text-gray-600">{{ $item['quantity']+0 }} {{ $item['unit'] }}</td>
                        <td class="px-3 py-2.5 text-right text-gray-600">{{ number_format($item['unit_price'],2,',','.') }} ₺</td>
                        <td class="px-3 py-2.5 text-right font-semibold text-gray-900">{{ number_format($item['total_price'],2,',','.') }} ₺</td>
                        <td class="px-3 py-2.5 text-center">
                            <button wire:click="removeItem({{ $i }})" class="text-red-400 hover:text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-700">Toplam Tutar</td>
                        <td class="px-3 py-3 text-right font-black text-lg text-cyan-700">{{ number_format($this->total,2,',','.') }} ₺</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        @error('items') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

        {{-- 4. Ödeme Al --}}
        <div class="bg-slate-50 rounded-xl p-4 space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ödeme Al</p>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.live="takePayment" class="sr-only peer">
                    <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-cyan-600 transition after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                </label>
            </div>
            @if($takePayment)
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-1">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Ödeme Yöntemi</label>
                    <div class="flex rounded-xl border border-gray-200 overflow-hidden bg-white">
                        @foreach(['nakit' => 'Nakit', 'kart' => 'Kart', 'havale' => 'Havale'] as $val => $lbl)
                        <button type="button" wire:click="$set('paymentType','{{ $val }}')"
                            class="flex-1 py-2 text-xs font-semibold transition {{ $paymentType === $val ? 'bg-cyan-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                            {{ $lbl }}
                        </button>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Alınan Tutar
                        @if(!empty($items))
                        <span class="text-gray-400 font-normal">({{ number_format($this->total,2,',','.') }} ₺)</span>
                        @endif
                    </label>
                    <input type="number" wire:model.live="paymentAmount" placeholder="0,00" min="0" step="0.01"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
                    @error('paymentAmount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        <span class="text-orange-500 font-semibold">İndirim</span> (opsiyonel)
                    </label>
                    <input type="number" wire:model.live="paymentDiscount" placeholder="0,00" min="0" step="0.01"
                        class="w-full border border-orange-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 outline-none">
                </div>
            </div>
            @if(!empty($items) && ($paymentAmount > 0 || $paymentDiscount > 0))
            @php
                $discounted = $this->total - (float)$paymentDiscount;
                $remaining  = $discounted - (float)$paymentAmount;
            @endphp
            <div class="bg-white rounded-xl border border-gray-200 px-4 py-2.5 space-y-1.5 text-sm">
                @if($paymentDiscount > 0)
                <div class="flex justify-between text-orange-600">
                    <span>İndirim</span>
                    <span class="font-semibold">-{{ number_format((float)$paymentDiscount,2,',','.') }} ₺</span>
                </div>
                <div class="flex justify-between text-gray-700">
                    <span>İndirimli tutar</span>
                    <span class="font-semibold">{{ number_format($discounted,2,',','.') }} ₺</span>
                </div>
                @endif
                <div class="flex justify-between {{ $remaining <= 0 ? 'text-green-600' : 'text-gray-500' }}">
                    <span>Kalan bakiye</span>
                    <span class="font-bold">{{ $remaining <= 0 ? 'Tamamı ödendi' : number_format($remaining,2,',','.').' ₺' }}</span>
                </div>
            </div>
            @endif
            @endif
        </div>

    </div>

    {{-- Modal footer --}}
    <div class="flex gap-3 p-5 border-t border-gray-100">
        <button wire:click="save" class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white py-3 rounded-xl font-bold text-sm transition">
            İş Emri Oluştur
        </button>
        <button wire:click="$set('showForm',false)" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-semibold text-sm transition">
            İptal
        </button>
    </div>
</div>
</div>
@endif

{{-- ── SEKMELER ─────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-5 overflow-hidden">
    <div class="flex">
        @foreach(['bekleyen' => ['Bekleyen','yellow'], 'devam_eden' => ['Devam Eden','blue'], 'tamamlandi' => ['Tamamlandı','green']] as $tab => [$label, $color])
        <button wire:click="$set('activeTab','{{ $tab }}')"
            class="flex-1 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 py-3.5 px-2 text-sm font-semibold transition border-b-2
            {{ $activeTab === $tab
                ? ($color === 'yellow' ? 'border-yellow-500 text-yellow-700 bg-yellow-50' : ($color === 'blue' ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-green-500 text-green-700 bg-green-50'))
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
            {{ $label }}
            <span class="text-xs font-bold px-2 py-0.5 rounded-full
                {{ $activeTab === $tab
                    ? ($color === 'yellow' ? 'bg-yellow-200 text-yellow-800' : ($color === 'blue' ? 'bg-blue-200 text-blue-800' : 'bg-green-200 text-green-800'))
                    : 'bg-gray-100 text-gray-600' }}">
                {{ $counts[$tab] }}
            </span>
        </button>
        @endforeach
    </div>
</div>

{{-- ── ARAMA ────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3 mb-5">
    <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Müşteri, telefon veya iş emri no..."
            class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition">
    </div>
</div>

{{-- ── İŞ EMRİ LİSTESİ ─────────────────────────────────── --}}
<div class="space-y-3">
@forelse($orders as $order)
<div class="bg-white rounded-2xl border shadow-sm overflow-hidden
    {{ $activeTab === 'bekleyen' ? 'border-yellow-100' : ($activeTab === 'devam_eden' ? 'border-blue-100' : 'border-green-100') }}">

    {{-- Satır --}}
    <div class="flex items-start gap-3 p-4 cursor-pointer hover:bg-gray-50 transition" wire:click="toggleDetail({{ $order->id }})">

        {{-- Durum noktası --}}
        <div class="mt-1 w-2.5 h-2.5 rounded-full flex-shrink-0
            {{ $activeTab === 'bekleyen' ? 'bg-yellow-400' : ($activeTab === 'devam_eden' ? 'bg-blue-500' : 'bg-green-500') }}"></div>

        {{-- Bilgi --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <span class="font-bold text-gray-900">{{ $order->customer->name }}</span>
                    <span class="text-xs text-gray-400 ml-2 font-mono">{{ $order->order_no }}</span>
                </div>
                <span class="font-black text-cyan-700 text-base flex-shrink-0">{{ number_format($order->effective_total,0,',','.') }} ₺</span>
            </div>

            {{-- Araç --}}
            @if($order->vehicle)
            <p class="text-sm text-gray-500 mt-0.5 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 1h6l2-1z"/></svg>
                {{ $order->vehicle->brand?->name }} {{ $order->vehicle->model?->name }}
                @if($order->vehicle->plate) · <span class="font-mono text-xs">{{ $order->vehicle->plate }}</span> @endif
            </p>
            @endif

            {{-- Alt bilgi --}}
            <div class="flex flex-wrap items-center gap-2 mt-2">
                {{-- Kalem sayısı --}}
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                    {{ $order->items->count() }} kalem
                </span>
                {{-- Personel --}}
                @if($order->staff)
                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $order->staff->name }}
                </span>
                @endif
                {{-- Ödeme durumu --}}
                @php $remaining = $order->effective_total - $order->paid_amount; @endphp
                @if($remaining <= 0)
                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold">Ödendi</span>
                @elseif($order->paid_amount > 0)
                <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full font-semibold">Kısmi: {{ number_format($remaining,0,',','.') }} ₺ kalan</span>
                @else
                <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-semibold">Ödenmedi</span>
                @endif
                {{-- Tarih --}}
                <span class="text-xs text-gray-400">{{ $order->created_at->format('d.m.Y H:i') }}</span>
            </div>
        </div>

        {{-- Chevron --}}
        <svg class="w-5 h-5 text-gray-300 flex-shrink-0 mt-1 transition-transform {{ $viewingId === $order->id ? 'rotate-180' : '' }}"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>

    {{-- Detay Paneli --}}
    @if($viewingId === $order->id && $viewingOrder)
    <div class="border-t border-gray-100 bg-gray-50 p-4 space-y-4">

        {{-- Kalemler --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Kalemler</p>
                <button wire:click="$toggle('showDetailItemForm')"
                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg transition
                    {{ $showDetailItemForm ? 'bg-gray-200 text-gray-600' : 'bg-cyan-600 text-white hover:bg-cyan-700' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $showDetailItemForm ? 'M6 18L18 6M6 6l12 12' : 'M12 4v16m8-8H4' }}"/></svg>
                    {{ $showDetailItemForm ? 'İptal' : 'Kalem Ekle' }}
                </button>
            </div>

            {{-- Kalem ekleme formu --}}
            @if($showDetailItemForm)
            <div class="bg-cyan-50 border border-cyan-200 rounded-xl p-3 mb-3 space-y-2">
                {{-- Tip --}}
                <div class="flex rounded-xl border border-gray-200 overflow-hidden bg-white">
                    <button type="button" wire:click="$set('detailItemType','service')"
                        class="flex-1 py-2 text-xs font-semibold transition {{ $detailItemType === 'service' ? 'bg-cyan-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">Hizmet</button>
                    <button type="button" wire:click="$set('detailItemType','product')"
                        class="flex-1 py-2 text-xs font-semibold transition {{ $detailItemType === 'product' ? 'bg-cyan-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">Ürün</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <div>
                        @if($detailItemType === 'service')
                        <select wire:model.live="detailServiceId" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-cyan-500 outline-none bg-white">
                            <option value="">-- Hizmet --</option>
                            @foreach($services as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        @error('detailServiceId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @else
                        <select wire:model.live="detailProductId" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-cyan-500 outline-none bg-white">
                            <option value="">-- Ürün --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->stock_quantity+0 }} {{ $p->unit }})</option>
                            @endforeach
                        </select>
                        @error('detailProductId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @endif
                    </div>
                    <div>
                        <input type="number" wire:model="detailItemQty" placeholder="Miktar" min="0.001" step="0.001"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-cyan-500 outline-none">
                        @error('detailItemQty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <input type="number" wire:model="detailItemPrice" placeholder="Fiyat ₺" min="0" step="0.01"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-cyan-500 outline-none">
                            @error('detailItemPrice') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <button type="button" wire:click="addItemToOrder({{ $order->id }})"
                            class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 rounded-xl font-bold text-lg transition flex-shrink-0">+</button>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-gray-100">
                        @foreach($viewingOrder->items as $item)
                        <tr>
                            <td class="px-4 py-2.5">
                                <span class="text-xs px-1.5 py-0.5 rounded font-medium mr-1.5 {{ $item->type === 'service' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $item->type === 'service' ? 'Hizmet' : 'Ürün' }}
                                </span>
                                {{ $item->name }}
                            </td>
                            <td class="px-3 py-2.5 text-center text-gray-500 text-xs">{{ $item->quantity+0 }} {{ $item->unit }}</td>
                            <td class="px-3 py-2.5 text-right text-gray-500 text-xs">{{ number_format($item->unit_price,2,',','.') }} ₺</td>
                            <td class="px-3 py-2.5 text-right font-semibold">{{ number_format($item->total_price,2,',','.') }} ₺</td>
                            <td class="px-2 py-2.5 text-center">
                                <button wire:click="removeItemFromOrder({{ $item->id }})"
                                    wire:confirm="Bu kalemi silmek istediğinize emin misiniz?"
                                    class="text-red-400 hover:text-red-600 p-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="3" class="px-4 py-2.5 text-right font-bold text-gray-700 text-sm">Toplam</td>
                            <td class="px-3 py-2.5 text-right font-black text-cyan-700">{{ number_format($viewingOrder->total_amount,2,',','.') }} ₺</td>
                            <td></td>
                        </tr>
                        @if($viewingOrder->discount_amount > 0)
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right text-sm text-orange-600 font-medium">İndirim</td>
                            <td class="px-3 py-2 text-right text-orange-600 font-semibold">-{{ number_format($viewingOrder->discount_amount,2,',','.') }} ₺</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right text-sm text-gray-700 font-semibold">İndirimli Tutar</td>
                            <td class="px-3 py-2 text-right font-black text-gray-900">{{ number_format($viewingOrder->effective_total,2,',','.') }} ₺</td>
                            <td></td>
                        </tr>
                        @endif
                        @if($viewingOrder->paid_amount > 0)
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right text-sm text-green-600 font-medium">Ödenen</td>
                            <td class="px-3 py-2 text-right text-green-600 font-semibold">{{ number_format($viewingOrder->paid_amount,2,',','.') }} ₺</td>
                            <td></td>
                        </tr>
                        @php $remaining = $viewingOrder->effective_total - $viewingOrder->paid_amount; @endphp
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right text-sm font-medium {{ $remaining <= 0 ? 'text-green-600' : 'text-red-600' }}">Kalan</td>
                            <td class="px-3 py-2 text-right font-bold {{ $remaining <= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $remaining <= 0 ? 'Tamamı ödendi' : number_format($remaining,2,',','.').' ₺' }}
                            </td>
                            <td></td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Ödemeler --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Ödemeler</p>
                @if($viewingOrder->effective_total > $viewingOrder->paid_amount)
                <button wire:click="$toggle('showPaymentForm')"
                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg transition
                    {{ $showPaymentForm ? 'bg-gray-200 text-gray-600' : 'bg-green-600 text-white hover:bg-green-700' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $showPaymentForm ? 'M6 18L18 6M6 6l12 12' : 'M12 4v16m8-8H4' }}"/></svg>
                    {{ $showPaymentForm ? 'İptal' : 'Ödeme Al' }}
                </button>
                @endif
            </div>

            @if($showPaymentForm)
            <div class="bg-green-50 border border-green-200 rounded-xl p-3 mb-3 space-y-2">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Yöntem</label>
                        <div class="flex rounded-xl border border-gray-200 overflow-hidden bg-white">
                            @foreach(['nakit' => 'Nakit', 'kart' => 'Kart', 'havale' => 'Havale'] as $val => $lbl)
                            <button type="button" wire:click="$set('detailPayType','{{ $val }}')"
                                class="flex-1 py-2 text-xs font-semibold transition {{ $detailPayType === $val ? 'bg-green-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                                {{ $lbl }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="sm:col-span-2 space-y-2">
                        @php $kalan = $viewingOrder->total_amount - $viewingOrder->discount_amount - $viewingOrder->paid_amount; @endphp
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                    Tutar <span class="text-gray-400">(Kalan: {{ number_format($kalan,2,',','.') }} ₺)</span>
                                </label>
                                <input type="number" wire:model.live="detailPayAmount" placeholder="0,00" min="0.01" step="0.01"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                @error('detailPayAmount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-orange-500 mb-1">İndirim (opsiyonel)</label>
                                <input type="number" wire:model.live="detailPayDiscount" placeholder="0,00" min="0" step="0.01"
                                    class="w-full border border-orange-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 outline-none">
                            </div>
                            <button type="button" wire:click="addPaymentToOrder({{ $order->id }})"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 rounded-xl text-sm font-semibold transition self-end py-2 flex-shrink-0">
                                Kaydet
                            </button>
                        </div>
                        @if($detailPayDiscount > 0)
                        <div class="text-xs text-orange-600 bg-orange-50 rounded-lg px-3 py-1.5">
                            İndirim sonrası kalan: <span class="font-bold">{{ number_format($kalan - (float)$detailPayDiscount - (float)$detailPayAmount, 2, ',', '.') }} ₺</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Ödeme geçmişi --}}
            @if($viewingOrder->payments && $viewingOrder->payments->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
                @foreach($viewingOrder->payments as $payment)
                <div class="flex items-center justify-between px-4 py-2.5">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                            {{ $payment->payment_type === 'nakit' ? 'bg-green-100 text-green-700' : ($payment->payment_type === 'kart' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                            {{ ucfirst($payment->payment_type) }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $payment->paid_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <span class="font-semibold text-sm text-gray-800">{{ number_format($payment->amount,2,',','.') }} ₺</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-xs text-gray-400 text-center py-3">Henüz ödeme kaydı yok.</p>
            @endif
        </div>

        {{-- Personel Sayacı --}}
        @if($viewingOrder->staff)
        @php $activeTimer = $viewingOrder->timers->whereNull('ended_at')->first(); @endphp
        <div wire:poll.10s>
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Personel Sayacı</p>
                @if($activeTimer)
                    <span class="text-xs text-green-600 font-semibold animate-pulse">● Çalışıyor — {{ $activeTimer->started_at->diffForHumans(null, true) }}</span>
                @endif
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3 flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $viewingOrder->staff->name }}</p>
                    @php $totalMin = $viewingOrder->timers->sum('duration_minutes'); @endphp
                    @if($totalMin > 0)
                    <p class="text-xs text-gray-500">Toplam: {{ intdiv($totalMin,60) }}s {{ $totalMin%60 }}dk</p>
                    @endif
                </div>
                @if($activeTimer)
                <button wire:click="stopTimer({{ $activeTimer->id }})"
                    class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-4 py-2 rounded-xl text-sm font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                    Durdur
                </button>
                @else
                <button wire:click="startTimer({{ $order->id }})"
                    class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Başlat
                </button>
                @endif
            </div>
        </div>
        @endif

        {{-- Not --}}
        @if($viewingOrder->notes)
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-sm text-yellow-800">
            <span class="font-semibold">Not:</span> {{ $viewingOrder->notes }}
        </div>
        @endif

        {{-- Aksiyon butonları --}}
        <div class="flex flex-wrap gap-2">
            @if($activeTab === 'bekleyen')
                <button wire:click="moveToDevamEden({{ $order->id }})"
                    class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    Devam Edene Al
                </button>
            @elseif($activeTab === 'devam_eden')
                <button wire:click="moveToTamamlandi({{ $order->id }})"
                    class="inline-flex items-center gap-1.5 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Tamamlandı
                </button>
                <button wire:click="moveToBekleyen({{ $order->id }})"
                    class="inline-flex items-center gap-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Geri Al
                </button>
            @elseif($activeTab === 'tamamlandi')
                <button wire:click="moveToDevamEden({{ $order->id }})"
                    class="inline-flex items-center gap-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Devam Edene Geri Al
                </button>
            @endif

            <a href="{{ route('pdf.is-emri', $order->id) }}" target="_blank"
                class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-xl text-sm font-semibold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                PDF
            </a>
            <button wire:click="delete({{ $order->id }})" wire:confirm="Bu iş emrini silmek istediğinize emin misiniz? Stok iade edilecek."
                class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-xl text-sm font-semibold transition ml-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Sil
            </button>
        </div>
    </div>
    @endif

</div>
@empty
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
    <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    <p class="font-medium">
        {{ $activeTab === 'bekleyen' ? 'Bekleyen iş emri yok' : ($activeTab === 'devam_eden' ? 'Devam eden iş yok' : 'Tamamlanan iş yok') }}
    </p>
    @if($activeTab === 'bekleyen')
    <p class="text-sm mt-1">Yeni iş emri oluşturmak için butona tıklayın.</p>
    @endif
</div>
@endforelse
</div>

<div class="mt-5">{{ $orders->links() }}</div>

</div>
