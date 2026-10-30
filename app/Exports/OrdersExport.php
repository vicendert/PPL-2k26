<?php

namespace App\Exports;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithCustomStartCell
{
    private bool $includeAmounts;
    private int $rowCount = 0;
    private int $currentRow = 0;

    public function __construct(bool $includeAmounts = false)
    {
        $this->includeAmounts = $includeAmounts;
    }

    public function title(): string
    {
        return 'Laporan Order';
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function collection(): Collection
    {
        $this->currentRow = 0;

        $bookings = Booking::query()
            ->with(['user', 'court'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        $this->rowCount = $bookings->count();

        return $bookings;
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'ID Booking',
            'Tanggal',
            'Jam Sewa',
            'Lapangan',
            'Nama Pelanggan',
            'Tipe Pesanan',
            'Status',
        ];

        if ($this->includeAmounts) {
            $headings[] = 'Harga / Jam';
            $headings[] = 'Total Bayar';
        }

        return $headings;
    }

    public function map($booking): array
    {
        $this->currentRow++;

        $displayName = $booking->customer_name ?? $booking->user?->name ?? '-';
        $orderType   = !empty($booking->customer_name) ? 'WALK-IN' : 'ONLINE';
        
        $statusLabel = match($booking->status) {
            'confirmed' => 'APPROVED',
            'cancelled' => 'CANCELED',
            default     => 'PENDING',
        };

        $row = [
            $this->currentRow,
            '#' . $booking->id,
            $booking->date?->format('d/m/Y') ?? '-',
            Carbon::parse($booking->start_time)->format('H:i') . ' - ' . Carbon::parse($booking->end_time)->format('H:i'),
            $booking->court->name ?? '-',
            $displayName,
            $orderType,
            $statusLabel,
        ];

        if ($this->includeAmounts) {
            $row[] = (float) $booking->price_per_hour;
            $row[] = (float) $booking->total_price;
        }

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = $this->includeAmounts ? 'J' : 'H';
        $headerRow = 4;
        $startDataRow = 5;
        $endDataRow = max($startDataRow + $this->rowCount - 1, $startDataRow);

        // ── 1. TITLE BANNER (A1) ──
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'PADELCOURT — LAPORAN ORDER PEMESANAN');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'name' => 'Segoe UI',
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => '1E293B'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EFF6FF'],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // ── 2. SUBTITLE (A2) ──
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Sistem Manajemen Lapangan Padel | Tanggal Cetak: ' . now()->format('d/m/Y H:i') . ' WIB | Dokumen Kasir');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'name' => 'Segoe UI',
                'italic' => true,
                'size' => 9.5,
                'color' => ['rgb' => '64748B'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(18);

        // ── 3. TABLE HEADINGS (Row 4) ──
        $sheet->getStyle("A{$headerRow}:{$lastCol}{$headerRow}")->applyFromArray([
            'font' => [
                'name' => 'Segoe UI',
                'bold' => true,
                'size' => 10.5,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'], // Royal Blue Website
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '1D4ED8'],
                ],
            ],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(26);

        // ── 4. DATA ROWS ──
        if ($this->rowCount > 0) {
            for ($r = $startDataRow; $r <= $endDataRow; $r++) {
                $sheet->getRowDimension($r)->setRowHeight(22);
                $isEven = ($r % 2 === 0);
                $bgColor = $isEven ? 'F8FAFC' : 'FFFFFF';

                $sheet->getStyle("A{$r}:{$lastCol}{$r}")->applyFromArray([
                    'font' => [
                        'name' => 'Segoe UI',
                        'size' => 9.5,
                        'color' => ['rgb' => '1E293B'],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $bgColor],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'E2E8F0'],
                        ],
                    ],
                ]);

                // Alignments
                $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("G{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("H{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Highlight ID Booking
                $sheet->getStyle("B{$r}")->getFont()->setBold(true)->getColor()->setRGB('2563EB');

                // Highlight Lapangan
                $sheet->getStyle("E{$r}")->getFont()->setBold(true);

                // Highlight Tipe Pesanan
                $typeVal = $sheet->getCell("G{$r}")->getValue();
                if ($typeVal === 'WALK-IN') {
                    $sheet->getStyle("G{$r}")->getFont()->setBold(true)->getColor()->setRGB('B45309');
                } else {
                    $sheet->getStyle("G{$r}")->getFont()->getColor()->setRGB('1D4ED8');
                }

                // Highlight Status
                $statusVal = $sheet->getCell("H{$r}")->getValue();
                if ($statusVal === 'APPROVED') {
                    $sheet->getStyle("H{$r}")->getFont()->setBold(true)->getColor()->setRGB('059669');
                } elseif ($statusVal === 'PENDING') {
                    $sheet->getStyle("H{$r}")->getFont()->setBold(true)->getColor()->setRGB('D97706');
                } elseif ($statusVal === 'CANCELED') {
                    $sheet->getStyle("H{$r}")->getFont()->setBold(true)->getColor()->setRGB('DC2626');
                }

                // Currency format if included
                if ($this->includeAmounts) {
                    $sheet->getStyle("I{$r}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    $sheet->getStyle("J{$r}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                    $sheet->getStyle("J{$r}")->getFont()->setBold(true);
                }
            }

            // Summary row if amounts included
            if ($this->includeAmounts) {
                $totalRow = $endDataRow + 1;
                $sheet->mergeCells("A{$totalRow}:H{$totalRow}");
                $sheet->setCellValue("A{$totalRow}", 'TOTAL PENDAPATAN:');
                $sheet->setCellValue("J{$totalRow}", "=SUM(J{$startDataRow}:J{$endDataRow})");

                $sheet->getStyle("A{$totalRow}:J{$totalRow}")->applyFromArray([
                    'font' => [
                        'name' => 'Segoe UI',
                        'bold' => true,
                        'size' => 10,
                        'color' => ['rgb' => '0F172A'],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E0F2FE'],
                    ],
                    'borders' => [
                        'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '0284C7']],
                        'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '0284C7']],
                    ],
                ]);
                $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("J{$totalRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                $sheet->getRowDimension($totalRow)->setRowHeight(24);
            }
        }

        return [];
    }
}
