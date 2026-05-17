@php
    $periode = $alternatif->periodeBantuan;
    $tanggalAkhir = $periode->tanggal_akhir;
    $isTutup = $periode->status === 'tutup';
@endphp

<div class="rounded-xl border p-5 {{ $isTutup ? 'bg-green-50 border-green-200' : 'bg-blue-50 border-blue-200' }}">
    <div class="flex items-start justify-between gap-3 mb-3">
        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $isTutup ? 'bg-green-200 text-green-800' : 'bg-blue-200 text-blue-800' }}">
            Riwayat {{ $iteration }}@if($isLatest ?? false) · Terbaru @endif
        </span>
        @if($isTutup)
            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-700">Periode Selesai</span>
        @else
            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-amber-100 text-amber-800">Menunggu Pengumuman</span>
        @endif
    </div>

    <div class="flex items-start gap-3">
        <div class="mt-0.5 shrink-0 {{ $isTutup ? 'text-green-600' : 'text-blue-600' }}">
            @if($isTutup)
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            @else
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
            @endif
        </div>
        <div class="min-w-0 flex-1">
            @if($isTutup)
                <h3 class="text-base font-bold text-green-800">Terdaftar — Pengumuman Resmi</h3>
                <p class="mt-1 text-sm text-green-700">Anda tercatat sebagai penerima bantuan pada periode ini.</p>
            @else
                <h3 class="text-base font-bold text-blue-800">Terdaftar — Periode Berlangsung</h3>
                <p class="mt-1 text-sm text-blue-700">Pendaftaran tercatat, namun periode <strong>belum ditutup</strong> sehingga pengumuman resmi belum tersedia.</p>
            @endif

            <div class="mt-3 text-sm space-y-1 {{ $isTutup ? 'text-green-700' : 'text-blue-700' }}">
                <p><strong>Nama:</strong> {{ $alternatif->nama }}</p>
                <p><strong>Jenis Bantuan:</strong> {{ $periode->assistanceType->name }}</p>
                <p><strong>Periode:</strong> {{ $periode->judul }}</p>
                @if($periode->tanggal_mulai)
                    <p><strong>Jadwal:</strong> {{ $periode->tanggal_mulai->format('d-m-Y') }}
                        @if($tanggalAkhir)
                            s/d {{ $tanggalAkhir->format('d-m-Y') }}
                        @endif
                    </p>
                @endif
            </div>

            @if(!$isTutup)
                @if($tanggalAkhir)
                    <p class="mt-3 text-sm text-blue-800 bg-blue-100/60 rounded-lg px-3 py-2.5 border border-blue-200">
                        Periode dijadwalkan berakhir pada
                        <strong>{{ $tanggalAkhir->locale('id')->translatedFormat('d F Y') }}</strong>
                        ({{ $tanggalAkhir->format('d-m-Y') }}).
                        Setelah ditutup petugas, hasil akan diumumkan resmi.
                    </p>
                @else
                    <p class="mt-3 text-sm text-blue-800 bg-blue-100/60 rounded-lg px-3 py-2.5 border border-blue-200">
                        Tanggal berakhir belum tersedia. Silakan <strong>hubungi petugas</strong> untuk informasi jadwal.
                    </p>
                @endif
            @endif
        </div>
    </div>
</div>
