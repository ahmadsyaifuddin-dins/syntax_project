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

            checkCode() {
                if (!this.materi || !this.materi[this.currentStep]) return;

                let expected = this.materi[this.currentStep].kode_harapan.replace(/\s+/g, '');
                let typed = this.userCode.replace(/\s+/g, '');

                if (typed.includes(expected)) {
                    this.isStepSuccess = true;
                } else {
                    this.isStepSuccess = false;
                }
            },

            nextStep() {
                if (this.currentStep < Object.keys(this.materi).length) {
                    this.currentStep++;
                    this.userCode = '';
                    this.isStepSuccess = false;
                    this.simpanProgress(false);
                } else {
                    alert("MISSION ACCOMPLISHED! Selamat, kamu telah menyelesaikan modul CRUD ini.");
                    this.simpanProgress(true);
                    window.location.href = "{{ route('belajar.index') }}";
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
