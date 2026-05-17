<x-app-layout>
    <x-slot name="header">Buat Periode Bantuan</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('periode.store') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Periode <span class="text-red-500">*</span></label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required placeholder="Penyaluran Tahap 1 2026"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="assistance_type_id" class="block text-sm font-medium text-gray-700 mb-1">Jenis Bantuan <span class="text-red-500">*</span></label>
                            <select id="assistance_type_id" name="assistance_type_id" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">— Pilih —</option>
                                @foreach($assistanceTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('assistance_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                    <a href="{{ route('periode.index') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
