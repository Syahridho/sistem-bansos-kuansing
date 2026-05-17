<?php

namespace Database\Seeders;

use App\Models\AssistanceType;
use App\Models\PeriodeBantuan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PeriodeBantuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : null;

        $types = AssistanceType::all();

        foreach ($types as $type) {
            PeriodeBantuan::create([
                'judul' => 'Periode ' . $type->name . ' 2026',
                'assistance_type_id' => $type->id,
                'tanggal' => Carbon::create(2026, rand(1, 6), rand(1, 28))->toDateString(),
                'status' => 'buka',
                'user_id' => $adminId,
            ]);
        }
    }
}
