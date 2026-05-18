<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Tentang Sistem Bansos Kuansing

Sistem Bansos Kuansing adalah aplikasi web untuk mendukung pengelolaan bantuan sosial di Kabupaten Kuantan Singingi. Aplikasi ini memudahkan admin dan operator dalam membuat periode bantuan, menentukan jenis bantuan, mengelola kriteria penilaian, memasukkan data warga, serta melakukan perhitungan peringkat penerima dengan metode Weighted Product (WP). Sistem juga memeriksa duplikat NIK antar periode dan menampilkan peringatan tanpa menghentikan proses impor.

## Fitur Utama

- Manajemen periode bantuan dengan status `buka` / `tutup`
- Pengelolaan alternatif warga (`Alternatif`) dan penilaian kriteria (`Penilaian`)
- Definisi jenis bantuan (`AssistanceType`) dan kriteria penilaian (`Kriteria`)
- Impor data warga dan penilaian dari Excel dengan validasi header
- Peringatan duplikat NIK antar periode tetapi data tetap diimpor
- Perhitungan perangkingan WP dan ekspor hasil rekomendasi penerima
- Pencarian publik untuk mengecek bantuan berdasarkan NIK
- Otentikasi dan otorisasi user dengan peran `admin` dan `operator`

## Teknologi

- Laravel (PHP)
- Blade untuk templating frontend
- Vite untuk asset bundling
- Maatwebsite Excel untuk impor/ekspor data

## Cara Menjalankan

Jalankan perintah berikut dari root proyek:

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run dev
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Struktur Penting

- `routes/web.php` - definisi rute dan middleware
- `app/Http/Controllers/PeriodeBantuanController.php` - lifecycle periode, impor Excel, toggle status
- `app/Http/Controllers/SpkController.php` - perhitungan WP dan ekspor hasil
- `app/Imports/AlternatifImport.php` dan `app/Imports/AlternatifSheetImport.php` - logika impor Excel
- `app/Exports/PerangkinganExport.php` - ekspor hasil perangkingan
- `app/Services/NikValidationService.php` - validasi NIK lintas periode

## Pengujian

Jalankan:

```bash
vendor/bin/phpunit
# atau
php artisan test
```

## Lisensi

Aplikasi ini menggunakan lisensi MIT.
