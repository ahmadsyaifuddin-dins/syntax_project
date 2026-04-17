<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center font-mono">
            <h2 class="font-black text-2xl text-gray-900 uppercase tracking-widest">
                <i class="fa-solid fa-gamepad mr-2 text-indigo-600 animate-pulse"></i> {{ __('Stage Select') }}
            </h2>
            <div
                class="bg-yellow-300 border-2 border-black px-4 py-1 font-bold shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-sm">
                PLAYER: {{ strtoupper(Auth::user()->name) }}
            </div>
        </div>
    </x-slot>

    <div class="py-12 min-h-[calc(100vh-140px)] bg-indigo-50"
        style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="text-center mb-10 font-mono">
                <h1 class="text-4xl font-black text-gray-900 uppercase tracking-widest mb-2"
                    style="text-shadow: 2px 2px 0px #a5b4fc;">
                    Choose Your Module
                </h1>
                <p class="text-gray-600 font-bold">Selesaikan tantangan CRUD untuk mendapatkan sertifikat kelulusan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 font-mono relative">

                <div
                    class="bg-white border-4 border-black p-6 relative shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all hover:-translate-y-1 hover:shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">

                    <div
                        class="absolute -top-5 -right-5 border-4 border-black px-4 py-2 font-black uppercase text-sm shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rotate-3 
                        {{ $progressPerpus->status === 'selesai' ? 'bg-green-400 text-black' : ($progressPerpus->status === 'sedang' ? 'bg-yellow-400 text-black' : 'bg-red-400 text-white') }}">
                        {{ $progressPerpus->status === 'selesai' ? 'STAGE CLEARED' : ($progressPerpus->status === 'sedang' ? 'IN PROGRESS' : 'NEW MISSION') }}
                    </div>

                    <div class="flex items-start gap-4 mb-6 mt-2">
                        <div
                            class="w-16 h-16 bg-indigo-200 border-2 border-black flex items-center justify-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                            <i class="fa-solid fa-book-open text-3xl text-indigo-700"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-2xl uppercase tracking-wider text-gray-900">Perpustakaan</h3>
                            <p class="text-sm font-bold text-gray-500 mt-1">Level: Beginner (2 Tables)</p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-700 font-medium mb-6 leading-relaxed">
                        Bangun sistem manajemen buku sederhana. Kamu akan belajar koneksi database, menampilkan data,
                        dan upload gambar (cover buku).
                    </p>

                    <div class="mb-6">
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span>PROGRESS</span>
                            <span>STEP {{ $progressPerpus->step_sekarang }} / MAX</span>
                        </div>
                        <div class="w-full bg-gray-200 border-2 border-black h-6 p-0.5">
                            @php
                                // Asumsi max step = 5 untuk kalkulasi UI
                                $persentase = ($progressPerpus->step_sekarang / 5) * 100;
                            @endphp
                            <div class="bg-green-400 h-full border-r-2 border-black transition-all duration-500"
                                style="width: {{ $persentase > 100 ? 100 : $persentase }}%;"></div>
                        </div>
                    </div>

                    <a href="{{ route('belajar.workspace', 'perpustakaan') }}"
                        class="block w-full text-center bg-indigo-600 text-white font-black uppercase tracking-widest py-3 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-indigo-500 active:shadow-[0px_0px_0px_0px_rgba(0,0,0,1)] active:translate-y-[4px] active:translate-x-[4px] transition-all">
                        {{ $progressPerpus->status === 'belum' ? '► PRESS START' : '► CONTINUE' }}
                    </a>
                </div>

                <div class="bg-gray-200 border-4 border-gray-400 p-6 relative opacity-70 cursor-not-allowed">

                    <div
                        class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-gray-900/10 backdrop-blur-[1px]">
                        <div
                            class="bg-gray-800 text-white border-2 border-black px-6 py-3 font-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center gap-2">
                            <i class="fa-solid fa-lock text-red-400"></i> STAGE LOCKED
                        </div>
                        <span
                            class="text-xs font-bold text-gray-600 mt-3 bg-white px-2 py-1 border border-gray-400">Clear
                            Perpustakaan First</span>
                    </div>

                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-16 h-16 bg-gray-300 border-2 border-gray-400 flex items-center justify-center">
                            <i class="fa-solid fa-hospital text-3xl text-gray-500"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-2xl uppercase tracking-wider text-gray-500">Rumah Sakit</h3>
                            <p class="text-sm font-bold text-gray-400 mt-1">Level: Intermediate (4 Tables)</p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 font-medium mb-6 leading-relaxed">
                        Sistem antrian dan rekam medis pasien. Membutuhkan pemahaman Relasi Database (Join) yang lebih
                        kompleks.
                    </p>

                    <div
                        class="w-full text-center bg-gray-400 text-gray-600 font-black uppercase tracking-widest py-3 border-2 border-gray-500">
                        ???
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
