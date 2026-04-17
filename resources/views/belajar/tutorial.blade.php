<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/tokyo-night-dark.min.css">
        <div class="flex justify-between items-center font-mono">
            <h2 class="font-black text-2xl text-gray-900 uppercase tracking-widest">
                <i class="fa-solid fa-graduation-cap mr-2 text-indigo-600"></i> Training Camp
            </h2>
            @if ($isDemo)
                <div
                    class="bg-red-500 text-white font-black px-3 py-1 border-2 border-black animate-pulse text-sm shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                    DEMO MODE</div>
            @endif
        </div>
    </x-slot>

    <div class="py-8 min-h-[calc(100vh-140px)] bg-indigo-50 font-mono"
        style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px;">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black uppercase text-gray-900" style="text-shadow: 2px 2px 0px #a5b4fc;">PHP
                    Native Cheat Sheet</h1>
                <p class="font-bold text-gray-600">Pahami anatomi kodenya sebelum memulai misimu.</p>
            </div>

            <div x-data="tutorialData()" x-init="initTutorial()" class="flex flex-col md:flex-row gap-6">

                <div class="w-full md:w-1/3 flex flex-col gap-3">
                    <template x-for="(item, index) in materi" :key="index">
                        <button @click="selectTab(index)"
                            :class="{
                                'bg-indigo-600 text-white translate-x-2 shadow-none': activeTab ===
                                    index,
                                'bg-white text-gray-800 hover:bg-gray-100 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]': activeTab !==
                                    index,
                                'opacity-50 cursor-not-allowed': isDemo && item.is_locked_for_guest
                            }"
                            class="text-left font-black uppercase p-4 border-4 border-black transition-all flex justify-between items-center relative group">

                            <span>
                                <i :class="'fa-solid ' + item.icon + ' w-6 mr-2'"></i>
                                <span x-text="item.judul"></span>
                            </span>

                            <template x-if="isDemo && item.is_locked_for_guest">
                                <i class="fa-solid fa-lock text-red-500 text-xl"></i>
                            </template>

                            <template x-if="isDemo && item.is_locked_for_guest">
                                <div
                                    class="absolute -top-10 left-1/2 -translate-x-1/2 bg-black text-yellow-300 text-[10px] px-2 py-1 hidden group-hover:block whitespace-nowrap z-10 border-2 border-white">
                                    Register to Unlock!
                                </div>
                            </template>
                        </button>
                    </template>

                    @if ($isDemo)
                        <div
                            class="mt-6 bg-yellow-300 border-4 border-black p-4 text-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                            <p class="font-black text-sm mb-2">Ingin melihat kode Create, Update & Delete?</p>
                            <a href="{{ route('register') }}"
                                class="block w-full bg-black text-white font-black py-2 hover:bg-gray-800 transition">REGISTER
                                SEKARANG</a>
                        </div>
                    @endif
                </div>

                <div
                    class="w-full md:w-2/3 bg-gray-900 border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex flex-col">

                    <div class="bg-gray-800 text-white p-3 border-b-4 border-black flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        </div>
                        <span class="text-xs text-green-400 font-bold uppercase tracking-widest"><i
                                class="fa-solid fa-server mr-1"></i> Syntax_Terminal_v1.0</span>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <h2 class="text-2xl font-black text-yellow-400 uppercase tracking-widest mb-4 border-b-2 border-gray-700 pb-2"
                            x-text="'>> ' + materi[activeTab].judul"></h2>

                        <div class="text-gray-300 text-sm leading-relaxed mb-6" x-html="materi[activeTab].penjelasan">
                        </div>

                        <div
                            class="bg-black border-2 border-gray-700 rounded p-4 flex-1 relative overflow-hidden group">
                            <button
                                @click="navigator.clipboard.writeText(materi[activeTab].kode); RetroAlert('Kode berhasil disalin ke clipboard!');"
                                class="absolute top-2 right-2 bg-gray-800 text-white px-2 py-1 text-[10px] font-bold border border-gray-600 hidden group-hover:block hover:bg-gray-700 transition">
                                <i class="fa-solid fa-copy"></i> COPY
                            </button>
                            <pre class="h-full overflow-y-auto"><code class="language-php text-sm" x-ref="codeBlock" x-text="materi[activeTab].kode"></code></pre>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
        window.tutorialData = function() {
            return {
                activeTab: 0,
                materi: @json($materi),
                isDemo: {{ $isDemo ? 'true' : 'false' }},

                initTutorial() {
                    // Trigger highlight saat halaman pertama kali diload
                    this.$nextTick(() => {
                        hljs.highlightElement(this.$refs.codeBlock);
                    });
                },

                selectTab(index) {
                    // Cegah klik jika sedang demo dan materinya dikunci
                    if (this.isDemo && this.materi[index].is_locked_for_guest) {
                        return;
                    }
                    this.activeTab = index;

                    // Reset dan highlight ulang sintaks saat tab diganti
                    this.$nextTick(() => {
                        this.$refs.codeBlock.removeAttribute('data-highlighted');
                        hljs.highlightElement(this.$refs.codeBlock);
                    });
                }
            }
        }
    </script>
</x-app-layout>
