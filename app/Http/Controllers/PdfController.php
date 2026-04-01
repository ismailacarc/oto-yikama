<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Payment;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PdfController extends Controller
{
    public function rapor(\Illuminate\Http\Request $request)
    {
        $from = Carbon::parse($request->input('from', now()->startOfMonth()))->startOfDay();
        $to   = Carbon::parse($request->input('to',   now()))->endOfDay();

        $orders = WorkOrder::with(['customer', 'vehicle.brand', 'vehicle.model', 'staff', 'items', 'payments'])
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('created_at')
            ->get();

        $totalRevenue   = Payment::whereBetween('paid_at', [$from, $to])->sum('amount');
        $totalDiscount  = $orders->sum('discount_amount');
        $pendingRevenue = $orders->sum(fn($o) => max(0, $o->remaining_amount));

        $paymentBreakdown = Payment::whereBetween('paid_at', [$from, $to])
            ->selectRaw('payment_type, SUM(amount) as total')
            ->groupBy('payment_type')
            ->pluck('total', 'payment_type');

        $firmName  = Setting::get('firm_name',    'UFK Garage');
        $firmPhone = Setting::get('firm_phone',   '');
        $firmEmail = Setting::get('firm_email',   '');
        $firmAddr  = Setting::get('firm_address', '');

        $pdf = Pdf::loadView('pdf.rapor', compact(
            'orders', 'from', 'to',
            'totalRevenue', 'totalDiscount', 'pendingRevenue',
            'paymentBreakdown',
            'firmName', 'firmPhone', 'firmEmail', 'firmAddr'
        ))->setPaper('a4', 'portrait');

        $fileName = 'rapor-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.pdf';
        return $pdf->download($fileName);
    }

    public function isEmri(int $id)
    {
        $order = WorkOrder::with([
            'customer',
            'vehicle.brand',
            'vehicle.model',
            'staff',
            'items',
            'payments',
        ])->findOrFail($id);

        $firmName      = Setting::get('firm_name',       'UFK Garage');
        $firmPhone     = Setting::get('firm_phone',      '');
        $firmEmail     = Setting::get('firm_email',      '');
        $firmAddr      = Setting::get('firm_address',    '');
        $firmTaxNo     = Setting::get('firm_tax_no',     '');
        $firmTaxOffice = Setting::get('firm_tax_office', '');

        $pdf = Pdf::loadView('pdf.is-emri', compact(
            'order', 'firmName', 'firmPhone', 'firmEmail', 'firmAddr', 'firmTaxNo', 'firmTaxOffice'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('is-emri-' . $order->order_no . '.pdf');
    }
}
