<div>

{{-- ── BAŞLIK ───────────────────────────────────────────── --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Müşteriler</h1>
        <p class="text-sm text-gray-500 mt-0.5">Müşteri ve araç yönetimi</p>
    </div>
    <button wire:click="openCustomerForm()" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yeni Müşteri
    </button>
</div>

{{-- ── CARİ İSTATİSTİKLERİ ─────────────────────────────── --}}
@if($totalDebt > 0)
<div class="grid grid-cols-2 gap-3 mb-5">
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
        <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">Toplam Cari Alacak</p>
        <p class="text-2xl font-black text-amber-700">{{ number_format($totalDebt, 2, ',', '.') }} ₺</p>
    </div>
    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Bakiyeli Müşteri</p>
        <p class="text-2xl font-black text-slate-700">{{ $debtorCount }} kişi</p>
    </div>
</div>
@endif

{{-- ── MÜŞTERİ FORM MODAL ───────────────────────────────── --}}
@if($showCustomerForm)
<div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4" style="background:rgba(0,0,0,0.5)" wire:click.self="$set('showCustomerForm',false)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 animate-fade-up">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-bold text-gray-900">
                {{ $editingCustomerId ? 'Müşteriyi Düzenle' : 'Yeni Müşteri Ekle' }}
            </h2>
            <button wire:click="$set('showCustomerForm',false)" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form wire:submit="saveCustomer" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad *</label>
                <input type="text" wire:model="customerName" placeholder="Ahmet Yılmaz"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent outline-none transition">
                @error('customerName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telefon *</label>
                <input type="tel" wire:model="customerPhone" placeholder="05XX XXX XX XX"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent outline-none transition">
                @error('customerPhone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-posta</label>
                <input type="email" wire:model="customerEmail" placeholder="ornek@mail.com"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent outline-none transition">
                @error('customerEmail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Not</label>
                <textarea wire:model="customerNotes" rows="2" placeholder="Müşteri hakkında not..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent outline-none transition resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white py-2.5 rounded-xl font-semibold text-sm transition">
                    {{ $editingCustomerId ? 'Güncelle' : 'Kaydet' }}
                </button>
                <button type="button" wire:click="$set('showCustomerForm',false)" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 rounded-xl font-semibold text-sm transition">
                    İptal
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ── ARAÇ FORM MODAL ──────────────────────────────────── --}}
@if($showVehicleForm)
<div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4" style="background:rgba(0,0,0,0.5)" wire:click.self="$set('showVehicleForm',false)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-bold text-gray-900">
                {{ $editingVehicleId ? 'Araç Düzenle' : 'Araç Ekle' }}
            </h2>
            <button wire:click="$set('showVehicleForm',false)" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form wire:submit="saveVehicle" class="space-y-4">
            {{-- Marka --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Marka *</label>
                <select wire:model.live="selectedBrandId" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition bg-white">
                    <option value="">-- Marka Seçin --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('selectedBrandId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            {{-- Model --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Model *</label>
                <select wire:model="selectedModelId" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition bg-white" {{ !$selectedBrandId ? 'disabled' : '' }}>
                    <option value="">{{ $selectedBrandId ? '-- Model Seçin --' : 'Önce marka seçin' }}</option>
                    @foreach($models as $model)
                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                    @endforeach
                </select>
                @error('selectedModelId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            {{-- Plaka + Renk --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plaka</label>
                    <input type="text" wire:model="vehiclePlate" placeholder="34 ABC 123"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition uppercase">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Renk</label>
                    <input type="text" wire:model="vehicleColor" placeholder="Beyaz"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition">
                </div>
            </div>
            {{-- Yıl --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Model Yılı</label>
                <input type="number" wire:model="vehicleYear" placeholder="{{ date('Y') }}" min="1980" max="{{ date('Y')+1 }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition">
                @error('vehicleYear') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white py-2.5 rounded-xl font-semibold text-sm transition">
                    {{ $editingVehicleId ? 'Güncelle' : 'Ekle' }}
                </button>
                <button type="button" wire:click="$set('showVehicleForm',false)" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 rounded-xl font-semibold text-sm transition">
                    İptal
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ── ARAMA & FİLTRE ──────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 mb-5 flex gap-2">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="İsim veya telefon ile ara..."
            class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent outline-none transition">
    </div>
    <button wire:click="$toggle('filterDebtor')"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition flex-shrink-0
        {{ $filterDebtor ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-amber-50 hover:text-amber-700' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Cari Bakiye
    </button>
</div>

{{-- ── MÜŞTERİ LİSTESİ ─────────────────────────────────── --}}
<div class="space-y-3">
    @forelse($customers as $customer)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200">

        {{-- Müşteri Satırı --}}
        <div class="flex items-center gap-4 p-4 cursor-pointer hover:bg-gray-50 transition-colors" wire:click="toggleCustomer({{ $customer->id }})">
            {{-- Avatar --}}
            <div class="w-11 h-11 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold text-base"
                 style="background: linear-gradient(135deg, #0891b2, #0d2137)">
                {{ mb_substr($customer->name, 0, 1) }}
            </div>

            {{-- Bilgi --}}
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900 truncate">{{ $customer->name }}</p>
                <p class="text-sm text-gray-500">{{ $customer->phone }}</p>
            </div>

            {{-- Borç badge --}}
            @php $customerBalance = ($customer->total_amount_sum ?? 0) - ($customer->discount_amount_sum ?? 0) - ($customer->paid_amount_sum ?? 0); @endphp
            @if($customerBalance > 0)
            <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-full">
                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-xs font-bold text-amber-700">{{ number_format($customerBalance, 0, ',', '.') }} ₺ cari</span>
            </div>
            @endif

            {{-- Araç sayısı --}}
            <div class="hidden sm:flex items-center gap-1.5 bg-slate-100 px-3 py-1.5 rounded-full">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4-4 4 4m-4-4V3m-7 14h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="text-xs font-semibold text-slate-600">{{ $customer->vehicles_count }} araç</span>
            </div>

            {{-- Chevron --}}
            <svg class="w-5 h-5 text-gray-400 transition-transform {{ $activeCustomerId === $customer->id ? 'rotate-180' : '' }}"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>

        {{-- Detay Paneli --}}
        @if($activeCustomerId === $customer->id)
        <div class="border-t border-gray-100 bg-gray-50">

            {{-- Müşteri Bilgi + Aksiyon --}}
            <div class="p-4 flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-2 text-sm">
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wide">Telefon</span>
                        <p class="font-medium text-gray-800">{{ $customer->phone }}</p>
                    </div>
                    @if($customer->email)
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wide">E-posta</span>
                        <p class="font-medium text-gray-800 truncate">{{ $customer->email }}</p>
                    </div>
                    @endif
                    @if($customer->notes)
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wide">Not</span>
                        <p class="font-medium text-gray-800">{{ $customer->notes }}</p>
                    </div>
                    @endif
                    @if($customerDebt > 0)
                    <div class="sm:col-span-3">
                        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-center justify-between mt-1">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-sm font-semibold text-amber-700">Toplam Cari Alacak</span>
                            </div>
                            <span class="text-lg font-black text-amber-700">{{ number_format($customerDebt, 2, ',', '.') }} ₺</span>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="flex gap-2 sm:flex-col sm:items-end">
                    <button wire:click="openCustomerForm({{ $customer->id }})" class="text-xs bg-white border border-gray-200 text-gray-600 hover:border-cyan-400 hover:text-cyan-600 px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Düzenle
                    </button>
                    <button wire:click="deleteCustomer({{ $customer->id }})" wire:confirm="Bu müşteriyi silmek istediğinize emin misiniz?" class="text-xs bg-white border border-gray-200 text-red-500 hover:border-red-300 hover:bg-red-50 px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Sil
                    </button>
                </div>
            </div>

            {{-- Cari Kart --}}
            @if($openOrders->count() > 0)
            <div class="px-4 pb-2">
                <div class="bg-white border border-amber-200 rounded-2xl overflow-hidden">
                    {{-- Cari başlık --}}
                    <div class="flex items-center justify-between px-4 py-3 bg-amber-50 border-b border-amber-200">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                            <span class="text-sm font-bold text-amber-800">Cari Hesap</span>
                        </div>
                        <span class="text-sm font-black text-amber-700">{{ number_format($customerDebt, 2, ',', '.') }} ₺ açık</span>
                    </div>

                    {{-- Açık iş emirleri --}}
                    <div class="divide-y divide-gray-100">
                        @foreach($openOrders as $openOrder)
                        @php $remaining = $openOrder->effective_total - $openOrder->paid_amount; @endphp
                        <div class="p-3">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-xs font-mono text-gray-500">{{ $openOrder->order_no }}</span>
                                        <span class="text-xs text-gray-400">{{ $openOrder->created_at->format('d.m.Y') }}</span>
                                        @if($openOrder->vehicle)
                                        <span class="text-xs text-gray-500">· {{ $openOrder->vehicle->brand?->name }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 mt-1 text-sm">
                                        <span class="text-gray-500">Toplam: {{ number_format($openOrder->effective_total, 2, ',', '.') }} ₺</span>
                                        @if($openOrder->paid_amount > 0)
                                        <span class="text-green-600">Ödenen: {{ number_format($openOrder->paid_amount, 2, ',', '.') }} ₺</span>
                                        @endif
                                        <span class="font-bold text-amber-700">Kalan: {{ number_format($remaining, 2, ',', '.') }} ₺</span>
                                    </div>
                                </div>
                                <button wire:click="openCariPay({{ $openOrder->id }})"
                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg transition flex-shrink-0
                                    {{ $cariPayOrderId === $openOrder->id ? 'bg-gray-200 text-gray-600' : 'bg-green-600 text-white hover:bg-green-700' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cariPayOrderId === $openOrder->id ? 'M6 18L18 6M6 6l12 12' : 'M12 4v16m8-8H4' }}"/></svg>
                                    {{ $cariPayOrderId === $openOrder->id ? 'İptal' : 'Ödeme Al' }}
                                </button>
                            </div>

                            {{-- Ödeme formu --}}
                            @if($cariPayOrderId === $openOrder->id)
                            <div class="mt-3 bg-green-50 border border-green-200 rounded-xl p-3 space-y-2">
                                {{-- Yöntem --}}
                                <div class="flex rounded-xl border border-gray-200 overflow-hidden bg-white">
                                    @foreach(['nakit' => 'Nakit', 'kart' => 'Kart', 'havale' => 'Havale'] as $val => $lbl)
                                    <button type="button" wire:click="$set('cariPayType','{{ $val }}')"
                                        class="flex-1 py-1.5 text-xs font-semibold transition {{ $cariPayType === $val ? 'bg-green-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                                        {{ $lbl }}
                                    </button>
                                    @endforeach
                                </div>
                                <div class="flex gap-2">
                                    <div class="flex-1">
                                        <input type="number" wire:model="cariPayAmount"
                                            placeholder="Tutar (Kalan: {{ number_format($remaining,2,',','.') }} ₺)"
                                            min="0.01" step="0.01"
                                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                        @error('cariPayAmount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="flex-1">
                                        <input type="number" wire:model="cariPayDiscount"
                                            placeholder="İndirim ₺"
                                            min="0" step="0.01"
                                            class="w-full border border-orange-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 outline-none">
                                    </div>
                                    <button wire:click="saveCariPayment"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition flex-shrink-0">
                                        Kaydet
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Araçlar --}}
            <div class="px-4 pb-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 1h6l2-1z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 6h3l3 5v5h-2"/>
                        </svg>
                        Araçlar
                    </h3>
                    <button wire:click="openVehicleForm({{ $customer->id }})" class="inline-flex items-center gap-1.5 text-xs bg-cyan-600 hover:bg-cyan-700 text-white px-3 py-1.5 rounded-lg font-semibold transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Araç Ekle
                    </button>
                </div>

                @if($vehicles->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach($vehicles as $vehicle)
                    <div class="bg-white border border-gray-200 rounded-xl p-3.5 flex items-start gap-3">
                        {{-- Araç ikonu --}}
                        <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 1h6l2-1zm0 0h3l3-5V6h-3"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 text-sm">
                                {{ $vehicle->brand?->name }} {{ $vehicle->model?->name }}
                                @if($vehicle->year) <span class="text-gray-400 font-normal">({{ $vehicle->year }})</span> @endif
                            </p>
                            <div class="flex flex-wrap gap-x-3 mt-1">
                                @if($vehicle->plate)
                                <span class="text-xs font-mono bg-yellow-50 border border-yellow-200 text-yellow-800 px-2 py-0.5 rounded">{{ $vehicle->plate }}</span>
                                @endif
                                @if($vehicle->color)
                                <span class="text-xs text-gray-500">{{ $vehicle->color }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <button wire:click="openVehicleForm({{ $customer->id }}, {{ $vehicle->id }})" class="text-gray-400 hover:text-cyan-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="deleteVehicle({{ $vehicle->id }})" wire:confirm="Bu aracı silmek istediğinize emin misiniz?" class="text-gray-400 hover:text-red-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-6 text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 1h6l2-1z"/>
                    </svg>
                    <p class="text-sm">Henüz araç eklenmedi</p>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <p class="font-medium">Müşteri bulunamadı</p>
        <p class="text-sm mt-1">Yeni müşteri eklemek için butona tıklayın.</p>
    </div>
    @endforelse
</div>

{{-- Sayfalama --}}
<div class="mt-5">
    {{ $customers->links() }}
</div>

</div>
