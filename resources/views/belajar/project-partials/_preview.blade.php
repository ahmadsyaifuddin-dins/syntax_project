<div
    class="w-full lg:w-2/5 bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex flex-col min-h-[500px]">
    <div class="bg-gray-200 p-3 border-b-4 border-black flex items-center gap-2 shrink-0">
        <div class="flex gap-1 mr-4">
            <div class="w-3 h-3 bg-red-500 rounded-full border border-black"></div>
            <div class="w-3 h-3 bg-yellow-400 rounded-full border border-black"></div>
            <div class="w-3 h-3 bg-green-500 rounded-full border border-black"></div>
        </div>
        <div class="flex-1 bg-white border-2 border-black text-xs px-2 py-1 text-gray-500 truncate">
            localhost:8000/sandbox/<span x-text="activeFile"></span></div>
    </div>

    <div class="flex-1 bg-white relative">
        <div x-show="!previewHtml"
            class="absolute inset-0 flex items-center justify-center text-gray-400 font-bold uppercase text-xs text-center border-4 border-dashed border-gray-300 m-4">
            Browser Output<br>(HTML Tags Only)
        </div>
        <iframe x-show="previewHtml" x-bind:srcdoc="previewHtml"
            class="absolute inset-0 w-full h-full border-none"></iframe>
    </div>
</div>
