<x-app-layout>
    <x-slot name="header">Data Kriteria</x-slot>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search --}}
    <div x-data="{
        query: '',
        open: false,
        selectedIndex: -1,
        items: {{ $assistanceTypes->map(fn($t) => [
            'id' => $t->id,
            'name' => $t->name,
            'count' => isset($grouped[$t->id]) ? $grouped[$t->id]->count() : 0,
            'url' => route('kriteria.index', ['tab' => $t->id])
        ])->values()->toJson() }},
        get filteredItems() {
            if (this.query.trim() === '') return [];
            return this.items.filter(i => i.name.toLowerCase().includes(this.query.toLowerCase())).slice(0, 8);
        },
        selectItem(url) { window.location.href = url; },
        handleKeydown(e) {
            if (e.key === 'ArrowDown') { this.selectedIndex = (this.selectedIndex + 1) % this.filteredItems.length; e.preventDefault(); }
            else if (e.key === 'ArrowUp') { this.selectedIndex = (this.selectedIndex - 1 + this.filteredItems.length) % this.filteredItems.length; e.preventDefault(); }
            else if (e.key === 'Enter' && this.selectedIndex !== -1) { this.selectItem(this.filteredItems[this.selectedIndex].url); }
            else if (e.key === 'Escape') { this.open = false; this.query = ''; }
        }
    }" class="mb-8">
        <div class="relative max-w-xl mx-auto">
            <div class="relative">
                <input
                    type="text"
                    x-model="query"
                    @input="open = true; selectedIndex = -1"
                    @focus="open = true"
                    @click.away="open = false"
                    @keydown="handleKeydown($event)"
                    placeholder="Cari jenis bantuan..."
                    class="w-full h-10 rounded-md border border-slate-200 bg-white pl-9 pr-12 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-950/10 shadow-sm"
                >
            </div>

            <div
                x-show="open && filteredItems.length > 0"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute z-50 w-full mt-1.5 bg-white border border-slate-200 rounded-md shadow-md overflow-hidden"
            >
                <div class="p-1">
                    <p class="px-2 py-1.5 text-xs font-medium text-slate-400">Saran</p>
                    <template x-for="(item, index) in filteredItems" :key="item.id">
                        <div
                            @click="selectItem(item.url)"
                            @mouseenter="selectedIndex = index"
                            :class="selectedIndex === index ? 'bg-slate-100 text-slate-900' : 'text-slate-600'"
                            class="flex cursor-pointer select-none items-center rounded-sm px-2 py-1.5 text-sm transition-colors"
                        >
                            <span class="flex-1" x-text="item.name"></span>
                            <span class="text-[10px] text-slate-400" x-text="item.count + ' kriteria'"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    @php
        $kriterias = $grouped[$activeTabId] ?? collect();
        $activeType = $assistanceTypes->firstWhere('id', $activeTabId);
    @endphp

    {{-- Main Card --}}
    <div class="rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-0.5">
                    <div class="flex items-center gap-2">
                        <h3 class="text-base font-semibold text-slate-900">{{ $activeType->name ?? 'Pilih Jenis' }}</h3>
                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">Aktif</span>
                    </div>
                    <p class="text-xs text-slate-400">Kelola bobot dan parameter untuk jenis bantuan ini.</p>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <div class="relative">
                        <input type="text" placeholder="Filter kriteria..."
                               class="h-9 w-52 rounded-md border border-slate-200 bg-white pl-8 pr-3 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-950/10"
                               onkeyup="filterKriteria(this.value)">
                    </div>

                    @if($activeType)
                        <a href="{{ route('kriteria.create', ['assistance_type_id' => $activeType->id]) }}"
                           style="display:inline-flex;align-items:center;gap:6px;height:36px;padding:0 16px;background:rgb(59 130 246);color:#fff;border-radius:6px;font-size:13px;font-weight:500;text-decoration:none;white-space:nowrap;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Tambah Kriteria
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if($kriterias->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center px-4">
                <div class="rounded-full bg-slate-50 border border-slate-100 p-3 mb-3">
                    <svg class="h-5 w-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-700">Belum ada kriteria</p>
                <p class="text-xs text-slate-400 mt-1">Mulai dengan menambahkan kriteria penilaian untuk jenis bantuan ini.</p>
            </div>
        @else
            {{-- Desktop Table --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="h-11 px-4 text-left text-[11px] font-medium uppercase tracking-wide text-slate-400">Kode</th>
                            <th class="h-11 px-4 text-left text-[11px] font-medium uppercase tracking-wide text-slate-400">Nama</th>
                            <th class="h-11 px-4 text-left text-[11px] font-medium uppercase tracking-wide text-slate-400">Bobot</th>
                            <th class="h-11 px-4 text-left text-[11px] font-medium uppercase tracking-wide text-slate-400">Jenis</th>
                            <th class="h-11 px-4 text-left text-[11px] font-medium uppercase tracking-wide text-slate-400">Tipe Input</th>
                            <th class="h-11 px-4 text-right text-[11px] font-medium uppercase tracking-wide text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($kriterias as $kriteria)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <code class="rounded bg-slate-100 border border-slate-200 px-1.5 py-0.5 font-mono text-[11px] font-semibold text-slate-700">{{ $kriteria->kode }}</code>
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $kriteria->nama }}</td>
                                <td class="px-4 py-3 text-slate-500 tabular-nums">{{ number_format($kriteria->bobot, 4) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold
                                        {{ $kriteria->jenis === 'benefit' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        {{ ucfirst($kriteria->jenis) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs text-slate-500 capitalize">{{ $kriteria->tipe_input }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('kriteria.edit', $kriteria) }}"
                                           class="inline-flex items-center h-7 px-2.5 rounded border border-slate-200 bg-white text-[11px] font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('kriteria.destroy', $kriteria) }}" method="POST" onsubmit="return confirm('Hapus kriteria ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center h-7 px-2.5 rounded border border-rose-200 bg-white text-[11px] font-medium text-rose-600 hover:bg-rose-50 transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="sm:hidden divide-y divide-slate-100">
                @foreach($kriterias as $kriteria)
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <code class="rounded bg-slate-100 border border-slate-200 px-1.5 py-0.5 font-mono text-[10px] font-bold text-slate-700">{{ $kriteria->kode }}</code>
                                    <span class="text-sm font-semibold text-slate-900">{{ $kriteria->nama }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-[11px] text-slate-400">
                                    <span>Bobot: {{ number_format($kriteria->bobot, 4) }}</span>
                                    <span>·</span>
                                    <span class="capitalize">{{ $kriteria->tipe_input }}</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold
                                {{ $kriteria->jenis === 'benefit' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                {{ ucfirst($kriteria->jenis) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('kriteria.edit', $kriteria) }}"
                               class="flex-1 inline-flex items-center justify-center h-8 rounded border border-slate-200 bg-white text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('kriteria.destroy', $kriteria) }}" method="POST" onsubmit="return confirm('Hapus?')" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center h-8 rounded border border-rose-200 bg-white text-xs font-medium text-rose-600 hover:bg-rose-50 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Footer --}}
            @php $totalBobot = $kriterias->sum('bobot'); @endphp
            <div class="flex items-center justify-between px-5 py-3 bg-slate-50/50 border-t border-slate-100">
                <span class="text-xs text-slate-400 italic">Total bobot harus sama dengan 1.0000</span>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-400">Total:</span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                        {{ abs($totalBobot - 1) < 0.0001 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                        {{ number_format($totalBobot, 4) }}
                        @if(abs($totalBobot - 1) > 0.0001)
                            <span class="ml-1 opacity-60 font-normal">≠ 1</span>
                        @endif
                    </span>
                </div>
            </div>
        @endif
    </div>

    <script>
        function filterKriteria(query) {
            const q = query.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
            });
            document.querySelectorAll('.sm\\:hidden > div').forEach(card => {
                card.style.display = card.innerText.toLowerCase().includes(q) ? '' : 'none';
            });
        }
    </script>
</x-app-layout>