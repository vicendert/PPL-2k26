<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RevenueExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        return Booking::query()
            ->with(['user', 'court'])
            ->where('status', Booking::STATUS_CONFIRMED)
            ->orderBy('date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Lapangan',
            'Customer',
            'Total Harga',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->date?->format('Y-m-d'),
            $booking->court->name ?? '-',
            $booking->user->name ?? '-',
            $booking->total_price,
        ];
    }
}
