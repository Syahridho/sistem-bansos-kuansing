<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\PeriodeBantuan;
use App\Models\AssistanceType;
use Illuminate\Http\Request;

use App\Imports\AlternatifImport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class PeriodeBantuanController extends Controller
{
    public function index(Request $request)
    {
        $query = PeriodeBantuan::with(['alternatifs', 'assistanceType'])->withCount('alternatifs');
        $assistanceTypes = AssistanceType::orderBy('name')->get();

        if ($search = $request->input('search')) {
            $query->where('judul', 'like', "%{$search}%");
        }

        if ($typeId = $request->input('assistance_type_id')) {
            $query->where('assistance_type_id', $typeId);
        }

        $periodes = $query->latest('tanggal')->paginate(10)->withQueryString();

        return view('periode.index', compact('periodes', 'assistanceTypes'));
    }

    public function create()
    {
        $assistanceTypes = AssistanceType::orderBy('name')->get();
        return view('periode.create', compact('assistanceTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'              => 'required|string|max:255',
            'assistance_type_id' => 'required|exists:assistance_types,id',
            'tanggal'            => 'required|date',
        ]);

        $data = $request->only('judul', 'assistance_type_id', 'tanggal');
        $data['user_id'] = auth()->id();
        $periode = PeriodeBantuan::create($data);

        return redirect()->route('periode.show', $periode)
            ->with('success', 'Periode bantuan berhasil dibuat.');
    }

    public function show(Request $request, PeriodeBantuan $periode)
    {
        $periode->load(['assistanceType']);

        $query = $periode->alternatifs()->with(['penilaians.kriteria']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $alternatifs = $query->latest()->paginate(100)->withQueryString();

        $kriterias = Kriteria::where('assistance_type_id', $periode->assistance_type_id)
            ->orderBy('kode')->get();

        return view('periode.show', compact('periode', 'alternatifs', 'kriterias', 'search'));
    }

    public function edit(PeriodeBantuan $periode)
    {
        $assistanceTypes = AssistanceType::orderBy('name')->get();
        return view('periode.edit', compact('periode', 'assistanceTypes'));
    }

    public function update(Request $request, PeriodeBantuan $periode)
    {
        $request->validate([
            'judul'              => 'required|string|max:255',
            'assistance_type_id' => 'required|exists:assistance_types,id',
            'tanggal'            => 'required|date',
        ]);

        $periode->update($request->only('judul', 'assistance_type_id', 'tanggal'));

        return redirect()->route('periode.index')
            ->with('success', 'Periode bantuan berhasil diperbarui.');
    }

    public function destroy(PeriodeBantuan $periode)
    {
        $periode->delete();

        return redirect()->route('periode.index')
            ->with('success', 'Periode bantuan berhasil dihapus.');
    }

    public function toggleStatus(PeriodeBantuan $periode)
    {
        if (auth()->user()->role === 'operator' && $periode->status === 'tutup') {
            abort(403, 'Operator tidak memiliki akses untuk membuka kembali periode bantuan.');
        }

        $periode->update([
            'status' => $periode->status === 'buka' ? 'tutup' : 'buka'
        ]);

        $statusText = $periode->status === 'tutup' ? 'ditutup' : 'dibuka';
        return redirect()->back()->with('success', "Status periode berhasil {$statusText}.");
    }

    public function import(Request $request, PeriodeBantuan $periode)
    {
        if ($periode->status === 'tutup') {
            return redirect()->route('periode.show', $periode)->with('error', 'Periode bantuan ini telah ditutup.');
        }

        $request->validate([
            'excel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $importer = new AlternatifImport($periode);
            Excel::import($importer, $request->file('excel'));

            $warnings = $importer->duplicateWarnings;

            if (!empty($warnings)) {
                $count = count($warnings);
                $names = collect($warnings)->take(3)->map(fn($w) => $w['nama'])->join(', ');
                $more  = $count > 3 ? " dan " . ($count - 3) . " lainnya" : "";

                return redirect()->back()
                    ->with('success', "Data alternatif berhasil diimport!")
                    ->with('import_duplicate_warning', 
                        "{$count} warga ({$names}{$more}) memiliki NIK yang pernah terdaftar di periode lain. Data tetap diimport.");
            }

            return redirect()->back()->with('success', 'Data alternatif berhasil diimport!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
