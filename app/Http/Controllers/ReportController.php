<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Exports\RevenueExport;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function exportOrders(Request $request): Response|BinaryFileResponse
    {
        $format = $request->query('format', 'pdf');
        $preview = $request->boolean('preview');
        $includeAmounts = $request->user()?->isOwner() ?? false;

        if ($format === 'excel') {
            if ($preview) {
                $html = Excel::raw(new OrdersExport($includeAmounts), \Maatwebsite\Excel\Excel::HTML);

                $customCss = '
                <style>
                    body {
                        background: #f1f5f9;
                        padding: 20px;
                        margin: 0;
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                    }
                    .excel-preview-card {
                        background: #ffffff;
                        box-shadow: 0 4px 15px -2px rgba(0,0,0,0.08), 0 2px 6px -1px rgba(0,0,0,0.04);
                        border-radius: 8px;
                        overflow: hidden;
                        border: 1px solid #cbd5e1;
                    }
                    .excel-ribbon-bar {
                        background: #1e293b;
                        color: #ffffff;
                        padding: 10px 18px;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        font-size: 12.5px;
                        font-weight: 600;
                        border-bottom: 2px solid #2563eb;
                    }
                    .excel-ribbon-title {
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    }
                    .excel-ribbon-tag {
                        background: #2563eb;
                        color: #ffffff;
                        padding: 3px 10px;
                        border-radius: 4px;
                        font-size: 10.5px;
                        font-weight: 700;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    }
                    .excel-table-wrap {
                        padding: 20px;
                        overflow-x: auto;
                    }
                    table {
                        border-collapse: collapse !important;
                        margin: 0 auto !important;
                        width: 100% !important;
                    }
                    td, th {
                        padding: 8px 12px !important;
                    }
                </style>
                ';
                $html = str_replace('</head>', $customCss . '</head>', $html);
                $html = str_replace('<body>', '<body><div class="excel-preview-card"><div class="excel-ribbon-bar"><div class="excel-ribbon-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg> <span>laporan-order-kasir.xlsx</span></div><span class="excel-ribbon-tag">Pratinjau Lembar Kerja Excel</span></div><div class="excel-table-wrap">', $html);
                $html = str_replace('</body>', '</div></div></body>', $html);

                return response($html)->header('Content-Type', 'text/html');
            }
            return Excel::download(new OrdersExport($includeAmounts), 'laporan-order-kasir.xlsx');
        }

        $bookings = Booking::query()
            ->with(['user', 'court'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        $pdf = Pdf::loadView('reports.orders_pdf', [
            'bookings' => $bookings,
            'includeAmounts' => $includeAmounts,
        ]);

        if ($preview) {
            return $pdf->stream('laporan-order-kasir.pdf');
        }

        return $pdf->download('laporan-order-kasir.pdf');
    }

    public function exportRevenue(Request $request): Response|BinaryFileResponse
    {
        $format = $request->query('format', 'pdf');
        $preview = $request->boolean('preview');

        if ($format === 'excel') {
            if ($preview) {
                $html = Excel::raw(new RevenueExport(), \Maatwebsite\Excel\Excel::HTML);

                $customCss = '
                <style>
                    body {
                        background: #f1f5f9;
                        padding: 20px;
                        margin: 0;
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                    }
                    .excel-preview-card {
                        background: #ffffff;
                        box-shadow: 0 4px 15px -2px rgba(0,0,0,0.08), 0 2px 6px -1px rgba(0,0,0,0.04);
                        border-radius: 8px;
                        overflow: hidden;
                        border: 1px solid #cbd5e1;
                    }
                    .excel-ribbon-bar {
                        background: #1e293b;
                        color: #ffffff;
                        padding: 10px 18px;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        font-size: 12.5px;
                        font-weight: 600;
                        border-bottom: 2px solid #0d9488;
                    }
                    .excel-ribbon-title {
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    }
                    .excel-ribbon-tag {
                        background: #0d9488;
                        color: #ffffff;
                        padding: 3px 10px;
                        border-radius: 4px;
                        font-size: 10.5px;
                        font-weight: 700;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    }
                    .excel-table-wrap {
                        padding: 20px;
                        overflow-x: auto;
                    }
                    table {
                        border-collapse: collapse !important;
                        margin: 0 auto !important;
                        width: 100% !important;
                    }
                    td, th {
                        padding: 8px 12px !important;
                    }
                </style>
                ';
                $html = str_replace('</head>', $customCss . '</head>', $html);
                $html = str_replace('<body>', '<body><div class="excel-preview-card"><div class="excel-ribbon-bar"><div class="excel-ribbon-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg> <span>laporan-keuangan.xlsx</span></div><span class="excel-ribbon-tag">Pratinjau Lembar Kerja Excel</span></div><div class="excel-table-wrap">', $html);
                $html = str_replace('</body>', '</div></div></body>', $html);

                return response($html)->header('Content-Type', 'text/html');
            }
            return Excel::download(new RevenueExport(), 'laporan-keuangan.xlsx');
        }

        $bookings = Booking::query()
            ->with(['user', 'court'])
            ->where('status', Booking::STATUS_CONFIRMED)
            ->orderBy('date', 'desc')
            ->get();

        $totalRevenue = $bookings->sum('total_price');

        $pdf = Pdf::loadView('reports.revenue_pdf', [
            'bookings' => $bookings,
            'totalRevenue' => $totalRevenue,
        ]);

        if ($preview) {
            return $pdf->stream('laporan-keuangan.pdf');
        }

        return $pdf->download('laporan-keuangan.pdf');
    }
}
