<div>

{{-- Başlık --}}
<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-900">Ödemeler & Cari</h1>
    <p class="text-sm text-gray-500 mt-0.5">Ödeme takibi ve cari hesaplar</p>
</div>

{{-- Özet kartlar --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
    @foreach(['nakit'=>['Nakit','green'], 'kart'=>['Kart','blue'], 'havale'=>['Havale','purple']] as $type=>[$label,$color])
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ $label }}</p>
        <p class="text-xl font-black text-gray-800">{{ number_format($totalByType[$type] ?? 0, 0, ',', '.') }} ₺</p>
    </div>
    @endforeach
    <div class="bg-amber-50 border border-amber-200 rounded-2xl shadow-sm p-4">
        <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">Cari Alacak</p>
        <p class="text-xl font-black text-amber-700">{{ number_format($totalCari, 0, ',', '.') }} ₺</p>
    </div>
</div>

{{-- Sekmeler --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-5 overflow-hidden">
    <div class="flex">
        <button wire:click="$set('activeTab','odemeler')"
            class="flex-1 py-3.5 text-sm font-semibold transition border-b-2 {{ $activeTab === 'odemeler' ? 'border-cyan-500 text-cyan-700 bg-cyan-50' : 'border-transparent text-gray-500 hover:bg-gray-50' }}">
            Ödeme Geçmişi
        </button>
        <button wire:click="$set('activeTab','cari')"
            class="flex-1 py-3.5 text-sm font-semibold transition border-b-2 {{ $activeTab === 'cari' ? 'border-amber-500 text-amber-700 bg-amber-50' : 'border-transparent text-gray-500 hover:bg-gray-50' }}">
            Cari Hesaplar
        </button>
    </div>
</div>

{{-- Arama + Filtre --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3 mb-5 flex gap-2">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Müşteri ara..."
            class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
    </div>
    @if($activeTab === 'odemeler')
    <select wire:model.live="filterType" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500 outline-none bg-white">
        <option value="">Tümü</option>
        <option value="nakit">Nakit</option>
        <option value="kart">Kart</option>
        <option value="havale">Havale</option>
    </select>
    @endif
</div>

{{-- Ödeme Geçmişi --}}
@if($activeTab === 'odemeler')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Müşteri</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden sm:table-cell">İş Emri</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Yöntem</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Tutar</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase hidden sm:table-cell">Tarih</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($payments as $p)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $p->customer?->name }}</td>
                <td class="px-4 py-3 text-gray-500 font-mono text-xs hidden sm:table-cell">{{ $p->workOrder?->order_no }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="text-xs font-semibold px-2 py-1 rounded-full
                        {{ $p->payment_type === 'nakit' ? 'bg-green-100 text-green-700' : ($p->payment_type === 'kart' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                        {{ ucfirst($p->payment_type) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right font-bold text-gray-900">{{ number_format($p->amount,2,',','.') }} ₺</td>
                <td class="px-4 py-3 text-right text-gray-400 text-xs hidden sm:table-cell">{{ $p->paid_at->format('d.m.Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400">Henüz ödeme kaydı yok.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $payments->links() }}</div>
@endif

{{-- Cari Hesaplar --}}
@if($activeTab === 'cari')
<div class="space-y-3">
    @forelse($cariList as $customer)
    @php $debt = ($customer->total_sum ?? 0) - ($customer->discount_sum ?? 0) - ($customer->paid_sum ?? 0); @endphp
    <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold text-sm"
             style="background: linear-gradient(135deg, #0891b2, #0d2137)">
            {{ mb_substr($customer->name, 0, 1) }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-semibold text-gray-900">{{ $customer->name }}</p>
            <p class="text-sm text-gray-500">{{ $customer->phone }}</p>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-400">Cari Alacak</p>
            <p class="font-black text-amber-700 text-lg">{{ number_format($debt,2,',','.') }} ₺</p>
        </div>
        <a href="{{ url('/admin/uyeler') }}" class="text-gray-400 hover:text-cyan-600 p-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="font-medium">Tüm ödemeler tam — cari alacak yok</p>
    </div>
    @endforelse
    @if($cariList instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-4">{{ $cariList->links() }}</div>
    @endif
</div>
@endif

</div>
