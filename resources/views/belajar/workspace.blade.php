<x-app-layout>
    <x-slot name="header">
        @guest
            <div
                class="mt-2 bg-red-500 text-white text-[10px] font-black uppercase px-2 py-1 border-2 border-black inline-block animate-pulse">
                DEMO MODE: Progress tidak akan tersimpan!
            </div>
        @endguest
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/tokyo-night-dark.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css" />

        <div class="flex flex-col md:flex-row justify-between items-center font-mono gap-4">
            <h2 class="font-black text-xl md:text-2xl text-gray-900 uppercase tracking-widest text-center md:text-left">
                <i class="fa-solid fa-terminal mr-2 text-indigo-600 animate-pulse"></i> Mission:
                {{ strtoupper($studi_kasus) }}
            </h2>
            <div class="bg-indigo-200 border-4 border-black px-4 py-2 font-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-sm uppercase id-step-badge"
                x-data>
                STAGE <span x-text="$store.workspace.currentStep" class="text-indigo-800 text-lg"></span> /
                {{ count($materi) }}
            </div>
        </div>
    </x-slot>

    <div x-data class="py-6 min-h-[calc(100vh-140px)] bg-indigo-50 font-mono"
        style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px;">

        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col md:flex-row gap-8 md:gap-6 md:h-[calc(100vh-220px)]">

            <div
                class="w-full md:w-1/3 bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex flex-col overflow-hidden id-kolom-materi h-auto md:h-full">
                <div class="bg-indigo-600 text-white p-4 border-b-4 border-black">
                    <h3 class="font-black text-lg uppercase tracking-wider"
                        x-text="$store.workspace.materi[$store.workspace.currentStep].judul"></h3>
                </div>

                <div class="p-6 flex-1 overflow-y-auto bg-gray-50">
                    <div class="text-sm text-gray-800 font-medium leading-relaxed mb-6"
                        x-html="$store.workspace.materi[$store.workspace.currentStep].teks"></div>

                    <div class="bg-yellow-300 border-4 border-black p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        <p
                            class="text-sm font-black text-black uppercase tracking-wider border-b-2 border-black pb-1 mb-2">
                            Objective:</p>
                        <p class="text-sm text-gray-900 font-bold"
                            x-text="$store.workspace.materi[$store.workspace.currentStep].instruksi"></p>
                    </div>

                    <button x-show="$store.workspace.isStepSuccess" @click="$store.workspace.nextStep()"
                        style="display: none;"
                        class="mt-8 w-full bg-green-400 text-black px-4 py-3 font-black uppercase tracking-widest border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-green-300 active:shadow-none active:translate-y-[4px] active:translate-x-[4px] transition-all">
                        NEXT STAGE ►
                    </button>
                </div>
            </div>

            <div
                class="w-full md:w-2/3 bg-gray-900 border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex flex-col overflow-hidden id-kolom-kode h-[500px] md:h-full">
                <div
                    class="bg-black text-green-400 px-4 py-2 text-xs font-bold flex justify-between items-center border-b-4 border-black">
                    <span class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        <span class="ml-2 text-gray-300">index.php</span>
                    </span>
                    <button @click="$store.workspace.mulaiTour()"
                        class="bg-gray-800 text-white px-2 py-1 border-2 border-black hover:bg-gray-700 transition uppercase text-[10px]">HINT</button>
                </div>

                <div class="flex-1 p-4 flex flex-col gap-4 relative overflow-hidden bg-[#1a1b26]">
                    <textarea x-model="$store.workspace.userCode"
                        @input="$store.workspace.checkCode(); updateHighlight($store.workspace.userCode, $refs.codeBlock)"
                        class="w-full h-1/2 md:h-40 bg-[#24283b] text-indigo-300 font-mono text-sm border-4 border-black rounded p-4 focus:ring-0 focus:border-indigo-500 resize-none id-area-ketik shadow-inner"
                        placeholder=">_ ketik kodemu di sini..."></textarea>

                    <div class="flex-1 bg-black rounded border-4 border-black p-4 overflow-y-auto relative">
                        <p class="text-[10px] text-green-500 mb-2 uppercase tracking-widest font-black">PREVIEW WINDOW:
                        </p>
                        <pre><code class="language-php" x-ref="codeBlock" x-init="hljs.highlightElement($el)">{{ '<?php' }}</code></pre>
                    </div>

                    <div x-show="$store.workspace.isStepSuccess" style="display: none;"
                        class="absolute inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-10 p-4">
                        <div
                            class="bg-green-400 text-black px-8 py-4 border-4 border-black shadow-[8px_8px_0px_0px_rgba(255,255,255,1)] transform rotate-2 animate-bounce">
                            <h2 class="text-xl md:text-2xl font-black uppercase tracking-widest text-center">CODE
                                ACCEPTED!</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('belajar.partials._scripts')
</x-app-layout>
