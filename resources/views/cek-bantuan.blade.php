<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Cek Status Bantuan Sosial</h2>
        <p class="mt-2 text-sm text-gray-600">Masukkan NIK Anda untuk memeriksa apakah Anda terdaftar sebagai penerima bantuan.</p>
    </div>

    <!-- Cek Bantuan Form -->
    <form method="POST" action="{{ route('cek-bantuan.search') }}">
        @csrf
        <div>
            <x-input-label for="nik" value="Nomor Induk Kependudukan (NIK)" />
            <x-text-input id="nik" class="block mt-1 w-full" type="text" name="nik" :value="old('nik', $nik ?? '')" required autofocus placeholder="Masukkan 16 digit NIK..." />
            <x-input-error :messages="$errors->get('nik')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3 w-full justify-center">
                {{ __('Cari') }}
            </x-primary-button>
        </div>
    </form>

    @if(isset($nik))
        <div class="mt-8 border-t border-gray-200 pt-6">
            @if($alternatif)
                @if($alternatif->periodeBantuan->status === 'tutup')
                    <div class="rounded-xl bg-green-50 border border-green-200 p-5">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 shrink-0 text-green-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-green-800">Selamat! Anda terdaftar sebagai penerima bantuan.</h3>
                                <div class="mt-2 text-sm text-green-700 space-y-1">
                                    <p><strong>NIK:</strong> {{ $alternatif->nik }}</p>
                                    <p><strong>Nama:</strong> {{ $alternatif->nama }}</p>
                                    <p><strong>Jenis Bantuan:</strong> {{ $alternatif->periodeBantuan->assistanceType->name }}</p>
                                    <p><strong>Periode:</strong> {{ $alternatif->periodeBantuan->judul }} ({{ $alternatif->periodeBantuan->tanggal->format('d F Y') }})</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-xl bg-blue-50 border border-blue-200 p-5">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 shrink-0 text-blue-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-blue-800">Sedang Tahap Pendataan</h3>
                                <p class="mt-1 text-sm text-blue-700">Data NIK <strong>{{ $nik }}</strong> Anda sudah masuk ke dalam sistem, namun saat ini sedang dalam tahap pendataan masyarakat lain dan belum diumumkan secara resmi.</p>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="rounded-xl bg-red-50 border border-red-200 p-5">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 shrink-0 text-red-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-red-800">Maaf, Data Tidak Ditemukan</h3>
                            <p class="mt-1 text-sm text-red-700">NIK <strong>{{ $nik }}</strong> tidak ditemukan dalam daftar pendaftar/penerima bantuan.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="mt-6 text-center">
            <a href="{{ route('cek-bantuan.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition">Kembali</a>
        </div>
    @endif
</x-guest-layout>
