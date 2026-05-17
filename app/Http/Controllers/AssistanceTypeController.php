<?php

namespace App\Http\Controllers;

use App\Models\AssistanceType;
use Illuminate\Http\Request;

class AssistanceTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = AssistanceType::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $assistanceTypes = $query->paginate(10)->withQueryString();

        return view('assistance_types.index', compact('assistanceTypes'));
    }

    public function create()
    {
        return view('assistance_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:assistance_types,name',
            'description' => 'nullable|string',
            'jumlah_diterima' => 'nullable|numeric|min:0',
            'maksimal_penerima' => 'nullable|integer|min:0',
        ]);

        AssistanceType::create($request->only('name', 'description', 'jumlah_diterima', 'maksimal_penerima'));

        return redirect()->route('kriteria.index')->with('success', 'Jenis Bantuan berhasil ditambahkan.');
    }

    public function edit(AssistanceType $assistanceType)
    {
        return view('assistance_types.edit', compact('assistanceType'));
    }

    public function update(Request $request, AssistanceType $assistanceType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:assistance_types,name,' . $assistanceType->id,
            'description' => 'nullable|string',
            'jumlah_diterima' => 'nullable|numeric|min:0',
            'maksimal_penerima' => 'nullable|integer|min:0',
        ]);

        $assistanceType->update($request->only('name', 'description', 'jumlah_diterima', 'maksimal_penerima'));

        return redirect()->route('assistance_types.index')->with('success', 'Jenis Bantuan berhasil diperbarui.');
    }

    public function destroy(AssistanceType $assistanceType)
    {
        $assistanceType->delete();
        return redirect()->route('assistance_types.index')->with('success', 'Jenis Bantuan berhasil dihapus.');
    }
}
