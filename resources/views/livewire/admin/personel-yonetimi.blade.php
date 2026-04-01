<div wire:poll.10s>

{{-- Başlık --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Personel</h1>
        <p class="text-sm text-gray-500 mt-0.5">Çalışan yönetimi ve iş takibi</p>
    </div>
    <button wire:click="create" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Personel Ekle
    </button>
</div>

{{-- Modal --}}
@if($showForm)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
<div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-bold text-gray-900">{{ $editingId ? 'Personeli Düzenle' : 'Yeni Personel' }}</h2>
        <button wire:click="$set('showForm',false)" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad *</label>
            <input type="text" wire:model="name" placeholder="Ali Yılmaz"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
            <input type="tel" wire:model="phone" placeholder="05XX XXX XX XX"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
        </div>
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-cyan-600 rounded">
            <span class="text-sm font-medium text-gray-700">Aktif personel</span>
        </label>
        <div class="flex gap-3 pt-2">
            <button wire:click="save" class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white py-2.5 rounded-xl font-semibold text-sm transition">
                {{ $editingId ? 'Güncelle' : 'Kaydet' }}
            </button>
            <button wire:click="$set('showForm',false)" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 rounded-xl font-semibold text-sm transition">
                İptal
            </button>
        </div>
    </div>
</div>
</div>
@endif

{{-- Personel Listesi --}}
<div class="space-y-3">
    @forelse($staffList as $staff)
    @php $activeTimer = $activeTimers->get($staff->id); @endphp
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden
        {{ $activeTimer ? 'border-green-200' : 'border-gray-100' }}">

        {{-- Kart satırı --}}
        <div class="p-4 flex items-center gap-4 cursor-pointer hover:bg-gray-50 transition"
             wire:click="toggleStaff({{ $staff->id }})">

            {{-- Avatar --}}
            <div class="relative flex-shrink-0">
                <div class="w-11 h-11 rounded-full flex items-center justify-center text-white font-bold text-base"
                     style="background: linear-gradient(135deg, #0891b2, #0d2137)">
                    {{ mb_substr($staff->name, 0, 1) }}
                </div>
                {{-- Aktif nokta --}}
                @if($activeTimer)
                <span class="absolute -top-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></span>
                @endif
            </div>

            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900">{{ $staff->name }}</p>

                @if($activeTimer)
                {{-- Şu an çalışıyor --}}
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-xs text-green-700 font-semibold">Çalışıyor —
                        {{ $activeTimer->workOrder->order_no }}
                        @if($activeTimer->workOrder->vehicle)
                        · {{ $activeTimer->workOrder->vehicle->brand?->name }} {{ $activeTimer->workOrder->vehicle->model?->name }}
                        @if($activeTimer->workOrder->vehicle->plate)
                        ({{ $activeTimer->workOrder->vehicle->plate }})
                        @endif
                        @endif
                    </span>
                </div>
                <p class="text-xs text-green-600 mt-0.5">
                    Başlangıç: {{ $activeTimer->started_at->format('H:i') }} —
                    {{ $activeTimer->started_at->diffForHumans(null, true) }} geçti
                </p>
                @else
                <p class="text-sm text-gray-400">{{ $staff->phone ?? 'Beklemede' }}</p>
                @endif
            </div>

            <div class="flex items-center gap-3">
                @if(!$activeTimer)
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $staff->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $staff->is_active ? 'Aktif' : 'Pasif' }}
                </span>
                @endif
                <div class="flex gap-1" wire:click.stop>
                    <button wire:click="edit({{ $staff->id }})" class="text-gray-400 hover:text-cyan-600 p-1.5 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button wire:click="delete({{ $staff->id }})" wire:confirm="Bu personeli silmek istediğinize emin misiniz?"
                        class="text-gray-400 hover:text-red-500 p-1.5 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                <svg class="w-4 h-4 text-gray-300 transition-transform {{ $viewingStaffId === $staff->id ? 'rotate-180' : '' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        {{-- Geçmiş paneli --}}
        @if($viewingStaffId === $staff->id)
        <div class="border-t border-gray-100 bg-gray-50 p-4">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">İş Geçmişi</p>

            @if($timerHistory->count())
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500">İş Emri</th>
                            <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 hidden sm:table-cell">Müşteri / Araç</th>
                            <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 hidden sm:table-cell">Tarih</th>
                            <th class="px-3 py-2.5 text-right text-xs font-semibold text-gray-500">Süre</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($timerHistory as $t)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2.5 font-mono text-xs text-gray-700">{{ $t->workOrder?->order_no }}</td>
                            <td class="px-3 py-2.5 hidden sm:table-cell">
                                <p class="text-gray-800 text-xs font-medium">{{ $t->workOrder?->customer?->name }}</p>
                                @if($t->workOrder?->vehicle)
                                <p class="text-gray-400 text-xs">
                                    {{ $t->workOrder->vehicle->brand?->name }}
                                    {{ $t->workOrder->vehicle->model?->name }}
                                    @if($t->workOrder->vehicle->plate)
                                    · <span class="font-mono">{{ $t->workOrder->vehicle->plate }}</span>
                                    @endif
                                </p>
                                @endif
                            </td>
                            <td class="px-3 py-2.5 text-xs text-gray-400 hidden sm:table-cell">
                                {{ $t->started_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-3 py-2.5 text-right">
                                @if($t->duration_minutes)
                                <span class="inline-flex items-center gap-1 text-xs font-bold
                                    {{ $t->duration_minutes > 120 ? 'text-orange-600' : 'text-cyan-700' }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ intdiv($t->duration_minutes, 60) > 0 ? intdiv($t->duration_minutes,60).'s ' : '' }}{{ $t->duration_minutes % 60 }}dk
                                </span>
                                @else
                                <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="3" class="px-3 py-2 text-right text-xs font-semibold text-gray-500">Toplam Çalışma</td>
                            <td class="px-3 py-2 text-right text-xs font-black text-cyan-700">
                                @php $total = $timerHistory->sum('duration_minutes'); @endphp
                                {{ intdiv($total,60) > 0 ? intdiv($total,60).'s ' : '' }}{{ $total%60 }}dk
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-4">Henüz tamamlanmış iş kaydı yok.</p>
            @endif
        </div>
        @endif

    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <p class="font-medium">Henüz personel eklenmedi</p>
    </div>
    @endforelse
</div>

</div>
