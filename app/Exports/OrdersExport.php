<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    private bool $includeAmounts;

    public function __construct(bool $includeAmounts)
    {
        $this->includeAmounts = $includeAmounts;
    }

    public function collection(): Collection
    {
        return Booking::query()
            ->with(['user', 'court'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();
    }

    public function headings(): array
    {
        $headings = [
            'ID',
            'Tanggal',
            'Jam Mulai',
            'Jam Selesai',
            'Lapangan',
            'Customer',
            'Status',
        ];

        if ($this->includeAmounts) {
            $headings[] = 'Harga / Jam';
            $headings[] = 'Total Harga';
        }

        return $headings;
    }

    public function map($booking): array
    {
        $row = [
            $booking->id,
            $booking->date?->format('Y-m-d'),
            $booking->start_time,
            $booking->end_time,
            $booking->court->name ?? '-',
            $booking->user->name ?? '-',
            $booking->status,
        ];

        if ($this->includeAmounts) {
            $row[] = $booking->price_per_hour;
            $row[] = $booking->total_price;
        }

        return $row;
    }
}
