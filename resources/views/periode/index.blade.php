<x-app-layout>
    <x-slot name="header">Periode Bantuan</x-slot>

    @if(session('success'))
        <div class="mb-4 sm:mb-6 rounded-lg bg-green-50 border border-green-200 p-3 sm:p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h3 class="text-sm sm:text-base font-semibold text-gray-900">Daftar Periode Bantuan</h3>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Kelola periode penyaluran bantuan sosial</p>
            </div>
            <a href="{{ route('periode.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Buat Periode
            </a>
        </div>

        {{-- Search & Filter --}}
        <div class="px-4 sm:px-6 py-3 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('periode.index') }}" class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <div class="flex-1 relative">
                  
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul periode atau nama warga..."
                           class="w-full rounded-lg border border-gray-300 pl-9 pr-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 sm:flex-none px-4 py-2 rounded-lg bg-gray-800 text-sm font-medium text-white hover:bg-gray-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                        Cari
                    </button>
                    @if(request('search') || request('assistance_type_id'))
                        <a href="{{ route('periode.index') }}" class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50 transition text-center">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        @if($periodes->isEmpty())
            <div class="px-4 sm:px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                <p class="mt-3 text-sm text-gray-500">Belum ada periode bantuan.</p>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($periodes as $p)
                    <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            {{-- Info --}}
                            <a href="{{ route('periode.show', $p) }}" class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $p->judul }}</h4>
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $p->assistanceType->name }}</span>
                                    @if($p->status === 'tutup')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Ditutup</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Dibuka</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-4 mt-1 text-xs text-gray-500">
                                    <span>{{ $p->tanggal->format('d M Y') }}</span>
                                    <span>{{ $p->alternatifs_count }} warga</span>
                                </div>
                            </a>

                            {{-- Actions --}}
                            <div class="flex flex-wrap items-center gap-2">
                                @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('periode.toggle-status', $p) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ $p->status === 'tutup' ? 'text-green-600 border border-green-200 hover:bg-green-50' : 'text-orange-600 border border-orange-200 hover:bg-orange-50' }} transition">
                                            {{ $p->status === 'tutup' ? 'Buka' : 'Tutup' }}
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('periode.show', $p) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-blue-600 border border-blue-200 hover:bg-blue-50 transition">Lihat</a>
                                <a href="{{ route('periode.edit', $p) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 border border-gray-300 hover:bg-gray-50 transition">Edit</a>
                                <form action="{{ route('periode.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus periode ini beserta semua datanya?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 border border-red-200 hover:bg-red-50 transition">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($periodes->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                    {{ $periodes->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
