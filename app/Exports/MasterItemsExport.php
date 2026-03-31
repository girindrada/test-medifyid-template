<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MasterItemsExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles,
    ShouldAutoSize
{
    public function collection()
    {
        return MasterItem::with('kategori')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Nama Item',
            'Nama Supplier',
            'Harga Beli',
            'Laba',
            'Harga Jual',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        $hargaJual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

        return [
            $no,
            $item->kategori ? $item->kategori->nama : '-',
            $item->nama,
            $item->supplier ?? '-',
            $item->harga_beli,
            $item->laba,
            round($hargaJual),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header row
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E3A8A'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Format kolom harga sebagai number
        $lastRow = MasterItem::count() + 1;
        $sheet->getStyle("E2:E{$lastRow}")->getNumberFormat()
            ->setFormatCode('#,##0');
        $sheet->getStyle("G2:G{$lastRow}")->getNumberFormat()
            ->setFormatCode('#,##0');

        // Border semua cell
        $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        return [];
    }
}