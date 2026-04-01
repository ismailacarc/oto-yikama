<div wire:poll.30s>

    {{-- ── Header ─────────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-slate-800 via-slate-700 to-cyan-800 rounded-2xl p-6 md:p-8 mb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-400/10 rounded-full -mr-24 -mt-24 pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/3 w-48 h-48 bg-white/5 rounded-full -mb-20 pointer-events-none"></div>
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-cyan-300 text-sm font-medium mb-1">{{ now()->translatedFormat('d F Y, l') }}</p>
                <h1 class="text-2xl md:text-3xl font-bold">Hoşgeldin, {{ auth()->user()->name ?? 'Admin' }}</h1>
                <p class="text-slate-300 mt-1 text-sm">UFK Garage kontrol paneli</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="/admin/is-emirleri" class="bg-cyan-500 hover:bg-cyan-400 px-5 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2 shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Yeni İş Emri
                </a>
                <a href="/admin/personel" class="bg-white/15 hover:bg-white/25 px-5 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Personel
                </a>
            </div>
        </div>

        @if($pendingCount > 0)
        <div class="relative mt-4 bg-amber-400/20 border border-amber-400/30 rounded-xl px-4 py-3 flex items-center gap-3">
            <div class="w-8 h-8 bg-amber-400 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="font-semibold">{{ $pendingCount }} iş emri bekliyor</span>
            <a href="/admin/is-emirleri" class="text-amber-300 hover:text-amber-200 underline ml-1 text-sm">İncele</a>
        </div>
        @endif
    </div>

    {{-- ── KPI Cards ───────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-cyan-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-xs font-medium text-cyan-600 bg-cyan-50 px-2 py-1 rounded-lg">Bugün</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $todayOrderCount }}</p>
            <p class="text-sm text-gray-500 mt-1">iş emri</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Bu ay</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($monthRevenue, 0, ',', '.') }} <span class="text-lg text-gray-500">₺</span></p>
            <p class="text-sm text-gray-500 mt-1">tahsilat</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">Bekleyen</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $pendingCount }}</p>
            <p class="text-sm text-gray-500 mt-1">iş emri</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">Cari</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($cariAlacak, 0, ',', '.') }} <span class="text-lg text-gray-500">₺</span></p>
            <p class="text-sm text-gray-500 mt-1">alacak</p>
        </div>
    </div>

    {{-- ── İş Durumu + Aktif Personel ─────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- İş Durumu Özeti --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-bold text-gray-900 mb-4">İş Durumu Özeti</h2>
            <div class="space-y-3">
                <a href="/admin/is-emirleri" class="flex items-center justify-between p-3 bg-amber-50 rounded-xl hover:bg-amber-100 transition group">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                        <span class="text-sm font-medium text-amber-900">Bekleyen</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-bold text-amber-700">{{ $pendingCount }}</span>
                        <svg class="w-4 h-4 text-amber-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
                <a href="/admin/is-emirleri" class="flex items-center justify-between p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition group">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-blue-500 animate-pulse"></span>
                        <span class="text-sm font-medium text-blue-900">Devam Eden</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-bold text-blue-700">{{ $inProgressCount }}</span>
                        <svg class="w-4 h-4 text-blue-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
                <a href="/admin/is-emirleri" class="flex items-center justify-between p-3 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition group">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="text-sm font-medium text-emerald-900">Tamamlandı (Bu ay)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-bold text-emerald-700">{{ $doneCount }}</span>
                        <svg class="w-4 h-4 text-emerald-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Toplam Müşteri</span>
                    <span class="font-bold text-gray-800">{{ $totalCustomers }}</span>
                </div>
                @if($newCustomersMonth > 0)
                <p class="text-xs text-emerald-600 font-medium mt-1">+{{ $newCustomersMonth }} bu ay yeni</p>
                @endif
            </div>
        </div>

        {{-- Aktif Personel --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-gray-900">Aktif Personel</h2>
                <a href="/admin/personel" class="text-sm text-cyan-600 hover:underline">Tümünü gör</a>
            </div>

            @if($activeTimers->count() > 0)
            <div class="space-y-3">
                @foreach($activeTimers as $timer)
                <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl">
                    <div class="relative flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-slate-500 rounded-xl flex items-center justify-center text-white font-bold text-sm">
                            {{ mb_substr($timer->staff->name ?? '?', 0, 1) }}
                        </div>
                        <span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 text-sm">{{ $timer->staff->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ $timer->workOrder?->order_no }} ·
                            {{ $timer->workOrder?->vehicle?->brand?->name }} {{ $timer->workOrder?->vehicle?->model?->name }}
                            @if($timer->workOrder?->vehicle?->plate)
                            · {{ $timer->workOrder->vehicle->plate }}
                            @endif
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs text-emerald-600 font-semibold">{{ $timer->started_at->diffForHumans(['parts' => 1, 'options' => \Carbon\CarbonInterface::JUST_NOW]) }}</p>
                        <p class="text-xs text-gray-400">başladı</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-8 text-center">
                <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <p class="text-gray-400 text-sm">Şu an aktif çalışan yok</p>
            </div>
            @endif
        </div>
    </div>

    {{-- ── Bu Ayki Ciro Grafiği + Ödeme Dağılımı ──────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- Daily bar chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-bold text-gray-900">Bu Ay Günlük Tahsilat</h2>
                    <p class="text-sm text-gray-500">{{ now()->translatedFormat('F Y') }}</p>
                </div>
                <span class="text-lg font-bold text-cyan-600">{{ number_format(array_sum(array_column($chartData, 'value')), 0, ',', '.') }} ₺</span>
            </div>
            <div class="flex items-end gap-1 h-32 overflow-x-auto pb-2">
                @foreach($chartData as $d)
                @php $pct = $chartMax > 0 ? max(4, ($d['value'] / $chartMax) * 100) : 4; @endphp
                <div class="flex flex-col items-center gap-1 flex-1 min-w-[14px]" title="{{ $d['label'] }}. gün: {{ number_format($d['value'], 0, ',', '.') }} ₺">
                    <div class="w-full rounded-t-sm {{ $d['value'] > 0 ? 'bg-gradient-to-t from-cyan-600 to-cyan-400' : 'bg-gray-100' }}" style="height: {{ $pct }}px;"></div>
                    @if($d['label'] % 5 === 0 || $d['label'] === 1)
                    <span class="text-[9px] text-gray-400">{{ $d['label'] }}</span>
                    @else
                    <span class="text-[9px] text-transparent">·</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Ödeme Tipi Dağılımı --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-bold text-gray-900 mb-1">Ödeme Dağılımı</h2>
            <p class="text-sm text-gray-500 mb-5">Bu ay tahsilat türleri</p>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700">Nakit</span>
                        <span class="font-bold text-emerald-700">{{ number_format($nakitTotal, 0, ',', '.') }} ₺</span>
                    </div>
                    <div class="bg-gray-100 rounded-full h-2.5">
                        <div class="bg-emerald-500 h-2.5 rounded-full" style="width: {{ round(($nakitTotal / $payTotal) * 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">%{{ round(($nakitTotal / $payTotal) * 100) }}</p>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700">Kart</span>
                        <span class="font-bold text-blue-700">{{ number_format($kartTotal, 0, ',', '.') }} ₺</span>
                    </div>
                    <div class="bg-gray-100 rounded-full h-2.5">
                        <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ round(($kartTotal / $payTotal) * 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">%{{ round(($kartTotal / $payTotal) * 100) }}</p>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700">Havale / EFT</span>
                        <span class="font-bold text-purple-700">{{ number_format($havaleTotal, 0, ',', '.') }} ₺</span>
                    </div>
                    <div class="bg-gray-100 rounded-full h-2.5">
                        <div class="bg-purple-500 h-2.5 rounded-full" style="width: {{ round(($havaleTotal / $payTotal) * 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">%{{ round(($havaleTotal / $payTotal) * 100) }}</p>
                </div>
            </div>

            <a href="/admin/raporlar" class="mt-5 flex items-center justify-center gap-2 w-full bg-slate-50 hover:bg-slate-100 text-slate-700 text-sm font-medium py-2.5 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Detaylı Raporlar
            </a>
        </div>
    </div>

    {{-- ── Günlük İş Emirleri ──────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8">
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-gray-900">Günlük İş Emirleri</h2>
                <p class="text-sm text-gray-500">{{ $date->translatedFormat('d F Y, l') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <input
                    type="date"
                    wire:model.live="selectedDate"
                    class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400"
                />
                <span class="bg-cyan-100 text-cyan-700 px-3 py-1.5 rounded-xl text-sm font-bold">{{ $dailyOrders->count() }} emri</span>
            </div>
        </div>

        @if($dailyOrders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-left">
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">No</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Müşteri</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Araç</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Personel</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Durum</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">Tutar</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide text-right">PDF</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($dailyOrders as $order)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-5 py-3 font-mono font-semibold text-cyan-700 text-xs">{{ $order->order_no }}</td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-900">{{ $order->customer->name }}</p>
                            <p class="text-xs text-gray-400">{{ $order->customer->phone }}</p>
                        </td>
                        <td class="px-5 py-3 text-gray-600">
                            @if($order->vehicle)
                            {{ $order->vehicle->brand?->name }} {{ $order->vehicle->model?->name }}
                            @if($order->vehicle->plate)
                            <span class="ml-1 bg-gray-100 text-gray-600 text-xs px-1.5 py-0.5 rounded font-mono">{{ $order->vehicle->plate }}</span>
                            @endif
                            @else
                            <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-600 text-xs">{{ $order->staff?->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            @if($order->status === 'bekleyen')
                                <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-1 rounded-lg">Bekleyen</span>
                            @elseif($order->status === 'devam_eden')
                                <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded-lg">Devam Eden</span>
                            @else
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-2 py-1 rounded-lg">Tamamlandı</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <p class="font-bold text-gray-900">{{ number_format($order->effective_total, 0, ',', '.') }} ₺</p>
                            @if($order->remaining_amount > 0)
                            <p class="text-xs text-orange-500">{{ number_format($order->remaining_amount, 0, ',', '.') }} ₺ kalan</p>
                            @else
                            <p class="text-xs text-emerald-600 font-medium">Tahsil edildi</p>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('pdf.is-emri', $order->id) }}" target="_blank"
                               class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-cyan-600 hover:bg-cyan-50 px-2 py-1 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                PDF
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <p class="text-gray-400 text-sm">Bu gün için iş emri yok</p>
            <a href="/admin/is-emirleri" class="text-cyan-600 text-sm hover:underline mt-1">İş emri oluştur</a>
        </div>
        @endif
    </div>

    {{-- ── Düşük Stok + PDF Rapor ──────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Düşük Stok Uyarıları --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-gray-900">Stok Uyarıları</h2>
                <a href="/admin/urunler" class="text-sm text-cyan-600 hover:underline">Stok Yönetimi</a>
            </div>

            @if($lowStock->count() > 0)
            <div class="space-y-2">
                @foreach($lowStock as $product)
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl border border-red-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-red-900">{{ $product->name }}</p>
                            <p class="text-xs text-red-500">Min: {{ $product->min_stock_alert+0 }} {{ $product->unit }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-red-700">{{ $product->stock_quantity+0 }}</p>
                        <p class="text-xs text-red-500">{{ $product->unit }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-8">
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-emerald-700 font-medium text-sm">Tüm stoklar yeterli</p>
            </div>
            @endif
        </div>

        {{-- PDF Rapor İndir --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-bold text-gray-900 mb-1">PDF Rapor İndir</h2>
            <p class="text-sm text-gray-500 mb-5">Seçilen tarihe ait kapsamlı raporu indirin</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Başlangıç Tarihi</label>
                    <input type="date" wire:model="reportFrom"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Bitiş Tarihi</label>
                    <input type="date" wire:model="reportTo"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
                <a
                    href="{{ route('pdf.rapor', ['from' => $reportFrom, 'to' => $reportTo]) }}"
                    target="_blank"
                    class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-cyan-600 to-slate-700 hover:from-cyan-500 hover:to-slate-600 text-white font-semibold py-3 rounded-xl transition shadow-sm"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF İndir
                </a>
                <p class="text-xs text-gray-400 text-center">İş emirleri, tahsilat ve stok özeti</p>
            </div>
        </div>
    </div>

</div>
