<?php
namespace App\Services;

use App\Models\Alternatif;
use App\Models\PeriodeBantuan;

class NikValidationService
{
    /**
     * Check if NIK has received aid in any OTHER closed periode.
     * Returns collection of matching alternatifs with periode info.
     */
    public function checkDuplicate(string $nik, int $currentPeriodeId): \Illuminate\Support\Collection
    {
        return Alternatif::where('nik', $nik)
            ->where('periode_bantuan_id', '!=', $currentPeriodeId)
            ->whereHas('periodeBantuan', function ($q) {
                $q->where('status', 'tutup');
            })
            ->with(['periodeBantuan.assistanceType'])
            ->get();
    }

    /**
     * Bulk check for Excel import — returns array of NIKs that are duplicates.
     * Key: nik, Value: collection of previous periode info
     */
    public function bulkCheck(array $niks, int $currentPeriodeId): array
    {
        $duplicates = Alternatif::whereIn('nik', $niks)
            ->where('periode_bantuan_id', '!=', $currentPeriodeId)
            ->whereHas('periodeBantuan', function ($q) {
                $q->where('status', 'tutup');
            })
            ->with(['periodeBantuan.assistanceType'])
            ->get()
            ->groupBy('nik');

        return $duplicates->toArray();
    }
}
