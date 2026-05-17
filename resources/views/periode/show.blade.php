<x-app-layout>
    <x-slot name="header">{{ $periode->judul }}</x-slot>

    @if(session('success'))
        <div class="mb-4 sm:mb-6 rounded-lg bg-green-50 border border-green-200 p-3 sm:p-4 text-sm text-green-700 flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('import_duplicate_warning'))
        <div class="mb-4 sm:mb-6 rounded-lg bg-amber-50 border border-amber-200 p-3 sm:p-4 text-sm text-amber-800 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="font-semibold mb-0.5">Peringatan Duplikat NIK</p>
                <p class="text-xs text-amber-700 leading-relaxed">{{ session('import_duplicate_warning') }}</p>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 sm:mb-6 rounded-lg bg-red-50 border border-red-200 p-3 sm:p-4 text-sm text-red-700 flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 sm:mb-6 rounded-lg bg-red-50 border border-red-200 p-3 sm:p-4 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Periode Info --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h2 class="text-base sm:text-lg font-bold text-gray-900">{{ $periode->judul }}</h2>
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $periode->assistanceType->name }}</span>
                    @if($periode->status === 'tutup')
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-zinc-900 text-zinc-100">Terkunci</span>
                    @endif
                </div>
                <p class="text-xs sm:text-sm text-gray-500">Tanggal: {{ $periode->tanggal->format('d F Y') }} &middot; {{ $alternatifs->total() }} warga terdaftar</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <a href="{{ route('spk.hasil', $periode) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-green-600 text-sm font-semibold text-white hover:bg-green-700 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                    Hitung Perangkingan
                </a>
                @if($periode->status === 'buka')
                    <a href="{{ route('alternatif.create', $periode) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Warga
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if($periode->status === 'tutup')
        <div class="mb-4 sm:mb-6 rounded-xl bg-zinc-50 border border-zinc-200 p-4 sm:p-5 text-sm text-zinc-700 flex items-start gap-3 shadow-sm">
            <div class="p-2 bg-zinc-900 text-white rounded-lg shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-zinc-900">Periode Bantuan Ini Telah Ditutup</h4>
                <p class="text-xs text-zinc-500 mt-0.5">Hasil perhitungan penerima bantuan telah ditetapkan secara resmi dan dikunci. Modifikasi data warga (tambah, edit, hapus, import) dinonaktifkan.</p>
            </div>
        </div>
    @endif

    {{-- Data Warga Table --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <h3 class="text-sm sm:text-base font-semibold text-gray-900">Data Warga (Alternatif)</h3>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <form action="{{ route('periode.show', $periode) }}" method="GET" class="relative w-full sm:w-auto">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIK / Nama..." class="w-full sm:w-64 pl-9 pr-4 py-2 rounded-lg border border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </form>

                @if($periode->status === 'buka')
                    <x-modal-import-excel :periodeId="$periode->id" />
                @endif
            </div>
        </div>

        @if($alternatifs->isEmpty())
            <div class="px-4 sm:px-6 py-12 text-center">
                <p class="text-sm text-gray-500">Belum ada data warga dalam periode ini.</p>
                <a href="{{ route('alternatif.create', $periode) }}" class="inline-flex items-center gap-2 mt-3 text-sm font-medium text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Tambah warga pertama
                </a>
            </div>
        @else
            {{-- Desktop: Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left px-6 py-3 font-medium text-gray-500">#</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500">NIK</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500">Nama</th>
                            <th class="text-left px-6 py-3 font-medium text-gray-500">Alamat</th>
                            @foreach($kriterias as $k)
                                <th class="text-center px-4 py-3 font-medium text-gray-500">{{ $k->kode }}</th>
                            @endforeach
                            @if($periode->status === 'buka')
                                <th class="text-right px-6 py-3 font-medium text-gray-500">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($alternatifs as $index => $alt)
                            @php $nilaiMap = $alt->penilaians->keyBy('kriteria_id'); @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-3 text-gray-400">{{ $alternatifs->firstItem() + $index }}</td>
                                <td class="px-6 py-3 font-mono text-gray-600 text-xs">{{ $alt->nik }}</td>
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $alt->nama }}</td>
                                <td class="px-6 py-3 text-gray-500 max-w-[150px] truncate">{{ $alt->alamat ?? '-' }}</td>
                                @foreach($kriterias as $k)
                                    @php
                                        $pen = $nilaiMap[$k->id] ?? null;
                                        $val = $pen ? $pen->nilai : null;
                                    @endphp
                                    <td class="px-4 py-3 text-center text-gray-600 text-xs">
                                        @if($val === null)
                                            <span class="text-gray-300">-</span>
                                        @elseif($k->tipe_input === 'rupiah')
                                            Rp {{ number_format($val, 0, ',', '.') }}
                                        @elseif($k->tipe_input === 'angka')
                                            {{ (int) $val }}
                                        @elseif($k->tipe_input === 'pilihan' && $k->opsi)
                                            @php $label = collect($k->opsi)->firstWhere('nilai', $val); @endphp
                                            {{ $label ? $label['label'] : $val }}
                                        @elseif($k->tipe_input === 'checkbox')
                                            @php $items = $pen && $pen->nilai_detail ? json_decode($pen->nilai_detail, true) : []; @endphp
                                            {{ implode(', ', $items) ?: (int)$val }}
                                        @elseif($k->tipe_input === 'status' && $k->opsi)
                                            @php $label = collect($k->opsi)->firstWhere('nilai', $val); @endphp
                                            {{ $label ? $label['label'] : $val }}
                                        @else
                                            {{ $val }}
                                        @endif
                                    </td>
                                @endforeach
                                @if($periode->status === 'buka')
                                    <td class="px-6 py-3 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('alternatif.edit', [$periode, $alt]) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 border border-gray-300 hover:bg-gray-50 transition">Edit</a>
                                            <form action="{{ route('alternatif.destroy', [$periode, $alt]) }}" method="POST" @submit.prevent="triggerConfirm($event.target, 'Hapus Data Warga', 'Apakah Anda yakin ingin menghapus data warga {{ $alt->nama }} dari periode ini?', 'Tindakan ini akan menghapus seluruh data alternatif warga dan hasil penilaian di dalamnya secara permanen.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 border border-red-200 hover:bg-red-50 transition">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile/Tablet: Card layout --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($alternatifs as $index => $alt)
                    @php $nilaiMap = $alt->penilaians->keyBy('kriteria_id'); @endphp
                    <div class="px-4 py-3">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $alt->nama }}</h4>
                                <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $alt->nik }}</p>
                            </div>
                            @if($periode->status === 'buka')
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <a href="{{ route('alternatif.edit', [$periode, $alt]) }}" class="px-2.5 py-1 rounded-lg text-xs font-medium text-gray-600 border border-gray-300 hover:bg-gray-50 transition">Edit</a>
                                    <form action="{{ route('alternatif.destroy', [$periode, $alt]) }}" method="POST" @submit.prevent="triggerConfirm($event.target, 'Hapus Data Warga', 'Apakah Anda yakin ingin menghapus data warga {{ $alt->nama }} dari periode ini?', 'Tindakan ini akan menghapus seluruh data alternatif warga dan hasil penilaian di dalamnya secara permanen.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-medium text-red-600 border border-red-200 hover:bg-red-50 transition">Hapus</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        @if($alt->alamat)
                            <p class="text-xs text-gray-500 mb-2">{{ $alt->alamat }}</p>
                        @endif
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs">
                            @foreach($kriterias as $k)
                                @php
                                    $pen = $nilaiMap[$k->id] ?? null;
                                    $val = $pen ? $pen->nilai : null;
                                @endphp
                                <div class="flex gap-2 py-0.5">
                                    <span class="text-gray-400">{{ $k->kode }}:</span>
                                    <span class="text-gray-700 font-medium">
                                        @if($val === null)
                                            -
                                        @elseif($k->tipe_input === 'rupiah')
                                            Rp {{ number_format($val, 0, ',', '.') }}
                                        @elseif($k->tipe_input === 'angka')
                                            {{ (int) $val }}
                                        @elseif($k->tipe_input === 'pilihan' && $k->opsi)
                                            @php $label = collect($k->opsi)->firstWhere('nilai', $val); @endphp
                                            {{ $label ? $label['label'] : $val }}
                                        @elseif($k->tipe_input === 'checkbox')
                                            @php $items = $pen && $pen->nilai_detail ? json_decode($pen->nilai_detail, true) : []; @endphp
                                            {{ implode(', ', $items) ?: (int)$val }}
                                        @elseif($k->tipe_input === 'status' && $k->opsi)
                                            @php $label = collect($k->opsi)->firstWhere('nilai', $val); @endphp
                                            {{ $label ? $label['label'] : $val }}
                                        @else
                                            {{ $val }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($alternatifs->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                    {{ $alternatifs->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
