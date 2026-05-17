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
        $kriterias = Kriteria::where('assistance_type_id', $periode->assistance_type_id)
            ->orderBy('kode')->get();

        return view('alternatif.create', compact('periode', 'kriterias'));
    }

    public function store(Request $request, PeriodeBantuan $periode)
    {
        $request->validate([
            'nik'    => 'required|string|max:16',
            'nama'   => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $exists = Alternatif::where('periode_bantuan_id', $periode->id)->where('nik', $request->nik)->exists();
        if ($exists) {
            return back()->withErrors(['nik' => 'NIK sudah terdaftar dalam periode ini.'])->withInput();
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
        $kriterias = Kriteria::where('assistance_type_id', $periode->assistance_type_id)
            ->orderBy('kode')->get();

        $penilaians = $alternatif->penilaians->keyBy('kriteria_id');

        return view('alternatif.edit', compact('periode', 'alternatif', 'kriterias', 'penilaians'));
    }

    public function update(Request $request, PeriodeBantuan $periode, Alternatif $alternatif)
    {
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
