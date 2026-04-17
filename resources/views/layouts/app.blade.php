<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Syntax Project') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-100">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        @include('layouts.navigation')

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

            <header
                class="sticky top-0 z-30 flex items-center justify-between px-4 py-4 bg-white shadow-sm sm:px-6 md:hidden">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none focus:text-gray-700">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
                <div class="font-semibold text-gray-800">Syntax Project</div>
            </header>

            @isset($header)
                <div class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <main class="w-full grow p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
