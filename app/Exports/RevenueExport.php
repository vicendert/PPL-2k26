<?php

namespace App\Exports;

use App\Models\Booking;
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

class RevenueExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithCustomStartCell
{
    private int $rowCount = 0;
    private int $currentRow = 0;

    public function title(): string
    {
        return 'Laporan Keuangan';
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
            ->where('status', Booking::STATUS_CONFIRMED)
            ->orderBy('date', 'desc')
            ->get();

        $this->rowCount = $bookings->count();

        return $bookings;
    }

    public function headings(): array
    {
        return [
            'No',
            'ID Booking',
            'Tanggal',
            'Lapangan',
            'Nama Pelanggan',
            'Total Pendapatan',
        ];
    }

    public function map($booking): array
    {
        $this->currentRow++;
        $displayName = $booking->customer_name ?? $booking->user?->name ?? '-';

        return [
            $this->currentRow,
            '#' . $booking->id,
            $booking->date?->format('d/m/Y') ?? '-',
            $booking->court->name ?? '-',
            $displayName,
            (float) $booking->total_price,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = 'F';
        $headerRow = 4;
        $startDataRow = 5;
        $endDataRow = max($startDataRow + $this->rowCount - 1, $startDataRow);

        // 1. Title Banner
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'PADELCOURT — LAPORAN KEUANGAN & PENDAPATAN');
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

        // 2. Subtitle
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Sistem Manajemen Lapangan Padel | Tanggal Cetak: ' . now()->format('d/m/Y H:i') . ' WIB');
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

        // 3. Table Headings
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
                'startColor' => ['rgb' => '0D9488'], // Teal Website
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '0F766E'],
                ],
            ],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(26);

        // 4. Data Rows
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

                $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("B{$r}")->getFont()->setBold(true)->getColor()->setRGB('0D9488');
                $sheet->getStyle("D{$r}")->getFont()->setBold(true);

                $sheet->getStyle("F{$r}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
                $sheet->getStyle("F{$r}")->getFont()->setBold(true);
            }

            // Total row
            $totalRow = $endDataRow + 1;
            $sheet->mergeCells("A{$totalRow}:E{$totalRow}");
            $sheet->setCellValue("A{$totalRow}", 'TOTAL PENDAPATAN BERSIH:');
            $sheet->setCellValue("F{$totalRow}", "=SUM(F{$startDataRow}:F{$endDataRow})");

            $sheet->getStyle("A{$totalRow}:F{$totalRow}")->applyFromArray([
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
                    'startColor' => ['rgb' => 'CCFBF1'],
                ],
                'borders' => [
                    'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '0D9488']],
                    'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '0D9488']],
                ],
            ]);
            $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("F{$totalRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheet->getRowDimension($totalRow)->setRowHeight(24);
        }

        return [];
    }
}
