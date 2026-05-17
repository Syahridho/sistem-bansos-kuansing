@php
    $inputClass = 'flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-950 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50';
    $labelClass = 'text-sm font-medium leading-none text-slate-900';
    $hintClass = 'text-xs text-slate-500 mt-1.5';
@endphp

<x-app-layout>
    <x-slot name="header">Edit Periode — {{ $periode->judul }}</x-slot>

    <div class="max-w-2xl">
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold tracking-tight text-slate-900">Ubah Periode</h2>
                <p class="mt-1 text-sm text-slate-500">Perbarui judul, jenis bantuan, atau rentang waktu.</p>
            </div>

            <div class="p-6">
                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('periode.update', $periode) }}" method="POST" class="space-y-6">
                    @csrf @method('PUT')

                    <div class="space-y-2">
                        <label for="judul" class="{{ $labelClass }}">Judul Periode <span class="text-red-500">*</span></label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $periode->judul) }}" required class="{{ $inputClass }}">
                    </div>

                    <div class="space-y-2">
                        <label for="assistance_type_id" class="{{ $labelClass }}">Jenis Bantuan <span class="text-red-500">*</span></label>
                        <select id="assistance_type_id" name="assistance_type_id" required class="{{ $inputClass }}">
                            @foreach($assistanceTypes as $type)
                                <option value="{{ $type->id }}" {{ old('assistance_type_id', $periode->assistance_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <p class="{{ $labelClass }}">Rentang Waktu <span class="text-red-500">*</span></p>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label for="tanggal_mulai" class="text-xs font-medium uppercase tracking-wide text-slate-500">Tanggal Mulai</label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                                       value="{{ old('tanggal_mulai', $periode->tanggal_mulai->format('Y-m-d')) }}" required class="{{ $inputClass }}">
                            </div>
                            <div class="space-y-2">
                                <label for="tanggal_akhir" class="text-xs font-medium uppercase tracking-wide text-slate-500">Tanggal Akhir</label>
                                <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                                       value="{{ old('tanggal_akhir', $periode->tanggal_akhir->format('Y-m-d')) }}" required
                                       min="{{ old('tanggal_mulai', $periode->tanggal_mulai->format('Y-m-d')) }}" class="{{ $inputClass }}">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                        <a href="{{ route('periode.index') }}" class="inline-flex h-10 items-center justify-center rounded-md border border-slate-200 bg-white px-4 text-sm font-medium text-slate-900 shadow-sm hover:bg-slate-50">Batal</a>
                        <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-slate-900 px-5 text-sm font-medium text-white shadow hover:bg-slate-800">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const mulai = document.getElementById('tanggal_mulai');
            const akhir = document.getElementById('tanggal_akhir');
            mulai.addEventListener('change', function () {
                akhir.min = mulai.value;
                if (akhir.value && akhir.value < mulai.value) akhir.value = mulai.value;
            });
        })();
    </script>
</x-app-layout>
