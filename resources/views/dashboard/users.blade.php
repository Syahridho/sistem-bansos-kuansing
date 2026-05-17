<x-app-layout>
    <x-slot name="header">Manajemen Pengguna</x-slot>

    <div x-data="userManagement()" class="space-y-6">

        <!-- Session Alerts -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 class="rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-700 flex items-center gap-3 shadow-sm transition duration-300"
                 x-transition:leave="opacity-0 scale-95">
                <svg class="w-5 h-5 shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 shrink-0 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700 shadow-sm">
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

        <!-- Subtitle and description -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="text-xs text-gray-500 mt-1">Kelola akun pengguna, ubah role, dan nonaktifkan akses akun jika diperlukan.</p>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pengguna</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                </div>
                <div class="p-3 bg-zinc-50 text-zinc-600 rounded-xl border border-zinc-100">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.97 5.97 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94-3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Operator</p>
                    <p class="text-2xl font-bold text-blue-600">{{ \App\Models\User::where('role', 'operator')->count() }}</p>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl border border-blue-100">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                    </svg>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nonaktif</p>
                    <p class="text-2xl font-bold text-rose-600">{{ \App\Models\User::where('is_active', false)->count() }}</p>
                </div>
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl border border-rose-100">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Search Bar and Main Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h3 class="font-bold text-gray-900 text-sm">Daftar Pengguna Sistem</h3>
                
                <!-- Search Form -->
                <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama atau email..."
                               class="h-9 w-full rounded-lg border border-gray-300 bg-white pl-9 pr-3 text-xs text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                    <button type="submit" class="h-9 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition shrink-0">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('users.index') }}" class="h-9 px-3 border border-gray-200 text-gray-500 hover:bg-gray-50 rounded-lg text-xs font-medium inline-flex items-center justify-center transition shrink-0">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse text-left">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/30">
                            <th class="h-11 px-6 text-[10px] font-bold uppercase tracking-wider text-gray-400 w-12 text-center">No</th>
                            <th class="h-11 px-6 text-[10px] font-bold uppercase tracking-wider text-gray-400">Nama & Email</th>
                            <th class="h-11 px-6 text-[10px] font-bold uppercase tracking-wider text-gray-400 w-32">Role</th>
                            <th class="h-11 px-6 text-[10px] font-bold uppercase tracking-wider text-gray-400 w-32 text-center">Status</th>
                            <th class="h-11 px-6 text-[10px] font-bold uppercase tracking-wider text-gray-400 w-44">Bergabung</th>
                            <th class="h-11 px-6 text-[10px] font-bold uppercase tracking-wider text-gray-400 w-64 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4.5 text-center font-medium text-gray-500 text-xs">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4.5">
                                    <div class="space-y-0.5">
                                        <p class="font-semibold text-gray-900 text-sm">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4.5">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center rounded-full bg-zinc-900 px-2.5 py-0.5 text-[10px] font-semibold text-zinc-100">
                                            Admin
                                        </span>
                                    @elseif($user->role === 'operator')
                                        <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 text-[10px] font-semibold">
                                            Operator
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-100 text-slate-700 border border-slate-200 px-2.5 py-0.5 text-[10px] font-semibold">
                                            Masyarakat
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4.5 text-center">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 text-[10px] font-semibold">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-rose-50 text-rose-700 border border-rose-200 px-2.5 py-0.5 text-[10px] font-semibold">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4.5 text-xs text-gray-500 font-medium">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4.5 text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <!-- Ubah Role Button -->
                                        <button type="button" 
                                                @click="openRoleModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->role }}')"
                                                class="inline-flex items-center justify-center h-8 px-3 border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg text-xs font-semibold shadow-sm transition">
                                            Ubah Role
                                        </button>

                                        <!-- Toggle Status Button -->
                                        @if($user->is_active)
                                            <button type="button" 
                                                    @click="openStatusModal({{ $user->id }}, '{{ addslashes($user->name) }}', true)"
                                                    class="inline-flex items-center justify-center h-8 px-3 border border-rose-200 hover:border-rose-300 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-semibold shadow-sm transition">
                                                Nonaktifkan
                                            </button>
                                        @else
                                            <button type="button" 
                                                    @click="openStatusModal({{ $user->id }}, '{{ addslashes($user->name) }}', false)"
                                                    class="inline-flex items-center justify-center h-8 px-3 border border-emerald-200 hover:border-emerald-300 text-emerald-600 hover:bg-emerald-50 rounded-lg text-xs font-semibold shadow-sm transition">
                                                Aktifkan
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-xs">
                                    <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.97 5.97 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94-3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>
                                    Tidak ada data pengguna ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            @if($users->hasPages())
                <div class="flex items-center justify-between px-6 py-4.5 border-t border-gray-100 bg-gray-50/50">
                    <span class="text-xs text-gray-400">
                        Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                    </span>
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Role Form (Hidden, Submitted via JS) -->
        <form x-ref="roleForm" method="POST" :action="`/dashboard/users/${selectedUserId}/role`" style="display:none;">
            @csrf
            <input type="hidden" name="role" :value="selectedRole">
        </form>

        <!-- Status Form (Hidden, Submitted via JS) -->
        <form x-ref="statusForm" method="POST" :action="`/dashboard/users/${selectedUserId}/toggle-status`" style="display:none;">
            @csrf
        </form>

        <!-- Modal 1: Ubah Role (Shadcn-like style) -->
        <div style="position:fixed; inset:0; z-index:50; display:flex; align-items:center; justify-content:center; background:rgba(9, 9, 11, 0.65); backdrop-filter: blur(4px);"
             x-show="showRoleModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            
            <div class="bg-white border border-gray-200 rounded-2xl shadow-xl p-6 max-w-md w-full mx-4 space-y-6 transform transition-all"
                 @click.away="showRoleModal = false"
                 x-show="showRoleModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4 sm:translate-y-0">
                
                <div class="space-y-1.5">
                    <h3 class="text-base font-bold text-gray-900">Ubah Role Pengguna</h3>
                    <p class="text-xs text-gray-500">Anda akan mengubah role akun <span class="font-semibold text-gray-800" x-text="selectedUserName"></span> dari role saat ini.</p>
                </div>

                <!-- Radio Group -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Pilih Role Baru</label>
                    
                    <div class="space-y-2">
                        <!-- Admin Radio -->
                        <label @click="selectedRole = 'admin'" 
                               class="flex items-center justify-between p-3.5 border rounded-xl cursor-pointer transition"
                               :class="selectedRole === 'admin' ? 'border-zinc-950 bg-zinc-50/50 ring-1 ring-zinc-950' : 'border-gray-200 hover:bg-gray-50'">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full border border-gray-300 flex items-center justify-center"
                                      :class="selectedRole === 'admin' ? 'border-zinc-950 bg-zinc-950' : ''">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white" x-show="selectedRole === 'admin'"></span>
                                </span>
                                <div>
                                    <p class="text-xs font-bold text-zinc-900">Admin</p>
                                    <p class="text-[10px] text-gray-400">Akses penuh terhadap data, kriteria, and pengaturan sistem.</p>
                                </div>
                            </div>
                        </label>

                        <!-- Operator Radio -->
                        <label @click="selectedRole = 'operator'" 
                               class="flex items-center justify-between p-3.5 border rounded-xl cursor-pointer transition"
                               :class="selectedRole === 'operator' ? 'border-blue-600 bg-blue-50/10 ring-1 ring-blue-600' : 'border-gray-200 hover:bg-gray-50'">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full border border-gray-300 flex items-center justify-center"
                                      :class="selectedRole === 'operator' ? 'border-blue-600 bg-blue-600' : ''">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white" x-show="selectedRole === 'operator'"></span>
                                </span>
                                <div>
                                    <p class="text-xs font-bold text-blue-900">Operator</p>
                                    <p class="text-[10px] text-gray-400">Akses input alternatif warga, kuesioner, and detail periode.</p>
                                </div>
                            </div>
                        </label>

                        <!-- Masyarakat Radio -->
                        <label @click="selectedRole = 'masyarakat'" 
                               class="flex items-center justify-between p-3.5 border rounded-xl cursor-pointer transition"
                               :class="selectedRole === 'masyarakat' ? 'border-gray-900 bg-slate-50 ring-1 ring-gray-900' : 'border-gray-200 hover:bg-gray-50'">
                            <div class="flex items-center gap-3">
                                <span class="w-4 h-4 rounded-full border border-gray-300 flex items-center justify-center"
                                      :class="selectedRole === 'masyarakat' ? 'border-gray-950 bg-gray-950' : ''">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white" x-show="selectedRole === 'masyarakat'"></span>
                                </span>
                                <div>
                                    <p class="text-xs font-bold text-gray-900">Masyarakat</p>
                                    <p class="text-[10px] text-gray-400">Melihat ranking perangkingan and cek mandiri bantuan.</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl flex gap-2.5 items-start">
                    <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-[10px] text-amber-800 leading-normal"><strong>Peringatan:</strong> Tindakan ini akan segera mengubah hak akses dan tingkat keamanan pengguna.</p>
                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" 
                            @click="showRoleModal = false"
                            class="h-9 px-4 border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition">
                        Batal
                    </button>
                    <button type="button" 
                            @click="submitRoleChange()"
                            class="h-9 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                        Ya, Ubah Role
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal 2: Toggle Status -->
        <div style="position:fixed; inset:0; z-index:50; display:flex; align-items:center; justify-content:center; background:rgba(9, 9, 11, 0.65); backdrop-filter: blur(4px);"
             x-show="showStatusModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            
            <div class="bg-white border border-gray-200 rounded-2xl shadow-xl p-6 max-w-md w-full mx-4 space-y-5 transform transition-all"
                 @click.away="showStatusModal = false"
                 x-show="showStatusModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4 sm:translate-y-0">
                
                <div class="space-y-1.5">
                    <h3 class="text-base font-bold text-gray-900" x-text="selectedUserActive ? 'Nonaktifkan Akun' : 'Aktifkan Akun'"></h3>
                    <p class="text-xs text-gray-500">
                        Apakah Anda yakin ingin <span x-text="selectedUserActive ? 'menonaktifkan' : 'mengaktifkan'"></span> akun pengguna <span class="font-semibold text-gray-800" x-text="selectedUserName"></span>?
                    </p>
                </div>

                <div x-show="selectedUserActive" class="p-3 bg-red-50 border border-red-200 rounded-xl flex gap-2.5 items-start">
                    <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-[10px] text-red-800 leading-normal">
                        <strong>Perhatian:</strong> Pengguna ini tidak akan bisa login, melakukan input data, atau mengakses fitur aplikasi lainnya sampai akun diaktifkan kembali.
                    </p>
                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" 
                            @click="showStatusModal = false"
                            class="h-9 px-4 border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition">
                        Batal
                    </button>
                    <button type="button" 
                            @click="submitStatusChange()"
                            class="h-9 px-4 text-white rounded-xl text-xs font-semibold shadow-sm transition"
                            :class="selectedUserActive ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700'">
                        <span x-text="selectedUserActive ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan'"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Alpine.js userManagement script -->
    <script>
        function userManagement() {
            return {
                // Role Modal
                showRoleModal: false,
                selectedUserId: null,
                selectedUserName: '',
                currentRole: '',
                selectedRole: '',

                openRoleModal(id, name, role) {
                    this.selectedUserId = id;
                    this.selectedUserName = name;
                    this.currentRole = role;
                    this.selectedRole = role;
                    this.showRoleModal = true;
                },
                submitRoleChange() {
                    if (this.selectedRole === this.currentRole) {
                        this.showRoleModal = false;
                        return;
                    }
                    this.$refs.roleForm.submit();
                },

                // Status Modal
                showStatusModal: false,
                selectedUserActive: true,

                openStatusModal(id, name, isActive) {
                    this.selectedUserId = id;
                    this.selectedUserName = name;
                    this.selectedUserActive = isActive;
                    this.showStatusModal = true;
                },
                submitStatusChange() {
                    this.$refs.statusForm.submit();
                }
            }
        }
    </script>
</x-app-layout>
