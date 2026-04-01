<div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background:linear-gradient(135deg,#0d2137,#0891b2)">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 1h6l2-1z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 6h3l3 5v5h-2"/>
                </svg>
            </div>
        </div>
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-1">UFK Garage</h2>
        <p class="text-center text-gray-400 mb-8">Yönetim Paneline Giriş</p>

        <form wire:submit="login">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">E-posta</label>
                <input type="email" wire:model="email" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="admin@example.com">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Şifre</label>
                <input type="password" wire:model="password" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="••••••••">
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full text-white py-2.5 px-4 rounded-xl transition font-semibold shadow-lg" style="background:linear-gradient(135deg,#0d2137,#0891b2)">
                Giriş Yap
            </button>
        </form>
    </div>
</div>