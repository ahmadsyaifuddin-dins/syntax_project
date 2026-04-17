@props(['name', 'required' => false, 'accept' => '.pdf,.doc,.docx', 'currentFile' => null])

<div x-data="{
    fileName: null,
    fileSize: null,
    currentFile: '{{ $currentFile }}',

    fileSelected(event) {
        const file = event.target.files[0];
        if (!file) {
            this.fileName = null;
            this.fileSize = null;
            return;
        }

        this.fileName = file.name;
        // Hitung ukuran file dalam MB atau KB
        const sizeInKB = file.size / 1024;
        this.fileSize = sizeInKB > 1024 ?
            (sizeInKB / 1024).toFixed(2) + ' MB' :
            sizeInKB.toFixed(0) + ' KB';
    }
}" class="w-full">

    <input type="file" name="{{ $name }}" x-ref="fileInput" @change="fileSelected" accept="{{ $accept }}"
        class="hidden" {{ $required ? 'required' : '' }}>

    <div class="mt-1">
        <div x-show="!fileName" @click="$refs.fileInput.click()"
            class="border-2 border-dashed border-gray-300 rounded-md p-4 flex items-center justify-center bg-gray-50 cursor-pointer hover:bg-gray-100 transition duration-200">

            <template x-if="!currentFile">
                <div class="text-center">
                    <i class="fa-solid fa-file-arrow-up text-2xl text-gray-400 mb-1"></i>
                    <p class="text-sm text-gray-500 font-medium">Pilih Dokumen</p>
                    <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX (Maks 5MB)</p>
                </div>
            </template>

            <template x-if="currentFile">
                <div class="flex items-center gap-3 w-full px-2">
                    <i class="fa-solid fa-file-pdf text-red-500 text-2xl"></i>
                    <div class="flex-1 overflow-hidden">
                        <p class="text-sm font-medium text-gray-700 truncate" x-text="currentFile"></p>
                        <p class="text-xs text-gray-500">File tersimpan. Klik untuk mengganti.</p>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="fileName" style="display: none;"
            class="border border-indigo-200 bg-indigo-50 rounded-md p-3 flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <i class="fa-solid fa-file-lines text-indigo-500 text-2xl"></i>
                <div class="flex-1 overflow-hidden">
                    <p class="text-sm font-medium text-indigo-700 truncate" x-text="fileName"></p>
                    <p class="text-xs text-indigo-500" x-text="fileSize"></p>
                </div>
            </div>

            <button type="button" @click="$refs.fileInput.value = ''; fileName = null; fileSize = null;"
                class="p-2 text-indigo-400 hover:text-red-500 transition-colors focus:outline-none">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
    </div>
</div>
