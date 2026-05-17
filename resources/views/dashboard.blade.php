<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    {{-- Welcome Banner --}}
    <div class="bg-blue-600 rounded-xl p-4 sm:p-6 mb-6 sm:mb-8">
        <h2 class="text-lg sm:text-xl font-bold text-white">Selamat Datang, {{ Auth::user()->name }}!</h2>
        <p class="mt-1 text-blue-100 text-xs sm:text-sm">Sistem Pendukung Keputusan Penerima Bantuan Sosial — Metode AHP & Weighted Product</p>
    </div>

    {{-- Statistik Card --}}
    <div class="mb-6 sm:mb-8">
        @if(auth()->user()->role === 'admin')
            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Statistik Sistem</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-5">
                <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                    <div class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Total Periode</div>
                    <div class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $stats['total_periode'] ?? 0 }}</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                    <div class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Total Warga</div>
                    <div class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $stats['total_warga'] ?? 0 }}</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                    <div class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Jenis Bantuan</div>
                    <div class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $stats['total_jenis_bantuan'] ?? 0 }}</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                    <div class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Total Pengguna</div>
                    <div class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $stats['total_user'] ?? 0 }}</div>
                </div>
            </div>

            {{-- Grafik Warga per Bulan --}}
            <div class="mt-6 sm:mt-8 bg-white border border-gray-200 rounded-xl p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-900">Grafik Pendaftaran Warga</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Jumlah warga yang didaftarkan setiap bulan.</p>
                    </div>
                    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2 w-full sm:w-auto">
                        <label for="year" class="text-sm font-medium text-gray-700 whitespace-nowrap">Tahun:</label>
                        <select name="year" id="year" onchange="this.form.submit()" class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 w-full sm:w-auto">
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                
                <div class="w-full overflow-x-auto pb-2">
                    <div class="relative h-48 sm:h-64 md:h-72 min-w-[600px]">
                        <canvas id="wargaChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Chart.js --}}
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('wargaChart').getContext('2d');
                    const chartData = @json($chartData);
                    
                    // Shorter labels on mobile
                    const isMobile = window.innerWidth < 640;
                    const labels = isMobile 
                        ? ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D']
                        : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Warga',
                                data: chartData,
                                borderColor: '#2563eb', // blue-600
                                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                pointBackgroundColor: '#2563eb',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: '#2563eb',
                                pointRadius: isMobile ? 2 : 4,
                                pointHoverRadius: isMobile ? 4 : 6,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                        font: { size: isMobile ? 10 : 12 }
                                    }
                                },
                                x: {
                                    ticks: {
                                        font: { size: isMobile ? 10 : 12 }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                });
            </script>
        @elseif(auth()->user()->role === 'operator')
            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Aktivitas Saya</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5">
                <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                    <div class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Periode Dibuat</div>
                    <div class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $stats['periode_dibuat'] ?? 0 }}</div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                    <div class="text-xs sm:text-sm font-medium text-gray-500 mb-1">Warga Diinput</div>
                    <div class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $stats['warga_diinput'] ?? 0 }}</div>
                </div>
            </div>
        @elseif(auth()->user()->role === 'masyarakat')
            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Riwayat Bantuan Saya</h3>
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-5">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                </div>
                <div>
                    <h4 class="text-lg sm:text-xl font-bold text-gray-900">{{ $stats['kali_ikut'] ?? 0 }} Kali</h4>
                    <p class="text-gray-500 text-xs sm:text-sm">Anda telah terdaftar dalam sistem penerima bantuan sosial sebanyak ini.</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Alur Kerja --}}
    <div>
        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-4">Alur Kerja Sistem</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5 text-center">
                <div class="text-3xl font-extrabold text-blue-100 mb-2">01</div>
                <h4 class="font-semibold text-gray-900 mb-1">Input Kriteria</h4>
                <p class="text-xs sm:text-sm text-gray-500">Admin menentukan kriteria penilaian dan melakukan perbandingan AHP.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5 text-center">
                <div class="text-3xl font-extrabold text-blue-100 mb-2">02</div>
                <h4 class="font-semibold text-gray-900 mb-1">Input Data Warga</h4>
                <p class="text-xs sm:text-sm text-gray-500">Operator memasukkan data alternatif beserta nilai setiap kriteria.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5 text-center">
                <div class="text-3xl font-extrabold text-blue-100 mb-2">03</div>
                <h4 class="font-semibold text-gray-900 mb-1">Perhitungan WP</h4>
                <p class="text-xs sm:text-sm text-gray-500">Sistem menghitung vektor S dan V secara otomatis berdasarkan bobot AHP.</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5 text-center">
                <div class="text-3xl font-extrabold text-blue-100 mb-2">04</div>
                <h4 class="font-semibold text-gray-900 mb-1">Hasil Ranking</h4>
                <p class="text-xs sm:text-sm text-gray-500">Daftar peringkat warga yang layak menerima bantuan sosial ditampilkan.</p>
            </div>
        </div>
    </div>
</x-app-layout>
