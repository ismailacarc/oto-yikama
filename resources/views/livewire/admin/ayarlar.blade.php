<div x-data="{ saved: false }" @saved.window="saved=true; setTimeout(()=>saved=false,3000)">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ayarlar</h1>
            <p class="text-sm text-gray-500 mt-1">Firma bilgileri ve uygulama ayarları</p>
        </div>
        <button wire:click="save" wire:loading.attr="disabled"
            class="flex items-center gap-2 bg-cyan-600 hover:bg-cyan-500 disabled:opacity-60 text-white font-semibold px-6 py-2.5 rounded-xl transition shadow-sm">
            <span wire:loading.remove wire:target="save">
                <svg class="w-4 h-4 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Kaydet
            </span>
            <span wire:loading wire:target="save">Kaydediliyor...</span>
        </button>
    </div>

    {{-- Success toast --}}
    <div x-show="saved" x-transition
        class="fixed top-6 right-6 z-50 bg-emerald-600 text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm font-semibold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Ayarlar başarıyla kaydedildi
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Sol sütun --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Firma Bilgileri --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 bg-cyan-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h2 class="text-base font-bold text-gray-900">Firma Bilgileri</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Firma Adı <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="firm_name" placeholder="UFK Garage"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        @error('firm_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Telefon</label>
                        <input type="text" wire:model="firm_phone" placeholder="0212 000 00 00"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">E-posta</label>
                        <input type="email" wire:model="firm_email" placeholder="info@ufkgarage.com"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        @error('firm_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Adres</label>
                        <textarea wire:model="firm_address" rows="2" placeholder="Mahalle, sokak, ilçe, şehir"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 resize-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Vergi No</label>
                        <input type="text" wire:model="firm_tax_no" placeholder="1234567890"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Vergi Dairesi</label>
                        <input type="text" wire:model="firm_tax_office" placeholder="Kadıköy V.D."
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    </div>
                </div>
            </div>

            {{-- Uygulama Ayarları --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h2 class="text-base font-bold text-gray-900">Uygulama Ayarları</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Para Birimi Sembolü</label>
                        <input type="text" wire:model="currency" placeholder="₺"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <p class="text-xs text-gray-400 mt-1">PDF ve ekranlarda gösterilir</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Varsayılan Düşük Stok Eşiği</label>
                        <input type="number" wire:model="low_stock_default" min="0" placeholder="5"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <p class="text-xs text-gray-400 mt-1">Yeni ürün eklerken varsayılan değer</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sağ sütun: Logo --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h2 class="text-base font-bold text-gray-900">Logo</h2>
                </div>

                @if($current_logo)
                <div class="mb-4 p-3 bg-slate-50 rounded-xl flex items-center justify-between gap-3">
                    <img src="{{ Storage::url($current_logo) }}" alt="Logo" class="h-12 object-contain rounded">
                    <button wire:click="removeLogo" class="text-xs text-red-500 hover:text-red-700 flex-shrink-0">Kaldır</button>
                </div>
                @endif

                <label class="flex flex-col items-center gap-3 border-2 border-dashed border-gray-200 rounded-xl p-5 cursor-pointer hover:border-cyan-400 hover:bg-cyan-50/30 transition">
                    @if($logo)
                        <img src="{{ $logo->temporaryUrl() }}" class="h-16 object-contain rounded">
                        <span class="text-xs text-cyan-600 font-medium">{{ $logo->getClientOriginalName() }}</span>
                    @else
                        <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-700">Logo yükle</p>
                            <p class="text-xs text-gray-400 mt-0.5">PNG, JPG — maks. 2MB</p>
                        </div>
                    @endif
                    <input type="file" wire:model="logo" accept="image/*" class="hidden">
                </label>
                @error('logo') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-3">Logo iş emri PDF'lerinde görünür</p>
            </div>

            {{-- PDF Önizleme Bilgisi --}}
            <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">PDF'lerde Kullanılan Alanlar</h3>
                <div class="space-y-2 text-xs text-slate-500">
                    @foreach(['Firma Adı', 'Telefon', 'E-posta', 'Adres', 'Logo'] as $field)
                    <div class="flex gap-2 items-center">
                        <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ $field }}
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <p class="text-xs text-slate-500 font-medium mb-2">Mevcut değerler:</p>
                    <p class="text-xs text-slate-600 font-semibold">{{ $firm_name ?: '—' }}</p>
                    @if($firm_phone) <p class="text-xs text-slate-500">{{ $firm_phone }}</p> @endif
                    @if($firm_email) <p class="text-xs text-slate-500">{{ $firm_email }}</p> @endif
                    @if($firm_address) <p class="text-xs text-slate-500 leading-relaxed">{{ $firm_address }}</p> @endif
                </div>
            </div>
        </div>
    </div>

</div>
