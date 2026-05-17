@props([
    'periodeId',
    'importRoute' => null,
    'templateRoute' => '#'
])

@php
    // Use the provided import route, or fallback to the standard periode.import route
    $importRoute = $importRoute ?? route('periode.import', $periodeId);
    
    // For the template route, only try to use it if the route actually exists, otherwise fallback to '#'
    $templateRoute = $templateRoute !== '#' ? $templateRoute : (Route::has('alternatif.template') ? route('alternatif.template', $periodeId) : '#');
@endphp

<div x-data="{
        open: false,
        dragging: false,
        file: null,
        fileName: '',
        fileSize: '',
        error: '',
        loading: false,

        openModal() { this.open = true; this.reset(); },
        closeModal() { this.open = false; this.reset(); },
        reset() { this.file = null; this.fileName = ''; this.fileSize = ''; this.error = ''; this.loading = false; },

        handleDrop(e) {
            this.dragging = false;
            const f = e.dataTransfer.files[0];
            if (f) this.setFile(f);
        },

        handleInput(e) {
            const f = e.target.files[0];
            if (f) this.setFile(f);
        },

        setFile(f) {
            const allowed = ['xlsx','xls','csv'];
            const ext = f.name.split('.').pop().toLowerCase();
            if (!allowed.includes(ext)) {
                this.error = 'Format tidak didukung. Gunakan .xlsx, .xls, atau .csv';
                this.file = null;
                return;
            }
            this.file = f;
            this.fileName = f.name;
            this.fileSize = (f.size / 1024).toFixed(0) + ' KB';
            this.error = '';
        },

        submitForm() {
            if (!this.file) return;
            this.loading = true;
            const dt = new DataTransfer();
            dt.items.add(this.file);
            this.$refs.hiddenFile.files = dt.files;
            this.$refs.importForm.submit();
        }
    }" 
    class="w-full sm:w-auto"
>
    <!-- Trigger button -->
    <button type="button" @click="openModal()" class="px-4 py-2 rounded-lg bg-green-600 text-sm font-semibold text-white hover:bg-green-700 transition w-full sm:w-auto flex items-center justify-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h2"/><path d="M8 17h2"/><path d="M14 13h2"/><path d="M14 17h2"/></svg>
        Import Excel
    </button>

    <!-- Modal overlay -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         style="display: none;">
        
        <!-- Modal backdrop click to close -->
        <div class="fixed inset-0" @click.self="closeModal()"></div>

        <!-- Modal card -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col z-10">
            
            <!-- Modal header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Import Data Excel</h3>
                    <p class="text-sm text-gray-500 mt-1">Upload file untuk menambahkan banyak data warga sekaligus</p>
                </div>
                <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-6">
                <!-- Drag & drop zone -->
                <div x-show="file === null"
                     @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="handleDrop($event)"
                     :class="{ 'border-blue-500 bg-blue-50': dragging, 'border-gray-300 bg-gray-50': !dragging }"
                     class="border-2 border-dashed rounded-xl p-10 flex flex-col items-center justify-center text-center transition-colors">
                    
                    <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.742 3.742 0 0115 19.5H6.75z" />
                    </svg>
                    
                    <p class="text-sm font-medium text-gray-700">Drag & drop file di sini</p>
                    <p class="text-xs text-gray-500 mt-1 mb-4">atau</p>
                    
                    <button type="button" @click="$refs.fileInput.click()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                        Pilih File
                    </button>
                    
                    <!-- Hidden file input -->
                    <input type="file" x-ref="fileInput" @change="handleInput($event)" accept=".xlsx,.xls,.csv" class="hidden">
                </div>

                <!-- Error message -->
                <div x-show="error !== ''" class="mt-4 flex items-center gap-2 text-red-600 bg-red-50 p-3 rounded-lg text-sm border border-red-200" style="display: none;">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span x-text="error"></span>
                </div>

                <!-- File preview -->
                <div x-show="file !== null" class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center justify-between" style="display: none;">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="bg-green-100 text-green-600 p-2 rounded-lg shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 9 0 00-9-9z" /></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-green-900 truncate" x-text="fileName"></p>
                            <p class="text-xs text-green-700 mt-0.5" x-text="fileSize"></p>
                        </div>
                    </div>
                    <button type="button" @click="reset()" class="text-green-700 hover:text-green-900 bg-green-100 hover:bg-green-200 p-1.5 rounded-lg transition shrink-0 ml-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Download template link -->
                <div class="mt-4 text-center">
                    <a href="{{ $templateRoute }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium underline-offset-2 hover:underline transition">
                        Belum punya template? Download di sini
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3 rounded-b-xl">
                <button type="button" @click="closeModal()" class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50 transition shadow-sm">
                    Batal
                </button>
                <button type="button" 
                        x-show="file !== null"
                        @click="submitForm()" 
                        :disabled="loading"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition shadow-sm flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                        style="display: none;">
                    
                    <!-- Spinner -->
                    <svg x-show="loading" class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    
                    <!-- Upload icon -->
                    <svg x-show="!loading" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                    </svg>
                    
                    <span x-text="loading ? 'Mengupload...' : 'Tambahkan Data Excel'"></span>
                </button>
            </div>
            
            <!-- Hidden form -->
            <!-- The controller expects "excel" as the file name, not "file", so keep it as excel -->
            <form x-ref="importForm" action="{{ $importRoute }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" name="excel" x-ref="hiddenFile">
            </form>
        </div>
    </div>
</div>
