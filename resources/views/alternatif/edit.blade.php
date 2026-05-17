<x-app-layout>
    <x-slot name="header">Edit Warga — {{ $alternatif->nama }}</x-slot>

    <div class="max-w-4xl space-y-6">
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('alternatif.update', [$periode, $alternatif]) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-8">
                    <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900 mb-4">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-600">1</span>
                        Data Warga
                    </h3>
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK <span class="text-red-500">*</span></label>
                            <input type="text" id="nik" name="nik" value="{{ old('nik', $alternatif->nik) }}" maxlength="16" required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $alternatif->nama) }}" required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="2"
                                      class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">{{ old('alamat', $alternatif->alamat) }}</textarea>
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
                        <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-4 text-sm text-yellow-700">Belum ada data kriteria yang dikonfigurasi untuk jenis bantuan ini. Silakan atur kriteria terlebih dahulu di menu Pengaturan.</div>
                    @else
                        <div class="space-y-5">
                            @foreach($kriterias as $kriteria)
                                @php
                                    $pen = $penilaians[$kriteria->id] ?? null;
                                    $currentVal = old("nilai.$kriteria->id", $pen ? $pen->nilai : '');
                                    $checkedItems = ($pen && $pen->nilai_detail) ? json_decode($pen->nilai_detail, true) : [];
                                @endphp
                                <div class="p-4 rounded-lg border border-gray-200 bg-gray-50">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-xs font-semibold">{{ $kriteria->kode }}</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $kriteria->nama }}</span>
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $kriteria->jenis === 'benefit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($kriteria->jenis) }}</span>
                                    </div>
                                    @switch($kriteria->tipe_input)
                                        @case('rupiah')
                                            @php $rawVal = (int) $currentVal; @endphp
                                            <div x-data="{ raw: '{{ $rawVal }}', formatted: '{{ $rawVal ? number_format($rawVal, 0, ',', '.') : '' }}' }">
                                                <div class="relative">
                                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">Rp</span>
                                                    <input type="text" x-model="formatted" @input="let v=$event.target.value.replace(/\D/g,'');raw=v;formatted=v?parseInt(v).toLocaleString('id-ID'):''" placeholder="1.000.000"
                                                           class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                                    <input type="hidden" name="nilai[{{ $kriteria->id }}]" :value="raw">
                                                </div>
                                            </div>
                                            @break
                                        @case('angka')
                                            <input type="number" name="nilai[{{ $kriteria->id }}]" value="{{ (int) $currentVal }}" min="0" step="1"
                                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                            @break
                                        @case('pilihan')
                                            <select name="nilai[{{ $kriteria->id }}]" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">— Pilih —</option>
                                                @if($kriteria->opsi)
                                                    @foreach($kriteria->opsi as $opsi)
                                                        <option value="{{ $opsi['nilai'] }}" {{ $currentVal == $opsi['nilai'] ? 'selected' : '' }}>{{ $opsi['label'] }} (nilai: {{ $opsi['nilai'] }})</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @break
                                        @case('checkbox')
                                            @if($kriteria->opsi)
                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                                    @foreach($kriteria->opsi as $item)
                                                        <label class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 bg-white cursor-pointer hover:bg-blue-50 transition text-sm">
                                                            <input type="checkbox" name="nilai_detail[{{ $kriteria->id }}][]" value="{{ $item }}"
                                                                   {{ in_array($item, $checkedItems) ? 'checked' : '' }}
                                                                   class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                            {{ $item }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @break
                                        @case('status')
                                            <select name="nilai[{{ $kriteria->id }}]" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                                <option value="">— Pilih —</option>
                                                @if($kriteria->opsi)
                                                    @foreach($kriteria->opsi as $opsi)
                                                        <option value="{{ $opsi['nilai'] }}" {{ $currentVal == $opsi['nilai'] ? 'selected' : '' }}>{{ $opsi['label'] }}</option>
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

                <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5">
                    <a href="{{ route('periode.show', $periode) }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">Perbarui</button>
                </div>
            </form>
        </div>

        {{-- Riwayat NIK --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900 mb-4">
                <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                Riwayat pendaftaran NIK ini di periode lain:
            </h3>

            @if($riwayat->isEmpty())
                <div class="text-sm text-gray-500 bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                    Tidak ada riwayat di periode lain.
                </div>
            @else
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50 text-gray-500 font-semibold text-xs uppercase tracking-wider">
                                <th class="px-4 py-3">Periode</th>
                                <th class="px-4 py-3">Jenis Bantuan</th>
                                <th class="px-4 py-3 text-center">Status Periode</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-700">
                            @foreach($riwayat as $alt)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $alt->periodeBantuan->judul }}</td>
                                    <td class="px-4 py-3">{{ $alt->periodeBantuan->assistanceType->name }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $alt->periodeBantuan->status === 'tutup' ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-green-50 text-green-700 border border-green-100' }}">
                                            {{ ucfirst($alt->periodeBantuan->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
