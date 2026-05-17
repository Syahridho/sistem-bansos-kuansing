<?php

namespace App\Exports;

use App\Models\PeriodeBantuan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PerangkinganExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithMapping, WithCustomStartCell, WithColumnFormatting
{
    private $periode;
    private $no = 0;
    private $totalCount = 0;

    public function __construct(PeriodeBantuan $periode)
    {
        $this->periode = $periode;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function collection()
    {
        $spkController = new \App\Http\Controllers\SpkController();
        $rankings = $spkController->calculateWP($this->periode);

        $maxPenerima = $this->periode->assistanceType->maksimal_penerima ?? 10;

        // Filter only alternatif where ranking <= maksimal_penerima
        $eligible = $rankings->filter(function ($item) use ($maxPenerima) {
            return $item['ranking'] <= $maxPenerima;
        })->values();

        $this->totalCount = $eligible->count();

        // Map and cast to objects so map() can access properties like an object
        return $eligible->map(function ($item) {
            return (object) $item;
        });
    }

    public function headings(): array
    {
        return ['No', 'Ranking', 'NIK', 'Nama', 'Alamat', 'Vektor S', 'Nilai Akhir (V)', 'Status'];
    }

    public function map($row): array
    {
        return [
            ++$this->no,
            $row->ranking,
            "\t" . (string) $row->nik,
            $row->nama,
            $row->alamat,
            number_format($row->vektor_s, 6),
            number_format($row->nilai_akhir, 6),
            'Layak',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function title(): string
    {
        return 'Penerima Layak';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 10,  // Ranking
            'C' => 22,  // NIK
            'D' => 28,  // Nama
            'E' => 38,  // Alamat
            'F' => 16,  // Vektor S
            'G' => 18,  // Nilai Akhir
            'H' => 12,  // Status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Write info headers on rows 1 to 4
        $sheet->setCellValue('A1', 'Daftar Penerima Bantuan Layak');
        $sheet->setCellValue('A2', 'Periode: ' . $this->periode->judul);
        $sheet->setCellValue('A3', 'Tanggal Export: ' . now()->format('d M Y'));
        $sheet->setCellValue('A4', 'Total Penerima: ' . $this->totalCount . ' orang');

        // Merge cells for info rows
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');
        $sheet->mergeCells('A4:H4');

        // Style info headers
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
        ]);
        $sheet->getStyle('A2:A4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
                'color' => ['argb' => 'FF52525B'], // neutral-600
            ],
        ]);

        // Style the table header row (row 6)
        $sheet->getStyle('A6:H6')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF18181B'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Apply alternating row colors to data rows (starts at row 7)
        $lastRow = $sheet->getHighestRow();
        if ($lastRow >= 7) {
            for ($i = 7; $i <= $lastRow; $i++) {
                $color = ($i % 2 === 0) ? 'FFF4F4F5' : 'FFFFFFFF';
                $sheet->getStyle("A{$i}:H{$i}")->applyFromArray([
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => $color],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
            }

            // Center NIK, Ranking, No, Status columns
            $sheet->getStyle("A7:B{$lastRow}")->getAlignment()
                  ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C7:C{$lastRow}")->getAlignment()
                  ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F7:H{$lastRow}")->getAlignment()
                  ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Force NIK column (C) as text from row 7 (where data starts)
            $sheet->getStyle("C7:C{$lastRow}")
                  ->getNumberFormat()
                  ->setFormatCode(NumberFormat::FORMAT_TEXT);

            // Also set explicit string data type for each NIK cell
            // This ensures no scientific notation regardless of Excel version
            for ($rowNum = 7; $rowNum <= $lastRow; $rowNum++) {
                $cell = $sheet->getCell("C{$rowNum}");
                $cell->setValueExplicit(
                    $cell->getValue(),
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );
            }
        }

        // Set row heights
        $sheet->getDefaultRowDimension()->setRowHeight(20);
        $sheet->getRowDimension(1)->setRowHeight(24);
        $sheet->getRowDimension(2)->setRowHeight(18);
        $sheet->getRowDimension(3)->setRowHeight(18);
        $sheet->getRowDimension(4)->setRowHeight(18);
        $sheet->getRowDimension(5)->setRowHeight(10); // blank spacer row
        $sheet->getRowDimension(6)->setRowHeight(28); // header row

        // Freeze pane starting from A7 so headers remain visible when scrolling
        $sheet->freezePane('A7');
    }
}
