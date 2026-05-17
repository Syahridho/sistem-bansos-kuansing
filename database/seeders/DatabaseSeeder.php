<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks to safely truncate existing data if seed is run separately
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Truncate tables to ensure a clean slate (SettingsSeeder handles its own table)
        \App\Models\User::truncate();
        \App\Models\AssistanceType::truncate();
        \App\Models\Kriteria::truncate();
        \App\Models\PeriodeBantuan::truncate();
        \App\Models\Alternatif::truncate();
        \App\Models\Penilaian::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Call the seeders in the designated correct order
        $this->call([
            UserSeeder::class,
            SettingsSeeder::class,      // existing - do not change
            AssistanceTypeSeeder::class,
            PeriodeBantuanSeeder::class,
            AlternatifSeeder::class,
        ]);
    }
}
