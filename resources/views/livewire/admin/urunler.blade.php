<div>

{{-- ── BAŞLIK ───────────────────────────────────────────── --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Ürünler & Stok</h1>
        <p class="text-sm text-gray-500 mt-0.5">Malzeme ve ürün stok takibi</p>
    </div>
    <button wire:click="openForm()" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yeni Ürün
    </button>
</div>

{{-- ── ÖZET KARTLAR ─────────────────────────────────────── --}}
<div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Toplam Ürün</p>
        <p class="text-2xl font-black text-gray-900">{{ $products->total() }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Stok Değeri</p>
        <p class="text-2xl font-black text-cyan-600">{{ number_format($totalValue, 0, ',', '.') }} ₺</p>
    </div>
    <div class="col-span-2 sm:col-span-1 rounded-2xl border shadow-sm p-4 {{ $lowStock > 0 ? 'bg-red-50 border-red-200' : 'bg-white border-gray-100' }}">
        <p class="text-xs font-medium uppercase tracking-wide mb-1 {{ $lowStock > 0 ? 'text-red-400' : 'text-gray-400' }}">Düşük Stok</p>
        <p class="text-2xl font-black {{ $lowStock > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $lowStock }}</p>
    </div>
</div>

{{-- ── ÜRÜN FORM MODAL ──────────────────────────────────── --}}
@if($showForm)
<div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4" style="background:rgba(0,0,0,0.5)" wire:click.self="$set('showForm',false)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-bold text-gray-900">{{ $editingId ? 'Ürün Düzenle' : 'Yeni Ürün Ekle' }}</h2>
            <button wire:click="$set('showForm',false)" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ürün Adı *</label>
                <input type="text" wire:model="name" placeholder="örn: 3M PPF Film"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent outline-none transition">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select wire:model.live="category" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition bg-white">
                        <option value="">Seçin</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                        <option value="__manuel__">+ Manuel Gir</option>
                    </select>
                    @if($category === '__manuel__')
                    <input type="text" wire:model="customCategory" placeholder="Kategori adı girin..."
                        class="w-full mt-2 border border-cyan-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition">
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Birim *</label>
                    <select wire:model="unit" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition bg-white">
                        @foreach($units as $u)
                            <option value="{{ $u }}">{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Birim Fiyat (₺) *</label>
                    <input type="number" wire:model="unit_price" placeholder="0.00" step="0.01" min="0"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition">
                    @error('unit_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mevcut Stok *</label>
                    <input type="number" wire:model="stock_quantity" placeholder="0" step="0.001" min="0"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition">
                    @error('stock_quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Stok Uyarısı</label>
                <input type="number" wire:model="min_stock_alert" placeholder="0 = devre dışı" step="0.001" min="0"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition">
                <p class="text-xs text-gray-400 mt-1">Bu değerin altına düşünce uyarı verir</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Not</label>
                <textarea wire:model="notes" rows="2" placeholder="Ürün hakkında not..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none transition resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white py-2.5 rounded-xl font-semibold text-sm transition">
                    {{ $editingId ? 'Güncelle' : 'Kaydet' }}
                </button>
                <button type="button" wire:click="$set('showForm',false)" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 rounded-xl font-semibold text-sm transition">
                    İptal
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ── STOK GİRİŞ MODAL ────────────────────────────────── --}}
@if($showStockForm)
@php $sp = \App\Models\Product::find($stockProductId); @endphp
<div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4" style="background:rgba(0,0,0,0.5)" wire:click.self="$set('showStockForm',false)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Stok Ekle</h2>
                @if($sp) <p class="text-sm text-gray-500">{{ $sp->name }}</p> @endif
            </div>
            <button wire:click="$set('showStockForm',false)" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @if($sp)
        <div class="bg-cyan-50 border border-cyan-200 rounded-xl p-3 mb-4 flex items-center gap-3">
            <div class="text-center">
                <p class="text-xs text-cyan-600 font-medium">Mevcut Stok</p>
                <p class="text-xl font-black text-cyan-700">{{ $sp->stock_quantity + 0 }} {{ $sp->unit }}</p>
            </div>
            <svg class="w-5 h-5 text-cyan-400 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            <div class="text-center">
                <p class="text-xs text-cyan-600 font-medium">Eklenecek</p>
                <p class="text-xl font-black text-cyan-700">+ {{ $stockAmount ?: '?' }} {{ $sp->unit }}</p>
            </div>
        </div>
        @endif
        <form wire:submit="addStock" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Eklenecek Miktar ({{ $sp?->unit }}) *
                </label>
                <input type="number" wire:model.live="stockAmount" placeholder="0" step="0.001" min="0.001" autofocus
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-lg font-semibold focus:ring-2 focus:ring-cyan-500 outline-none transition">
                @error('stockAmount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white py-2.5 rounded-xl font-semibold text-sm transition">
                    Stoğa Ekle
                </button>
                <button type="button" wire:click="$set('showStockForm',false)" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 rounded-xl font-semibold text-sm transition">
                    İptal
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ── FİLTRE & ARAMA ──────────────────────────────────── --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3 mb-5 flex flex-col sm:flex-row gap-3">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Ürün ara..."
            class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent outline-none transition">
    </div>
    <select wire:model.live="filterCat" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none bg-white">
        <option value="">Tüm Kategoriler</option>
        @foreach($categories as $key => $label)
            <option value="{{ $key }}">{{ $label }}</option>
        @endforeach
    </select>
</div>

{{-- ── ÜRÜN LİSTESİ ─────────────────────────────────────── --}}
<div class="space-y-2">
    @forelse($products as $product)
    @php $isLow = $product->min_stock_alert > 0 && $product->stock_quantity <= $product->min_stock_alert; @endphp
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden {{ $isLow ? 'border-red-200' : 'border-gray-100' }}">
        <div class="flex items-center gap-4 p-4">

            {{-- Kategori ikonu --}}
            <div class="w-11 h-11 rounded-xl flex-shrink-0 flex items-center justify-center
                {{ $product->category === 'ppf' ? 'bg-blue-50' :
                  ($product->category === 'seramik' ? 'bg-purple-50' :
                  ($product->category === 'kimyasal' ? 'bg-green-50' : 'bg-slate-50')) }}">
                <svg class="w-5 h-5
                    {{ $product->category === 'ppf' ? 'text-blue-500' :
                      ($product->category === 'seramik' ? 'text-purple-500' :
                      ($product->category === 'kimyasal' ? 'text-green-500' : 'text-slate-400')) }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>

            {{-- Bilgi --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <p class="font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                    @if($product->category)
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium
                        {{ $product->category === 'ppf' ? 'bg-blue-100 text-blue-700' :
                          ($product->category === 'seramik' ? 'bg-purple-100 text-purple-700' :
                          ($product->category === 'kimyasal' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600')) }}">
                        {{ $categories[$product->category] ?? $product->category }}
                    </span>
                    @endif
                    @if($isLow)
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-red-100 text-red-600 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        Düşük Stok
                    </span>
                    @endif
                </div>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-sm font-bold {{ $isLow ? 'text-red-600' : 'text-gray-700' }}">
                        {{ $product->stock_quantity + 0 }} {{ $product->unit }}
                    </span>
                    <span class="text-xs text-gray-400">·</span>
                    <span class="text-sm text-gray-500">{{ number_format($product->unit_price, 2, ',', '.') }} ₺ / {{ $product->unit }}</span>
                </div>
            </div>

            {{-- Stok değeri + Aksiyonlar --}}
            <div class="flex flex-col items-end gap-2">
                <span class="text-sm font-bold text-cyan-600">
                    {{ number_format($product->stock_quantity * $product->unit_price, 0, ',', '.') }} ₺
                </span>
                <div class="flex items-center gap-1.5">
                    <button wire:click="openStockForm({{ $product->id }})" title="Stok Ekle"
                        class="w-8 h-8 rounded-lg bg-cyan-50 hover:bg-cyan-100 text-cyan-600 flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                    <button wire:click="openForm({{ $product->id }})" title="Düzenle"
                        class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-500 flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button wire:click="delete({{ $product->id }})" wire:confirm="Bu ürünü silmek istediğinize emin misiniz?" title="Sil"
                        class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Stok bar --}}
        @if($product->min_stock_alert > 0)
        @php
            $pct = min(100, ($product->stock_quantity / ($product->min_stock_alert * 3)) * 100);
        @endphp
        <div class="px-4 pb-3">
            <div class="w-full bg-gray-100 rounded-full h-1.5">
                <div class="h-1.5 rounded-full transition-all {{ $isLow ? 'bg-red-400' : 'bg-cyan-400' }}" style="width: {{ $pct }}%"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1">Min. uyarı: {{ $product->min_stock_alert + 0 }} {{ $product->unit }}</p>
        </div>
        @endif
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        <p class="font-medium">Ürün bulunamadı</p>
        <p class="text-sm mt-1">Yeni ürün eklemek için butona tıklayın.</p>
    </div>
    @endforelse
</div>

<div class="mt-5">{{ $products->links() }}</div>

</div>
