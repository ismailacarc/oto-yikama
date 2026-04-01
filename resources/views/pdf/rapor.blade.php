<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<style>
    @font-face {
        font-family: 'DejaVu Sans';
        src: url('{{ storage_path("fonts/DejaVuSans.ttf") }}') format('truetype');
        font-weight: normal;
    }
    @font-face {
        font-family: 'DejaVu Sans';
        src: url('{{ storage_path("fonts/DejaVuSans-Bold.ttf") }}') format('truetype');
        font-weight: bold;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; background: #fff; }

    .page { padding: 28px 30px; }

    /* Header */
    .header { background: linear-gradient(135deg, #0f172a 0%, #155e75 100%); color: #fff; padding: 18px 22px; border-radius: 8px; margin-bottom: 18px; }
    .header-title { font-size: 18px; font-weight: bold; }
    .header-sub  { font-size: 10px; color: #94a3b8; margin-top: 2px; }
    .header-meta { font-size: 9px; color: #cbd5e1; margin-top: 8px; }

    /* KPI row */
    .kpi-row { display: table; width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-bottom: 18px; }
    .kpi-cell { display: table-cell; width: 25%; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; vertical-align: top; }
    .kpi-label { font-size: 9px; color: #64748b; }
    .kpi-value { font-size: 16px; font-weight: bold; color: #0f172a; margin-top: 4px; }
    .kpi-value.green { color: #059669; }
    .kpi-value.amber { color: #d97706; }
    .kpi-value.red   { color: #dc2626; }

    /* Section title */
    .section-title { font-size: 11px; font-weight: bold; color: #0f172a; border-left: 3px solid #0891b2; padding-left: 8px; margin-bottom: 10px; margin-top: 16px; }

    /* Payment breakdown */
    .pay-row { display: table; width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    .pay-cell { display: table-cell; width: 33.33%; border: 1px solid #e2e8f0; padding: 10px; vertical-align: top; }
    .pay-cell:first-child { border-radius: 6px 0 0 6px; }
    .pay-cell:last-child  { border-radius: 0 6px 6px 0; }
    .pay-type  { font-size: 9px; color: #64748b; }
    .pay-amount { font-size: 14px; font-weight: bold; margin-top: 2px; }
    .nakit  { color: #059669; }
    .kart   { color: #2563eb; }
    .havale { color: #7c3aed; }

    /* Orders table */
    table { width: 100%; border-collapse: collapse; font-size: 9px; }
    thead tr { background: #0f172a; color: #fff; }
    thead th { padding: 7px 8px; text-align: left; font-weight: bold; font-size: 8.5px; }
    tbody tr { border-bottom: 1px solid #f1f5f9; }
    tbody tr:nth-child(even) { background: #f8fafc; }
    tbody td { padding: 6px 8px; vertical-align: middle; }

    .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; }
    .badge-amber  { background: #fef3c7; color: #92400e; }
    .badge-blue   { background: #dbeafe; color: #1e40af; }
    .badge-green  { background: #d1fae5; color: #065f46; }
    .badge-red    { background: #fee2e2; color: #991b1b; }

    .amount { font-weight: bold; text-align: right; }
    .remaining { color: #dc2626; font-size: 8px; }
    .paid-ok   { color: #059669; font-size: 8px; }

    /* Footer */
    .footer { margin-top: 24px; padding-top: 12px; border-top: 1px solid #e2e8f0; font-size: 8.5px; color: #94a3b8; display: table; width: 100%; }
    .footer-left  { display: table-cell; text-align: left; }
    .footer-right { display: table-cell; text-align: right; }

    .page-break { page-break-after: always; }
</style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div style="display:table;width:100%">
            <div style="display:table-cell;vertical-align:middle">
                <div class="header-title">{{ $firmName }}</div>
                <div class="header-sub">Dönem Raporu</div>
                <div class="header-meta">
                    {{ $from->format('d.m.Y') }} – {{ $to->format('d.m.Y') }}
                    @if($firmPhone) · {{ $firmPhone }} @endif
                    @if($firmEmail) · {{ $firmEmail }} @endif
                </div>
            </div>
            <div style="display:table-cell;vertical-align:middle;text-align:right">
                <div style="font-size:9px;color:#94a3b8;">Oluşturma tarihi</div>
                <div style="font-size:11px;font-weight:bold;color:#e2e8f0;">{{ now()->format('d.m.Y H:i') }}</div>
            </div>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="kpi-row">
        <div class="kpi-cell">
            <div class="kpi-label">Toplam İş Emri</div>
            <div class="kpi-value">{{ $orders->count() }}</div>
        </div>
        <div class="kpi-cell">
            <div class="kpi-label">Tahsilat</div>
            <div class="kpi-value green">{{ number_format($totalRevenue, 2, ',', '.') }} ₺</div>
        </div>
        <div class="kpi-cell">
            <div class="kpi-label">Toplam İndirim</div>
            <div class="kpi-value amber">{{ number_format($totalDiscount, 2, ',', '.') }} ₺</div>
        </div>
        <div class="kpi-cell">
            <div class="kpi-label">Cari Alacak</div>
            <div class="kpi-value red">{{ number_format($pendingRevenue, 2, ',', '.') }} ₺</div>
        </div>
    </div>

    {{-- Payment Breakdown --}}
    <div class="section-title">Ödeme Tipi Dağılımı</div>
    <div class="pay-row">
        <div class="pay-cell">
            <div class="pay-type">Nakit</div>
            <div class="pay-amount nakit">{{ number_format($paymentBreakdown['nakit'] ?? 0, 2, ',', '.') }} ₺</div>
        </div>
        <div class="pay-cell">
            <div class="pay-type">Kredi / Banka Kartı</div>
            <div class="pay-amount kart">{{ number_format($paymentBreakdown['kart'] ?? 0, 2, ',', '.') }} ₺</div>
        </div>
        <div class="pay-cell">
            <div class="pay-type">Havale / EFT</div>
            <div class="pay-amount havale">{{ number_format($paymentBreakdown['havale'] ?? 0, 2, ',', '.') }} ₺</div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="section-title">İş Emirleri ({{ $orders->count() }} adet)</div>

    <table>
        <thead>
            <tr>
                <th>İş No</th>
                <th>Müşteri</th>
                <th>Araç</th>
                <th>Personel</th>
                <th>Tarih</th>
                <th>Durum</th>
                <th style="text-align:right">Tutar</th>
                <th style="text-align:right">İndirim</th>
                <th style="text-align:right">Net</th>
                <th style="text-align:right">Tahsilat</th>
                <th style="text-align:right">Kalan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td style="font-family:monospace;font-size:8px;color:#0891b2">{{ $order->order_no }}</td>
                <td>{{ $order->customer->name }}</td>
                <td>
                    @if($order->vehicle)
                    {{ $order->vehicle->brand?->name }} {{ $order->vehicle->model?->name }}
                    @if($order->vehicle->plate)
                    <br><span style="font-size:8px;color:#64748b">{{ $order->vehicle->plate }}</span>
                    @endif
                    @endif
                </td>
                <td>{{ $order->staff?->name ?? '—' }}</td>
                <td style="color:#64748b">{{ $order->created_at->format('d.m.Y') }}</td>
                <td>
                    @if($order->status === 'bekleyen')
                        <span class="badge badge-amber">Bekleyen</span>
                    @elseif($order->status === 'devam_eden')
                        <span class="badge badge-blue">Devam</span>
                    @else
                        <span class="badge badge-green">Tamam</span>
                    @endif
                </td>
                <td class="amount">{{ number_format($order->total_amount, 2, ',', '.') }} ₺</td>
                <td class="amount" style="color:#d97706">
                    @if($order->discount_amount > 0)
                    -{{ number_format($order->discount_amount, 2, ',', '.') }} ₺
                    @else —
                    @endif
                </td>
                <td class="amount" style="font-weight:bold">{{ number_format($order->effective_total, 2, ',', '.') }} ₺</td>
                <td class="amount" style="color:#059669">{{ number_format($order->paid_amount, 2, ',', '.') }} ₺</td>
                <td class="amount">
                    @php $rem = $order->remaining_amount; @endphp
                    @if($rem > 0)
                        <span class="remaining">{{ number_format($rem, 2, ',', '.') }} ₺</span>
                    @else
                        <span class="paid-ok">✓</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align:center;padding:16px;color:#94a3b8">Bu dönemde iş emri yok</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Totals row --}}
    @if($orders->count() > 0)
    <table style="margin-top:4px">
        <tbody>
            <tr style="background:#0f172a;color:#fff">
                <td style="padding:8px;font-weight:bold;font-size:9px" colspan="6">TOPLAM</td>
                <td style="padding:8px;text-align:right;font-weight:bold">{{ number_format($orders->sum('total_amount'), 2, ',', '.') }} ₺</td>
                <td style="padding:8px;text-align:right;color:#fbbf24">-{{ number_format($totalDiscount, 2, ',', '.') }} ₺</td>
                <td style="padding:8px;text-align:right;font-weight:bold">{{ number_format($orders->sum('effective_total'), 2, ',', '.') }} ₺</td>
                <td style="padding:8px;text-align:right;color:#6ee7b7">{{ number_format($totalRevenue, 2, ',', '.') }} ₺</td>
                <td style="padding:8px;text-align:right;color:#fca5a5">{{ number_format($pendingRevenue, 2, ',', '.') }} ₺</td>
            </tr>
        </tbody>
    </table>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-left">{{ $firmName }} · Dönem Raporu · {{ $from->format('d.m.Y') }} – {{ $to->format('d.m.Y') }}</div>
        <div class="footer-right">{{ now()->format('d.m.Y H:i') }} tarihinde oluşturuldu</div>
    </div>

</div>
</body>
</html>
