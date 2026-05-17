<x-app-layout>
    <x-slot name="header">Tambah Warga — {{ $periode->judul }}</x-slot>

    <div class="max-w-4xl">
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form id="tambahWargaForm" action="{{ route('alternatif.store', $periode) }}" method="POST">
                @csrf

                <div class="mb-8">
                    <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900 mb-4">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-600">1</span>
                        Data Warga
                    </h3>
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK <span class="text-red-500">*</span></label>
                            <input type="text" id="nik" name="nik" value="{{ old('nik') }}" maxlength="16" required placeholder="3201xxxxxxxxxxxxx"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="2" placeholder="Alamat lengkap"
                                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                <hr class="mb-8 border-gray-200">

                <div class="mb-8">
                    <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900 mb-4">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-600">2</span>
                        Penilaian Kriteria
                    </h3>
                    @if($kriterias->isEmpty())
                        <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-4 text-sm text-yellow-700">Belum ada data kriteria.</div>
                    @else
                        <div class="space-y-5">
                            @foreach($kriterias as $kriteria)
                                <div class="p-4 rounded-lg border border-gray-200 bg-gray-50">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-xs font-semibold">{{ $kriteria->kode }}</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $kriteria->nama }}</span>
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $kriteria->jenis === 'benefit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($kriteria->jenis) }}</span>
                                    </div>
                                    @switch($kriteria->tipe_input)
                                        @case('rupiah')
                                            <div x-data="{ raw: '{{ old("nilai.$kriteria->id", '') }}', formatted: '' }" x-init="if(raw){formatted=parseInt(raw).toLocaleString('id-ID')}">
                                                <div class="relative">
                                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">Rp</span>
                                                    <input type="text" x-model="formatted" @input="let v=$event.target.value.replace(/\D/g,'');raw=v;formatted=v?parseInt(v).toLocaleString('id-ID'):''" placeholder="1.000.000"
                                                           class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                                    <input type="hidden" name="nilai[{{ $kriteria->id }}]" :value="raw">
                                                </div>
                                            </div>
                                            @break
                                        @case('angka')
                                            <input type="number" name="nilai[{{ $kriteria->id }}]" value="{{ old("nilai.$kriteria->id") }}" min="0" step="1" placeholder="0"
                                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                            @break
                                        @case('pilihan')
                                            <select name="nilai[{{ $kriteria->id }}]" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">— Pilih —</option>
                                                @if($kriteria->opsi)
                                                    @foreach($kriteria->opsi as $opsi)
                                                        <option value="{{ $opsi['nilai'] }}" {{ old("nilai.$kriteria->id") == $opsi['nilai'] ? 'selected' : '' }}>{{ $opsi['label'] }} (nilai: {{ $opsi['nilai'] }})</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @break
                                        @case('checkbox')
                                            @if($kriteria->opsi)
                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                                    @foreach($kriteria->opsi as $item)
                                                        <label class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 bg-white cursor-pointer hover:bg-blue-50 transition text-sm">
                                                            <input type="checkbox" name="nilai_detail[{{ $kriteria->id }}][]" value="{{ $item }}" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                            {{ $item }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                                <p class="mt-2 text-xs text-gray-500">Nilai = jumlah item yang dicentang</p>
                                            @endif
                                            @break
                                        @case('status')
                                            <select name="nilai[{{ $kriteria->id }}]" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">— Pilih —</option>
                                                @if($kriteria->opsi)
                                                    @foreach($kriteria->opsi as $opsi)
                                                        <option value="{{ $opsi['nilai'] }}" {{ old("nilai.$kriteria->id") == $opsi['nilai'] ? 'selected' : '' }}>{{ $opsi['label'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @break
                                    @endswitch
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Duplicate warning (shown when session has duplicate_warning) --}}
                @if(session('duplicate_warning'))
                <div x-data="{ show: true }" x-show="show"
                     class="border border-amber-200 bg-amber-50 rounded-xl p-4 mb-5">
                    <div class="flex gap-3 items-start">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-sm text-amber-800 mb-1">
                                Peringatan: NIK Sudah Pernah Terdaftar
                            </p>
                            <p class="text-xs text-amber-700 mb-2 leading-relaxed">
                                NIK <strong>{{ session('duplicate_warning.nik') }}</strong> 
                                atas nama <strong>{{ session('duplicate_warning.nama') }}</strong> 
                                sebelumnya pernah menerima bantuan pada periode:
                                <strong>{{ session('duplicate_warning.periodes') }}</strong>.
                            </p>
                            <p class="text-xs text-amber-700 mb-3 font-medium">
                                Apakah Anda tetap ingin menambahkan warga ini?
                            </p>
                            <div class="flex gap-2">
                                <button type="button" @click="
                                    document.getElementById('confirm_duplicate').value = '1';
                                    document.getElementById('tambahWargaForm').submit();
                                " class="bg-amber-600 hover:bg-amber-700 text-white border-0 px-3.5 py-1.5 rounded-lg text-xs font-semibold shadow-sm transition">
                                    Ya, Tetap Tambahkan
                                </button>
                                <button type="button" @click="show = false"
                                    class="border border-amber-300 hover:bg-amber-100 text-amber-700 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Hidden confirm field --}}
                <input type="hidden" name="confirm_duplicate" id="confirm_duplicate" value="0">

                <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5">
                    <a href="{{ route('periode.show', $periode) }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
