<?php

namespace App\Exports;

use App\Models\PeriodeBantuan;
use App\Models\Kriteria;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class AlternatifTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithColumnFormatting
{
    protected $periode;
    protected $kriterias;

    public function __construct(PeriodeBantuan $periode)
    {
        $this->periode = $periode;
        $this->kriterias = Kriteria::where('assistance_type_id', $this->periode->assistance_type_id)
            ->orderBy('kode')
            ->get();
    }

    public function title(): string
    {
        return 'Template Import Warga';
    }

    public function headings(): array
    {
        $headers = ['NIK', 'Nama', 'Alamat'];
        foreach ($this->kriterias as $kriteria) {
            $headers[] = $kriteria->nama;
        }
        return $headers;
    }

    public function array(): array
    {
        // 1 row of example dummy data
        $exampleRow = [
            '1234567890123456', // NIK
            'Budi Santoso',     // Nama
            'Jl. Melati No. 123, Kuansing', // Alamat
        ];

        foreach ($this->kriterias as $kriteria) {
            switch ($kriteria->tipe_input) {
                case 'rupiah':
                    $exampleRow[] = 'Rp 1.500.000';
                    break;
                case 'checkbox':
                    $exampleRow[] = 'Ya';
                    break;
                case 'pilihan':
                case 'status':
                    // Pick the first option's label if available
                    $opsi = $kriteria->opsi;
                    if (!empty($opsi) && is_array($opsi)) {
                        $firstOpsi = $opsi[0];
                        $exampleRow[] = isset($firstOpsi['label']) ? $firstOpsi['label'] : (is_array($firstOpsi) ? '' : $firstOpsi);
                    } else {
                        $exampleRow[] = 'Layak';
                    }
                    break;
                case 'angka':
                default:
                    // Usia, jarak, dll
                    $namaLower = strtolower($kriteria->nama);
                    if (str_contains($namaLower, 'usia')) {
                        $exampleRow[] = '65';
                    } elseif (str_contains($namaLower, 'jarak')) {
                        $exampleRow[] = '5';
                    } else {
                        $exampleRow[] = '80';
                    }
                    break;
            }
        }

        return [
            $exampleRow
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function columnWidths(): array
    {
        $widths = [
            'A' => 24, // NIK
            'B' => 28, // Nama
            'C' => 38, // Alamat
        ];

        // Give remaining criteria columns a reasonable default width
        $cols = range('D', 'Z');
        $index = 0;
        foreach ($this->kriterias as $kriteria) {
            if ($index < count($cols)) {
                $widths[$cols[$index]] = 20;
                $index++;
            }
        }

        return $widths;
    }

    public function styles(Worksheet $sheet)
    {
        // Style headers row (Row 1)
        $lastCol = $sheet->getHighestColumn();
        
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF18181B'], // premium dark theme header
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style the example row (Row 2) to look slightly styled (e.g. italic / gray text) so they know it is an example
        $sheet->getStyle("A2:{$lastCol}2")->applyFromArray([
            'font' => [
                'italic' => true,
                'color'  => ['argb' => 'FF71717A'], // text-zinc-500
            ],
            'alignment' => [
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Center NIK column example
        $sheet->getStyle("A2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Force NIK (Column A) values to be treated as String explicitly to prevent scientific notation
        $sheet->getCell("A2")->setValueExplicit('1234567890123456', DataType::TYPE_STRING);

        $sheet->getRowDimension(1)->setRowHeight(28);
        $sheet->getRowDimension(2)->setRowHeight(22);
    }
}
