<x-app-layout>
    {{--
    We voegen een custom <style> blok toe specifiek voor deze pagina.
    Dit is een schone manier om pagina-specifieke CSS toe te voegen
    zonder de hoofdlayout te vervuilen.
    --}}
    @push('styles')
        <style>
            .safe-area-padding {
                padding-top: 100px; /* Hoogte van de header */
                padding-bottom: 80px; /* Hoogte van de footer */
            }
        </style>
    @endpush

    <!-- Mobiele Container -->
    <div class="w-full max-w-sm mx-auto h-screen bg-white shadow-2xl relative overflow-hidden flex flex-col"
         x-data="{openAccordion: {{ $categories->first()->id ?? 'null' }}}">
       <x-slot:header>
           <header class="absolute top-0 left-0 right-0 bg-white z-10 shadow-md">
               <div class="p-4 border-b border-gray-200">
                   <h1 class="text-2xl font-extrabold text-blue-600 text-center">Natuur kaart dex</h1>
               </div>
               <div class="p-2 flex justify-between items-center bg-gray-50">
                   <span class="font-bold text-gray-700">{{ $location }}</span>
                   <div x-show="openAccordion === {{ $categories->first()->id ?? 'null' }}"
                        x-transition
                        class="flex items-center gap-1 font-semibold {{ $seasonStyles['color'] }}">

                       <form method="GET" id="seasonForm" class="mb-4">
                           <select name="season" id="seasonSelect"
                                   onchange="document.getElementById('seasonForm').submit();">
                               @foreach (['Lente', 'Zomer', 'Herfst', 'Winter'] as $s)
                                   <option value="{{ $s }}" {{ $s === $season ? 'selected' : '' }}>
                                       {{ $s }}
                                   </option>
                               @endforeach
                           </select>
                       </form>

                       @include($seasonStyles['icon'])
                   </div>
               </div>
           </header>
       </x-slot:header>

        <!-- 2. Scrollbare Content Sectie -->
        <main class="flex-1 overflow-y-auto safe-area-padding">
            <div class="p-4 space-y-2">
                @foreach($categories as $category)
                    <div>
                        <button
                            @click="openAccordion = (openAccordion === {{ $category->id }} ? null : {{ $category->id }})"
                            :class="openAccordion === {{ $category->id }} ? 'bg-blue-100 text-blue-800' : 'bg-cyan-700 text-white'"
                            class="w-full flex justify-between items-center p-3 rounded-lg shadow transition-all duration-300">
                            <span class="text-lg font-bold">{{ $category->name }}</span>

                            {{-- Pijl iconen --}}
                            <svg x-show="openAccordion !== {{ $category->id }}" xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                 class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                            </svg>
                            <svg x-show="openAccordion === {{ $category->id }}" xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                 class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"/>
                            </svg>
                        </button>

                        <div x-show="openAccordion === {{ $category->id }}" x-collapse class="pt-4">
                            @forelse($category->grouped_items as $subGroup => $items)
                                <h3 class="text-lg font-bold text-gray-800 mb-2 mt-4">{{ $subGroup }}</h3>
                                <div class="grid grid-cols-3 gap-4">

                                    @foreach($items as $item)
                                        @php
                                            // ✅ pivot komt nu via items.users (gefilterd op ingelogde user)
                                            $ownership = $item->users->first()?->pivot;
                                            $isShiny = (bool) optional($ownership)->is_shiny;
                                        @endphp

                                        <a href="{{ route('cards.show', $item->id) }}">
                                            <div @class([
                                        'bg-yellow-100 border border-yellow-200 rounded-lg p-2 text-center shadow',
                                        'shiny' => $isShiny,
                                    ])>
                                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                                     class="mx-auto mb-2 rounded">
                                                <span class="block text-xs font-bold text-gray-500">{{ $item->number }}</span>
                                                <span class="block text-sm font-semibold text-gray-800">{{ $item->name }}</span>
                                            </div>
                                        </a>
                                    @endforeach

                                </div>
                            @empty
                                <p class="text-center text-gray-500 p-4">Geen items gevonden in deze categorie.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach

            </div>
        </main>


        <!-- 3. Vaste Footer -->
        <footer class="absolute bottom-0 left-0 right-0 bg-cyan-800 text-white z-10">
            <div class="flex justify-between items-center p-4">
                <a href="#" class="flex flex-col items-center gap-1 text-sm font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd"
                              d="M10.22 1.22a.75.75 0 00-1.44 0l-6.5 12.5c-.11.21-.023.48.184.59l6.5 3.5c.21.11.48.023.59-.184l6.5-12.5c.11-.21.023-.48-.184-.59l-6.5-3.5zM9.25 4.5a.75.75 0 01.75.75v5a.75.75 0 01-1.5 0v-5a.75.75 0 01.75-.75z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span>Profiel</span>
                </a>
                <a href="#" class="flex flex-col items-center gap-1 text-sm font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6">
                        <path
                            d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
                        <path fill-rule="evenodd"
                              d="M8.25 2.25a.75.75 0 01.75.75v.5a.75.75 0 01-1.5 0v-.5a.75.75 0 01.75-.75zM10.75 2.25a.75.75 0 01.75.75v.5a.75.75 0 01-1.5 0v-.5a.75.75 0 01.75-.75zM10 5.25a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5z"
                              clip-rule="evenodd"/>
                        <path fill-rule="evenodd"
                              d="M3.93 3.93a.75.75 0 011.06 0l1.192 1.192a.75.75 0 01-1.06 1.06L3.93 5.05a.75.75 0 010-1.06zm11.14 0a.75.75 0 010 1.06l-1.192 1.192a.75.75 0 01-1.06-1.06l1.192-1.192a.75.75 0 011.06 0zM3.5 10a.75.75 0 01.75-.75h.5a.75.75 0 010 1.5h-.5a.75.75 0 01-.75-.75zm12.5 0a.75.75 0 01-.75.75h-.5a.75.75 0 010-1.5h.5a.75.75 0 01.75.75zM8.25 15.25a.75.75 0 01.75.75v.5a.75.75 0 01-1.5 0v-.5a.75.75 0 01.75-.75zm2.5.75a.75.75 0 00-1.5 0v.5a.75.75 0 001.5 0v-.5z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span>Collectie</span>
                </a>
            </div>
        </footer>
    </div>
</x-app-layout>
