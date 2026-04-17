<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/tokyo-night-dark.min.css">
        <div class="flex justify-between items-center font-mono gap-4">
            <h2 class="font-black text-xl text-gray-900 uppercase tracking-widest">
                <i class="fa-solid fa-folder-tree mr-2 text-indigo-600 animate-pulse"></i> Project:
                {{ strtoupper($studi_kasus) }}
            </h2>
            <div class="bg-indigo-200 border-4 border-black px-4 py-2 font-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-xs uppercase"
                x-data>
                AUTO-SAVE: <span class="text-green-700 animate-pulse">ACTIVE</span>
            </div>
        </div>
    </x-slot>

    <div x-data="projectSandbox()" x-init="initSandbox()" class="py-6 min-h-[calc(100vh-140px)] bg-indigo-50 font-mono"
        style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px;">

        <div class="max-w-[1400px] mx-auto px-4 h-full flex flex-col lg:flex-row gap-6 items-stretch">

            @include('belajar.project-partials._sidebar')

            @include('belajar.project-partials._editor')

            @include('belajar.project-partials._preview')

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
        window.projectSandbox = function() {
            return {
                files: @json($files),
                activeFile: 'koneksi.php',
                studi_kasus: '{{ $studi_kasus }}',
                feedbackMsg: 'Awaiting input...',
                feedbackStatus: 'idle',
                previewHtml: '',
                showUnlockAnim: false,
                saveTimeout: null,

                initSandbox() {
                    let targetFile = Object.keys(this.files)[0];
                    for (let name in this.files) {
                        if (!this.files[name].is_locked && !this.files[name].is_completed) {
                            targetFile = name;
                            break;
                        }
                    }
                    this.activeFile = targetFile;
                    this.updateHighlight();
                    this.updatePreview();
                },

                selectFile(fileName) {
                    if (this.files[fileName].is_locked) {
                        RetroAlert("MISSION LOCKED! Selesaikan file sebelumnya terlebih dahulu.");
                        return;
                    }
                    this.activeFile = fileName;
                    this.feedbackStatus = 'idle';
                    this.feedbackMsg = 'Switched to ' + fileName;

                    this.$nextTick(() => {
                        this.updateHighlight();
                        this.updatePreview();
                    });
                },

                showHint() {
                    this.files[this.activeFile].content = this.files[this.activeFile].hint;
                    this.feedbackStatus = 'warning';
                    this.feedbackMsg = '💡 HINT APPLIED: Lengkapi bagian titik-titik (...)';
                    this.updateHighlight();
                    this.updatePreview();
                    this.checkCode();
                },

                resetFile() {
                    RetroConfirm("Reset isi file " + this.activeFile + "?", () => {
                        this.files[this.activeFile].content = '';
                        this.files[this.activeFile].is_completed = false;

                        let foundActive = false;
                        for (let name in this.files) {
                            if (foundActive) {
                                this.files[name].is_locked = true;
                                this.files[name].is_completed = false;
                            }
                            if (name === this.activeFile) foundActive = true;
                        }

                        this.updateHighlight();
                        this.updatePreview();
                        this.saveData();
                        this.checkCode();
                    });
                },

                onCodeInput() {
                    this.updateHighlight();
                    this.updatePreview();
                    this.checkCode();

                    clearTimeout(this.saveTimeout);
                    this.saveTimeout = setTimeout(() => this.saveData(), 1000);
                },

                updateHighlight() {
                    let code = this.files[this.activeFile].content || '';
                    this.$refs.codeBlock.textContent = code;
                    this.$refs.codeBlock.removeAttribute('data-highlighted');
                    hljs.highlightElement(this.$refs.codeBlock);
                },

                updatePreview() {
                    let code = this.files[this.activeFile].content || '';
                    let htmlContent = "";

                    // Deteksi HTML Diperluas: Cukup cek apakah ada tag pembuka dan penutup
                    if (code.includes('<') && code.includes('>')) {
                        htmlContent = `
                        <style>
                            body { font-family: sans-serif; padding: 20px; color: #333; }
                            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                            table, th, td { border: 1px solid #ccc; padding: 8px; }
                            th { background: #f4f4f4; }
                            form { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; }
                            input { padding: 8px; margin: 5px 0; width: 100%; box-sizing: border-box; }
                            button { background: #4f46e5; color: white; border: none; padding: 10px 15px; cursor: pointer; margin-top: 10px; font-weight: bold; }
                        </style>
                        ${code}
                        `;
                    }
                    this.previewHtml = htmlContent;
                },

                checkCode() {
                    let file = this.files[this.activeFile];
                    let expected = file.kode_harapan;
                    let typed = file.content || '';

                    if (typed.trim() === '') {
                        this.feedbackStatus = 'idle';
                        this.feedbackMsg = 'Awaiting input...';
                        return;
                    }

                    let normExpected = expected.replace(/['"]/g, '"').replace(/\s+/g, '').toLowerCase();
                    let normTyped = typed.replace(/['"]/g, '"').replace(/\s+/g, '').toLowerCase();

                    if (normTyped.includes(normExpected)) {
                        this.feedbackStatus = 'success';
                        this.feedbackMsg = '✅ CODE ACCEPTED! Syntax sempurna.';

                        if (!file.is_completed) {
                            file.is_completed = true;
                            this.unlockNextFile();
                            this.saveData();

                            this.showUnlockAnim = true;
                            setTimeout(() => {
                                this.showUnlockAnim = false;
                            }, 2000);
                        }
                        return;
                    }

                    if (!typed.includes(';')) {
                        this.feedbackStatus = 'error';
                        this.feedbackMsg = '❌ FATAL ERROR: Lupa titik koma (;)';
                        return;
                    }
                    if (expected.includes('mysqli_') && !typed.includes('mysqli_')) {
                        this.feedbackStatus = 'error';
                        this.feedbackMsg = '❌ DB ERROR: mysqli_ function missing.';
                        return;
                    }

                    let rasio = (normTyped.length / normExpected.length) * 100;
                    if (rasio > 75 && rasio <= 120) {
                        this.feedbackStatus = 'warning';
                        this.feedbackMsg = '🔥 HAMPIR BENAR! Cek typo atau parameter.';
                    } else {
                        this.feedbackStatus = 'error';
                        this.feedbackMsg = '❌ Syntax belum tepat. Teruslah mencoba.';
                    }
                },

                unlockNextFile() {
                    let unlockNext = false;
                    for (let name in this.files) {
                        if (unlockNext) {
                            this.files[name].is_locked = false;
                            break;
                        }
                        if (name === this.activeFile) unlockNext = true;
                    }
                },

                saveData() {
                    fetch(`{{ url('/belajar/project') }}/${this.studi_kasus}/save`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            file_name: this.activeFile,
                            content: this.files[this.activeFile].content,
                            is_completed: this.files[this.activeFile].is_completed
                        })
                    }).catch(err => console.error("Gagal save:", err));
                }
            }
        }
    </script>
</x-app-layout>
