<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center font-mono">
            <h2 class="font-black text-2xl text-gray-900 uppercase tracking-widest">
                <i class="fa-solid fa-trophy mr-2 text-yellow-500"></i> Player Stats
            </h2>
            <div
                class="bg-black text-green-400 border-4 border-gray-600 px-4 py-2 font-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-sm uppercase">
                TOTAL SCORE: <span class="text-yellow-400 text-lg ml-2">{{ number_format($user->points) }} PTS</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 min-h-[calc(100vh-140px)] bg-indigo-50 font-mono"
        style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px;">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex flex-col">
                    <div class="bg-indigo-600 text-white p-4 border-b-4 border-black">
                        <h3 class="font-black text-xl uppercase tracking-widest"><i class="fa-solid fa-medal mr-2"></i>
                            Achievements</h3>
                    </div>

                    <div class="p-6 flex flex-col gap-4 bg-gray-50 flex-1">
                        @foreach ($badges as $key => $badge)
                            @php
                                $isUnlocked = in_array($key, $userAchievements);
                            @endphp

                            <div
                                class="border-4 flex items-center p-4 transition-all {{ $isUnlocked ? $badge['bg'] . ' opacity-100 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]' : 'border-gray-300 bg-gray-200 opacity-50 grayscale' }}">
                                <div
                                    class="w-16 h-16 flex items-center justify-center bg-white border-4 border-black mr-4 shadow-inner">
                                    <i
                                        class="fa-solid {{ $badge['icon'] }} text-3xl {{ $isUnlocked ? $badge['warna'] : 'text-gray-400' }}"></i>
                                </div>
                                <div class="flex-1">
                                    <h4
                                        class="font-black uppercase text-lg {{ $isUnlocked ? 'text-black' : 'text-gray-500' }}">
                                        {{ $badge['nama'] }}</h4>
                                    <p
                                        class="text-xs font-bold mt-1 {{ $isUnlocked ? 'text-gray-700' : 'text-gray-500' }}">
                                        {{ $badge['deskripsi'] }}</p>
                                </div>
                                @if ($isUnlocked)
                                    <div class="text-green-600 font-black text-2xl rotate-12 ml-2">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                @else
                                    <div class="text-gray-400 font-black text-xl ml-2">
                                        <i class="fa-solid fa-lock"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div
                    class="bg-black border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex flex-col relative overflow-hidden">
                    <div class="absolute inset-0 pointer-events-none opacity-10"
                        style="background: repeating-linear-gradient(0deg, transparent, transparent 2px, #fff 2px, #fff 4px);">
                    </div>

                    <div class="bg-gray-800 text-yellow-400 p-4 border-b-4 border-gray-600 text-center relative z-10">
                        <h3 class="font-black text-2xl uppercase tracking-widest animate-pulse"><i
                                class="fa-solid fa-ranking-star mr-2"></i> HALL OF FAME</h3>
                    </div>

                    <div class="p-6 flex flex-col gap-2 relative z-10">
                        <div
                            class="mb-4 bg-indigo-900 border-2 border-indigo-400 p-3 flex justify-between items-center text-white">
                            <span class="font-bold text-sm text-indigo-300">YOUR RANK:</span>
                            <span class="font-black text-xl">#{{ $myRank }}</span>
                        </div>

                        <div
                            class="flex justify-between text-green-500 font-black text-xs uppercase tracking-widest border-b-2 border-green-900 pb-2 mb-2">
                            <span class="w-12 text-center">RANK</span>
                            <span class="flex-1">PLAYER</span>
                            <span class="w-24 text-right">SCORE</span>
                        </div>

                        @foreach ($leaderboard as $index => $board)
                            <div
                                class="flex justify-between items-center font-bold text-sm py-2 border-b border-gray-800 {{ $board->id === $user->id ? 'text-yellow-300 bg-gray-800 px-2' : 'text-gray-300' }}">
                                <span
                                    class="w-12 text-center font-black {{ $index == 0 ? 'text-red-500 text-lg' : ($index == 1 ? 'text-blue-400 text-lg' : ($index == 2 ? 'text-green-400 text-lg' : '')) }}">
                                    @if ($index == 0)
                                        1ST
                                    @elseif($index == 1)
                                        2ND
                                    @elseif($index == 2)
                                        3RD
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </span>
                                <span class="flex-1 uppercase truncate pr-4">
                                    {{ $board->name }}
                                    @if ($board->id === $user->id)
                                        <span class="text-[10px] ml-2 text-indigo-400">(YOU)</span>
                                    @endif
                                </span>
                                <span
                                    class="w-24 text-right font-mono text-green-400">{{ number_format($board->points) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
