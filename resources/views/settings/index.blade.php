<x-app-layout>
    <x-slot name="header">Pengaturan Sistem</x-slot>

    <!-- Session Alerts -->
    @if(session('success'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-700 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 shrink-0 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 shrink-0 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="font-semibold">Mohon perbaiki kesalahan berikut:</span>
            </div>
            <ul class="list-disc pl-7 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Informasi Aplikasi (Takes 2 columns on large screens) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 11.517 1.294l-.041.02a.75.75 0 01-.517-1.294zm1.5 1.5l.041-.02a.75.75 0 11.517 1.294l-.041.02a.75.75 0 01-.517-1.294zm-3-3l.041-.02a.75.75 0 11.517 1.294l-.041.02a.75.75 0 01-.517-1.294zm1.5 1.5l.041-.02a.75.75 0 11.517 1.294l-.041.02a.75.75 0 01-.517-1.294zm-3-3l.041-.02a.75.75 0 11.517 1.294l-.041.02a.75.75 0 01-.517-1.294zm1.5 1.5l.041-.02a.75.75 0 11.517 1.294l-.041.02a.75.75 0 01-.517-1.294z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Informasi Aplikasi</h3>
                            <p class="text-xs text-gray-500">Sesuaikan nama identitas dan logo sistem bansos Anda</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Nama Aplikasi -->
                        <div>
                            <label for="app_name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Aplikasi</label>
                            <input type="text" name="app_name" id="app_name" value="{{ App\Models\Setting::get('app_name') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm" placeholder="Contoh: SPK Bansos">
                        </div>

                        <!-- Deskripsi Aplikasi -->
                        <div>
                            <label for="app_description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Aplikasi</label>
                            <textarea name="app_description" id="app_description" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm" placeholder="Jelaskan mengenai sistem ini...">{{ App\Models\Setting::get('app_description') }}</textarea>
                        </div>

                        <!-- Logo Aplikasi -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Logo Aplikasi</label>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 p-4 bg-gray-50 rounded-xl border border-gray-200/60">
                                <div class="w-20 h-20 rounded-xl bg-white border border-gray-200/80 shadow-sm flex items-center justify-center overflow-hidden shrink-0">
                                    @if(App\Models\Setting::get('app_logo'))
                                        <img src="{{ Storage::url(App\Models\Setting::get('app_logo')) }}" alt="App Logo" class="w-full h-full object-contain p-1">
                                    @else
                                        <!-- Fallback placeholder logo -->
                                        <div class="text-gray-300 flex flex-col items-center">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375 0 11-.75 0 .375 0 11.75 0z" />
                                            </svg>
                                            <span class="text-[10px] font-medium text-gray-400 mt-1">Belum ada</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-2 w-full">
                                    <input type="file" name="app_logo" id="app_logo" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                                    <p class="text-xs text-gray-400">Format PNG/JPG, maks 2MB. Logo akan ditampilkan di bagian navigasi aplikasi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Mode Maintenance (Takes 1 column on large screens) -->
            <div class="space-y-6" x-data="{ maintenanceOn: {{ App\Models\Setting::get('maintenance_mode') == '1' ? 'true' : 'false' }} }">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-orange-50 text-orange-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l-4.2-4.2m8.4 4.2a2.653 2.653 0 000-3.75l-4.2-4.2m-4.2 4.2L3 3m4.2 4.2a2.653 2.653 0 003.75 0l4.2-4.2M7.2 11.4H3m14.4 0h3.6M11.4 7.2V3m0 14.4v3.6" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Mode Maintenance</h3>
                                <p class="text-xs text-gray-500">Kendalikan akses publik saat perawatan website</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Status Badge and Toggle -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200/60">
                            <div class="space-y-1">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Mode</span>
                                <div class="flex items-center gap-2">
                                    <span x-show="maintenanceOn" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700" style="display: none;">
                                        Aktif
                                    </span>
                                    <span x-show="!maintenanceOn" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        Nonaktif
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Premium Toggle Switch -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="maintenance_mode" value="0">
                                <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" x-model="maintenanceOn">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                            </label>
                        </div>

                        <!-- Maintenance Message (Shown only when toggled active) -->
                        <div x-show="maintenanceOn" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="space-y-2"
                             style="display: none;">
                            <label for="maintenance_message" class="block text-sm font-semibold text-gray-700">Pesan Pemeliharaan</label>
                            <textarea name="maintenance_message" id="maintenance_message" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm" placeholder="Pesan ini akan ditampilkan ke pengguna umum...">{{ App\Models\Setting::get('maintenance_message') }}</textarea>
                            <p class="text-xs text-gray-400">Pengguna dengan role Operator dan Masyarakat akan melihat pesan ini secara full-screen saat berkunjung. Administrator tetap dapat masuk.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Form Footer -->
        <div class="mt-8 flex justify-end gap-3 p-4 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-sm font-semibold text-white transition shadow-sm hover:shadow">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25L7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                </svg>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</x-app-layout>
