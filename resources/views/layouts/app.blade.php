<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Syntax Project') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-100">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        @auth
            @include('layouts.navigation')
        @endauth

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

            @auth
                <header
                    class="sticky top-0 z-30 flex items-center justify-between px-4 py-4 bg-white shadow-sm sm:px-6 md:hidden">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none focus:text-gray-700">
                            <i class="fa-solid fa-bars text-2xl"></i>
                        </button>
                    </div>
                    <div class="font-semibold text-gray-800">Syntax Project</div>
                </header>
            @else
                <header
                    class="sticky top-0 z-30 flex items-center justify-between px-4 py-4 bg-white border-b-4 border-black sm:px-6">
                    <a href="/" class="flex items-center gap-2">
                        <i class="fa-solid fa-code text-indigo-600 text-xl"></i>
                        <span class="font-black text-lg uppercase tracking-tighter hidden sm:inline">Syntax<span
                                class="text-indigo-600">Proj</span></span>
                    </a>
                    <div class="flex items-center gap-3 font-mono">
                        <span
                            class="bg-red-500 text-white text-xs font-black px-2 py-1 border-2 border-black animate-pulse">DEMO
                            MODE</span>
                        <a href="{{ route('register') }}"
                            class="bg-yellow-400 text-black text-xs font-black uppercase px-3 py-1 border-2 border-black hover:bg-yellow-300 transition">Register
                            / Login</a>
                    </div>
                </header>
            @endauth

            @isset($header)
                <div class="bg-white shadow z-10 relative">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <main class="w-full grow">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
