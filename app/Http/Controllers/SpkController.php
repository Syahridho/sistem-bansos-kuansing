<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\PeriodeBantuan;
use Illuminate\Http\Request;

class SpkController extends Controller
{
    public function calculateWP(PeriodeBantuan $periode)
    {
        // Only use criteria matching this periode's assistance type
        $kriterias = Kriteria::where('assistance_type_id', $periode->assistance_type_id)
            ->orderBy('kode')->get();

        $alternatifs = Alternatif::with('penilaians')
            ->where('periode_bantuan_id', $periode->id)
            ->get();

        if ($kriterias->isEmpty() || $alternatifs->isEmpty()) {
            return collect();
        }

        $totalBobot = $kriterias->sum('bobot');

        if ($totalBobot == 0) {
            return collect();
        }

        $bobotNormalisasi = [];
        foreach ($kriterias as $k) {
            $wj = $k->bobot / $totalBobot;
            $bobotNormalisasi[$k->id] = ($k->jenis === 'cost') ? -$wj : $wj;
        }

        $vektorS = [];
        foreach ($alternatifs as $alt) {
            $si = 1;
            $penilaianMap = $alt->penilaians->pluck('nilai', 'kriteria_id');

            foreach ($kriterias as $k) {
                $nilai = $penilaianMap[$k->id] ?? 0;
                if ($nilai <= 0) { $si = 0; break; }
                $si *= pow($nilai, $bobotNormalisasi[$k->id]);
            }
            $vektorS[$alt->id] = $si;
        }

        $totalS = array_sum($vektorS);

        $hasilAkhir = collect();
        foreach ($alternatifs as $alt) {
            $vi = ($totalS > 0) ? $vektorS[$alt->id] / $totalS : 0;
            $hasilAkhir->push([
                'id'          => $alt->id,
                'nik'         => $alt->nik,
                'nama'        => $alt->nama,
                'alamat'      => $alt->alamat,
                'vektor_s'    => round($vektorS[$alt->id], 6),
                'nilai_akhir' => round($vi, 6),
            ]);
        }

        $hasilAkhir = $hasilAkhir->sortByDesc('nilai_akhir')->values();

        // Assign rankings to each array item using map to modify collection correctly
        $hasilAkhir = $hasilAkhir->map(function ($item, $index) {
            $item['ranking'] = $index + 1;
            return $item;
        });

        return $hasilAkhir;
    }

    public function hitungWP(PeriodeBantuan $periode)
    {
        // Only use criteria matching this periode's assistance type
        $kriterias = Kriteria::where('assistance_type_id', $periode->assistance_type_id)
            ->orderBy('kode')->get();

        $hasilAkhir = $this->calculateWP($periode);

        if ($kriterias->isEmpty() || $hasilAkhir->isEmpty()) {
            return view('spk.hasil', [
                'hasilAkhir' => collect(),
                'kriterias'  => $kriterias,
                'totalBobot' => 0,
                'periode'    => $periode,
                'pesan'      => 'Data kriteria atau alternatif belum tersedia untuk periode ini.',
            ]);
        }

        $totalBobot = $kriterias->sum('bobot');

        if ($totalBobot == 0) {
            return view('spk.hasil', [
                'hasilAkhir' => collect(),
                'kriterias'  => $kriterias,
                'totalBobot' => 0,
                'periode'    => $periode,
                'pesan'      => 'Total bobot kriteria = 0.',
            ]);
        }

        $bobotNormalisasi = [];
        foreach ($kriterias as $k) {
            $wj = $k->bobot / $totalBobot;
            $bobotNormalisasi[$k->id] = ($k->jenis === 'cost') ? -$wj : $wj;
        }

        // 1. Filter Search (Server-side)
        if (request('search')) {
            $q = strtolower(request('search'));
            $hasilAkhir = $hasilAkhir->filter(function($item) use ($q) {
                return str_contains(strtolower($item['nama'] ?? ''), $q) ||
                       str_contains(strtolower($item['nik'] ?? ''), $q);
            });
        }

        // 2. Pagination Logic (Manual)
        $perPage = 15;
        $page = request()->get('page', 1);
        $total = $hasilAkhir->count();
        
        $rankingsSliced = $hasilAkhir->slice(($page - 1) * $perPage, $perPage)->values();
        
        $paginatedResults = new \Illuminate\Pagination\LengthAwarePaginator(
            $rankingsSliced,
            $total,
            $perPage,
            $page,
            [
                'path' => route('spk.hasil', $periode->id),
                'query' => request()->query()
            ]
        );

        $maxPenerima = $periode->assistanceType->maksimal_penerima ?? 10;
        
        // Menghitung jumlah yang "Layak" secara eksplisit dari data hasil ranking (sebelum dipaginate)
        $jumlahLayak = $hasilAkhir->take($maxPenerima)->count();
        
        $sisaKuota = max(0, $maxPenerima - $jumlahLayak);
        
        $uangDiberikan = $periode->assistanceType->jumlah_diterima ?? 0;

        return view('spk.hasil', [
            'hasilAkhir' => $paginatedResults,
            'kriterias' => $kriterias,
            'totalBobot' => $totalBobot,
            'bobotNormalisasi' => $bobotNormalisasi,
            'periode' => $periode,
            'maxPenerima' => $maxPenerima,
            'jumlahLayak' => $jumlahLayak,
            'sisaKuota' => $sisaKuota,
            'uangDiberikan' => $uangDiberikan
        ]);
    }

    public function export(PeriodeBantuan $periode)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PerangkinganExport($periode),
            'penerima-layak-' . \Illuminate\Support\Str::slug($periode->judul) . '-' . now()->format('Ymd') . '.xlsx'
        );
    }
}
