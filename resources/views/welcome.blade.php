<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ App\Models\Setting::get('app_name', 'SPK Bansos') }}</title>
    <meta name="description" content="{{ App\Models\Setting::get('app_description', 'Sistem Pendukung Keputusan Penerima Bantuan Sosial') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="antialiased bg-white text-gray-800">

    {{-- NAVBAR --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
            <a href="/" class="flex items-center gap-2.5">
                @if(App\Models\Setting::get('app_logo'))
                    <img src="{{ Storage::url(App\Models\Setting::get('app_logo')) }}" alt="Logo" class="w-9 h-9 rounded-lg object-contain">
                @else
                    <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                    </div>
                @endif
                <span class="text-lg font-bold text-gray-900">{{ App\Models\Setting::get('app_name', 'SPK Bansos') }}</span>
            </a>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-5 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">Daftar</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="bg-blue-600 py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-blue-500 text-white text-xs font-semibold uppercase tracking-wide mb-5">Metode AHP & Weighted Product</span>
                <h1 class="text-4xl md:text-5xl font-extrabold text-white leading-tight">
                    {{ App\Models\Setting::get('app_name', 'SPK Bansos') }}
                </h1>
                <p class="mt-5 text-lg text-blue-100 leading-relaxed max-w-lg">
                    {{ App\Models\Setting::get('app_description', 'Menentukan penerima bantuan sosial secara objektif, transparan, dan akurat menggunakan metode AHP untuk pembobotan dan Weighted Product untuk perangkingan.') }}
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('cek-bantuan.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-white text-blue-600 font-semibold hover:bg-blue-50 transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        Cek Status Bantuan Anda
                    </a>
                    
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border-2 border-white text-white font-semibold hover:bg-white/10 transition">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border-2 border-white text-white font-semibold hover:bg-white/10 transition">
                            Login Petugas
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Mock Table --}}
            <div class="hidden lg:block bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700">Contoh Hasil Perangkingan</h3>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-500">
                            <th class="text-left px-6 py-3 font-medium">#</th>
                            <th class="text-left px-6 py-3 font-medium">Nama</th>
                            <th class="text-left px-6 py-3 font-medium">Skor</th>
                            <th class="text-left px-6 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <tr class="border-b border-gray-50">
                            <td class="px-6 py-3 font-bold text-blue-600">1</td>
                            <td class="px-6 py-3">Ahmad Fauzi</td>
                            <td class="px-6 py-3 font-mono text-gray-500">0.2847</td>
                            <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Layak</span></td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="px-6 py-3 font-bold text-blue-600">2</td>
                            <td class="px-6 py-3">Siti Aminah</td>
                            <td class="px-6 py-3 font-mono text-gray-500">0.2531</td>
                            <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Layak</span></td>
                        </tr>
                        <tr class="border-b border-gray-50">
                            <td class="px-6 py-3 font-bold text-blue-600">3</td>
                            <td class="px-6 py-3">Budi Santoso</td>
                            <td class="px-6 py-3 font-mono text-gray-500">0.2214</td>
                            <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Layak</span></td>
                        </tr>
                        <tr>
                            <td class="px-6 py-3 text-gray-400">4</td>
                            <td class="px-6 py-3 text-gray-400">Dewi Lestari</td>
                            <td class="px-6 py-3 font-mono text-gray-400">0.1205</td>
                            <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Tidak Layak</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- FITUR --}}
    <section id="fitur" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14">
                <p class="text-blue-600 font-semibold text-sm uppercase tracking-widest mb-2">Fitur Utama</p>
                <h2 class="text-3xl font-bold text-gray-900">Mengapa Menggunakan Sistem Ini?</h2>
                <p class="mt-3 text-gray-500 max-w-2xl mx-auto">Sistem ini dirancang untuk membantu pemerintah desa/kelurahan dalam menentukan penerima bantuan sosial secara adil dan terukur.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="border border-gray-200 rounded-xl p-7 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Metode AHP</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Menghitung bobot prioritas kriteria menggunakan Analytical Hierarchy Process dengan uji konsistensi matriks perbandingan berpasangan.</p>
                </div>

                <div class="border border-gray-200 rounded-xl p-7 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Weighted Product</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Perangkingan alternatif menggunakan metode WP dengan perhitungan vektor S dan vektor V untuk menghasilkan ranking yang akurat.</p>
                </div>

                <div class="border border-gray-200 rounded-xl p-7 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Transparan & Objektif</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Setiap keputusan didukung data dan perhitungan matematis yang dapat diaudit, mengurangi subjektivitas dalam penyaluran bantuan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ALUR KERJA --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14">
                <p class="text-blue-600 font-semibold text-sm uppercase tracking-widest mb-2">Alur Kerja</p>
                <h2 class="text-3xl font-bold text-gray-900">Bagaimana Sistem Ini Bekerja?</h2>
            </div>

            <div class="grid md:grid-cols-4 gap-6">
                <div class="bg-white border border-gray-200 rounded-xl p-6 text-center">
                    <div class="text-4xl font-extrabold text-blue-100 mb-3">01</div>
                    <h3 class="text-base font-bold text-gray-900 mb-2">Input Kriteria</h3>
                    <p class="text-sm text-gray-500">Admin menentukan kriteria penilaian dan melakukan perbandingan AHP.</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-6 text-center">
                    <div class="text-4xl font-extrabold text-blue-100 mb-3">02</div>
                    <h3 class="text-base font-bold text-gray-900 mb-2">Input Data Warga</h3>
                    <p class="text-sm text-gray-500">Operator memasukkan data alternatif beserta nilai setiap kriteria.</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-6 text-center">
                    <div class="text-4xl font-extrabold text-blue-100 mb-3">03</div>
                    <h3 class="text-base font-bold text-gray-900 mb-2">Perhitungan WP</h3>
                    <p class="text-sm text-gray-500">Sistem menghitung vektor S dan V secara otomatis berdasarkan bobot AHP.</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-6 text-center">
                    <div class="text-4xl font-extrabold text-blue-100 mb-3">04</div>
                    <h3 class="text-base font-bold text-gray-900 mb-2">Hasil Ranking</h3>
                    <p class="text-sm text-gray-500">Daftar peringkat warga yang layak menerima bantuan sosial ditampilkan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-blue-600 py-16">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-3">Siap Mengecek Status Bantuan Anda?</h2>
            <p class="text-blue-100 text-lg mb-8">Gunakan NIK Anda untuk melihat apakah Anda terdaftar sebagai penerima bantuan sosial.</p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('cek-bantuan.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-lg bg-white text-blue-600 font-semibold hover:bg-blue-50 transition shadow-sm w-full sm:w-auto justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    Cek Status Bantuan
                </a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-lg border-2 border-white text-white font-semibold hover:bg-white/10 transition w-full sm:w-auto justify-center">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-lg border-2 border-white text-white font-semibold hover:bg-white/10 transition w-full sm:w-auto justify-center">
                        Login Petugas
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-white border-t border-gray-200 py-8">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} {{ App\Models\Setting::get('app_name', 'SPK Bansos') }}. All rights reserved.</p>
            <p class="text-gray-400 text-xs">Metode AHP & Weighted Product &mdash; Laravel {{ Illuminate\Foundation\Application::VERSION }}</p>
        </div>
    </footer>

</body>
</html>
