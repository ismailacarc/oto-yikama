<div>
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mali Bilgiler</h1>
            <p class="text-gray-500">Seçilen tarih aralığı için detaylı gelir özeti</p>
        </div>
        <div class="flex items-center gap-3">
            <div>
                <label class="block text-xs text-gray-500">Başlangıç</label>
                <input type="date" wire:model="dateFrom" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500">Bitiş</label>
                <input type="date" wire:model="dateTo" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <button wire:click="filter" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700 mt-4">Uygula</button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Toplam gelir</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($totalRevenue, 0, ',', '.') }} ₺</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Gün sayısı</p>
            <p class="text-2xl font-bold text-gray-900">{{ $dayCount }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Günlük ortalama</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($dailyAverage, 0, ',', '.') }} ₺</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Günlük gelir tablosu</h2>
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2 text-sm text-gray-500">Tarih</th>
                        <th class="text-center py-2 text-sm text-gray-500">Randevu sayısı</th>
                        <th class="text-right py-2 text-sm text-gray-500">Toplam gelir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyRevenue as $date => $data)
                    <tr class="border-b">
                        <td class="py-2 text-sm">{{ $date }}</td>
                        <td class="py-2 text-sm text-center">{{ $data['count'] }}</td>
                        <td class="py-2 text-sm text-right">{{ number_format($data['total'], 0, ',', '.') }} ₺</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">İşlem bazlı gelir</h2>
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2 text-sm text-gray-500">İşlem</th>
                        <th class="text-center py-2 text-sm text-gray-500">Randevu adedi</th>
                        <th class="text-right py-2 text-sm text-gray-500">Gelir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviceRevenue as $service)
                    <tr class="border-b">
                        <td class="py-2 text-sm">{{ $service->name }}</td>
                        <td class="py-2 text-sm text-center">{{ $service->count }}</td>
                        <td class="py-2 text-sm text-right">{{ number_format($service->total, 0, ',', '.') }} ₺</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-2">Saat / gün bazlı yoğunluk (ısı haritası)</h2>
        <p class="text-sm text-gray-500 mb-4">Seçilen tarih aralığında hangi gün ve saatlerde daha çok randevu alındığını gösterir.</p>
        @php
            $dayNames = ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'];
            $hourSlots = ['09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20'];
            $maxVal = max(1, !empty($heatmapData) ? max($heatmapData) : 1);
        @endphp
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="p-2 text-xs text-gray-500"></th>
                        @foreach($hourSlots as $h)
                            <th class="p-2 text-xs text-gray-500">{{ $h }}:00</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($dayNames as $idx => $dayName)
                    <tr>
                        <td class="p-2 text-xs text-gray-500 font-medium">{{ $dayName }}</td>
                        @foreach($hourSlots as $h)
                            @php
                                $key = ($idx + 1) . '-' . $h;
                                $val = $heatmapData[$key] ?? 0;
                                $intensity = $val > 0 ? max(0.15, $val / $maxVal) : 0;
                            @endphp
                            <td class="p-1">
                                <div class="w-full h-8 rounded" style="background-color: rgba(147, 51, 234, {{ $intensity }});">
                                    @if($val > 0)
                                        <span class="flex items-center justify-center h-full text-xs font-medium {{ $intensity > 0.5 ? 'text-white' : 'text-purple-800' }}">{{ $val }}</span>
                                    @endif
                                </div>
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
