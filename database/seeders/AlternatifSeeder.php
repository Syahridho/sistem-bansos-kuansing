<?php

namespace Database\Seeders;

use App\Models\Alternatif;
use App\Models\Penilaian;
use App\Models\PeriodeBantuan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlternatifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable query log for performance
        DB::disableQueryLog();

        $periodes = PeriodeBantuan::all();
        $userIds = User::pluck('id')->toArray();

        foreach ($periodes as $periode) {
            // Wrap each period in a DB transaction for maximum speed and integrity
            DB::transaction(function () use ($periode, $userIds) {
                $assistanceType = $periode->assistanceType;
                $kriterias = $assistanceType->kriterias;

                // 500 - 1000 random warga per periode
                $count = rand(500, 1000);
                $chunkSize = 100;

                for ($i = 0; $i < $count; $i += $chunkSize) {
                    $currentChunkSize = min($chunkSize, $count - $i);
                    $penilaiansData = [];

                    for ($j = 0; $j < $currentChunkSize; $j++) {
                        // Create Alternatif individually to get the generated ID safely
                        $alternatif = Alternatif::create([
                            'nik'                => fake()->unique()->numerify('################'), // 16 digits
                            'nama'               => fake()->name(),
                            'alamat'             => fake()->address(),
                            'periode_bantuan_id' => $periode->id,
                            'user_id'            => fake()->randomElement($userIds),
                        ]);

                        foreach ($kriterias as $kriteria) {
                            $nilai = 0;
                            $nilaiDetail = '';

                            switch ($kriteria->tipe_input) {
                                case 'rupiah':
                                    // Generate realistic income values
                                    $nilai = fake()->randomElement([
                                        500000, 750000, 1000000, 1250000, 1500000,
                                        2000000, 2500000, 3000000, 3500000, 4000000
                                    ]);
                                    $nilaiDetail = 'Rp ' . number_format($nilai, 0, ',', '.');
                                    break;

                                case 'angka':
                                    // Range depends on kriteria nama keywords
                                    $namaLower = strtolower($kriteria->nama);
                                    if (str_contains($namaLower, 'usia')) {
                                        $nilai = fake()->numberBetween(60, 90);
                                    } elseif (str_contains($namaLower, 'jarak')) {
                                        $nilai = fake()->numberBetween(1, 30);
                                    } elseif (str_contains($namaLower, 'karyawan')) {
                                        $nilai = fake()->numberBetween(0, 10);
                                    } elseif (str_contains($namaLower, 'lama usaha')) {
                                        $nilai = fake()->numberBetween(1, 20);
                                    } else {
                                        $nilai = fake()->numberBetween(1, 8);
                                    }
                                    $nilaiDetail = (string) $nilai;
                                    break;

                                case 'pilihan':
                                case 'status':
                                    // Pick random key from opsi, use its value as nilai
                                    $opsi = is_array($kriteria->opsi) ? $kriteria->opsi : json_decode($kriteria->opsi, true);
                                    if (!empty($opsi)) {
                                        $label = array_rand($opsi);
                                        $nilai = (float) $opsi[$label];
                                        $nilaiDetail = $label;
                                    } else {
                                        $nilai = fake()->numberBetween(1, 5);
                                        $nilaiDetail = (string) $nilai;
                                    }
                                    break;

                                case 'checkbox':
                                    $nilai = fake()->randomElement([0, 1]);
                                    $nilaiDetail = $nilai ? 'Ya' : 'Tidak';
                                    break;

                                default:
                                    $nilai = fake()->numberBetween(1, 5);
                                    $nilaiDetail = (string) $nilai;
                                    break;
                            }

                            $penilaiansData[] = [
                                'alternatif_id' => $alternatif->id,
                                'kriteria_id'   => $kriteria->id,
                                'nilai'         => $nilai,
                                'nilai_detail'  => $nilaiDetail,
                                'created_at'    => now(),
                                'updated_at'    => now(),
                            ];
                        }
                    }

                    // Bulk insert Penilaians to keep memory usage extremely low and DB performance high
                    Penilaian::insert($penilaiansData);
                }
            });
        }
    }
}
