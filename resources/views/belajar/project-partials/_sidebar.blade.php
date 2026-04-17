<div
    class="w-full lg:w-1/5 bg-gray-900 border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex flex-col overflow-hidden h-48 lg:h-[80vh]">
    <div class="bg-gray-800 text-white p-3 border-b-4 border-black font-black uppercase text-xs tracking-widest">
        EXPLORER
    </div>
    <div class="p-2 flex flex-col gap-1 overflow-y-auto">
        <template x-for="(file, fileName) in files" :key="fileName">
            <button @click="selectFile(fileName)"
                :class="{
                    'bg-indigo-600 text-white border-l-4 border-yellow-400': activeFile === fileName,
                    'text-gray-400 hover:bg-gray-800 hover:text-gray-200': activeFile !== fileName && !file.is_locked,
                    'text-gray-600 cursor-not-allowed opacity-50': file.is_locked
                }"
                class="text-left text-sm font-bold p-2 transition-all flex items-center justify-between group">

                <span class="flex items-center gap-2">
                    <i class="fa-brands fa-php text-indigo-400" x-show="!file.is_locked"></i>
                    <i class="fa-solid fa-lock text-red-500" x-show="file.is_locked"></i>
                    <span x-text="fileName"></span>
                </span>

                <i class="fa-solid fa-circle-check text-green-500" x-show="file.is_completed"></i>
            </button>
        </template>
    </div>
</div>
