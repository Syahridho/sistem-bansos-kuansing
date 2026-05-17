<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class LargeDataSeeder extends Seeder
{
    public function run(): void
    {
        // Disable query log to save memory
        DB::connection()->disableQueryLog();

        // Ensure we have at least one user
        $user = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $batchSize = 500; // Chunking for bulk inserts

        for ($i = 1; $i <= 100; $i++) {
            $this->command->info("Seeding Assistance Type $i of 100...");

            // 1. Create Assistance Type
            $typeId = DB::table('assistance_types')->insertGetId([
                'name' => "Jenis Bantuan " . $i,
                'description' => "Deskripsi untuk bantuan ke-" . $i,
                'jumlah_diterima' => rand(500000, 5000000),
                'maksimal_penerima' => rand(100, 1000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Create Kriteria for this type
            $kriterias = [
                ['kode' => 'C1', 'nama' => 'Penghasilan', 'bobot' => 0.3, 'jenis' => 'cost', 'tipe_input' => 'rupiah'],
                ['kode' => 'C2', 'nama' => 'Tanggungan', 'bobot' => 0.2, 'jenis' => 'benefit', 'tipe_input' => 'angka'],
                ['kode' => 'C3', 'nama' => 'Kondisi Rumah', 'bobot' => 0.2, 'jenis' => 'benefit', 'tipe_input' => 'pilihan', 'opsi' => json_encode([['label' => 'Sangat Layak', 'nilai' => 1], ['label' => 'Layak', 'nilai' => 2], ['label' => 'Cukup Layak', 'nilai' => 3], ['label' => 'Tidak Layak', 'nilai' => 4], ['label' => 'Sangat Tidak Layak', 'nilai' => 5]])],
                ['kode' => 'C4', 'nama' => 'Kepemilikan Aset', 'bobot' => 0.15, 'jenis' => 'cost', 'tipe_input' => 'angka'],
                ['kode' => 'C5', 'nama' => 'Pekerjaan', 'bobot' => 0.15, 'jenis' => 'benefit', 'tipe_input' => 'status', 'opsi' => json_encode([['label' => 'Tidak Bekerja', 'nilai' => 5], ['label' => 'Buruh', 'nilai' => 4], ['label' => 'Petani', 'nilai' => 3], ['label' => 'Wiraswasta', 'nilai' => 2], ['label' => 'PNS', 'nilai' => 1]])],
            ];

            $kriteriaIds = [];
            foreach ($kriterias as $k) {
                $k['assistance_type_id'] = $typeId;
                $k['created_at'] = now();
                $k['updated_at'] = now();
                $kriteriaIds[] = DB::table('kriterias')->insertGetId($k);
            }

            // 3. Create Periode Bantuan
            $periodeId = DB::table('periode_bantuans')->insertGetId([
                'judul' => "Periode " . $i . " - " . Carbon::now()->format('Y'),
                'assistance_type_id' => $typeId,
                'tanggal_mulai' => Carbon::now()->subMonths(rand(0, 12))->startOfMonth(),
                'tanggal_akhir' => Carbon::now()->subMonths(rand(0, 12))->endOfMonth(),
                'status' => rand(0, 1) ? 'buka' : 'tutup',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 4. Create 1000 Alternatifs in batches
            $alternatifsToInsert = [];
            for ($j = 1; $j <= 1000; $j++) {
                $alternatifsToInsert[] = [
                    'nik' => rand(100000, 999999) . rand(100000, 999999) . rand(1000, 9999),
                    'nama' => "Warga " . $j . " (Type $i)",
                    'alamat' => "Jl. Mawar No. $j, Kota Bantuan $i",
                    'periode_bantuan_id' => $periodeId,
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($alternatifsToInsert) >= $batchSize) {
                    DB::table('alternatifs')->insert($alternatifsToInsert);
                    
                    // Fetch IDs of the last inserted batch
                    $altIds = DB::table('alternatifs')
                        ->where('periode_bantuan_id', $periodeId)
                        ->orderBy('id', 'desc')
                        ->limit($batchSize)
                        ->pluck('id');

                    $penilaiansToInsert = [];
                    foreach ($altIds as $altId) {
                        foreach ($kriteriaIds as $kId) {
                            $penilaiansToInsert[] = [
                                'alternatif_id' => $altId,
                                'kriteria_id' => $kId,
                                'nilai' => rand(1, 5),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                    DB::table('penilaians')->insert($penilaiansToInsert);
                    $alternatifsToInsert = [];
                }
            }
            
            if (!empty($alternatifsToInsert)) {
                $remCount = count($alternatifsToInsert);
                DB::table('alternatifs')->insert($alternatifsToInsert);
                $altIds = DB::table('alternatifs')
                    ->where('periode_bantuan_id', $periodeId)
                    ->orderBy('id', 'desc')
                    ->limit($remCount)
                    ->pluck('id');

                $penilaiansToInsert = [];
                foreach ($altIds as $altId) {
                    foreach ($kriteriaIds as $kId) {
                        $penilaiansToInsert[] = [
                            'alternatif_id' => $altId,
                            'kriteria_id' => $kId,
                            'nilai' => rand(1, 5),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                DB::table('penilaians')->insert($penilaiansToInsert);
            }
        }
    }
}
