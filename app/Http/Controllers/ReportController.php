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
                return response($html)->header('Content-Type', 'text/html');
            }
            return Excel::download(new OrdersExport($includeAmounts), 'orders.xlsx');
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
            return $pdf->stream('orders.pdf');
        }

        return $pdf->download('orders.pdf');
    }

    public function exportRevenue(Request $request): Response|BinaryFileResponse
    {
        $format = $request->query('format', 'pdf');
        $preview = $request->boolean('preview');

        if ($format === 'excel') {
            if ($preview) {
                $html = Excel::raw(new RevenueExport(), \Maatwebsite\Excel\Excel::HTML);
                return response($html)->header('Content-Type', 'text/html');
            }
            return Excel::download(new RevenueExport(), 'revenue.xlsx');
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
            return $pdf->stream('revenue.pdf');
        }

        return $pdf->download('revenue.pdf');
    }
}
