<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\PeriodeBantuan;
use Illuminate\Http\Request;

class AlternatifController extends Controller
{
    public function create(PeriodeBantuan $periode)
    {
        if ($periode->status === 'tutup') {
            return redirect()->route('periode.show', $periode)->with('error', 'Periode bantuan ini telah ditutup.');
        }

        $kriterias = Kriteria::where('assistance_type_id', $periode->assistance_type_id)
            ->orderBy('kode')->get();

        return view('alternatif.create', compact('periode', 'kriterias'));
    }

    public function store(Request $request, PeriodeBantuan $periode, \App\Services\NikValidationService $nikValidationService)
    {
        if ($periode->status === 'tutup') {
            return redirect()->route('periode.show', $periode)->with('error', 'Periode bantuan ini telah ditutup.');
        }

        $request->validate([
            'nik'    => 'required|string|max:16',
            'nama'   => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $exists = Alternatif::where('periode_bantuan_id', $periode->id)->where('nik', $request->nik)->exists();
        if ($exists) {
            return back()->withErrors(['nik' => 'NIK sudah terdaftar dalam periode ini.'])->withInput();
        }

        $duplicates = $nikValidationService->checkDuplicate(
            $request->nik,
            $periode->id
        );

        if ($duplicates->isNotEmpty()) {
            // Check if user confirmed they want to proceed despite duplicate
            if (!$request->boolean('confirm_duplicate')) {
                $periodeList = $duplicates->map(function ($alt) {
                    return $alt->periodeBantuan->judul . 
                           ' (' . $alt->periodeBantuan->assistanceType->name . ')';
                })->join(', ');

                return redirect()->back()
                    ->withInput()
                    ->with('duplicate_warning', [
                        'nik'      => $request->nik,
                        'nama'     => $duplicates->first()->nama,
                        'periodes' => $periodeList,
                    ]);
            }
            // If confirmed, proceed to save normally
        }

        $alternatif = Alternatif::create([
            'periode_bantuan_id' => $periode->id,
            'nik'    => $request->nik,
            'nama'   => $request->nama,
            'alamat' => $request->alamat,
            'user_id' => auth()->id(),
        ]);

        $this->simpanPenilaian($request, $alternatif, $periode->assistance_type_id);

        return redirect()->route('periode.show', $periode)
            ->with('success', 'Data alternatif berhasil ditambahkan.');
    }

    public function edit(PeriodeBantuan $periode, Alternatif $alternatif)
    {
        if ($periode->status === 'tutup') {
            return redirect()->route('periode.show', $periode)->with('error', 'Periode bantuan ini telah ditutup.');
        }

        $kriterias = Kriteria::where('assistance_type_id', $periode->assistance_type_id)
            ->orderBy('kode')->get();

        $penilaians = $alternatif->penilaians->keyBy('kriteria_id');

        $riwayat = Alternatif::where('nik', $alternatif->nik)
            ->where('id', '!=', $alternatif->id)
            ->with(['periodeBantuan.assistanceType'])
            ->get();

        return view('alternatif.edit', compact('periode', 'alternatif', 'kriterias', 'penilaians', 'riwayat'));
    }

    public function update(Request $request, PeriodeBantuan $periode, Alternatif $alternatif)
    {
        if ($periode->status === 'tutup') {
            return redirect()->route('periode.show', $periode)->with('error', 'Periode bantuan ini telah ditutup.');
        }

        $request->validate([
            'nik'    => 'required|string|max:16',
            'nama'   => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $exists = Alternatif::where('periode_bantuan_id', $periode->id)
            ->where('nik', $request->nik)
            ->where('id', '!=', $alternatif->id)
            ->exists();
        if ($exists) {
            return back()->withErrors(['nik' => 'NIK sudah terdaftar dalam periode ini.'])->withInput();
        }

        $alternatif->update($request->only('nik', 'nama', 'alamat'));
        $this->simpanPenilaian($request, $alternatif, $periode->assistance_type_id);

        return redirect()->route('periode.show', $periode)
            ->with('success', 'Data alternatif berhasil diperbarui.');
    }

    public function destroy(PeriodeBantuan $periode, Alternatif $alternatif)
    {
        if ($periode->status === 'tutup') {
            return redirect()->route('periode.show', $periode)->with('error', 'Periode bantuan ini telah ditutup.');
        }

        $alternatif->delete();
        return redirect()->route('periode.show', $periode)
            ->with('success', 'Data alternatif berhasil dihapus.');
    }

    private function simpanPenilaian(Request $request, Alternatif $alternatif, int $assistanceTypeId): void
    {
        $kriterias = Kriteria::where('assistance_type_id', $assistanceTypeId)->get()->keyBy('id');

        foreach ($kriterias as $id => $kriteria) {
            $nilai = 0;
            $detail = null;

            switch ($kriteria->tipe_input) {
                case 'rupiah':
                    $raw = $request->input("nilai.$id", '0');
                    $nilai = (float) str_replace(['.', ','], ['', '.'], $raw);
                    break;
                case 'angka':
                case 'pilihan':
                case 'status':
                    $nilai = (float) $request->input("nilai.$id", 0);
                    break;
                case 'checkbox':
                    $items = $request->input("nilai_detail.$id", []);
                    if (is_array($items)) {
                        $detail = json_encode($items);
                        $nilai = count($items);
                    }
                    break;
            }

            Penilaian::updateOrCreate(
                ['alternatif_id' => $alternatif->id, 'kriteria_id' => $id],
                ['nilai' => $nilai, 'nilai_detail' => $detail]
            );
        }
    }
}
