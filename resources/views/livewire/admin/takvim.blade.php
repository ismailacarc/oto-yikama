<div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Takvim</h1>
            <p class="text-sm text-gray-500 mt-0.5">Planlanan iş emirleri</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            {{-- Personel filtre --}}
            <select wire:model.live="staffFilter" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 bg-white">
                <option value="0">Tüm Personel</option>
                @foreach($staffList as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
            <a href="/admin/is-emirleri" class="flex items-center gap-1.5 bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                İş Emri
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Takvim Sol (2/3) ────────────────────────────── --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Ay navigasyonu --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <button wire:click="previousMonth" class="w-9 h-9 rounded-xl hover:bg-gray-100 flex items-center justify-center transition">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <div class="text-center">
                        <p class="text-base font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($currentMonth . '-01')->translatedFormat('F Y') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="goToday" class="text-xs font-semibold text-cyan-600 hover:text-cyan-500 px-3 py-1.5 rounded-lg hover:bg-cyan-50 transition">Bugün</button>
                        <button wire:click="nextMonth" class="w-9 h-9 rounded-xl hover:bg-gray-100 flex items-center justify-center transition">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Gün başlıkları --}}
                <div class="grid grid-cols-7 border-b border-gray-100">
                    @foreach(['Pzt','Sal','Çar','Per','Cum','Cmt','Paz'] as $d)
                    <div class="py-2 text-center text-xs font-semibold {{ $d === 'Paz' ? 'text-red-400' : 'text-gray-500' }}">{{ $d }}</div>
                    @endforeach
                </div>

                {{-- Takvim grid --}}
                <div class="grid grid-cols-7">
                    @php
                        $today        = now()->format('Y-m-d');
                        $currentMon   = \Carbon\Carbon::parse($currentMonth . '-01')->format('Y-m');
                    @endphp

                    @foreach($days as $day)
                    @php
                        $dayStr     = $day->format('Y-m-d');
                        $dayMon     = $day->format('Y-m');
                        $isToday    = $dayStr === $today;
                        $isSelected = $dayStr === $selectedDay;
                        $isOtherMon = $dayMon !== $currentMon;
                        $isSunday   = $day->dayOfWeek === 0;
                        $dayOrds    = $ordersByDay[$dayStr] ?? collect();
                    @endphp
                    <div wire:click="selectDay('{{ $dayStr }}')"
                        class="min-h-[80px] p-1.5 border-b border-r border-gray-50 cursor-pointer transition
                            {{ $isSelected ? 'bg-cyan-50' : 'hover:bg-slate-50' }}
                            {{ $isOtherMon ? 'opacity-40' : '' }}">

                        {{-- Gün numarası --}}
                        <div class="flex justify-end mb-1">
                            <span class="w-6 h-6 text-xs font-bold flex items-center justify-center rounded-full
                                {{ $isToday ? 'bg-cyan-600 text-white' : ($isSunday ? 'text-red-400' : 'text-gray-700') }}">
                                {{ $day->day }}
                            </span>
                        </div>

                        {{-- İş emirleri (maks 3) --}}
                        <div class="space-y-0.5">
                            @foreach($dayOrds->take(3) as $order)
                            <div class="text-[10px] font-medium px-1.5 py-0.5 rounded truncate leading-tight
                                {{ $order->status === 'bekleyen' ? 'bg-amber-100 text-amber-800' :
                                   ($order->status === 'devam_eden' ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800') }}">
                                @if($order->scheduled_at){{ $order->scheduled_at->format('H:i') }} · @endif{{ $order->customer->name }}
                            </div>
                            @endforeach
                            @if($dayOrds->count() > 3)
                            <div class="text-[10px] text-gray-400 font-medium px-1.5">+{{ $dayOrds->count() - 3 }} daha</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Renk açıklaması --}}
                <div class="flex items-center gap-4 px-5 py-3 border-t border-gray-100 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-amber-200"></span>Bekleyen</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-blue-200"></span>Devam Eden</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-emerald-200"></span>Tamamlandı</span>
                </div>
            </div>
        </div>

        {{-- ── Gün Detay Sağ (1/3) ───────────────────────── --}}
        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 sticky top-6">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-900">
                            {{ $selectedDay ? \Carbon\Carbon::parse($selectedDay)->translatedFormat('d F Y, l') : 'Gün seçin' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $dayOrders->count() }} planlı iş emri</p>
                    </div>
                    @if($selectedDay === now()->format('Y-m-d'))
                    <span class="text-xs bg-cyan-100 text-cyan-700 font-semibold px-2 py-1 rounded-lg">Bugün</span>
                    @endif
                </div>

                <div class="p-4 max-h-[500px] overflow-y-auto space-y-3">

                    @forelse($dayOrders as $order)
                    <a href="/admin/is-emirleri" class="block p-3 rounded-xl border border-gray-100 hover:border-cyan-200 hover:bg-cyan-50/30 transition group">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2">
                                @if($order->scheduled_at)
                                <span class="text-sm font-bold text-cyan-700 tabular-nums">{{ $order->scheduled_at->format('H:i') }}</span>
                                @endif
                                <span class="text-xs font-mono text-gray-400">{{ $order->order_no }}</span>
                            </div>
                            @if($order->status === 'bekleyen')
                                <span class="text-[10px] bg-amber-100 text-amber-700 font-bold px-1.5 py-0.5 rounded flex-shrink-0">Bekleyen</span>
                            @elseif($order->status === 'devam_eden')
                                <span class="text-[10px] bg-blue-100 text-blue-700 font-bold px-1.5 py-0.5 rounded flex-shrink-0">Devam</span>
                            @else
                                <span class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-1.5 py-0.5 rounded flex-shrink-0">Tamam</span>
                            @endif
                        </div>
                        <p class="text-sm font-semibold text-gray-900">{{ $order->customer->name }}</p>
                        @if($order->vehicle)
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $order->vehicle->brand?->name }} {{ $order->vehicle->model?->name }}
                            @if($order->vehicle->plate)
                            · <span class="font-mono">{{ $order->vehicle->plate }}</span>
                            @endif
                        </p>
                        @endif
                        @if($order->staff)
                        <p class="text-xs text-gray-400 mt-1">{{ $order->staff->name }}</p>
                        @endif
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs font-bold text-gray-700">{{ number_format($order->effective_total, 0, ',', '.') }} ₺</span>
                            @if($order->remaining_amount > 0)
                            <span class="text-[10px] text-orange-500">{{ number_format($order->remaining_amount, 0, ',', '.') }} ₺ kalan</span>
                            @else
                            <span class="text-[10px] text-emerald-600 font-medium">Tahsil edildi</span>
                            @endif
                        </div>
                    </a>
                    @empty
                    @if(!$todayUnscheduled->count())
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-gray-400 text-sm">Bu gün için planlı iş emri yok</p>
                    </div>
                    @endif
                    @endforelse

                    {{-- Tarihi olmayan ama bugün açılan emirler --}}
                    @if($todayUnscheduled->count() > 0)
                    <div class="pt-2">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Tarifsiz (bugün açılan)</p>
                        @foreach($todayUnscheduled as $order)
                        <a href="/admin/is-emirleri" class="block p-3 rounded-xl border border-dashed border-gray-200 hover:border-slate-300 hover:bg-slate-50 transition mb-2">
                            <p class="text-xs font-mono text-gray-400">{{ $order->order_no }}</p>
                            <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $order->customer->name }}</p>
                            @if($order->vehicle)
                            <p class="text-xs text-gray-500">{{ $order->vehicle->brand?->name }}</p>
                            @endif
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
