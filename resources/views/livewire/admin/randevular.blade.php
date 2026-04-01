<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Randevular</h1>
        <button wire:click="openCreateForm" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Yeni Randevu
        </button>
    </div>

    {{-- Yeni Randevu Formu --}}
    @if($showCreateForm)
    <div class="bg-white rounded-lg shadow mb-6 p-6 border-l-4 border-purple-500">
        <h2 class="text-lg font-semibold mb-4">Manuel Randevu Oluştur</h2>
        <form wire:submit="createAppointment">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Müşteri Adı *</label>
                    <input type="text" wire:model="newCustomerName" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Ad Soyad">
                    @error('newCustomerName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefon *</label>
                    <input type="tel" wire:model="newCustomerPhone" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="05XX XXX XX XX">
                    @error('newCustomerPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Personel</label>
                    <select wire:model="newStaffId" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Genel (Farketmez)</option>
                        @foreach($allStaff as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tarih *</label>
                    <input type="date" wire:model="newDate" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @error('newDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Saat *</label>
                    <select wire:model="newTime" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Seçin</option>
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot }}">{{ $slot }}</option>
                        @endforeach
                    </select>
                    @error('newTime') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                    <select wire:model="newStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="onaylandi">Onaylı</option>
                        <option value="bekliyor">Bekliyor</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">İşlemler *</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($allServices as $service)
                    <label class="flex items-center gap-2 px-3 py-2 border rounded-lg cursor-pointer hover:border-purple-500 transition text-sm {{ in_array($service->id, $newSelectedServices) ? 'border-purple-500 bg-purple-50' : 'border-gray-200' }}">
                        <input type="checkbox" wire:model="newSelectedServices" value="{{ $service->id }}" class="rounded text-purple-600">
                        <span>{{ $service->name }}</span>
                        <span class="text-purple-600 font-medium">{{ number_format($service->price, 0, ',', '.') }} ₺</span>
                    </label>
                    @endforeach
                </div>
                @error('newSelectedServices') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Not</label>
                <input type="text" wire:model="newNote" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Randevu notu...">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 text-sm font-medium">Randevu Oluştur</button>
                <button type="button" wire:click="$set('showCreateForm', false)" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 text-sm">İptal</button>
            </div>
        </form>
    </div>
    @endif

    {{-- Filtre --}}
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <div class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm text-gray-500 mb-1">Durum</label>
                <select wire:model.live="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Tümü</option>
                    <option value="bekliyor">Bekliyor</option>
                    <option value="onaylandi">Onaylı</option>
                    <option value="iptal">İptal</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-1">Başlangıç</label>
                <input type="date" wire:model="dateFrom" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm text-gray-500 mb-1">Bitiş</label>
                <input type="date" wire:model="dateTo" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <button wire:click="filter" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">Filtrele</button>
        </div>
    </div>

    {{-- Tablo --}}
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Müşteri</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İletişim</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tarih / Saat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İşlemler</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Personel</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Not</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tutar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksiyon</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($appointments as $appointment)
                <tr>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $appointment->customer->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $appointment->customer->phone }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $appointment->appointment_date->format('d.m.Y - H:i') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $appointment->services->pluck('name')->join(', ') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $appointment->staff?->name ?? 'Genel' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $appointment->note ?? '—' }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ number_format($appointment->total_price, 0, ',', '.') }} ₺</td>
                    <td class="px-6 py-4">
                        @if($appointment->status === 'onaylandi')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Onaylı</span>
                        @elseif($appointment->status === 'bekliyor')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Bekliyor</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">İptal</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($appointment->status === 'bekliyor')
                            <div class="flex gap-1">
                                <button wire:click="approve({{ $appointment->id }})" class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">Onayla</button>
                                <button wire:click="cancel({{ $appointment->id }})" class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">İptal</button>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">Randevu bulunamadı</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
