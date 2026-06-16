<?php

namespace Database\Seeders;

use App\Models\AssistanceType;
use App\Models\Kriteria;
use Illuminate\Database\Seeder;

class AssistanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. BLT – Bantuan Langsung Tunai
        $blt = AssistanceType::create([
            'name'               => 'Bantuan Langsung Tunai (BLT)',
            'description'        => 'Pemberian uang tunai kepada keluarga miskin atau kurang mampu terdampak ekonomi.',
            'jumlah_diterima'    => 600000,
            'maksimal_penerima'  => 300,
        ]);
        Kriteria::create(['kode' => 'C1', 'nama' => 'Penghasilan Bulanan',       'bobot' => 0.40, 'jenis' => 'cost',    'tipe_input' => 'rupiah',  'opsi' => null, 'assistance_type_id' => $blt->id]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Jumlah Anggota Keluarga',   'bobot' => 0.30, 'jenis' => 'benefit', 'tipe_input' => 'angka',   'opsi' => null, 'assistance_type_id' => $blt->id]);
        Kriteria::create(['kode' => 'C3', 'nama' => 'Kondisi Rumah',             'bobot' => 0.30, 'jenis' => 'cost',    'tipe_input' => 'pilihan', 'opsi' => ['Sangat Layak' => 1, 'Layak' => 2, 'Cukup Layak' => 3, 'Tidak Layak' => 4, 'Sangat Tidak Layak' => 5], 'assistance_type_id' => $blt->id]);

        // 2. PKH – Program Keluarga Harapan
        $pkh = AssistanceType::create([
            'name'               => 'Program Keluarga Harapan (PKH)',
            'description'        => 'Bantuan sosial bersyarat untuk keluarga miskin dengan komponen pendidikan atau kesehatan.',
            'jumlah_diterima'    => 750000,
            'maksimal_penerima'  => 150,
        ]);
        Kriteria::create(['kode' => 'C1', 'nama' => 'Pendapatan Per Kapita',   'bobot' => 0.35, 'jenis' => 'cost',    'tipe_input' => 'rupiah',  'opsi' => null, 'assistance_type_id' => $pkh->id]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Jumlah Anak Sekolah',     'bobot' => 0.25, 'jenis' => 'benefit', 'tipe_input' => 'angka',   'opsi' => null, 'assistance_type_id' => $pkh->id]);
        Kriteria::create(['kode' => 'C3', 'nama' => 'Status Ibu Hamil',        'bobot' => 0.20, 'jenis' => 'benefit', 'tipe_input' => 'pilihan', 'opsi' => ['Tidak' => 1, 'Ya' => 5], 'assistance_type_id' => $pkh->id]);
        Kriteria::create(['kode' => 'C4', 'nama' => 'Kondisi Tempat Tinggal',  'bobot' => 0.20, 'jenis' => 'cost',    'tipe_input' => 'pilihan', 'opsi' => ['Permanen' => 1, 'Semi Permanen' => 3, 'Tidak Permanen' => 5], 'assistance_type_id' => $pkh->id]);

        // 3. BPNT – Bantuan Pangan Non-Tunai
        $bpnt = AssistanceType::create([
            'name'               => 'Bantuan Pangan Non-Tunai (BPNT)',
            'description'        => 'Program bantuan pangan berupa non-tunai untuk masyarakat kurang mampu.',
            'jumlah_diterima'    => 300000,
            'maksimal_penerima'  => 200,
        ]);
        Kriteria::create(['kode' => 'C1', 'nama' => 'Penghasilan Bulanan',  'bobot' => 0.30, 'jenis' => 'cost',    'tipe_input' => 'rupiah',  'opsi' => null, 'assistance_type_id' => $bpnt->id]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Jumlah Tanggungan',    'bobot' => 0.25, 'jenis' => 'benefit', 'tipe_input' => 'angka',   'opsi' => null, 'assistance_type_id' => $bpnt->id]);
        Kriteria::create(['kode' => 'C3', 'nama' => 'Kondisi Rumah',        'bobot' => 0.20, 'jenis' => 'cost',    'tipe_input' => 'pilihan', 'opsi' => ['Sangat Layak' => 1, 'Layak' => 2, 'Cukup Layak' => 3, 'Tidak Layak' => 4, 'Sangat Tidak Layak' => 5], 'assistance_type_id' => $bpnt->id]);
        Kriteria::create(['kode' => 'C4', 'nama' => 'Kepemilikan Aset',     'bobot' => 0.15, 'jenis' => 'cost',    'tipe_input' => 'angka',   'opsi' => null, 'assistance_type_id' => $bpnt->id]);
        Kriteria::create(['kode' => 'C5', 'nama' => 'Status Pekerjaan',     'bobot' => 0.10, 'jenis' => 'cost',    'tipe_input' => 'pilihan', 'opsi' => ['PNS' => 1, 'Wiraswasta' => 2, 'Pedagang' => 3, 'Buruh' => 4, 'Petani' => 5, 'Nelayan' => 5, 'Tidak Bekerja' => 5], 'assistance_type_id' => $bpnt->id]);
    }
}
