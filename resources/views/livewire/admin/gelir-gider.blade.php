<div>

    {{-- ── Header ──────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gelir / Gider</h1>
            <p class="text-sm text-gray-500 mt-0.5">Finansal takip ve karlılık analizi</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            {{-- Dönem --}}
            @foreach(['today'=>'Bugün','week'=>'Hafta','month'=>'Ay','year'=>'Yıl','custom'=>'Özel'] as $key => $label)
            <button wire:click="$set('period','{{ $key }}')"
                class="px-3 py-1.5 text-xs font-semibold rounded-xl transition
                    {{ $period === $key ? 'bg-cyan-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Özel tarih --}}
    @if($period === 'custom')
    <div class="flex items-center gap-3 mb-5 flex-wrap">
        <input type="date" wire:model.live="dateFrom" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
        <span class="text-gray-400 text-sm">—</span>
        <input type="date" wire:model.live="dateTo"   class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
    </div>
    @endif

    {{-- ── Tab navigasyon ─────────────────────────────────────── --}}
    <div class="flex border-b border-gray-200 mb-6 overflow-x-auto">
        @foreach(['ozet'=>'Özet','giderler'=>'Giderler','gelirler'=>'Gelirler','kategoriler'=>'Kategoriler'] as $tab => $label)
        <button wire:click="$set('activeTab','{{ $tab }}')"
            class="px-5 py-3 text-sm font-semibold whitespace-nowrap border-b-2 transition
                {{ $activeTab === $tab ? 'border-cyan-600 text-cyan-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         ÖZET SEKMESİ
    ═══════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'ozet')

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Gelir</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalIncome, 2, ',', '.') }} ₺</p>
            <p class="text-xs text-gray-400 mt-1">Tahsilat + manuel</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                </div>
                <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-lg">Gider</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalExpense, 2, ',', '.') }} ₺</p>
            <p class="text-xs text-gray-400 mt-1">Tüm giderler</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 {{ $netProfit >= 0 ? 'bg-cyan-100' : 'bg-orange-100' }} rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 {{ $netProfit >= 0 ? 'text-cyan-600' : 'text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-medium {{ $netProfit >= 0 ? 'text-cyan-600 bg-cyan-50' : 'text-orange-600 bg-orange-50' }} px-2 py-1 rounded-lg">Net Kâr</span>
            </div>
            <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-gray-900' : 'text-red-600' }}">
                {{ $netProfit >= 0 ? '' : '-' }}{{ number_format(abs($netProfit), 2, ',', '.') }} ₺
            </p>
            <p class="text-xs text-gray-400 mt-1">Gelir - Gider</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-1 rounded-lg">Marj</span>
            </div>
            <p class="text-2xl font-bold {{ $margin >= 0 ? 'text-gray-900' : 'text-red-600' }}">%{{ $margin }}</p>
            <p class="text-xs text-gray-400 mt-1">Kâr marjı</p>
        </div>
    </div>

    {{-- Trend Grafik + Kategori Dağılımı --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Son 6 ay trend --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-bold text-gray-900 mb-5">Son 6 Ay Gelir / Gider</h2>
            <div class="flex items-end gap-3 h-40">
                @foreach($trend as $t)
                @php
                    $incH = $trendMax > 0 ? max(4, ($t['income']  / $trendMax) * 120) : 4;
                    $expH = $trendMax > 0 ? max(4, ($t['expense'] / $trendMax) * 120) : 4;
                @endphp
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full flex items-end gap-0.5 justify-center" style="height:120px">
                        <div class="flex-1 bg-gradient-to-t from-emerald-500 to-emerald-300 rounded-t" style="height:{{ $incH }}px" title="Gelir: {{ number_format($t['income'],0,',','.') }} ₺"></div>
                        <div class="flex-1 bg-gradient-to-t from-red-400 to-red-300 rounded-t" style="height:{{ $expH }}px" title="Gider: {{ number_format($t['expense'],0,',','.') }} ₺"></div>
                    </div>
                    <span class="text-[10px] text-gray-500 font-medium">{{ $t['label'] }}</span>
                </div>
                @endforeach
            </div>
            <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-emerald-400"></span>Gelir</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-red-400"></span>Gider</span>
            </div>
        </div>

        {{-- Kategori dağılımı --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-bold text-gray-900 mb-5">Gider Kategorileri</h2>
            @if($expByCat->count())
            @php $maxCat = $expByCat->first()->total; @endphp
            <div class="space-y-3">
                @foreach($expByCat as $row)
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-medium text-gray-700">{{ $row->expenseCategory?->name ?? 'Kategorisiz' }}</span>
                        <span class="font-bold text-gray-800">{{ number_format($row->total, 0, ',', '.') }} ₺</span>
                    </div>
                    <div class="bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full bg-gradient-to-r from-red-400 to-red-300"
                            style="width:{{ $maxCat > 0 ? round(($row->total/$maxCat)*100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-8">Bu dönemde gider yok</p>
            @endif
        </div>
    </div>

    @endif

    {{-- ═══════════════════════════════════════════════════════════
         GİDERLER SEKMESİ
    ═══════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'giderler')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div class="flex items-center gap-3 flex-wrap">
            <input type="text" wire:model.live.debounce.300ms="expSearch" placeholder="Ara..."
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 w-48">
            <select wire:model.live="expFilterCat" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 bg-white">
                <option value="">Tüm Kategoriler</option>
                @foreach($categories->where('is_active', true) as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button wire:click="newExpense" class="flex items-center gap-2 bg-red-500 hover:bg-red-400 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Gider Ekle
        </button>
    </div>

    {{-- Gider Formu --}}
    @if($showExpenseForm)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 mb-5">
        <h3 class="text-sm font-bold text-gray-900 mb-4">{{ $editingExpenseId ? 'Gider Düzenle' : 'Yeni Gider' }}</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Başlık <span class="text-red-500">*</span></label>
                <input type="text" wire:model="expTitle" placeholder="Örn: Ocak Kirası"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                @error('expTitle') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                <select wire:model="expCategoryId" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 bg-white">
                    <option value="">Seçiniz</option>
                    @foreach($categories->where('is_active', true) as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('expCategoryId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tutar (₺) <span class="text-red-500">*</span></label>
                <input type="number" wire:model="expAmount" placeholder="0.00" step="0.01"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                @error('expAmount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tarih <span class="text-red-500">*</span></label>
                <input type="date" wire:model="expDate"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tedarikçi / Mağaza</label>
                <input type="text" wire:model="expSupplier" placeholder="Opsiyonel"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Not</label>
                <input type="text" wire:model="expNote" placeholder="Opsiyonel"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
        </div>

        {{-- Tekrarlayan --}}
        <div class="mt-4 flex items-center gap-4 flex-wrap">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" wire:model.live="expIsRecurring" class="w-4 h-4 rounded text-cyan-600">
                <span class="text-sm text-gray-700 font-medium">Tekrarlayan gider</span>
            </label>
            @if($expIsRecurring)
            <select wire:model="expRecurringPeriod" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 bg-white">
                <option value="monthly">Aylık</option>
                <option value="yearly">Yıllık</option>
            </select>
            @endif
        </div>

        <div class="flex items-center gap-3 mt-4">
            <button wire:click="saveExpense" class="bg-red-500 hover:bg-red-400 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                {{ $editingExpenseId ? 'Güncelle' : 'Kaydet' }}
            </button>
            <button wire:click="$set('showExpenseForm',false)" class="text-gray-500 hover:text-gray-700 text-sm px-4 py-2.5">İptal</button>
        </div>
    </div>
    @endif

    {{-- Tekrarlayan giderler özeti --}}
    @if($recurringExp->count())
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-5">
        <p class="text-xs font-bold text-amber-800 mb-2">Tekrarlayan Giderler ({{ $recurringExp->count() }})</p>
        <div class="flex flex-wrap gap-2">
            @foreach($recurringExp as $re)
            <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-1 rounded-lg">
                {{ $re->expenseCategory?->name }} · {{ $re->title }} · {{ number_format($re->amount,0,',','.') }} ₺
                <span class="text-amber-500">({{ $re->recurring_period === 'monthly' ? 'aylık' : 'yıllık' }})</span>
            </span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Giderler tablosu --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <span class="text-sm font-bold text-gray-900">{{ $expenses instanceof \Illuminate\Pagination\LengthAwarePaginator ? $expenses->total() : $expenses->count() }} gider</span>
            <span class="text-sm font-bold text-red-600">Toplam: {{ number_format($expTotal, 2, ',', '.') }} ₺</span>
        </div>
        @if($expenses->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tarih</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Başlık</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tedarikçi</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Tutar</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($expenses as $exp)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $exp->expense_date->format('d.m.Y') }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $exp->title }}</p>
                            @if($exp->note) <p class="text-xs text-gray-400 truncate max-w-xs">{{ $exp->note }}</p> @endif
                            @if($exp->is_recurring)
                            <span class="text-[10px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-medium">
                                {{ $exp->recurring_period === 'monthly' ? 'Aylık' : 'Yıllık' }}
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($exp->expenseCategory)
                            <span class="text-xs font-semibold px-2 py-1 rounded-lg {{ $exp->expenseCategory->badge_class }}">
                                {{ $exp->expenseCategory->name }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-sm">{{ $exp->supplier ?? '—' }}</td>
                        <td class="px-4 py-3 text-right font-bold text-red-600">{{ number_format($exp->amount, 2, ',', '.') }} ₺</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="editExpense({{ $exp->id }})" class="text-gray-400 hover:text-cyan-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="deleteExpense({{ $exp->id }})" wire:confirm="Bu gideri silmek istediğinize emin misiniz?" class="text-gray-400 hover:text-red-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($expenses instanceof \Illuminate\Pagination\LengthAwarePaginator && $expenses->hasPages())
        <div class="p-4">{{ $expenses->links() }}</div>
        @endif
        @else
        <div class="text-center py-10 text-gray-400 text-sm">Bu dönemde gider kaydı yok</div>
        @endif
    </div>

    @endif

    {{-- ═══════════════════════════════════════════════════════════
         GELİRLER SEKMESİ
    ═══════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'gelirler')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <input type="text" wire:model.live.debounce.300ms="incSearch" placeholder="Ara..."
            class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 w-56">
        <button wire:click="newIncome" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Manuel Gelir Ekle
        </button>
    </div>

    {{-- Manuel Gelir Formu --}}
    @if($showIncomeForm)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 mb-5">
        <h3 class="text-sm font-bold text-gray-900 mb-4">{{ $editingIncomeId ? 'Gelir Düzenle' : 'Manuel Gelir Ekle' }}</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Başlık <span class="text-red-500">*</span></label>
                <input type="text" wire:model="incTitle" placeholder="Örn: Sigorta Tazminatı"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                @error('incTitle') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kategori</label>
                <select wire:model="incCategory" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 bg-white">
                    <option value="is_emri">İş Emri</option>
                    <option value="sigorta">Sigorta</option>
                    <option value="satis">Satış</option>
                    <option value="diger">Diğer</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tutar (₺) <span class="text-red-500">*</span></label>
                <input type="number" wire:model="incAmount" placeholder="0.00" step="0.01"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                @error('incAmount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tarih <span class="text-red-500">*</span></label>
                <input type="date" wire:model="incDate"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kaynak</label>
                <input type="text" wire:model="incSource" placeholder="Opsiyonel"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Not</label>
                <input type="text" wire:model="incNote" placeholder="Opsiyonel"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>
        </div>
        <div class="flex items-center gap-3 mt-4">
            <button wire:click="saveIncome" class="bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                {{ $editingIncomeId ? 'Güncelle' : 'Kaydet' }}
            </button>
            <button wire:click="$set('showIncomeForm',false)" class="text-gray-500 hover:text-gray-700 text-sm px-4 py-2.5">İptal</button>
        </div>
    </div>
    @endif

    {{-- Manuel Gelirler --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
        <div class="px-5 py-4 border-b border-gray-100">
            <p class="text-sm font-bold text-gray-900">Manuel Gelirler</p>
        </div>
        @if($manualIncomes->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tarih</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Başlık</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kaynak</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Tutar</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($manualIncomes as $inc)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $inc->income_date->format('d.m.Y') }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $inc->title }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-semibold px-2 py-1 rounded-lg {{ $inc->category_badge }}">{{ $inc->category_label }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-sm">{{ $inc->source ?? '—' }}</td>
                        <td class="px-4 py-3 text-right font-bold text-emerald-600">{{ number_format($inc->amount, 2, ',', '.') }} ₺</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="editIncome({{ $inc->id }})" class="text-gray-400 hover:text-cyan-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="deleteIncome({{ $inc->id }})" wire:confirm="Bu geliri silmek istediğinize emin misiniz?" class="text-gray-400 hover:text-red-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($manualIncomes instanceof \Illuminate\Pagination\LengthAwarePaginator && $manualIncomes->hasPages())
        <div class="p-4">{{ $manualIncomes->links() }}</div>
        @endif
        @else
        <div class="text-center py-8 text-gray-400 text-sm">Bu dönemde manuel gelir yok</div>
        @endif
    </div>

    {{-- İş Emri Tahsilatları --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm font-bold text-gray-900">İş Emri Tahsilatları</p>
            <span class="text-xs text-gray-400">Otomatik — iş emirlerinden</span>
        </div>
        @if($payments->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tarih</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">İş Emri</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Müşteri</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tip</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Tutar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($payments as $pay)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $pay->paid_at->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-cyan-700">{{ $pay->workOrder?->order_no ?? '—' }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $pay->customer?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @php $typeColors = ['nakit'=>'bg-emerald-100 text-emerald-700','kart'=>'bg-blue-100 text-blue-700','havale'=>'bg-purple-100 text-purple-700']; @endphp
                            <span class="text-xs font-semibold px-2 py-1 rounded-lg {{ $typeColors[$pay->payment_type] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($pay->payment_type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-emerald-600">{{ number_format($pay->amount, 2, ',', '.') }} ₺</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($payments instanceof \Illuminate\Pagination\LengthAwarePaginator && $payments->hasPages())
        <div class="p-4">{{ $payments->links() }}</div>
        @endif
        @else
        <div class="text-center py-8 text-gray-400 text-sm">Bu dönemde tahsilat yok</div>
        @endif
    </div>

    @endif

    {{-- ═══════════════════════════════════════════════════════════
         KATEGORİLER SEKMESİ
    ═══════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'kategoriler')

    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-gray-500">Gider kategorilerini buradan yönetebilirsiniz</p>
        <button wire:click="newCategory" class="flex items-center gap-2 bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Yeni Kategori
        </button>
    </div>

    {{-- Kategori Formu --}}
    @if($showCatForm)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 mb-5">
        <h3 class="text-sm font-bold text-gray-900 mb-4">{{ $editingCatId ? 'Kategori Düzenle' : 'Yeni Kategori' }}</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kategori Adı <span class="text-red-500">*</span></label>
                <input type="text" wire:model="catName" placeholder="Örn: İnternet / Telefon"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                @error('catName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Renk</label>
                <select wire:model.live="catColor" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 bg-white">
                    @foreach(['slate'=>'Gri','red'=>'Kırmızı','orange'=>'Turuncu','amber'=>'Amber','yellow'=>'Sarı','green'=>'Yeşil','teal'=>'Teal','blue'=>'Mavi','indigo'=>'İndigo','purple'=>'Mor','pink'=>'Pembe'] as $val => $lbl)
                    <option value="{{ $val }}">{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{-- Önizleme --}}
        @if($catName)
        <div class="mt-3">
            <span class="text-xs font-semibold px-3 py-1.5 rounded-lg
                @if($catColor==='red') bg-red-100 text-red-700
                @elseif($catColor==='orange') bg-orange-100 text-orange-700
                @elseif($catColor==='amber') bg-amber-100 text-amber-700
                @elseif($catColor==='yellow') bg-yellow-100 text-yellow-700
                @elseif($catColor==='green') bg-green-100 text-green-700
                @elseif($catColor==='teal') bg-teal-100 text-teal-700
                @elseif($catColor==='blue') bg-blue-100 text-blue-700
                @elseif($catColor==='indigo') bg-indigo-100 text-indigo-700
                @elseif($catColor==='purple') bg-purple-100 text-purple-700
                @elseif($catColor==='pink') bg-pink-100 text-pink-700
                @else bg-slate-100 text-slate-700 @endif">
                {{ $catName }}
            </span>
        </div>
        @endif
        <div class="flex items-center gap-3 mt-4">
            <button wire:click="saveCategory" class="bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition">
                {{ $editingCatId ? 'Güncelle' : 'Kaydet' }}
            </button>
            <button wire:click="$set('showCatForm',false)" class="text-gray-500 hover:text-gray-700 text-sm px-4 py-2.5">İptal</button>
        </div>
    </div>
    @endif

    {{-- Kategoriler listesi --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($categories as $cat)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center justify-between gap-3 {{ !$cat->is_active ? 'opacity-50' : '' }}">
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold px-2.5 py-1 rounded-lg {{ $cat->badge_class }}">{{ $cat->name }}</span>
                <span class="text-xs text-gray-400">{{ $cat->expenses_count }} gider</span>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button wire:click="editCategory({{ $cat->id }})" class="text-gray-400 hover:text-cyan-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button wire:click="toggleCategory({{ $cat->id }})" class="text-gray-400 hover:text-amber-500 transition" title="{{ $cat->is_active ? 'Pasif yap' : 'Aktif yap' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cat->is_active ? 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21' : 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z' }}"/></svg>
                </button>
                @if($cat->expenses_count === 0)
                <button wire:click="deleteCategory({{ $cat->id }})" wire:confirm="Bu kategoriyi silmek istediğinize emin misiniz?" class="text-gray-400 hover:text-red-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @endif

</div>
