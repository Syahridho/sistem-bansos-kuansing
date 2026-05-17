<x-app-layout>
    <x-slot name="header">Tambah Jenis Bantuan</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('assistance_types.store') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bantuan <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3"
                                  class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Diterima</label>
                            <div x-data="{ raw: '{{ old('jumlah_diterima') }}', formatted: '{{ old('jumlah_diterima') ? number_format((int) old('jumlah_diterima'), 0, ',', '.') : '' }}' }">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">Rp</span>
                                    <input type="text" x-model="formatted" @input="let v=$event.target.value.replace(/\D/g,'');raw=v;formatted=v?parseInt(v).toLocaleString('id-ID'):''" placeholder="600.000"
                                           class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                    <input type="hidden" name="jumlah_diterima" :value="raw">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Maksimal Penerima (Orang)</label>
                            <input type="number" name="maksimal_penerima" value="{{ old('maksimal_penerima') }}" min="0" step="1" placeholder="Contoh: 100"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                    <a href="{{ route('assistance_types.index') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 border border-gray-300 hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
