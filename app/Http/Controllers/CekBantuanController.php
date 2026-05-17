<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\PeriodeBantuan;

class CekBantuanController extends Controller
{
    public function index()
    {
        return view('cek-bantuan');
    }

    public function search(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:16'
        ]);

        $nik = $request->input('nik');

        // Cari alternatif (warga) dengan NIK tersebut
        $alternatif = Alternatif::where('nik', $nik)
            ->with(['periodeBantuan.assistanceType'])
            ->latest()
            ->first();

        return view('cek-bantuan', compact('alternatif', 'nik'));
    }
}
