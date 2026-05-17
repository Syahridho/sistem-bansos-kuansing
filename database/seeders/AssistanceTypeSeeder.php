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
        // 1. BPNT
        $bpnt = AssistanceType::create([
            'name' => 'Bantuan Pangan Non-Tunai (BPNT)',
            'description' => 'Program bantuan pangan berupa non-tunai untuk masyarakat kurang mampu.',
            'jumlah_diterima' => 300000,
            'maksimal_penerima' => 200,
        ]);
        Kriteria::create(['kode' => 'C1', 'nama' => 'Penghasilan Bulanan', 'bobot' => 0.30, 'jenis' => 'cost', 'tipe_input' => 'rupiah', 'opsi' => null, 'assistance_type_id' => $bpnt->id]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Jumlah Tanggungan', 'bobot' => 0.25, 'jenis' => 'benefit', 'tipe_input' => 'angka', 'opsi' => null, 'assistance_type_id' => $bpnt->id]);
        Kriteria::create(['kode' => 'C3', 'nama' => 'Kondisi Rumah', 'bobot' => 0.20, 'jenis' => 'cost', 'tipe_input' => 'pilihan', 'opsi' => ["Sangat Layak" => 1, "Layak" => 2, "Cukup Layak" => 3, "Tidak Layak" => 4, "Sangat Tidak Layak" => 5], 'assistance_type_id' => $bpnt->id]);
        Kriteria::create(['kode' => 'C4', 'nama' => 'Kepemilikan Aset', 'bobot' => 0.15, 'jenis' => 'cost', 'tipe_input' => 'angka', 'opsi' => null, 'assistance_type_id' => $bpnt->id]);
        Kriteria::create(['kode' => 'C5', 'nama' => 'Status Pekerjaan', 'bobot' => 0.10, 'jenis' => 'cost', 'tipe_input' => 'pilihan', 'opsi' => ["PNS" => 1, "Wiraswasta" => 2, "Pedagang" => 3, "Buruh" => 4, "Petani" => 5, "Nelayan" => 5, "Tidak Bekerja" => 5], 'assistance_type_id' => $bpnt->id]);

        // 2. PKH
        $pkh = AssistanceType::create([
            'name' => 'Program Keluarga Harapan (PKH)',
            'description' => 'Bantuan sosial bersyarat untuk keluarga miskin dengan komponen pendidikan atau kesehatan.',
            'jumlah_diterima' => 750000,
            'maksimal_penerima' => 150,
        ]);
        Kriteria::create(['kode' => 'C1', 'nama' => 'Pendapatan Per Kapita', 'bobot' => 0.35, 'jenis' => 'cost', 'tipe_input' => 'rupiah', 'opsi' => null, 'assistance_type_id' => $pkh->id]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Jumlah Anak Sekolah', 'bobot' => 0.25, 'jenis' => 'benefit', 'tipe_input' => 'angka', 'opsi' => null, 'assistance_type_id' => $pkh->id]);
        Kriteria::create(['kode' => 'C3', 'nama' => 'Status Ibu Hamil', 'bobot' => 0.20, 'jenis' => 'benefit', 'tipe_input' => 'pilihan', 'opsi' => ["Tidak" => 1, "Ya" => 5], 'assistance_type_id' => $pkh->id]);
        Kriteria::create(['kode' => 'C4', 'nama' => 'Kondisi Tempat Tinggal', 'bobot' => 0.20, 'jenis' => 'cost', 'tipe_input' => 'pilihan', 'opsi' => ["Permanen" => 1, "Semi Permanen" => 3, "Tidak Permanen" => 5], 'assistance_type_id' => $pkh->id]);

        // 3. BLT
        $blt = AssistanceType::create([
            'name' => 'Bantuan Langsung Tunai (BLT)',
            'description' => 'Pemberian uang tunai kepada keluarga miskin atau kurang mampu terdampak ekonomi.',
            'jumlah_diterima' => 600000,
            'maksimal_penerima' => 300,
        ]);
        Kriteria::create(['kode' => 'C1', 'nama' => 'Penghasilan Bulanan', 'bobot' => 0.40, 'jenis' => 'cost', 'tipe_input' => 'rupiah', 'opsi' => null, 'assistance_type_id' => $blt->id]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Jumlah Anggota Keluarga', 'bobot' => 0.30, 'jenis' => 'benefit', 'tipe_input' => 'angka', 'opsi' => null, 'assistance_type_id' => $blt->id]);
        Kriteria::create(['kode' => 'C3', 'nama' => 'Kondisi Rumah', 'bobot' => 0.30, 'jenis' => 'cost', 'tipe_input' => 'pilihan', 'opsi' => ["Sangat Layak" => 1, "Layak" => 2, "Cukup Layak" => 3, "Tidak Layak" => 4, "Sangat Tidak Layak" => 5], 'assistance_type_id' => $blt->id]);

        // 4. Bantuan Sosial Lansia
        $lansia = AssistanceType::create([
            'name' => 'Bantuan Sosial Lansia',
            'description' => 'Program jaminan sosial khusus untuk warga lanjut usia.',
            'jumlah_diterima' => 500000,
            'maksimal_penerima' => 100,
        ]);
        Kriteria::create(['kode' => 'C1', 'nama' => 'Usia', 'bobot' => 0.30, 'jenis' => 'benefit', 'tipe_input' => 'angka', 'opsi' => null, 'assistance_type_id' => $lansia->id]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Penghasilan Bulanan', 'bobot' => 0.30, 'jenis' => 'cost', 'tipe_input' => 'rupiah', 'opsi' => null, 'assistance_type_id' => $lansia->id]);
        Kriteria::create(['kode' => 'C3', 'nama' => 'Kondisi Kesehatan', 'bobot' => 0.25, 'jenis' => 'cost', 'tipe_input' => 'pilihan', 'opsi' => ["Sangat Sehat" => 1, "Sehat" => 2, "Cukup Sehat" => 3, "Sakit" => 4, "Sakit Parah" => 5], 'assistance_type_id' => $lansia->id]);
        Kriteria::create(['kode' => 'C4', 'nama' => 'Tinggal Bersama', 'bobot' => 0.15, 'jenis' => 'cost', 'tipe_input' => 'pilihan', 'opsi' => ["Keluarga Mampu" => 1, "Keluarga Kurang Mampu" => 3, "Sendiri" => 5], 'assistance_type_id' => $lansia->id]);

        // 5. BSM
        $bsm = AssistanceType::create([
            'name' => 'Bantuan Siswa Miskin (BSM)',
            'description' => 'Bantuan dana pendidikan untuk siswa/siswi dari keluarga kurang mampu.',
            'jumlah_diterima' => 450000,
            'maksimal_penerima' => 250,
        ]);
        Kriteria::create(['kode' => 'C1', 'nama' => 'Penghasilan Orang Tua', 'bobot' => 0.35, 'jenis' => 'cost', 'tipe_input' => 'rupiah', 'opsi' => null, 'assistance_type_id' => $bsm->id]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Jumlah Saudara Kandung', 'bobot' => 0.20, 'jenis' => 'benefit', 'tipe_input' => 'angka', 'opsi' => null, 'assistance_type_id' => $bsm->id]);
        Kriteria::create(['kode' => 'C3', 'nama' => 'Jarak ke Sekolah (km)', 'bobot' => 0.20, 'jenis' => 'benefit', 'tipe_input' => 'angka', 'opsi' => null, 'assistance_type_id' => $bsm->id]);
        Kriteria::create(['kode' => 'C4', 'nama' => 'Prestasi Akademik', 'bobot' => 0.25, 'jenis' => 'benefit', 'tipe_input' => 'pilihan', 'opsi' => ["Kurang" => 1, "Cukup" => 2, "Baik" => 3, "Sangat Baik" => 4, "Istimewa" => 5], 'assistance_type_id' => $bsm->id]);

        // 6. RTLH
        $rtlh = AssistanceType::create([
            'name' => 'Subsidi Rumah Tidak Layak Huni (RTLH)',
            'description' => 'Subsidi perbaikan rumah tinggal agar layak huni bagi keluarga prasejahtera.',
            'jumlah_diterima' => 15000000,
            'maksimal_penerima' => 50,
        ]);
        Kriteria::create(['kode' => 'C1', 'nama' => 'Penghasilan Bulanan', 'bobot' => 0.25, 'jenis' => 'cost', 'tipe_input' => 'rupiah', 'opsi' => null, 'assistance_type_id' => $rtlh->id]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Kondisi Atap', 'bobot' => 0.25, 'jenis' => 'cost', 'tipe_input' => 'pilihan', 'opsi' => ["Baik" => 1, "Cukup" => 2, "Rusak Ringan" => 3, "Rusak Berat" => 4, "Tidak Ada" => 5], 'assistance_type_id' => $rtlh->id]);
        Kriteria::create(['kode' => 'C3', 'nama' => 'Kondisi Dinding', 'bobot' => 0.25, 'jenis' => 'cost', 'tipe_input' => 'pilihan', 'opsi' => ["Baik" => 1, "Cukup" => 2, "Rusak Ringan" => 3, "Rusak Berat" => 4, "Tidak Ada" => 5], 'assistance_type_id' => $rtlh->id]);
        Kriteria::create(['kode' => 'C4', 'nama' => 'Kondisi Lantai', 'bobot' => 0.25, 'jenis' => 'cost', 'tipe_input' => 'pilihan', 'opsi' => ["Keramik" => 1, "Semen" => 2, "Papan" => 3, "Tanah" => 4], 'assistance_type_id' => $rtlh->id]);

        // 7. Bantuan UMKM
        $umkm = AssistanceType::create([
            'name' => 'Bantuan UMKM',
            'description' => 'Bantuan modal usaha mikro kecil dan menengah untuk pengembangan usaha.',
            'jumlah_diterima' => 2400000,
            'maksimal_penerima' => 75,
        ]);
        Kriteria::create(['kode' => 'C1', 'nama' => 'Omset Per Bulan', 'bobot' => 0.30, 'jenis' => 'benefit', 'tipe_input' => 'rupiah', 'opsi' => null, 'assistance_type_id' => $umkm->id]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Lama Usaha (tahun)', 'bobot' => 0.25, 'jenis' => 'benefit', 'tipe_input' => 'angka', 'opsi' => null, 'assistance_type_id' => $umkm->id]);
        Kriteria::create(['kode' => 'C3', 'nama' => 'Jumlah Karyawan', 'bobot' => 0.20, 'jenis' => 'benefit', 'tipe_input' => 'angka', 'opsi' => null, 'assistance_type_id' => $umkm->id]);
        Kriteria::create(['kode' => 'C4', 'nama' => 'Jenis Usaha', 'bobot' => 0.25, 'jenis' => 'benefit', 'tipe_input' => 'pilihan', 'opsi' => ["Kuliner" => 3, "Kerajinan" => 4, "Pertanian" => 4, "Perdagangan" => 3, "Jasa" => 2], 'assistance_type_id' => $umkm->id]);

        // 8. Bantuan Ibu Melahirkan
        $ibu = AssistanceType::create([
            'name' => 'Bantuan Ibu Melahirkan',
            'description' => 'Program perlindungan sosial pasca melahirkan bagi ibu dari keluarga prasejahtera.',
            'jumlah_diterima' => 1500000,
            'maksimal_penerima' => 120,
        ]);
        Kriteria::create(['kode' => 'C1', 'nama' => 'Penghasilan Keluarga', 'bobot' => 0.40, 'jenis' => 'cost', 'tipe_input' => 'rupiah', 'opsi' => null, 'assistance_type_id' => $ibu->id]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Jumlah Anak', 'bobot' => 0.25, 'jenis' => 'benefit', 'tipe_input' => 'angka', 'opsi' => null, 'assistance_type_id' => $ibu->id]);
        Kriteria::create(['kode' => 'C3', 'nama' => 'Akses ke Fasilitas Kesehatan', 'bobot' => 0.35, 'jenis' => 'cost', 'tipe_input' => 'pilihan', 'opsi' => ["Mudah" => 1, "Cukup" => 3, "Sulit" => 5], 'assistance_type_id' => $ibu->id]);

        // 9. Bantuan Disabilitas
        $disabilitas = AssistanceType::create([
            'name' => 'Bantuan Disabilitas',
            'description' => 'Bantuan sosial pembinaan dan penunjang hidup bagi penyandang disabilitas.',
            'jumlah_diterima' => 600000,
            'maksimal_penerima' => 80,
        ]);
        Kriteria::create(['kode' => 'C1', 'nama' => 'Penghasilan Bulanan', 'bobot' => 0.30, 'jenis' => 'cost', 'tipe_input' => 'rupiah', 'opsi' => null, 'assistance_type_id' => $disabilitas->id]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Tingkat Disabilitas', 'bobot' => 0.40, 'jenis' => 'benefit', 'tipe_input' => 'pilihan', 'opsi' => ["Ringan" => 1, "Sedang" => 3, "Berat" => 5], 'assistance_type_id' => $disabilitas->id]);
        Kriteria::create(['kode' => 'C3', 'nama' => 'Dukungan Keluarga', 'bobot' => 0.30, 'jenis' => 'cost', 'tipe_input' => 'pilihan', 'opsi' => ["Sangat Mendukung" => 1, "Mendukung" => 2, "Cukup" => 3, "Kurang" => 4, "Tidak Ada" => 5], 'assistance_type_id' => $disabilitas->id]);

        // 10. Bantuan Korban Bencana
        $bencana = AssistanceType::create([
            'name' => 'Bantuan Korban Bencana',
            'description' => 'Bantuan pemulihan pasca bencana untuk keluarga terdampak bencana alam.',
            'jumlah_diterima' => 1000000,
            'maksimal_penerima' => 500,
        ]);
        Kriteria::create(['kode' => 'C1', 'nama' => 'Tingkat Kerusakan Aset', 'bobot' => 0.35, 'jenis' => 'benefit', 'tipe_input' => 'pilihan', 'opsi' => ["Tidak Ada" => 1, "Ringan" => 2, "Sedang" => 3, "Berat" => 4, "Total" => 5], 'assistance_type_id' => $bencana->id]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Penghasilan Bulanan', 'bobot' => 0.30, 'jenis' => 'cost', 'tipe_input' => 'rupiah', 'opsi' => null, 'assistance_type_id' => $bencana->id]);
        Kriteria::create(['kode' => 'C3', 'nama' => 'Jumlah Anggota Keluarga', 'bobot' => 0.20, 'jenis' => 'benefit', 'tipe_input' => 'angka', 'opsi' => null, 'assistance_type_id' => $bencana->id]);
        Kriteria::create(['kode' => 'C4', 'nama' => 'Kondisi Tempat Tinggal', 'bobot' => 0.15, 'jenis' => 'cost', 'tipe_input' => 'pilihan', 'opsi' => ["Aman" => 1, "Rusak Ringan" => 2, "Rusak Berat" => 4, "Tidak Layak Huni" => 5], 'assistance_type_id' => $bencana->id]);
    }
}
