<?php

namespace App\Imports;

use App\Models\PeriodeBantuan;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AlternatifImport implements WithMultipleSheets
{
    protected $periode;

    public function __construct(PeriodeBantuan $periode)
    {
        $this->periode = $periode;
    }

    public function sheets(): array
    {
        // Index 0 = sheet pertama (Data Warga), ignore semua sheet lainnya
        return [
            0 => new AlternatifSheetImport($this->periode),
        ];
    }
}
