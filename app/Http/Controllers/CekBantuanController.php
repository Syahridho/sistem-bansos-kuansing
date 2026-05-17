<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alternatif;

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

        // Semua riwayat pendaftaran NIK (bisa lebih dari satu periode/jenis bantuan)
        $alternatifs = Alternatif::where('nik', $nik)
            ->with(['periodeBantuan.assistanceType'])
            ->get()
            ->filter(fn (Alternatif $alt) => $alt->periodeBantuan !== null)
            ->sortByDesc(fn (Alternatif $alt) => $alt->periodeBantuan->tanggal_mulai)
            ->values();

        return view('cek-bantuan', compact('alternatifs', 'nik'));
    }
}
