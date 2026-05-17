<x-app-layout>
    <x-slot name="header">Perangkingan — {{ $periode->judul }}</x-slot>

    {{-- Back link --}}
    <div class="mb-5">
        <a href="{{ route('periode.show', $periode) }}"
           class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-700 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            Kembali ke {{ $periode->judul }}
        </a>
    </div>

    {{-- Periode Banner --}}
    <div class="rounded-lg bg-zinc-900 px-5 py-4 mb-5 flex items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2 flex-wrap">
                <h2 class="text-sm font-semibold text-zinc-100">{{ $periode->judul }}</h2>
                <span class="inline-flex items-center rounded-full bg-zinc-800 px-2.5 py-0.5 text-[11px] font-medium text-zinc-400">
                    {{ $periode->jenis_bantuan }}
                </span>
            </div>
            <p class="text-xs text-zinc-500 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $periode->tanggal->format('d F Y') }}
            </p>
        </div>
        <svg class="w-5 h-5 text-zinc-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
        </svg>
    </div>

    @if(isset($pesan))
        <div class="mb-5 rounded-lg bg-amber-50 border border-amber-200 p-4 text-xs text-amber-700 flex items-start gap-3">
            <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            {{ $pesan }}
        </div>
    @endif

    {{-- Stat Cards --}}
    @if($hasilAkhir->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide mb-1.5">Uang Diberikan</p>
                <p class="text-xl font-semibold text-emerald-600">Rp {{ number_format($uangDiberikan, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide mb-1.5">Maks. Penerima</p>
                <p class="text-xl font-semibold text-blue-600">{{ $maxPenerima }} Orang</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide mb-1.5">Sisa Kuota</p>
                <p class="text-xl font-semibold {{ $sisaKuota == 0 ? 'text-amber-500' : 'text-slate-700' }}">{{ $sisaKuota }} Orang</p>
            </div>
        </div>
    @endif

    {{-- Bobot Kriteria --}}
    @if($kriterias->isNotEmpty())
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden mb-5">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-semibold text-slate-900">Bobot Kriteria (Normalisasi WP)</h3>
                <p class="text-xs text-slate-400 mt-0.5">
                    Total bobot:
                    <span class="font-semibold {{ $totalBobot == 1 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $totalBobot }}</span>
                </p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/30">
                            <th class="h-10 px-5 text-left text-[10px] font-medium uppercase tracking-wide text-slate-400">Kode</th>
                            <th class="h-10 px-5 text-left text-[10px] font-medium uppercase tracking-wide text-slate-400">Kriteria</th>
                            <th class="h-10 px-5 text-left text-[10px] font-medium uppercase tracking-wide text-slate-400">Bobot</th>
                            <th class="h-10 px-5 text-left text-[10px] font-medium uppercase tracking-wide text-slate-400">Jenis</th>
                            @if(isset($bobotNormalisasi))
                                <th class="h-10 px-5 text-left text-[10px] font-medium uppercase tracking-wide text-slate-400">W (normalisasi)</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($kriterias as $k)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-3">
                                    <code class="rounded bg-slate-100 border border-slate-200 px-1.5 py-0.5 font-mono text-[11px] font-semibold text-blue-700">{{ $k->kode }}</code>
                                </td>
                                <td class="px-5 py-3 font-medium text-slate-900 text-sm">{{ $k->nama }}</td>
                                <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $k->bobot }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold
                                        {{ $k->jenis === 'benefit' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        {{ ucfirst($k->jenis) }}
                                    </span>
                                </td>
                                @if(isset($bobotNormalisasi))
                                    <td class="px-5 py-3 font-mono text-xs font-semibold {{ $bobotNormalisasi[$k->id] < 0 ? 'text-rose-600' : 'text-slate-500' }}">
                                        {{ number_format($bobotNormalisasi[$k->id], 4) }}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Ranking --}}
    @if($hasilAkhir->total() > 0 || request('search'))
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">

            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Peringkat Alternatif</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Hasil metode Weighted Product</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                    <form method="GET" action="{{ route('spk.hasil', $periode->id) }}" class="relative">
                        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama atau NIK..."
                               class="h-9 w-full sm:w-60 rounded-md border border-slate-200 bg-white pl-8 pr-3 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-950/10">
                    </form>
                    <a href="{{ route('perangkingan.export', $periode->id) }}"
                       class="inline-flex items-center gap-1.5 h-9 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-2 rounded-md transition shadow-sm shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>

            {{-- Desktop --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/30">
                            <th class="h-10 px-5 text-left text-[10px] font-medium uppercase tracking-wide text-slate-400">Ranking</th>
                            <th class="h-10 px-5 text-left text-[10px] font-medium uppercase tracking-wide text-slate-400">NIK</th>
                            <th class="h-10 px-5 text-left text-[10px] font-medium uppercase tracking-wide text-slate-400">Nama</th>
                            <th class="h-10 px-5 text-left text-[10px] font-medium uppercase tracking-wide text-slate-400">Vektor S</th>
                            <th class="h-10 px-5 text-left text-[10px] font-medium uppercase tracking-wide text-slate-400">Nilai Akhir (V)</th>
                            <th class="h-10 px-5 text-left text-[10px] font-medium uppercase tracking-wide text-slate-400">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php $maksimalPenerima = $periode->assistanceType->maksimal_penerima ?? 10; @endphp
                        @foreach($hasilAkhir as $hasil)
                            @php $realIndex = $loop->index + (($hasilAkhir->currentPage() - 1) * $hasilAkhir->perPage()); @endphp
                            <tr class="transition-colors {{ $realIndex < $maksimalPenerima ? 'hover:bg-slate-50/50' : 'opacity-50 bg-slate-50/30 hover:bg-slate-50' }}">
                                <td class="px-5 py-3">
                                    @if($realIndex === 0)
                                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">1</span>
                                    @elseif($realIndex === 1)
                                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">2</span>
                                    @elseif($realIndex === 2)
                                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-orange-100 text-orange-700 text-xs font-semibold">3</span>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium pl-2">{{ $realIndex + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 font-mono text-[11px] text-slate-500">{{ $hasil['nik'] }}</td>
                                <td class="px-5 py-3 font-medium text-slate-900 text-sm">{{ $hasil['nama'] }}</td>
                                <td class="px-5 py-3 font-mono text-xs text-slate-400">{{ number_format($hasil['vektor_s'], 6) }}</td>
                                <td class="px-5 py-3 font-mono text-xs font-semibold text-slate-900">{{ number_format($hasil['nilai_akhir'], 6) }}</td>
                                <td class="px-5 py-3">
                                    @if($realIndex < $maksimalPenerima)
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Layak</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Tidak Layak</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div class="md:hidden divide-y divide-slate-100">
                @php $maksimalPenerima = $periode->assistanceType->maksimal_penerima ?? 10; @endphp
                @foreach($hasilAkhir as $hasil)
                    @php $realIndex = $loop->index + (($hasilAkhir->currentPage() - 1) * $hasilAkhir->perPage()); @endphp
                    <div class="p-4 space-y-3 {{ $realIndex >= $maksimalPenerima ? 'opacity-50 bg-slate-50/50' : '' }}">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2.5">
                                @if($realIndex === 0)
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold shrink-0">1</span>
                                @elseif($realIndex === 1)
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-slate-600 text-xs font-semibold shrink-0">2</span>
                                @elseif($realIndex === 2)
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-orange-100 text-orange-700 text-xs font-semibold shrink-0">3</span>
                                @else
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-100 text-slate-400 text-xs font-medium shrink-0">{{ $realIndex + 1 }}</span>
                                @endif
                                <span class="font-semibold text-sm text-slate-900">{{ $hasil['nama'] }}</span>
                            </div>
                            @if($realIndex < $maksimalPenerima)
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">Layak</span>
                            @else
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 shrink-0">Tidak Layak</span>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 gap-y-1.5 text-[11px]">
                            <span class="text-slate-400 uppercase tracking-wide font-medium">NIK</span>
                            <span class="font-mono text-slate-600 text-right">{{ $hasil['nik'] }}</span>
                            <span class="text-slate-400 uppercase tracking-wide font-medium">Nilai Akhir (V)</span>
                            <span class="font-mono font-semibold text-slate-900 text-right">{{ number_format($hasil['nilai_akhir'], 6) }}</span>
                            <span class="text-slate-400 uppercase tracking-wide font-medium">Vektor S</span>
                            <span class="font-mono text-slate-400 text-right">{{ number_format($hasil['vektor_s'], 6) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($hasilAkhir->hasPages())
                <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100 bg-slate-50/50">
                    <span class="text-xs text-slate-400">
                        Menampilkan {{ $hasilAkhir->firstItem() }}–{{ $hasilAkhir->lastItem() }} dari {{ $hasilAkhir->total() }} warga
                    </span>
                    {{ $hasilAkhir->links() }}
                </div>
            @endif
        </div>
    @endif
</x-app-layout>