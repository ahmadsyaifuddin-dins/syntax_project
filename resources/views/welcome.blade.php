<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Syntax Project - Press Start</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600|space-mono:400,700&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .retro-shadow {
            shadow-offset: 8px 8px 0px 0px rgba(0, 0, 0, 1);
        }

        body {
            font-family: 'Space Mono', monospace;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 30px 30px;
        }
    </style>
</head>

<body class="antialiased bg-indigo-50 text-gray-900">

    <nav class="bg-white border-b-4 border-black p-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <i class="fa-solid fa-code text-indigo-600 text-2xl"></i>
                <span class="font-black text-xl uppercase tracking-tighter">Syntax<span
                        class="text-indigo-600">Proj</span></span>
            </a>

            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="border-2 border-black px-4 py-1 font-bold bg-yellow-300 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all uppercase text-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="font-bold uppercase text-sm hover:text-indigo-600 transition">Login</a>
                        <a href="{{ route('register') }}"
                            class="border-2 border-black px-4 py-1 font-bold bg-indigo-600 text-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all uppercase text-sm">Join</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <header class="py-16 md:py-24 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <div
                class="inline-block bg-green-400 border-4 border-black px-4 py-1 mb-6 rotate-2 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
                <span class="font-black uppercase tracking-widest text-xs md:text-sm italic">New Update: Laravel 11
                    Support!</span>
            </div>
            <h1 class="text-5xl md:text-7xl font-black uppercase tracking-tighter mb-6 leading-none"
                style="text-shadow: 4px 4px 0px #6366f1;">
                Mastering CRUD <br> PHP Native <br> <span class="text-indigo-600">Like a Pro</span>
            </h1>
            <p class="text-lg md:text-xl font-bold text-gray-700 mb-10 max-w-2xl mx-auto leading-relaxed">
                Platform interaktif untuk simulasi koding nyata. Bebas panik saat ujian skripsi karena sudah berlatih di
                "Syntax Project".
            </p>
            <div class="flex flex-col md:flex-row justify-center gap-6">
                <a href="#catalog"
                    class="bg-indigo-600 text-white text-xl font-black py-4 px-8 border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-2 hover:translate-y-2 transition-all uppercase tracking-widest">
                    <i class="fa-solid fa-play mr-2"></i> Start Mission
                </a>
                <a href="#how-it-works"
                    class="bg-white text-black text-xl font-black py-4 px-8 border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-2 hover:translate-y-2 transition-all uppercase tracking-widest">
                    How to Play
                </a>
            </div>
        </div>
    </header>

    <section id="catalog" class="py-20 bg-white border-y-4 border-black">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black uppercase tracking-widest">Stage Selection</h2>
                <p class="font-bold text-gray-500 mt-2">Pilih studi kasus yang ingin kamu taklukkan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

                <div
                    class="bg-indigo-50 border-4 border-black p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex flex-col h-full transition-transform hover:-translate-y-2">
                    <div class="flex justify-between items-start mb-6">
                        <div
                            class="bg-indigo-600 text-white p-3 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                            <i class="fa-solid fa-book-open text-2xl"></i>
                        </div>
                        <span
                            class="bg-green-400 border-2 border-black px-2 py-0.5 text-[10px] font-black uppercase">Free
                            Demo</span>
                    </div>
                    <h3 class="text-2xl font-black uppercase mb-3">Perpustakaan</h3>
                    <p class="font-bold text-sm text-gray-600 mb-8 flex-grow">
                        Mission: Create, Read, Update, Delete data buku dengan fitur upload cover & pencarian.
                    </p>
                    <div class="space-y-3">
                        <a href="{{ route('belajar.demo', 'perpustakaan') }}"
                            class="block w-full text-center bg-yellow-400 border-4 border-black py-2 font-black uppercase text-sm shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">
                            Coba Gratis (Demo)
                        </a>
                        <a href="{{ route('register') }}"
                            class="block w-full text-center bg-white border-2 border-black py-2 font-black uppercase text-sm hover:bg-gray-100 transition-colors">
                            Unlock Full Access
                        </a>
                    </div>
                </div>

                <div
                    class="bg-gray-100 border-4 border-gray-400 p-6 shadow-[8px_8px_0px_0px_rgba(156,163,175,1)] flex flex-col h-full grayscale opacity-75 relative overflow-hidden group">
                    <div
                        class="absolute inset-0 flex items-center justify-center bg-black/5 z-10 group-hover:bg-transparent transition-colors">
                        <i class="fa-solid fa-lock text-4xl text-gray-500 rotate-12"></i>
                    </div>
                    <div class="bg-gray-400 text-white p-3 border-2 border-gray-500 w-fit mb-6">
                        <i class="fa-solid fa-hospital text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-black uppercase mb-3 text-gray-500">Rumah Sakit</h3>
                    <p class="font-bold text-sm text-gray-400 mb-8 flex-grow">
                        Mission: Kelola data pasien & rekam medis. Membutuhkan relasi tabel yang lebih kompleks.
                    </p>
                    <button disabled
                        class="w-full text-center bg-gray-300 border-2 border-gray-400 py-2 font-black uppercase text-sm cursor-not-allowed">
                        Locked
                    </button>
                </div>

                <div
                    class="border-4 border-dashed border-gray-400 p-6 flex flex-col items-center justify-center text-center opacity-50">
                    <i class="fa-solid fa-circle-plus text-4xl text-gray-400 mb-4"></i>
                    <p class="font-black uppercase text-gray-400">Next Stage <br> Coming Soon</p>
                </div>

            </div>
        </div>
    </section>

    <section id="how-it-works" class="py-20 bg-indigo-600 text-white border-b-4 border-black overflow-hidden relative">
        <div class="absolute top-0 right-0 opacity-10 -mr-20 -mt-10">
            <i class="fa-solid fa-gamepad text-[300px]"></i>
        </div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <h2
                class="text-4xl font-black uppercase tracking-widest text-center mb-16 underline decoration-yellow-400 underline-offset-8">
                How to Play</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="text-center">
                    <div
                        class="w-20 h-20 bg-white text-indigo-600 border-4 border-black flex items-center justify-center text-3xl font-black mx-auto mb-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                        1</div>
                    <h4 class="text-xl font-black uppercase mb-2">Pilih Stage</h4>
                    <p class="font-bold text-indigo-100">Pilih studi kasus yang ingin kamu pelajari alur CRUD-nya.</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-20 h-20 bg-yellow-400 text-black border-4 border-black flex items-center justify-center text-3xl font-black mx-auto mb-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                        2</div>
                    <h4 class="text-xl font-black uppercase mb-2">Ikuti Guide</h4>
                    <p class="font-bold text-indigo-100">Baca materi singkat dan ketik kode PHP Native sesuai instruksi.
                    </p>
                </div>
                <div class="text-center">
                    <div
                        class="w-20 h-20 bg-green-400 text-black border-4 border-black flex items-center justify-center text-3xl font-black mx-auto mb-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                        3</div>
                    <h4 class="text-xl font-black uppercase mb-2">Mastering!</h4>
                    <p class="font-bold text-indigo-100">Pahami polanya dan kamu siap menghadapi dosen penguji.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white p-10 font-mono border-b-[12px] border-indigo-600">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="font-black uppercase text-sm">© {{ date('Y') }} Syntax Project. All Rights Reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-indigo-600 transition font-bold uppercase text-xs">About Us</a>
                <a href="#" class="hover:text-indigo-600 transition font-bold uppercase text-xs">FAQ</a>
                <a href="#" class="hover:text-indigo-600 transition font-bold uppercase text-xs">Github</a>
            </div>
        </div>
    </footer>

</body>

</html>
