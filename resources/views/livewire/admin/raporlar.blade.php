<div>

{{-- ── BAŞLIK + PERİYOT SEÇİCİ ────────────────────────────────── --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Raporlar</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} — {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
        </p>
    </div>
    {{-- Periyot butonları --}}
    <div class="flex flex-wrap gap-1.5">
        @foreach(['today'=>'Bugün','week'=>'Bu Hafta','month'=>'Bu Ay','year'=>'Bu Yıl','custom'=>'Özel'] as $val=>$label)
        <button wire:click="$set('period','{{ $val }}')"
            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
            {{ $period === $val ? 'bg-cyan-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-cyan-400 hover:text-cyan-600' }}">
            {{ $label }}
        </button>
        @endforeach
    </div>
</div>

{{-- Özel tarih aralığı --}}
@if($period === 'custom')
<div class="bg-white border border-cyan-200 rounded-2xl p-4 mb-6 flex flex-col sm:flex-row gap-3 items-end">
    <div class="flex-1">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Başlangıç</label>
        <input type="date" wire:model.live="dateFrom" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
    </div>
    <div class="flex-1">
        <label class="block text-xs font-semibold text-gray-500 mb-1">Bitiş</label>
        <input type="date" wire:model.live="dateTo" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500 outline-none">
    </div>
</div>
@endif

{{-- ── ÜST METRİK KARTLARI ───────────────────────────────────── --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    <div class="bg-gradient-to-br from-cyan-600 to-cyan-800 rounded-2xl p-4 text-white">
        <p class="text-xs font-semibold opacity-75 uppercase tracking-wider">Toplam Ciro</p>
        <p class="text-2xl font-black mt-1">{{ number_format($totalRevenue,0,',','.') }} ₺</p>
        <p class="text-xs opacity-60 mt-1">ödeme alınan</p>
    </div>
    <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">İş Emri</p>
        <p class="text-2xl font-black text-gray-900 mt-1">{{ $totalOrders }}</p>
        <p class="text-xs text-green-600 mt-1">{{ $completedOrders }} tamamlandı</p>
    </div>
    <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok Değeri</p>
        <p class="text-2xl font-black text-gray-900 mt-1">{{ number_format($stockValue,0,',','.') }} ₺</p>
        <p class="text-xs {{ $lowStock->count() > 0 ? 'text-red-500' : 'text-gray-400' }} mt-1">
            {{ $lowStock->count() > 0 ? $lowStock->count().' düşük stok' : 'stok sağlıklı' }}
        </p>
    </div>
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
        <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Cari Alacak</p>
        <p class="text-2xl font-black text-amber-700 mt-1">{{ number_format($pendingRevenue,0,',','.') }} ₺</p>
        <p class="text-xs text-amber-500 mt-1">tahsilat bekliyor</p>
    </div>
</div>

{{-- ── CİRO GRAFİĞİ + ÖDEME DAĞILIMI ──────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

    {{-- Günlük ciro bar grafiği --}}
    <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <p class="text-sm font-bold text-gray-800 mb-4">Günlük Ciro Grafiği</p>
        @if($chartData->sum() > 0)
        <div class="flex items-end gap-1 h-32 overflow-x-auto pb-1">
            @foreach($chartData as $day => $amount)
            @php $pct = $chartMax > 0 ? ($amount / $chartMax) * 100 : 0; @endphp
            <div class="flex flex-col items-center gap-1 flex-shrink-0" style="min-width: {{ max(12, 96/max(count($chartData),1)) }}px">
                <span class="text-xs text-gray-400 font-medium" style="font-size:9px">
                    {{ $amount > 0 ? number_format($amount/1000,1).'k' : '' }}
                </span>
                <div class="w-full rounded-t-md transition-all"
                     style="height:{{ max(2, $pct * 1.1) }}px; background: {{ $amount > 0 ? 'linear-gradient(to top,#0891b2,#06b6d4)' : '#f1f5f9' }}">
                </div>
                <span class="text-gray-400" style="font-size:8px; writing-mode:vertical-lr; transform:rotate(180deg)">
                    {{ \Carbon\Carbon::parse($day)->format('d/m') }}
                </span>
            </div>
            @endforeach
        </div>
        @else
        <div class="h-32 flex items-center justify-center text-gray-400 text-sm">Bu dönemde ödeme kaydı yok.</div>
        @endif
    </div>

    {{-- Ödeme yöntemi dağılımı --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <p class="text-sm font-bold text-gray-800 mb-4">Ödeme Dağılımı</p>
        <div class="space-y-4">
            @foreach(['nakit'=>['Nakit','#10b981'], 'kart'=>['Kart','#3b82f6'], 'havale'=>['Havale','#8b5cf6']] as $type=>[$label,$color])
            @php
                $amount = $revenueByType[$type] ?? 0;
                $pct = $totalRevenue > 0 ? round(($amount / $totalRevenue) * 100) : 0;
            @endphp
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="font-semibold text-gray-700">{{ $label }}</span>
                    <span class="text-gray-500">{{ number_format($amount,0,',','.') }} ₺ <span class="text-gray-400">({{ $pct }}%)</span></span>
                </div>
                <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500"
                         style="width:{{ $pct }}%; background-color:{{ $color }}"></div>
                </div>
            </div>
            @endforeach
        </div>
        @if($totalRevenue > 0)
        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between text-sm">
            <span class="font-semibold text-gray-600">Toplam</span>
            <span class="font-black text-cyan-700">{{ number_format($totalRevenue,2,',','.') }} ₺</span>
        </div>
        @endif
    </div>
</div>

{{-- ── EN ÇOK YAPILAN HİZMET/ÜRÜN + PERSONEL ───────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

    {{-- Top hizmetler --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <p class="text-sm font-bold text-gray-800 mb-4">En Çok Yapılan İşlemler</p>
        @if($topItems->count())
        @php $maxRev = $topItems->max('revenue') ?: 1; @endphp
        <div class="space-y-3">
            @foreach($topItems as $item)
            @php $pct = ($item->revenue / $maxRev) * 100; @endphp
            <div>
                <div class="flex items-center justify-between text-xs mb-1">
                    <div class="flex items-center gap-1.5">
                        <span class="px-1.5 py-0.5 rounded text-xs font-medium {{ $item->type === 'service' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                            {{ $item->type === 'service' ? 'Hizmet' : 'Ürün' }}
                        </span>
                        <span class="font-semibold text-gray-800 truncate max-w-32">{{ $item->name }}</span>
                        <span class="text-gray-400">×{{ $item->cnt }}</span>
                    </div>
                    <span class="font-bold text-cyan-700 flex-shrink-0">{{ number_format($item->revenue,0,',','.') }} ₺</span>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-cyan-500 to-cyan-700"
                         style="width:{{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-gray-400 text-center py-6">Bu dönemde işlem yok.</p>
        @endif
    </div>

    {{-- Personel verimliliği --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <p class="text-sm font-bold text-gray-800 mb-4">Personel Verimliliği</p>
        @if($staffStats->count())
        @php $maxMin = $staffStats->max('total_minutes') ?: 1; @endphp
        <div class="space-y-4">
            @foreach($staffStats as $stat)
            @php
                $hrs  = intdiv($stat->total_minutes, 60);
                $mins = $stat->total_minutes % 60;
                $pct  = ($stat->total_minutes / $maxMin) * 100;
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                             style="background: linear-gradient(135deg,#0891b2,#0d2137)">
                            {{ mb_substr($stat->staff?->name ?? '?', 0, 1) }}
                        </div>
                        <span class="text-sm font-semibold text-gray-800">{{ $stat->staff?->name }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-cyan-700">{{ $hrs > 0 ? $hrs.'s ' : '' }}{{ $mins }}dk</span>
                        <span class="text-xs text-gray-400 ml-1">/ {{ $stat->job_count }} iş</span>
                    </div>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-cyan-500 to-teal-600 transition-all"
                         style="width:{{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-gray-400 text-center py-6">Bu dönemde personel çalışma kaydı yok.</p>
        @endif
    </div>
</div>

{{-- ── STOK DURUMU ────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    {{-- Düşük stok uyarıları --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-bold text-gray-800">Stok Uyarıları</p>
            @if($lowStock->count() > 0)
            <span class="text-xs font-bold bg-red-100 text-red-600 px-2.5 py-1 rounded-full">
                {{ $lowStock->count() }} ürün kritik
            </span>
            @endif
        </div>
        @if($lowStock->count())
        <div class="space-y-2">
            @foreach($lowStock as $p)
            @php $pct = $p->min_stock_alert > 0 ? min(100, ($p->stock_quantity / $p->min_stock_alert) * 100) : 100; @endphp
            <div class="flex items-center gap-3 bg-red-50 border border-red-100 rounded-xl px-3 py-2.5">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $p->name }}</p>
                    <p class="text-xs text-red-600">
                        {{ $p->stock_quantity+0 }} {{ $p->unit }} kaldı
                        <span class="text-gray-400">/ min {{ $p->min_stock_alert+0 }} {{ $p->unit }}</span>
                    </p>
                </div>
                <div class="w-16 text-right">
                    <span class="text-xs font-black {{ $p->stock_quantity <= 0 ? 'text-red-700' : 'text-orange-600' }}">
                        {{ $p->stock_quantity <= 0 ? 'TÜKENDİ' : '⚠ Kritik' }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-4 py-3">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-semibold text-green-700">Tüm ürünler yeterli stokta</p>
        </div>
        @endif
    </div>

    {{-- Son stok hareketleri --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <p class="text-sm font-bold text-gray-800 mb-4">Son Stok Hareketleri</p>
        @if($recentMoves->count())
        <div class="space-y-2">
            @foreach($recentMoves as $m)
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0
                    {{ $m->type === 'in' ? 'bg-green-100' : 'bg-red-100' }}">
                    <svg class="w-3.5 h-3.5 {{ $m->type === 'in' ? 'text-green-600' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $m->type === 'in' ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-800 truncate">{{ $m->product?->name }}</p>
                    <p class="text-xs text-gray-400">{{ $m->created_at->format('d.m H:i') }}</p>
                </div>
                <span class="text-xs font-bold {{ $m->type === 'in' ? 'text-green-600' : 'text-red-500' }} flex-shrink-0">
                    {{ $m->type === 'in' ? '+' : '-' }}{{ $m->quantity+0 }}
                </span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-gray-400 text-center py-6">Bu dönemde stok hareketi yok.</p>
        @endif
    </div>

</div>

</div>
