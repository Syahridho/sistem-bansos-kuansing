<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\AssistanceType;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index(Request $request)
    {
        $assistanceTypes = AssistanceType::orderBy('name')->get();
        if ($assistanceTypes->isEmpty()) {
            return redirect()->route('assistance_types.index')->with('error', 'Silakan tambahkan Jenis Bantuan terlebih dahulu.');
        }

        $activeTabId = $request->input('tab', $assistanceTypes->first()->id);

        $grouped = Kriteria::with('assistanceType')
            ->orderBy('kode')
            ->get()
            ->groupBy('assistance_type_id');

        return view('kriteria.index', compact('grouped', 'activeTabId', 'assistanceTypes'));
    }

    public function create(Request $request)
    {
        $assistanceTypes = AssistanceType::orderBy('name')->get();
        $selectedTypeId = $request->input('assistance_type_id');
        return view('kriteria.create', compact('assistanceTypes', 'selectedTypeId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'               => 'required|string|max:10',
            'nama'               => 'required|string|max:255',
            'bobot'              => 'required|numeric|min:0|max:1',
            'jenis'              => 'required|in:benefit,cost',
            'tipe_input'         => 'required|in:rupiah,angka,pilihan,checkbox,status',
            'assistance_type_id' => 'required|exists:assistance_types,id',
        ]);

        $exists = Kriteria::where('kode', $request->kode)
            ->where('assistance_type_id', $request->assistance_type_id)
            ->exists();
        if ($exists) {
            return back()->withErrors(['kode' => 'Kode sudah digunakan untuk jenis bantuan ini.'])->withInput();
        }

        $opsi = $this->buildOpsi($request);

        Kriteria::create([
            'kode'               => $request->kode,
            'nama'               => $request->nama,
            'bobot'              => $request->bobot,
            'jenis'              => $request->jenis,
            'tipe_input'         => $request->tipe_input,
            'opsi'               => $opsi,
            'assistance_type_id' => $request->assistance_type_id,
        ]);

        return redirect()->route('kriteria.index', ['tab' => $request->assistance_type_id])
            ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit(Kriteria $kriteria)
    {
        $assistanceTypes = AssistanceType::orderBy('name')->get();
        return view('kriteria.edit', compact('kriteria', 'assistanceTypes'));
    }

    public function update(Request $request, Kriteria $kriteria)
    {
        $request->validate([
            'kode'               => 'required|string|max:10',
            'nama'               => 'required|string|max:255',
            'bobot'              => 'required|numeric|min:0|max:1',
            'jenis'              => 'required|in:benefit,cost',
            'tipe_input'         => 'required|in:rupiah,angka,pilihan,checkbox,status',
            'assistance_type_id' => 'required|exists:assistance_types,id',
        ]);

        $exists = Kriteria::where('kode', $request->kode)
            ->where('assistance_type_id', $request->assistance_type_id)
            ->where('id', '!=', $kriteria->id)
            ->exists();
        if ($exists) {
            return back()->withErrors(['kode' => 'Kode sudah digunakan untuk jenis bantuan ini.'])->withInput();
        }

        $opsi = $this->buildOpsi($request);

        $kriteria->update([
            'kode'               => $request->kode,
            'nama'               => $request->nama,
            'bobot'              => $request->bobot,
            'jenis'              => $request->jenis,
            'tipe_input'         => $request->tipe_input,
            'opsi'               => $opsi,
            'assistance_type_id' => $request->assistance_type_id,
        ]);

        return redirect()->route('kriteria.index', ['tab' => $kriteria->assistance_type_id])
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(Kriteria $kriteria)
    {
        $tabId = $kriteria->assistance_type_id;
        $kriteria->delete();

        return redirect()->route('kriteria.index', ['tab' => $tabId])
            ->with('success', 'Kriteria berhasil dihapus.');
    }

    private function buildOpsi(Request $request): ?array
    {
        $tipe = $request->tipe_input;

        if (in_array($tipe, ['pilihan', 'status']) && $request->has('opsi_label')) {
            $labels = $request->opsi_label;
            $values = $request->opsi_nilai;
            $opsi = [];
            foreach ($labels as $i => $label) {
                if (!empty(trim($label))) {
                    $opsi[] = ['label' => trim($label), 'nilai' => (float)($values[$i] ?? 0)];
                }
            }
            return $opsi;
        }

        if ($tipe === 'checkbox' && $request->has('opsi_item')) {
            return array_values(array_filter(array_map('trim', $request->opsi_item)));
        }

        return null;
    }
}
