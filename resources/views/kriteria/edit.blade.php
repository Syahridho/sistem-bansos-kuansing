<x-app-layout>
    <x-slot name="header">Edit Kriteria — {{ $kriteria->kode }}</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white border border-gray-200 rounded-xl p-6">

            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('kriteria.update', $kriteria) }}" method="POST" x-data="kriteriaForm()">
                @csrf @method('PUT')

                <div class="space-y-5">
                    <div class="mb-5">
                        <label for="assistance_type_id" class="block text-sm font-medium text-gray-700 mb-1">Jenis Bantuan <span class="text-red-500">*</span></label>
                        <select id="assistance_type_id" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 bg-gray-50 cursor-not-allowed" disabled>
                            @foreach($assistanceTypes as $type)
                                <option value="{{ $type->id }}" {{ old('assistance_type_id', $kriteria->assistance_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="assistance_type_id" value="{{ old('assistance_type_id', $kriteria->assistance_type_id) }}">
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode <span class="text-red-500">*</span></label>
                            <input type="text" id="kode" name="kode" value="{{ old('kode', $kriteria->kode) }}" required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis <span class="text-red-500">*</span></label>
                            <select id="jenis" name="jenis" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                <option value="benefit" {{ old('jenis', $kriteria->jenis) === 'benefit' ? 'selected' : '' }}>Benefit</option>
                                <option value="cost" {{ old('jenis', $kriteria->jenis) === 'cost' ? 'selected' : '' }}>Cost</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Kriteria <span class="text-red-500">*</span></label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $kriteria->nama) }}" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="bobot" class="block text-sm font-medium text-gray-700 mb-1">Bobot <span class="text-red-500">*</span></label>
                        <input type="number" id="bobot" name="bobot" value="{{ old('bobot', $kriteria->bobot) }}" step="0.0001" min="0" max="1" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="tipe_input" class="block text-sm font-medium text-gray-700 mb-1">Tipe Input <span class="text-red-500">*</span></label>
                        <select id="tipe_input" name="tipe_input" x-model="tipe" required
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                            <option value="angka">Angka (bilangan bulat)</option>
                            <option value="rupiah">Rupiah (mata uang)</option>
                            <option value="pilihan">Pilihan (dropdown)</option>
                            <option value="checkbox">Checkbox (pilihan ganda)</option>
                            <option value="status">Status (2 pilihan)</option>
                        </select>
                    </div>

                    {{-- Opsi Pilihan / Status --}}
                    <div x-show="tipe === 'pilihan' || tipe === 'status'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Opsi Pilihan</label>
                        <div class="space-y-2">
                            <template x-for="(opsi, idx) in opsiList" :key="idx">
                                <div class="flex items-center gap-2">
                                    <input type="text" :name="'opsi_label['+idx+']'" x-model="opsi.label" placeholder="Label"
                                           class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <input type="number" :name="'opsi_nilai['+idx+']'" x-model="opsi.nilai" placeholder="Nilai" step="0.01"
                                           class="w-24 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <button type="button" @click="opsiList.splice(idx, 1)" class="p-2 text-red-400 hover:text-red-600" x-show="opsiList.length > 1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="opsiList.push({label:'', nilai:''})"
                                class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium">+ Tambah opsi</button>
                    </div>

                    {{-- Opsi Checkbox --}}
                    <div x-show="tipe === 'checkbox'" x-cloak>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Item Checkbox</label>
                        <div class="space-y-2">
                            <template x-for="(item, idx) in checkItems" :key="idx">
                                <div class="flex items-center gap-2">
                                    <input type="text" :name="'opsi_item['+idx+']'" x-model="checkItems[idx]" placeholder="cth: Motor"
                                           class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <button type="button" @click="checkItems.splice(idx, 1)" class="p-2 text-red-400 hover:text-red-600" x-show="checkItems.length > 1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="checkItems.push('')"
                                class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium">+ Tambah item</button>
                    </div>

                    {{-- Hint Angka --}}
                    <div x-show="tipe === 'angka'" x-cloak>
                        <div class="flex items-start gap-3 rounded-lg border border-blue-100 bg-blue-50 p-4">
                            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                            <div>
                                <p class="text-sm font-medium text-blue-800">Tidak perlu konfigurasi tambahan</p>
                                <p class="text-xs text-blue-600 mt-0.5">Warga akan mengisi angka bilangan bulat saat pendaftaran. Contoh: jumlah tanggungan, usia, jumlah anak, dll.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Hint Rupiah --}}
                    <div x-show="tipe === 'rupiah'" x-cloak>
                        <div class="flex items-start gap-3 rounded-lg border border-blue-100 bg-blue-50 p-4">
                            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/></svg>
                            <div>
                                <p class="text-sm font-medium text-blue-800">Tidak perlu konfigurasi tambahan</p>
                                <p class="text-xs text-blue-600 mt-0.5">Warga akan mengisi nominal dalam Rupiah saat pendaftaran. Input otomatis diformat dengan pemisah ribuan. Contoh: penghasilan, pengeluaran, dll.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                    <a href="{{ route('kriteria.index') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function kriteriaForm() {
            let existing = @json($kriteria->opsi);
            let tipe = '{{ old("tipe_input", $kriteria->tipe_input) }}';

            let opsiList = [{label:'', nilai:''}];
            let checkItems = [''];

            if ((tipe === 'pilihan' || tipe === 'status') && Array.isArray(existing) && existing.length) {
                opsiList = existing.map(o => ({label: o.label, nilai: o.nilai}));
            }
            if (tipe === 'checkbox' && Array.isArray(existing) && existing.length) {
                checkItems = [...existing];
            }

            return { tipe, opsiList, checkItems };
        }
    </script>
</x-app-layout>
