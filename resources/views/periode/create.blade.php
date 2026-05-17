@php
    $inputClass = 'flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50';
    $labelClass = 'text-sm font-medium leading-none text-slate-900';
    $hintClass = 'text-xs text-slate-500 mt-1.5';
@endphp

<x-app-layout>
    <x-slot name="header">Buat Periode Bantuan</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-900">Periode Baru</h2>
                <p class="mt-1 text-sm text-slate-500">Atur judul, jenis bantuan, dan rentang waktu penyaluran.</p>
            </div>

            <div class="p-6">
                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
                        <p class="mb-2 font-medium">Periksa kembali formulir:</p>
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('periode.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="judul" class="{{ $labelClass }}">
                            Judul Periode <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="judul"
                               name="judul"
                               value="{{ old('judul') }}"
                               required
                               placeholder="Penyaluran Tahap 1 2026"
                               class="{{ $inputClass }}">
                        <p class="{{ $hintClass }}">Nama periode yang ditampilkan di daftar dan laporan.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="assistance_type_id" class="{{ $labelClass }}">
                            Jenis Bantuan <span class="text-red-500">*</span>
                        </label>
                        <select id="assistance_type_id"
                                name="assistance_type_id"
                                required
                                class="{{ $inputClass }}">
                            <option value="">— Pilih jenis bantuan —</option>
                            @foreach($assistanceTypes as $type)
                                <option value="{{ $type->id }}" {{ old('assistance_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <p class="{{ $labelClass }}">Rentang Waktu <span class="text-red-500">*</span></p>
                        <p class="text-xs text-slate-500">Tentukan kapan periode dimulai dan berakhir.</p>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label for="tanggal_mulai" class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                    Tanggal Mulai
                                </label>
                                <input type="date"
                                       id="tanggal_mulai"
                                       name="tanggal_mulai"
                                       value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                                       required
                                       class="{{ $inputClass }}">
                            </div>
                            <div class="space-y-2">
                                <label for="tanggal_akhir" class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                    Tanggal Akhir
                                </label>
                                <input type="date"
                                       id="tanggal_akhir"
                                       name="tanggal_akhir"
                                       value="{{ old('tanggal_akhir', date('Y-m-d')) }}"
                                       required
                                       min="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                                       class="{{ $inputClass }}">
                            </div>
                        </div>
                        <p id="rentang-preview" class="{{ $hintClass }}"></p>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                        <a href="{{ route('periode.index') }}"
                           class="inline-flex h-10 items-center justify-center rounded-md border border-slate-200 bg-white px-4 text-sm font-medium text-slate-900 shadow-sm transition-colors hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex h-10 items-center justify-center rounded-md bg-slate-900 px-5 text-sm font-medium text-white shadow transition-colors hover:bg-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2">
                            Simpan Periode
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const mulai = document.getElementById('tanggal_mulai');
            const akhir = document.getElementById('tanggal_akhir');
            const preview = document.getElementById('rentang-preview');

            const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

            function formatTampilan(iso) {
                if (!iso) return '';
                const [y, m, d] = iso.split('-');
                return `${d}-${m}-${y} (${parseInt(d, 10)} ${bulan[parseInt(m, 10) - 1]} ${y})`;
            }

            function sync() {
                if (mulai.value) {
                    akhir.min = mulai.value;
                    if (akhir.value && akhir.value < mulai.value) {
                        akhir.value = mulai.value;
                    }
                }
                if (mulai.value && akhir.value) {
                    preview.textContent = 'Jadwal: ' + formatTampilan(mulai.value) + ' s/d ' + formatTampilan(akhir.value);
                } else {
                    preview.textContent = '';
                }
            }

            mulai.addEventListener('change', sync);
            akhir.addEventListener('change', sync);
            sync();
        })();
    </script>
</x-app-layout>
