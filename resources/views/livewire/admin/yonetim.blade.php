<div>
    <h1 class="text-2xl font-bold text-gray-900">Yönetim</h1>
    <p class="text-gray-500 mb-6">Admin panele erişebilecek kullanıcıları ve yetkilerini yönetin.</p>

    @if(session('success'))
        <div class="bg-purple-100 text-purple-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Yeni kullanıcı ekle</h2>
            <form wire:submit="createUser" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad</label>
                    <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-posta</label>
                    <input type="email" wire:model="email" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Şifre</label>
                        <input type="password" wire:model="password" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Şifre (tekrar)</label>
                        <input type="password" wire:model="password_confirmation" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select wire:model="role" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="personel">Personel (sadece randevular)</option>
                        <option value="super">Süper Admin</option>
                    </select>
                </div>
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">Kullanıcı oluştur</button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Mevcut kullanıcılar</h2>
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 text-left text-sm text-gray-500">Ad Soyad</th>
                        <th class="py-2 text-left text-sm text-gray-500">E-posta</th>
                        <th class="py-2 text-left text-sm text-gray-500">Rol</th>
                        <th class="py-2 text-right text-sm text-gray-500"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b">
                        <td class="py-3 text-sm font-medium">{{ $user->name }}</td>
                        <td class="py-3 text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="py-3">
                            <select wire:change="updateRole({{ $user->id }}, $event.target.value)" class="text-sm border border-gray-300 rounded px-2 py-1">
                                <option value="super" {{ $user->role === 'super' ? 'selected' : '' }}>Süper</option>
                                <option value="personel" {{ $user->role === 'personel' ? 'selected' : '' }}>Personel</option>
                            </select>
                        </td>
                        <td class="py-3 text-right">
                            @if($user->id !== auth()->id())
                                <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Bu kullanıcıyı silmek istediğinize emin misiniz?" class="text-red-600 hover:underline text-sm">Sil</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
