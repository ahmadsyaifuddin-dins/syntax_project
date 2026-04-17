<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
                Alpine.store('workspace', {
                            currentStep: {{ $progress->step_sekarang ?? 1 }},
                            materi: @json($materi),
                            studi_kasus: '{{ $studi_kasus }}',
                            userCode: '',
                            isStepSuccess: false,
                            driverObj: null,

                            init() {
                                // Inisialisasi Driver.js (Tour Guide)
                                const driver = window.driver.js.driver;
                                this.driverObj = driver({
                                    showProgress: true,
                                    steps: [{
                                            element: '.id-kolom-materi',
                                            popover: {
                                                title: 'Materi Belajar',
                                                description: 'Baca instruksi misimu di sini.',
                                                side: "right",
                                                align: 'start'
                                            }
                                        },
                                        {
                                            element: '.id-area-ketik',
                                            popover: {
                                                title: 'Live Editor',
                                                description: 'Ketik kode PHP Native sesuai instruksi.',
                                                side: "bottom",
                                                align: 'start'
                                            }
                                        },
                                        {
                                            element: '.id-step-badge',
                                            popover: {
                                                title: 'Progress',
                                                description: 'Pantau sisa misimu.',
                                                side: "bottom",
                                                align: 'start'
                                            }
                                        }
                                    ]
                                });

                                // Jalankan tour jika baru mulai
                                if (this.currentStep === 1 && this.userCode === '') {
                                    setTimeout(() => this.driverObj.drive(), 800);
                                }
                            },

                            feedbackMsg: 'Ketik kode di atas untuk melihat analisis...',
                            feedbackStatus: 'idle', // idle, error, warning, success

                            showHint() {
                                if (this.materi && this.materi[this.currentStep]) {
                                    // Isi text area dengan template buntung
                                    this.userCode = this.materi[this.currentStep].hint;
                                    this.feedbackStatus = 'warning';
                                    this.feedbackMsg = '💡 HINT AKTIF: Ganti titik-titik (...) dengan kode yang tepat!';
                                    // Trigger update highlight
                                    this.checkCode();
                                }
                            },

                            checkCode() {
                                if (!this.materi || !this.materi[this.currentStep]) return;

                                let expected = this.materi[this.currentStep].kode_harapan;
                                let typed = this.userCode;

                                if (typed.trim() === '') {
                                    this.feedbackStatus = 'idle';
                                    this.feedbackMsg = 'Ketik kode di atas untuk melihat analisis...';
                                    this.isStepSuccess = false;
                                    return;
                                }

                                // 1. TOLERANSI TANDA KUTIP & SPASI (Fleksibel)
                                // Ubah semua petik satu (') jadi petik dua ("), hapus spasi berlebih, ubah lowercase
                                let normExpected = expected.replace(/['"]/g, '"').replace(/\s+/g, '').toLowerCase();
                                let normTyped = typed.replace(/['"]/g, '"').replace(/\s+/g, '').toLowerCase();

                                // Cek Validasi Sukses Dulu
                                if (normTyped.includes(normExpected)) {
                                    this.isStepSuccess = true;
                                    this.feedbackStatus = 'success';
                                    this.feedbackMsg = '✅ CODE ACCEPTED! Syntax kamu sudah sempurna.';
                                    return;
                                }

                                this.isStepSuccess = false;

                                // 2. SMART REGEX (Cek kesalahan umum PHP/SQL)
                                if (!typed.includes(';')) {
                                    this.feedbackStatus = 'error';
                                    this.feedbackMsg =
                                        '❌ FATAL ERROR: Kamu melupakan titik koma (;) di akhir perintah!';
                                    return;
                                }

                                if (expected.includes('$') && !typed.includes('$')) {
                                    this.feedbackStatus = 'error';
                                    this.feedbackMsg =
                                        '❌ SYNTAX ERROR: Variabel PHP harus diawali dengan tanda dollar ($).';
                                    return;
                                }

                                if (expected.includes('mysqli_') && !typed.includes('mysqli_')) {
                                    this.feedbackStatus = 'error';
                                    this.feedbackMsg =
                                        '❌ DB ERROR: Fungsi bawaan PHP untuk MySQL (mysqli_...) belum dipanggil.';
                                    return;
                                }

                                // 3. PERSENTASE KECOCOKAN (Kemiripan Panjang Karakter)
                                // Kita pakai rasio sederhana karakter yang diketik vs target
                                let targetLen = normExpected.length;
                                let typedLen = normTyped.length;
                                let rasio = (typedLen / targetLen) * 100;

                                if (rasio > 75 && rasio <= 120) {
                                    this.feedbackStatus = 'warning';
                                    this.feedbackMsg =
                                        '🔥 HAMPIR BENAR! Cek lagi apakah ada typo (salah eja) atau parameter yang kurang tepat.';
                                } else if (rasio > 30) {
                                    this.feedbackStatus = 'idle';
                                    this.feedbackMsg = '👍 Sudah di jalur yang benar. Lanjutkan ketikanmu!';
                                } else {
                                    this.feedbackStatus = 'error';
                                    this.feedbackMsg =
                                        '❌ Syntax masih jauh dari harapan. Periksa lagi instruksi objektifnya.';
                                }
                            },

                            nextStep() {
                                if (this.currentStep < Object.keys(this.materi).length) {
                                    this.currentStep++;

                                    // 1. Reset text area
                                    this.userCode = '';
                                    this.isStepSuccess = false;

                                    // 2. Reset Feedback Box UI ke state awal
                                    this.feedbackStatus = 'idle';
                                    this.feedbackMsg = 'Ketik kode di atas untuk melihat analisis...';

                                    // 3. Simpan progress ke database
                                    this.simpanProgress(false);

                                    // 4. Force Reset DOM Highlight.js (Output Preview)
                                    // Gunakan setTimeout kecil untuk menunggu DOM selesai update step-nya
                                    setTimeout(() => {
                                                const codeBlock = document.querySelector('code.language-php');
                                                if (codeBlock) {
                                                    codeBlock.textContent = '<?php\n\n';
                            codeBlock.removeAttribute('data-highlighted');
                            hljs.highlightElement(codeBlock);
                        }
                    }, 50);

                } else {
                    RetroAlert("MISSION ACCOMPLISHED! Selamat, kamu telah menyelesaikan modul ini.");
                    this.simpanProgress(true);
                    
                    // Redirect otomatis setelah tutup alert
                    setTimeout(() => {
                        window.location.href = "{{ route('belajar.index') }}";
                    }, 2000);
                }
            },

            simpanProgress(isFinish) {
                // CEK APAKAH USER LOGIN (Gunakan variabel Blade untuk cek)
                const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

                if (!isLoggedIn) {
                    console.log("Demo Mode: Progress tidak disimpan ke database.");
                    return; // Hentikan fungsi di sini jika Guest
                }

                fetch(`{{ url('/belajar/workspace') }}/${this.studi_kasus}/progress`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        step: this.currentStep,
                        is_finish: isFinish
                    })
                }).catch(err => console.error("Gagal menyimpan:", err));
            },

            mulaiTour() {
                if (this.driverObj) this.driverObj.drive();
            }
        });
    });

    // Handle highlight manual karena highlight.js butuh DOM siap
    function updateHighlight(code, el) {
        el.textContent = '<?php\n\n' + code;
        el.removeAttribute('data-highlighted');
        hljs.highlightElement(el);
    }
</script>
