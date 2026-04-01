<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1e293b; background: #fff; }

    .page { padding: 30px 35px; }

    /* ── Header ── */
    .header { display: table; width: 100%; border-bottom: 3px solid #0891b2; padding-bottom: 16px; margin-bottom: 20px; }
    .header-left { display: table-cell; vertical-align: middle; width: 60%; }
    .header-right { display: table-cell; vertical-align: middle; text-align: right; }
    .firm-name { font-size: 22px; font-weight: bold; color: #0d2137; letter-spacing: -0.5px; }
    .firm-sub  { font-size: 10px; color: #64748b; margin-top: 2px; }
    .order-badge { background: #0891b2; color: #fff; padding: 6px 14px; border-radius: 8px; display: inline-block; }
    .order-badge-no { font-size: 15px; font-weight: bold; }
    .order-badge-label { font-size: 9px; opacity: 0.85; }

    /* ── Info Grid ── */
    .info-grid { display: table; width: 100%; margin-bottom: 20px; border-collapse: separate; border-spacing: 8px; }
    .info-box { display: table-cell; width: 50%; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; vertical-align: top; }
    .info-box-title { font-size: 9px; font-weight: bold; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
    .info-row { font-size: 11px; color: #334155; margin-bottom: 3px; }
    .info-row strong { color: #0f172a; }

    /* ── Tablo ── */
    .section-title { font-size: 10px; font-weight: bold; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; padding-left: 2px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    thead tr { background: #0d2137; }
    thead th { color: #fff; font-size: 10px; font-weight: bold; padding: 8px 10px; text-align: left; }
    thead th:last-child { text-align: right; }
    thead th.center { text-align: center; }
    tbody tr { border-bottom: 1px solid #f1f5f9; }
    tbody tr:nth-child(even) { background: #f8fafc; }
    tbody td { padding: 8px 10px; font-size: 11px; color: #334155; vertical-align: middle; }
    tbody td.center { text-align: center; }
    tbody td.right { text-align: right; }
    .badge { display: inline-block; padding: 2px 7px; border-radius: 4px; font-size: 9px; font-weight: bold; }
    .badge-service { background: #dbeafe; color: #1d4ed8; }
    .badge-product { background: #dcfce7; color: #15803d; }

    /* ── Özet ── */
    .summary-wrap { display: table; width: 100%; margin-bottom: 20px; }
    .summary-left { display: table-cell; width: 55%; vertical-align: top; }
    .summary-right { display: table-cell; width: 45%; vertical-align: top; }
    .summary-box { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
    .summary-row { display: table; width: 100%; border-bottom: 1px solid #f1f5f9; }
    .summary-row:last-child { border-bottom: none; }
    .summary-label { display: table-cell; padding: 7px 12px; font-size: 11px; color: #64748b; }
    .summary-value { display: table-cell; padding: 7px 12px; font-size: 11px; font-weight: bold; text-align: right; color: #0f172a; }
    .summary-row.total .summary-label,
    .summary-row.total .summary-value { background: #0891b2; color: #fff; font-size: 13px; }
    .summary-row.discount .summary-value { color: #ea580c; }
    .summary-row.paid .summary-value { color: #16a34a; }
    .summary-row.remaining .summary-value { color: #dc2626; }
    .summary-row.done .summary-label,
    .summary-row.done .summary-value { background: #dcfce7; color: #15803d; font-weight: bold; }

    /* ── Ödemeler ── */
    .payments-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; }
    .payment-item { display: table; width: 100%; padding: 4px 0; border-bottom: 1px dashed #e2e8f0; }
    .payment-item:last-child { border-bottom: none; }
    .payment-type { display: table-cell; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 4px; width: 55px; text-align: center; }
    .payment-nakit   { background: #dcfce7; color: #15803d; }
    .payment-kart    { background: #dbeafe; color: #1d4ed8; }
    .payment-havale  { background: #f3e8ff; color: #7c3aed; }
    .payment-date { display: table-cell; padding-left: 8px; font-size: 10px; color: #64748b; vertical-align: middle; }
    .payment-amount { display: table-cell; text-align: right; font-size: 11px; font-weight: bold; color: #0f172a; vertical-align: middle; }

    /* ── Not ── */
    .notes-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 10px 14px; margin-bottom: 20px; }
    .notes-label { font-size: 9px; font-weight: bold; color: #92400e; text-transform: uppercase; margin-bottom: 4px; }
    .notes-text  { font-size: 11px; color: #78350f; }

    /* ── Footer ── */
    .footer { border-top: 1px solid #e2e8f0; padding-top: 12px; display: table; width: 100%; margin-top: 10px; }
    .footer-left  { display: table-cell; font-size: 9px; color: #94a3b8; vertical-align: middle; }
    .footer-right { display: table-cell; text-align: right; font-size: 9px; color: #94a3b8; vertical-align: middle; }
    .status-badge { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 10px; font-weight: bold; }
    .status-bekleyen   { background: #fef9c3; color: #a16207; }
    .status-devam_eden { background: #dbeafe; color: #1d4ed8; }
    .status-tamamlandi { background: #dcfce7; color: #15803d; }
</style>
</head>
<body>
<div class="page">

    {{-- ── Header ─────────────────────────────────────── --}}
    <div class="header">
        <div class="header-left">
            <div class="firm-name">{{ $firmName }}</div>
            @if($firmAddr || $firmPhone)
            <div class="firm-sub">
                @if($firmAddr){{ $firmAddr }}@endif
                @if($firmPhone) · {{ $firmPhone }}@endif
                @if($firmEmail) · {{ $firmEmail }}@endif
                @if($firmTaxNo) · VKN: {{ $firmTaxNo }}@if($firmTaxOffice) / {{ $firmTaxOffice }}@endif @endif
            </div>
            @endif
        </div>
        <div class="header-right">
            <div class="order-badge">
                <div class="order-badge-label">İŞ EMRİ</div>
                <div class="order-badge-no">{{ $order->order_no }}</div>
            </div>
            <div style="font-size:10px; color:#64748b; margin-top:6px;">
                {{ $order->created_at->format('d.m.Y H:i') }}
            </div>
            <div style="margin-top:4px;">
                <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
            </div>
        </div>
    </div>

    {{-- ── Müşteri & Araç Bilgisi ──────────────────────── --}}
    <div class="info-grid">
        <div class="info-box">
            <div class="info-box-title">Müşteri Bilgisi</div>
            <div class="info-row"><strong>{{ $order->customer->name }}</strong></div>
            @if($order->customer->phone)
            <div class="info-row">{{ $order->customer->phone }}</div>
            @endif
            @if($order->customer->email)
            <div class="info-row">{{ $order->customer->email }}</div>
            @endif
        </div>
        <div class="info-box">
            <div class="info-box-title">Araç Bilgisi</div>
            @if($order->vehicle)
            <div class="info-row">
                <strong>{{ $order->vehicle->brand?->name }} {{ $order->vehicle->model?->name }}</strong>
                @if($order->vehicle->year) ({{ $order->vehicle->year }})@endif
            </div>
            @if($order->vehicle->plate)
            <div class="info-row">Plaka: <strong>{{ $order->vehicle->plate }}</strong></div>
            @endif
            @if($order->vehicle->color)
            <div class="info-row">Renk: {{ $order->vehicle->color }}</div>
            @endif
            @else
            <div class="info-row" style="color:#94a3b8">Araç belirtilmemiş</div>
            @endif
            @if($order->staff)
            <div class="info-row" style="margin-top:4px;">Personel: <strong>{{ $order->staff->name }}</strong></div>
            @endif
        </div>
    </div>

    {{-- ── Kalemler ─────────────────────────────────────── --}}
    <div class="section-title">Yapılan İşlemler</div>
    <table>
        <thead>
            <tr>
                <th style="width:40%">İşlem / Ürün</th>
                <th class="center" style="width:10%">Tür</th>
                <th class="center" style="width:15%">Miktar</th>
                <th style="text-align:right; width:17%">Birim Fiyat</th>
                <th style="text-align:right; width:18%">Toplam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td class="center">
                    <span class="badge {{ $item->type === 'service' ? 'badge-service' : 'badge-product' }}">
                        {{ $item->type === 'service' ? 'Hizmet' : 'Ürün' }}
                    </span>
                </td>
                <td class="center">{{ $item->quantity+0 }} {{ $item->unit }}</td>
                <td class="right">{{ number_format($item->unit_price,2,',','.') }} ₺</td>
                <td class="right">{{ number_format($item->total_price,2,',','.') }} ₺</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ── Özet + Ödemeler ─────────────────────────────── --}}
    <div class="summary-wrap">

        {{-- Ödemeler --}}
        <div class="summary-left" style="padding-right:10px;">
            @if($order->payments->count())
            <div class="section-title">Ödeme Geçmişi</div>
            <div class="payments-box">
                @foreach($order->payments as $p)
                <div class="payment-item">
                    <span class="payment-type payment-{{ $p->payment_type }}">{{ ucfirst($p->payment_type) }}</span>
                    <span class="payment-date">{{ $p->paid_at->format('d.m.Y H:i') }}</span>
                    <span class="payment-amount">{{ number_format($p->amount,2,',','.') }} ₺</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Finansal Özet --}}
        <div class="summary-right">
            <div class="section-title">Finansal Özet</div>
            <div class="summary-box">
                <div class="summary-row">
                    <div class="summary-label">Ara Toplam</div>
                    <div class="summary-value">{{ number_format($order->total_amount,2,',','.') }} ₺</div>
                </div>
                @if($order->discount_amount > 0)
                <div class="summary-row discount">
                    <div class="summary-label">İndirim</div>
                    <div class="summary-value">-{{ number_format($order->discount_amount,2,',','.') }} ₺</div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">İndirimli Tutar</div>
                    <div class="summary-value">{{ number_format($order->effective_total,2,',','.') }} ₺</div>
                </div>
                @endif
                <div class="summary-row paid">
                    <div class="summary-label">Ödenen</div>
                    <div class="summary-value">{{ number_format($order->paid_amount,2,',','.') }} ₺</div>
                </div>
                @php $remaining = $order->remaining_amount; @endphp
                @if($remaining <= 0)
                <div class="summary-row done">
                    <div class="summary-label">✓ Tahsilat Tamamlandı</div>
                    <div class="summary-value">—</div>
                </div>
                @else
                <div class="summary-row remaining">
                    <div class="summary-label">Kalan Bakiye</div>
                    <div class="summary-value">{{ number_format($remaining,2,',','.') }} ₺</div>
                </div>
                @endif
                <div class="summary-row total">
                    <div class="summary-label">GENEL TOPLAM</div>
                    <div class="summary-value">{{ number_format($order->effective_total,2,',','.') }} ₺</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Not ──────────────────────────────────────────── --}}
    @if($order->notes)
    <div class="notes-box">
        <div class="notes-label">Not</div>
        <div class="notes-text">{{ $order->notes }}</div>
    </div>
    @endif

    {{-- ── İmza Alanı ──────────────────────────────────── --}}
    <div style="display:table; width:100%; margin-bottom:25px;">
        <div style="display:table-cell; width:50%; padding-right:15px;">
            <div style="border-top:1px solid #cbd5e1; padding-top:6px; margin-top:30px;">
                <div style="font-size:9px; color:#94a3b8; text-align:center;">Müşteri İmzası</div>
            </div>
        </div>
        <div style="display:table-cell; width:50%; padding-left:15px;">
            <div style="border-top:1px solid #cbd5e1; padding-top:6px; margin-top:30px;">
                <div style="font-size:9px; color:#94a3b8; text-align:center;">Yetkili İmzası / Kaşe</div>
            </div>
        </div>
    </div>

    {{-- ── Footer ──────────────────────────────────────── --}}
    <div class="footer">
        <div class="footer-left">{{ $firmName }} · İş Emri #{{ $order->order_no }}</div>
        <div class="footer-right">Oluşturma: {{ $order->created_at->format('d.m.Y H:i') }}</div>
    </div>

</div>
</body>
</html>
