<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\AssistanceType;
use App\Models\PeriodeBantuan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $stats = [];
        $chartData = [];
        $availableYears = [];
        $selectedYear = $request->input('year', date('Y'));

        if ($user->role === 'admin') {
            $stats['total_periode'] = PeriodeBantuan::count();
            $stats['total_warga'] = Alternatif::count();
            $stats['total_jenis_bantuan'] = AssistanceType::count();
            $stats['total_user'] = User::count();

            $availableYears = Alternatif::selectRaw('YEAR(created_at) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->toArray();

            if (! in_array(date('Y'), $availableYears)) {
                $availableYears[] = date('Y');
                rsort($availableYears);
            }

            $wargaPerBulan = Alternatif::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', $selectedYear)
                ->groupBy('month')
                ->pluck('count', 'month')
                ->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $chartData[] = $wargaPerBulan[$i] ?? 0;
            }
        } elseif ($user->role === 'operator') {
            $stats['periode_dibuat'] = PeriodeBantuan::where('user_id', $user->id)->count();
            $stats['warga_diinput'] = Alternatif::where('user_id', $user->id)->count();
        } elseif ($user->role === 'masyarakat') {
            $stats['kali_ikut'] = $user->nik
                ? Alternatif::where('nik', $user->nik)->count()
                : 0;
        }

        return view('dashboard', compact(
            'stats',
            'chartData',
            'selectedYear',
            'availableYears',
        ));
    }
}
