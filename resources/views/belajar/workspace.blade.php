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
                class="w-full md:w-2/3 bg-gray-900 border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex flex-col overflow-hidden id-kolom-kode h-[65vh] md:h-full">

                <div
                    class="bg-black text-green-400 px-4 py-2 text-xs font-bold font-mono flex justify-between items-center border-b-4 border-black">
                    <span class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-yellow-500 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>
                        <span class="ml-2 text-gray-300">index.php</span>
                    </span>
                    <div class="flex gap-2">
                        <button @click="$store.workspace.showHint()"
                            class="bg-yellow-400 text-black px-2 py-1 font-black border-2 border-black hover:bg-yellow-300 transition active:translate-y-1">
                            <i class="fa-solid fa-lightbulb mr-1"></i> USE HINT
                        </button>
                        <button @click="$store.workspace.mulaiTour()"
                            class="bg-gray-800 text-white px-2 py-1 border border-gray-600 hover:bg-gray-700 transition">
                            <i class="fa-solid fa-circle-question"></i>
                        </button>
                    </div>
                </div>

                <div class="flex-1 p-4 flex flex-col gap-3 relative overflow-hidden bg-[#1a1b26]">

                    <textarea x-model="$store.workspace.userCode"
                        @input="$store.workspace.checkCode(); updateHighlight($store.workspace.userCode, $refs.codeBlock)"
                        class="w-full h-1/3 md:h-32 bg-[#24283b] text-indigo-300 font-mono text-sm border-2 border-gray-700 rounded p-4 focus:ring-0 focus:border-indigo-500 resize-none id-area-ketik shadow-inner"
                        placeholder=">_ ketik kodemu di sini..."></textarea>

                    <div class="w-full border-2 border-dashed p-2 text-xs font-bold font-mono transition-colors"
                        :class="{
                            'bg-gray-800 border-gray-600 text-gray-400': $store.workspace.feedbackStatus === 'idle',
                            'bg-red-900/50 border-red-500 text-red-400': $store.workspace.feedbackStatus === 'error',
                            'bg-yellow-900/50 border-yellow-500 text-yellow-400': $store.workspace
                                .feedbackStatus === 'warning',
                            'bg-green-900/50 border-green-500 text-green-400': $store.workspace
                                .feedbackStatus === 'success',
                        }">
                        <i class="fa-solid fa-robot mr-2"></i> <span x-text="$store.workspace.feedbackMsg"></span>
                    </div>

                    <div class="flex-1 bg-black rounded p-3 overflow-y-auto border-2 border-gray-800 relative">
                        <p class="text-[10px] text-green-500 mb-1 uppercase tracking-widest font-bold">OUTPUT PREVIEW:
                        </p>
                        <pre><code class="language-php" x-ref="codeBlock" x-init="hljs.highlightElement($el)">{{ '<?php' }}</code></pre>
                    </div>

                    <div x-show="$store.workspace.isStepSuccess" style="display: none;"
                        class="absolute inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-10">
                        <div
                            class="bg-green-400 text-black px-8 py-4 border-4 border-black shadow-[8px_8px_0px_0px_rgba(255,255,255,1)] transform rotate-2 animate-bounce flex flex-col items-center">
                            <h2 class="text-2xl font-black uppercase tracking-widest text-center mb-2">
                                <i class="fa-solid fa-star text-white drop-shadow-md mr-2"></i>
                                CODE ACCEPTED!
                            </h2>
                            <p class="font-bold text-sm">Target berhasil dihancurkan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('belajar.partials._scripts')
</x-app-layout>
