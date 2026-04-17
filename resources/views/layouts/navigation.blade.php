<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-gray-900/80 md:hidden" @click="sidebarOpen = false"
    aria-hidden="true" style="display: none;"></div>

<nav :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="absolute z-50 flex flex-col w-64 h-screen px-4 py-8 overflow-y-auto bg-gray-900 border-r transition-transform duration-300 ease-in-out md:relative md:translate-x-0">

    <div class="flex items-center justify-between mb-6 md:justify-center">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-white">
            <i class="fa-solid fa-code text-indigo-500 text-3xl"></i>
            <span class="text-2xl font-bold">Syntax<span class="text-indigo-400">Proj</span></span>
        </a>
        <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-white">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </button>
    </div>

    <div class="flex items-center gap-3 px-4 py-3 mb-6 bg-gray-800 rounded-lg">
        <div class="flex items-center justify-center w-10 h-10 text-white bg-indigo-600 rounded-full">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div class="overflow-hidden">
            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-400 capitalize">{{ Auth::user()->role }}</p>
        </div>
    </div>

    <div class="flex flex-col justify-between flex-1 mt-2">
        <nav class="space-y-2">

            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-5"></i>
                Dashboard
            </a>

            @if (Auth::user()->role === 'admin')
                <div
                    class="pt-4 mt-4 text-xs font-semibold tracking-wider text-gray-500 uppercase border-t border-gray-700">
                    Master Data</div>

                <a href="{{ route('buku.index') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('buku.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-book w-5"></i>
                    Kelola Buku
                </a>

                <a href="{{ route('kategori.index') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('kategori.*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa-solid fa-tags w-5"></i>
                    Kelola Kategori
                </a>
            @endif

            @if (in_array(Auth::user()->role, ['student', 'admin']))
                <div
                    class="pt-4 mt-4 text-xs font-semibold tracking-wider text-gray-500 uppercase border-t border-gray-700">
                    Modul Belajar</div>

                <a href="{{ route('belajar.index') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium transition-colors rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white">
                    <i class="fa-solid fa-laptop-code w-5"></i>
                    Mulai Simulasi
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium transition-colors rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white">
                    <i class="fa-solid fa-trophy w-5"></i>
                    Progress Saya
                </a>
            @endif

        </nav>

        <div class="pt-4 mt-8 space-y-2 border-t border-gray-700">
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 transition-colors rounded-lg hover:bg-gray-800 hover:text-white">
                <i class="fa-solid fa-user-gear w-5"></i>
                Profile Setting
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center w-full gap-3 px-4 py-3 text-sm font-medium text-red-400 transition-colors rounded-lg hover:bg-red-500 hover:text-white">
                    <i class="fa-solid fa-right-from-bracket w-5"></i>
                    Log Out
                </button>
            </form>
        </div>
    </div>
</nav>
