<?php

namespace App\Services;

use App\Models\AssistanceType;
use App\Models\PeriodeBantuan;

class CalendarEventService
{
    private const TYPE_COLOR_RULES = [
        'blt' => '#2563eb',
        'pkh' => '#16a34a',
        'bpnt' => '#ea580c',
        'bsm' => '#9333ea',
        'lansia' => '#db2777',
        'rtlh' => '#d97706',
        'umkm' => '#0891b2',
        'melahirkan' => '#e11d48',
        'disabilitas' => '#7c3aed',
        'bencana' => '#dc2626',
    ];

    private const FALLBACK_PALETTE = [
        '#2563eb', '#16a34a', '#ea580c', '#9333ea', '#0891b2',
        '#db2777', '#d97706', '#e11d48', '#7c3aed', '#dc2626',
    ];

    /**
     * @return array<int, array<string, mixed>>
     */
    public function formatEvents(): array
    {
        return PeriodeBantuan::query()
            ->with('assistanceType:id,name')
            ->orderBy('tanggal_mulai')
            ->get()
            ->map(function (PeriodeBantuan $periode) {
                $jenisBantuan = $periode->assistanceType?->name ?? 'Lainnya';
                $color = $this->colorForAssistanceType($jenisBantuan, $periode->assistance_type_id);

                return [
                    'id' => $periode->id,
                    'title' => $periode->judul,
                    'start' => $periode->tanggal_mulai->format('Y-m-d'),
                    'end' => $periode->tanggal_akhir->copy()->addDay()->format('Y-m-d'),
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'judul_periode' => $periode->judul,
                        'jenis_bantuan' => $jenisBantuan,
                        'tanggal_mulai' => $periode->tanggal_mulai->format('d-m-Y'),
                        'tanggal_akhir' => $periode->tanggal_akhir->format('d-m-Y'),
                        'status' => $periode->status,
                        'status_label' => $periode->status === 'buka' ? 'Dibuka' : 'Ditutup',
                    ],
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{name: string, color: string}>
     */
    public function buildLegend(): array
    {
        return AssistanceType::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (AssistanceType $type) => [
                'name' => $type->name,
                'color' => $this->colorForAssistanceType($type->name, $type->id),
            ])
            ->values()
            ->all();
    }

    public function colorForAssistanceType(string $name, ?int $typeId = null): string
    {
        $normalized = strtolower($name);

        foreach (self::TYPE_COLOR_RULES as $keyword => $color) {
            if (str_contains($normalized, $keyword)) {
                return $color;
            }
        }

        $index = ($typeId ?? crc32($name)) % count(self::FALLBACK_PALETTE);

        return self::FALLBACK_PALETTE[$index];
    }
}
