<x-app-layout>
    <x-slot name="header">Jenis Bantuan</x-slot>

    @if(session('success'))
        <div class="mb-4 sm:mb-6 rounded-lg bg-green-50 border border-green-200 p-3 sm:p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 sm:mb-6 rounded-lg bg-red-50 border border-red-200 p-3 sm:p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="max-w-7xl mx-auto">
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-sm sm:text-base font-semibold text-gray-900">Daftar Jenis Bantuan</h3>
            </div>
            <a href="{{ route('assistance_types.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition whitespace-nowrap">
                Tambah Jenis Bantuan
            </a>
        </div>

        @if($assistanceTypes->isEmpty())
            <div class="px-4 sm:px-6 py-12 text-center text-sm text-gray-500">Belum ada data.</div>
        @else
            {{-- Desktop: Table --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left px-4 py-2.5 font-medium text-gray-500">Nama Bantuan</th>
                            <th class="text-left px-4 py-2.5 font-medium text-gray-500">Deskripsi</th>
                            <th class="text-left px-4 py-2.5 font-medium text-gray-500">Jumlah Diterima</th>
                            <th class="text-left px-4 py-2.5 font-medium text-gray-500">Maks. Penerima</th>
                            <th class="text-right px-4 py-2.5 font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($assistanceTypes as $type)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $type->name }}</td>
                                <td class="px-4 py-2.5 text-sm text-gray-500">{{ $type->description ?: '-' }}</td>
                                <td class="px-4 py-2.5 text-sm text-gray-500">{{ $type->jumlah_diterima ? 'Rp ' . number_format($type->jumlah_diterima, 0, ',', '.') : '-' }}</td>
                                <td class="px-4 py-2.5 text-sm text-gray-500">{{ $type->maksimal_penerima ? $type->maksimal_penerima . ' Orang' : '-' }}</td>
                                <td class="px-4 py-2.5 text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('assistance_types.edit', $type) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 border border-gray-300 hover:bg-gray-50 transition">Edit</a>
                                        <form action="{{ route('assistance_types.destroy', $type) }}" method="POST" @submit.prevent="triggerConfirm($event.target, 'Hapus Jenis Bantuan', 'Apakah Anda yakin ingin menghapus jenis bantuan {{ $type->name }}?', 'Menghapus jenis bantuan ini akan menghapus seluruh periode bantuan dan kriteria yang terikat dengannya secara permanen.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 border border-red-200 hover:bg-red-50 transition">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile: Card layout --}}
            <div class="sm:hidden divide-y divide-gray-100">
                @foreach($assistanceTypes as $type)
                    <div class="px-4 py-3">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h4 class="text-sm font-semibold text-gray-900">{{ $type->name }}</h4>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <a href="{{ route('assistance_types.edit', $type) }}" class="px-2.5 py-1 rounded-lg text-xs font-medium text-gray-600 border border-gray-300 hover:bg-gray-50 transition">Edit</a>
                                <form action="{{ route('assistance_types.destroy', $type) }}" method="POST" @submit.prevent="triggerConfirm($event.target, 'Hapus Jenis Bantuan', 'Apakah Anda yakin ingin menghapus jenis bantuan {{ $type->name }}?', 'Menghapus jenis bantuan ini akan menghapus seluruh periode bantuan dan kriteria yang terikat dengannya secara permanen.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-medium text-red-600 border border-red-200 hover:bg-red-50 transition">Hapus</button>
                                </form>
                            </div>
                        </div>
                        @if($type->description)
                            <p class="text-xs text-gray-500 mb-2">{{ $type->description }}</p>
                        @endif
                        <div class="flex flex-wrap gap-x-2 gap-y-1 text-xs text-gray-500">
                            <span>{{ $type->jumlah_diterima ? 'Rp ' . number_format($type->jumlah_diterima, 0, ',', '.') : '-' }}</span>
                            <span class="mx-1">/</span>
                            <span>{{ $type->maksimal_penerima ? $type->maksimal_penerima . ' Orang' : '-' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($assistanceTypes->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                    {{ $assistanceTypes->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
</x-app-layout>
