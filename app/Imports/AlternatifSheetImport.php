<?php

namespace App\Imports;

use App\Models\Alternatif;
use App\Models\Penilaian;
use App\Models\Kriteria;
use App\Models\PeriodeBantuan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Exception;

class AlternatifSheetImport implements ToCollection, WithHeadingRow
{
    protected $periode;
    protected $nikValidator;
    public array $duplicateWarnings = [];

    public function __construct(PeriodeBantuan $periode)
    {
        $this->periode = $periode;
        $this->nikValidator = new \App\Services\NikValidationService();
    }

    public function collection(Collection $rows)
    {
        $kriterias = Kriteria::where('assistance_type_id', $this->periode->assistance_type_id)->get();
        
        // Cek apakah file excel kosong
        if ($rows->isEmpty()) {
            throw new Exception("File Excel kosong atau tidak memiliki data.");
        }

        // Validasi header
        $firstRow = $rows->first()->toArray();
        $excelHeaders = array_keys($firstRow);
        
        $requiredHeaders = [
            $this->normalizeHeader('NIK'),    // → 'nik'
            $this->normalizeHeader('Nama'),   // → 'nama'
            $this->normalizeHeader('Alamat'), // → 'alamat'
        ];
        foreach ($kriterias as $kriteria) {
            $requiredHeaders[] = $this->normalizeHeader($kriteria->nama);
        }

        foreach ($requiredHeaders as $reqHeader) {
            if (!in_array($reqHeader, $excelHeaders)) {
                throw new Exception(
                    "Format kolom tidak sesuai. Kolom '{$reqHeader}' tidak ditemukan. " .
                    "Header yang terdeteksi: " . implode(', ', $excelHeaders)
                );
            }
        }

        // Bulk check all NIKs for duplicates in one query
        $allNiks = $rows->pluck('nik')->filter()->map(fn($n) => (string)$n)->toArray();
        $duplicateNiks = $this->nikValidator->bulkCheck($allNiks, $this->periode->id);

        // Store duplicate info to attach to import summary
        $this->duplicateWarnings = [];

        foreach ($rows as $row) {
            // Abaikan baris kosong
            if (empty($row['nik']) || empty($row['nama'])) {
                continue;
            }

            $nikStr = (string) ($row['nik'] ?? '');

            // Log duplicate warning but DO NOT skip — still import the row
            if (isset($duplicateNiks[$nikStr])) {
                $previousPeriodes = collect($duplicateNiks[$nikStr])
                    ->map(fn($d) => $d['periode_bantuan']['judul'])
                    ->join(', ');
                $this->duplicateWarnings[] = [
                    'nik'      => $nikStr,
                    'nama'     => $row['nama'] ?? '',
                    'periodes' => $previousPeriodes,
                ];
            }

            // Simpan Alternatif
            $alternatif = Alternatif::updateOrCreate(
                [
                    'nik' => $row['nik'],
                    'periode_bantuan_id' => $this->periode->id,
                ],
                [
                    'nama' => $row['nama'],
                    'alamat' => $row['alamat'] ?? null,
                    'user_id' => auth()->id(),
                ]
            );

            // Simpan Penilaian
            foreach ($kriterias as $kriteria) {
                $kriteriaHeader = $this->normalizeHeader($kriteria->nama);
                $raw = $row[$kriteriaHeader] ?? null;

                switch ($kriteria->tipe_input) {
                    case 'rupiah':
                        // Strip semua karakter non-angka (Rp, titik, koma, spasi)
                        $bersih = preg_replace('/[^0-9]/', '', (string) $raw);
                        $nilai = (float) $bersih;
                        break;
                
                    case 'pilihan':
                    case 'status':
                        // Lookup nilai numerik dari kolom opsi (JSON)
                        $opsi = is_array($kriteria->opsi) ? $kriteria->opsi : [];
                        $rawTrimmed = trim((string) $raw);
                        $nilai = 0;

                        // Check if opsi is formatted as [['label' => '...', 'nilai' => ...]]
                        if (!empty($opsi) && isset($opsi[0]['label'])) {
                            foreach ($opsi as $item) {
                                if (isset($item['label']) && strtolower((string)$item['label']) === strtolower($rawTrimmed)) {
                                    $nilai = (float) ($item['nilai'] ?? 0);
                                    break;
                                }
                            }
                        } else {
                            // Handle flat key-value format: ["Tidak Layak" => 2]
                            $opsiLower = [];
                            foreach ($opsi as $k => $v) {
                                $opsiLower[strtolower((string)$k)] = $v;
                            }
                            if (isset($opsiLower[strtolower($rawTrimmed)])) {
                                $nilai = (float) $opsiLower[strtolower($rawTrimmed)];
                            }
                        }
                        break;
                
                    case 'checkbox':
                        // 1 jika tercentang/true/ya/1, selainnya 0
                        $nilai = in_array(strtolower(trim((string) $raw)), ['1','true','ya','yes','✓']) ? 1.0 : 0.0;
                        break;
                
                    case 'angka':
                    default:
                        $nilai = (float) str_replace(',', '.', (string) $raw);
                        break;
                }
                
                // Simpan nilai mentah ke nilai_detail
                $nilaiDetail = (string) $raw;

                Penilaian::updateOrCreate(
                    [
                        'alternatif_id' => $alternatif->id,
                        'kriteria_id' => $kriteria->id,
                    ],
                    [
                        'nilai' => $nilai,
                        'nilai_detail' => $nilaiDetail,
                    ]
                );
            }
        }
    }

    private function normalizeHeader(string $header): string
    {
        // Match Maatwebsite Excel HeadingRowFormatter::slug behavior
        return strtolower(trim(preg_replace('/\s+/', '_', 
            preg_replace('/[^a-zA-Z0-9\s]/', '', $header)
        )));
    }
}
