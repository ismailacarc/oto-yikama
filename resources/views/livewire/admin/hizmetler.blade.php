<div>

{{-- Başlık --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Hizmetler</h1>
        <p class="text-sm text-gray-500 mt-0.5">İş emirlerinde kullanılan hizmet listesi</p>
    </div>
    <button wire:click="openForm()" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yeni Hizmet
    </button>
</div>

{{-- Modal --}}
@if($showForm)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5)">
<div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-bold text-gray-900">{{ $editingId ? 'Hizmeti Düzenle' : 'Yeni Hizmet' }}</h2>
        <button wire:click="$set('showForm',false)" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hizmet Adı *</label>
            <input type="text" wire:model="name" placeholder="PPF Kaplama, Cam Filmi..."
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
            <textarea wire:model="description" rows="2" placeholder="Kısa açıklama..."
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none resize-none"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fiyat (₺) *</label>
            <input type="number" wire:model="price" placeholder="0.00" min="0" step="0.01"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
            @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-cyan-600 rounded">
            <span class="text-sm font-medium text-gray-700">Aktif (iş emirlerinde görünsün)</span>
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

{{-- Arama --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3 mb-5">
    <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Hizmet ara..."
            class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
    </div>
</div>

{{-- Liste --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Hizmet</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Fiyat</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Durum</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($services as $s)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">
                    <p class="font-semibold text-gray-900">{{ $s->name }}</p>
                    @if($s->description)
                    <p class="text-xs text-gray-400 mt-0.5">{{ $s->description }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-right font-bold text-cyan-700">{{ number_format($s->price,2,',','.') }} ₺</td>
                <td class="px-4 py-3 text-center">
                    <button wire:click="toggleActive({{ $s->id }})"
                        class="text-xs font-semibold px-3 py-1 rounded-full transition
                        {{ $s->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                        {{ $s->is_active ? 'Aktif' : 'Pasif' }}
                    </button>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button wire:click="openForm({{ $s->id }})" class="text-gray-400 hover:text-cyan-600 transition p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Bu hizmeti silmek istediğinize emin misiniz?"
                            class="text-gray-400 hover:text-red-500 transition p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-4 py-12 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="font-medium">Henüz hizmet eklenmedi</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-5">{{ $services->links() }}</div>

</div>
