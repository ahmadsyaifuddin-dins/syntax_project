<div x-data="retroModal()" x-show="open" style="display: none;"
    class="fixed inset-0 z-[100] flex items-center justify-center font-mono px-4">

    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" x-show="open" x-transition.opacity></div>

    <div class="relative bg-indigo-50 border-4 border-black p-6 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-md w-full"
        x-show="open" x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100 transform"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95" @click.away="if(type === 'alert') close()">

        <div class="flex items-center gap-3 mb-4 border-b-4 border-black pb-3"
            :class="type === 'alert' ? 'text-indigo-600' : 'text-red-600'">
            <i class="fa-solid text-3xl"
                :class="type === 'alert' ? 'fa-circle-info animate-bounce' : 'fa-triangle-exclamation animate-pulse'"></i>
            <h3 class="text-xl font-black uppercase tracking-widest text-black"
                x-text="type === 'alert' ? 'SYSTEM MESSAGE' : 'WARNING!'"></h3>
        </div>

        <p class="text-gray-800 font-bold mb-8 leading-relaxed" x-text="message"></p>

        <div class="flex justify-end gap-4">
            <button x-show="type === 'confirm'" @click="close()"
                class="bg-gray-300 text-black border-4 border-black px-4 py-2 font-black uppercase shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-gray-400 active:shadow-none active:translate-y-1 active:translate-x-1 transition-all">
                CANCEL
            </button>

            <button @click="executeAction()"
                class="text-white border-4 border-black px-6 py-2 font-black uppercase shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-y-1 active:translate-x-1 transition-all"
                :class="type === 'alert' ? 'bg-indigo-600 hover:bg-indigo-500' : 'bg-red-500 hover:bg-red-400'">
                <span x-text="type === 'alert' ? 'OK' : 'YES, DO IT!'"></span>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('retroModal', () => ({
            open: false,
            type: 'alert', // 'alert' atau 'confirm'
            message: '',
            onConfirm: null,

            init() {
                // Daftarkan ke window agar bisa dipanggil dari mana saja seperti fungsi alert() biasa
                window.RetroAlert = (msg) => this.showAlert(msg);
                window.RetroConfirm = (msg, callback) => this.showConfirm(msg, callback);
            },

            showAlert(msg) {
                this.message = msg;
                this.type = 'alert';
                this.open = true;
            },

            showConfirm(msg, callback) {
                this.message = msg;
                this.type = 'confirm';
                this.onConfirm = callback;
                this.open = true;
            },

            close() {
                this.open = false;
                this.onConfirm = null;
            },

            executeAction() {
                if (this.type === 'confirm' && this.onConfirm) {
                    this.onConfirm();
                }
                this.close();
            }
        }));
    });
</script>
