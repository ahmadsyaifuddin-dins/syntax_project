<div class="w-full lg:w-2/5 flex flex-col gap-6">

    <div class="bg-white border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] p-4 shrink-0">
        <h3 class="font-black text-lg uppercase border-b-2 border-black pb-2 mb-2"
            x-text="'>> ' + files[activeFile].judul"></h3>
        <p class="text-xs text-gray-700 leading-relaxed mb-4" x-html="files[activeFile].teks"></p>
        <div class="bg-yellow-300 border-2 border-black p-3 text-sm font-bold text-black shadow-inner">
            <i class="fa-solid fa-bullseye mr-1"></i> <span x-text="files[activeFile].instruksi"></span>
        </div>
    </div>

    <div class="bg-gray-900 border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] flex flex-col flex-1">
        <div
            class="bg-black text-gray-300 p-2 text-xs font-bold border-b-4 border-black flex justify-between items-center shrink-0">
            <span x-text="activeFile"></span>
            <div class="flex gap-2">
                <button @click="showHint()"
                    class="bg-yellow-400 text-black px-2 py-1 text-[10px] hover:bg-yellow-300 border border-black"><i
                        class="fa-solid fa-lightbulb"></i> HINT</button>
                <button @click="resetFile()"
                    class="bg-red-500 text-white px-2 py-1 text-[10px] hover:bg-red-400 border border-black"><i
                        class="fa-solid fa-rotate-right"></i> RESET</button>
            </div>
        </div>

        <textarea x-model="files[activeFile].content" @input="onCodeInput()"
            class="w-full resize-y min-h-[300px] bg-[#1a1b26] text-indigo-300 font-mono text-sm border-b-2 border-gray-700 p-4 focus:ring-0 focus:border-indigo-500 shadow-inner"
            placeholder=">_ Write PHP code here..."></textarea>

        <div class="w-full p-2 text-[10px] font-black uppercase transition-colors shrink-0"
            :class="{ 'bg-gray-800 text-gray-400': feedbackStatus === 'idle', 'bg-red-900 text-red-400': feedbackStatus === 'error', 'bg-yellow-900 text-yellow-400': feedbackStatus === 'warning', 'bg-green-900 text-green-400': feedbackStatus === 'success' }">
            <i class="fa-solid fa-robot mr-1"></i> <span x-text="feedbackMsg"></span>
        </div>

        <div class="flex-1 bg-black p-4 overflow-y-auto relative min-h-[150px]">
            <pre><code class="language-php" x-ref="codeBlock" x-init="hljs.highlightElement($el)">{{ '<?php\n\n' }}</code></pre>
        </div>

        <div x-show="showUnlockAnim" style="display: none;"
            class="absolute inset-0 bg-green-500/90 backdrop-blur flex items-center justify-center z-20 flex-col border-4 border-white">
            <i class="fa-solid fa-unlock-keyhole text-6xl text-white animate-bounce mb-4"></i>
            <h2 class="text-3xl font-black text-black uppercase">FILE COMPLETED!</h2>
            <p class="text-white font-bold">Next file unlocked in explorer.</p>
        </div>
    </div>
</div>
