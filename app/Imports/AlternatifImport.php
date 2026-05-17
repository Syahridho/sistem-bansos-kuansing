<?php

namespace App\Imports;

use App\Models\PeriodeBantuan;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AlternatifImport implements WithMultipleSheets
{
    protected $periode;
    public $sheetInstance;

    public function __construct(PeriodeBantuan $periode)
    {
        $this->periode = $periode;
    }

    public function sheets(): array
    {
        // Index 0 = sheet pertama (Data Warga), ignore semua sheet lainnya
        $this->sheetInstance = new AlternatifSheetImport($this->periode);
        return [
            0 => $this->sheetInstance,
        ];
    }

    public function __get($name)
    {
        if ($name === 'duplicateWarnings') {
            return $this->sheetInstance ? $this->sheetInstance->duplicateWarnings : [];
        }
        return null;
    }
}
