<div class="max-w-2xl mx-auto px-4 py-10">
    <div class="rounded-2xl shadow-lg p-6 md:p-8" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1)">
        <!-- Steps indicator -->
        <div class="flex items-center justify-center mb-8">
            <div class="flex items-center">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all {{ $step >= 1 ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-900' : 'bg-white/10 text-gray-400' }}">
                        @if($step > 1)
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        @else
                            1
                        @endif
                    </div>
                    <span class="text-xs mt-1 {{ $step >= 1 ? 'text-cyan-400 font-medium' : 'text-gray-400' }}">Islem</span>
                </div>
                <div class="w-20 h-1 mx-2 rounded {{ $step >= 2 ? 'bg-cyan-600' : 'bg-gray-200' }}"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all {{ $step >= 2 ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-900' : 'bg-white/10 text-gray-400' }}">
                        @if($step > 2)
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        @else
                            2
                        @endif
                    </div>
                    <span class="text-xs mt-1 {{ $step >= 2 ? 'text-cyan-400 font-medium' : 'text-gray-400' }}">Tarih</span>
                </div>
                <div class="w-20 h-1 mx-2 rounded {{ $step >= 3 ? 'bg-cyan-600' : 'bg-gray-200' }}"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all {{ $step >= 3 ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-900' : 'bg-white/10 text-gray-400' }}">3</div>
                    <span class="text-xs mt-1 {{ $step >= 3 ? 'text-cyan-400 font-medium' : 'text-gray-400' }}">Bilgi</span>
                </div>
            </div>
        </div>

        @if($step === 1)
        <!-- Step 1: Select Services -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-cyan-900/30 rounded-full mb-3">
                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-white">Randevu Al</h2>
            <p class="text-gray-500 mt-1">Isleminizi secin, tarih ve saati belirleyin</p>
        </div>

        <div class="mb-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-6 bg-cyan-600 rounded-full"></div>
                <h3 class="font-semibold text-white">ISLEMLER</h3>
                <span class="text-xs bg-white/10 text-gray-400 px-2 py-1 rounded-full">Birden fazla secilebilir</span>
            </div>

            <div class="space-y-3">
                @foreach($services as $service)
                <div wire:click="toggleService({{ $service->id }})" class="flex items-center justify-between p-4 border-2 rounded-xl cursor-pointer hover:shadow-md transition-all {{ in_array($service->id, $selectedServices) ? 'border-cyan-500 bg-cyan-950/50 shadow-sm' : 'border-white/8 hover:border-cyan-700' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition-all {{ in_array($service->id, $selectedServices) ? 'bg-cyan-600 border-green-600 scale-110' : 'border-gray-300' }}">
                            @if(in_array($service->id, $selectedServices))
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            @endif
                        </div>
                        <div>
                            <span class="font-semibold text-white">{{ $service->name }}</span>
                            @if($service->description)
                                <p class="text-xs text-gray-400">{{ $service->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $service->duration }} dk
                        </div>
                        <span class="text-cyan-400 font-bold text-lg">{{ number_format($service->price, 0, ',', '.') }} &#8378;</span>
                    </div>
                </div>
                @endforeach
            </div>
            @error('services') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
        </div>

        @if(count($selectedServices) > 0)
            <div class="bg-cyan-950/50 border border-cyan-700 rounded-xl p-4 mb-4">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-sm text-cyan-300">{{ count($selectedServices) }} islem secildi</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-500">Toplam</span>
                        <p class="text-2xl font-bold text-cyan-300">{{ number_format($selectedServiceDetails->sum('price'), 0, ',', '.') }} &#8378;</p>
                    </div>
                </div>
            </div>
        @endif

        <button wire:click="nextStep" class="w-full bg-cyan-600 text-white py-3.5 rounded-xl hover:bg-cyan-700 font-semibold transition-all shadow-lg shadow-cyan-900 hover:shadow-xl hover:shadow-cyan-900 flex items-center justify-center gap-2">
            Devam Et
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        @elseif($step === 2)
        <!-- Step 2: Date, Time, Staff -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-cyan-900/30 rounded-full mb-3">
                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-white">Tarih ve Saat Secin</h2>
        </div>

        @if($staffList->count() > 0)
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-1 h-6 bg-cyan-600 rounded-full"></div>
                <h3 class="font-semibold text-white">PERSONEL</h3>
            </div>
            <div class="flex flex-wrap gap-2">
                <button wire:click="selectStaff(null)" class="px-5 py-2.5 border-2 rounded-xl text-sm font-medium transition-all {{ $staffGeneral ? 'border-cyan-500 bg-cyan-950/50 text-cyan-300 shadow-sm' : 'border-white/8 hover:border-cyan-700' }}">
                    Farketmez
                </button>
                @foreach($staffList as $staff)
                <button wire:click="selectStaff({{ $staff->id }})" class="px-5 py-2.5 border-2 rounded-xl text-sm font-medium transition-all {{ !$staffGeneral && $selectedStaff === $staff->id ? 'border-cyan-500 bg-cyan-950/50 text-cyan-300 shadow-sm' : 'border-white/8 hover:border-cyan-700' }}">
                    {{ $staff->name }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-1 h-6 bg-cyan-600 rounded-full"></div>
                <h3 class="font-semibold text-white">TARIH</h3>
            </div>
            <input type="date" wire:model.live="selectedDate" min="{{ now()->addDay()->format('Y-m-d') }}" class="w-full border-2 rounded-xl px-4 py-3 focus:border-cyan-500 focus:ring-0 outline-none transition text-white placeholder-gray-500" style="background:rgba(255,255,255,0.06); border-color:rgba(255,255,255,0.1)">
        </div>

        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-1 h-6 bg-cyan-600 rounded-full"></div>
                <h3 class="font-semibold text-white">SAAT</h3>
            </div>
            <div class="grid grid-cols-4 md:grid-cols-6 gap-2">
                @foreach($timeSlots as $slot)
                <button wire:click="$set('selectedTime', '{{ $slot }}')" class="py-2.5 text-sm rounded-xl border-2 transition-all font-medium {{ $selectedTime === $slot ? 'bg-cyan-600 text-white border-green-600 shadow-lg shadow-cyan-900' : 'border-white/8 hover:border-green-300 text-gray-300' }}">
                    {{ $slot }}
                </button>
                @endforeach
            </div>
            @error('datetime') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3">
            <button wire:click="previousStep" class="flex-1 bg-white/5 text-gray-200 py-3.5 rounded-xl hover:bg-gray-200 font-semibold transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Geri
            </button>
            <button wire:click="nextStep" class="flex-1 bg-cyan-600 text-white py-3.5 rounded-xl hover:bg-cyan-700 font-semibold transition-all shadow-lg shadow-cyan-900 flex items-center justify-center gap-2">
                Devam Et
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        @else
        <!-- Step 3: Customer Info -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-cyan-900/30 rounded-full mb-3">
                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-white">Bilgilerinizi Girin</h2>
        </div>

        <form wire:submit="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-200 mb-1">Ad Soyad</label>
                <input type="text" wire:model="customerName" class="w-full border-2 rounded-xl px-4 py-3 focus:border-cyan-500 focus:ring-0 outline-none transition text-white placeholder-gray-500" style="background:rgba(255,255,255,0.06); border-color:rgba(255,255,255,0.1)" placeholder="Adiniz Soyadiniz">
                @error('customerName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-200 mb-1">Telefon</label>
                <input type="tel" wire:model="customerPhone" class="w-full border-2 rounded-xl px-4 py-3 focus:border-cyan-500 focus:ring-0 outline-none transition text-white placeholder-gray-500" style="background:rgba(255,255,255,0.06); border-color:rgba(255,255,255,0.1)" placeholder="05XX XXX XX XX">
                @error('customerPhone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-200 mb-1">Not (opsiyonel)</label>
                <textarea wire:model="note" rows="2" class="w-full border-2 rounded-xl px-4 py-3 focus:border-cyan-500 focus:ring-0 outline-none transition text-white placeholder-gray-500" style="background:rgba(255,255,255,0.06); border-color:rgba(255,255,255,0.1)" placeholder="Eklemek istediginiz not..."></textarea>
            </div>

            <!-- Randevu Ozeti -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-5 border border-green-100">
                <h3 class="font-bold text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Randevu Ozeti
                </h3>
                <div class="text-sm space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Islemler</span>
                        <span class="font-medium text-white">{{ $selectedServiceDetails->pluck('name')->join(', ') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tarih</span>
                        <span class="font-medium text-white">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y, l') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Saat</span>
                        <span class="font-medium text-white">{{ $selectedTime }}</span>
                    </div>
                    <div class="border-t border-cyan-700 pt-2 mt-2 flex justify-between">
                        <span class="font-semibold text-white">Toplam</span>
                        <span class="font-bold text-xl text-cyan-300">{{ number_format($selectedServiceDetails->sum('price'), 0, ',', '.') }} &#8378;</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" wire:click="previousStep" class="flex-1 bg-white/5 text-gray-200 py-3.5 rounded-xl hover:bg-gray-200 font-semibold transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Geri
                </button>
                <button type="submit" class="flex-1 bg-cyan-600 text-white py-3.5 rounded-xl hover:bg-cyan-700 font-semibold transition-all shadow-lg shadow-cyan-900 hover:shadow-xl flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Randevu Al
                </button>
            </div>
        </form>
        @endif
    </div>
</div>
