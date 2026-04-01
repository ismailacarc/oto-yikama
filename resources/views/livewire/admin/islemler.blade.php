<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">İşlemler</h1>
            <p class="text-gray-500">Randevuda seçilebilecek hizmetleri yönetin</p>
        </div>
        <button wire:click="create" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">+ Yeni işlem</button>
    </div>

    @if($showForm)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">{{ $editingId ? 'İşlem Düzenle' : 'Yeni İşlem' }}</h2>
        <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">İşlem adı</label>
                <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                <input type="text" wire:model="description" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Süre (dk)</label>
                <input type="number" wire:model="duration" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @error('duration') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fiyat (₺)</label>
                <input type="number" step="0.01" wire:model="price" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Onay</label>
                <select wire:model="approval_type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="manual">Onay gerekli</option>
                    <option value="auto">Otomatik onay</option>
                </select>
            </div>
            <div class="flex items-center gap-2 mt-6">
                <input type="checkbox" wire:model="is_active" id="is_active" class="rounded">
                <label for="is_active" class="text-sm text-gray-700">Aktif</label>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">Kaydet</button>
                <button type="button" wire:click="$set('showForm', false)" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">İptal</button>
            </div>
        </form>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İşlem adı</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Süre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fiyat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Onay</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">İşlem</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($services as $service)
                <tr>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $service->name }}</div>
                        @if($service->description)
                            <div class="text-sm text-gray-500">{{ $service->description }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $service->duration }} dk</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ number_format($service->price, 0, ',', '.') }} ₺</td>
                    <td class="px-6 py-4">
                        <span class="{{ $service->is_active ? 'text-green-600' : 'text-red-600' }} text-sm font-medium">
                            {{ $service->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $service->approval_type === 'manual' ? 'Onay gerekli' : 'Otomatik onay' }}</td>
                    <td class="px-6 py-4 text-right">
                        <button wire:click="edit({{ $service->id }})" class="text-blue-600 hover:underline text-sm mr-2">Düzenle</button>
                        <button wire:click="delete({{ $service->id }})" wire:confirm="Bu işlemi silmek istediğinize emin misiniz?" class="text-red-600 hover:underline text-sm">Sil</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
