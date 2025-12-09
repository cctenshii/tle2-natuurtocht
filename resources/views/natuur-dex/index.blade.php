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
            }
        </style>
    @endpush

    <!-- Mobiele Container -->
    <div class="h-screen bg-white shadow-2xl relative overflow-hidden flex flex-col"
         x-data="{openAccordion: {{ $categories->first()->id ?? 'null' }}}">
       <x-slot:header>
           <div
               class="text-center text-[25px] p-6 font-extrabold text-blue-700 bg-gray-50 absolute top-0 left-0 right-0 z-10 shadow-md">
               <div class="p-2 flex justify-around gap-10 items-center bg-gray-50">
                   <h1 class="font-bold">{{ $location }} - {{ $percentage }}%</h1>
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
           </div>
       </x-slot:header>

        <!-- 2. Scrollbare Content Sectie -->
        <main class="flex-1 overflow-y-auto safe-area-padding">
            <div class="p-4 my-10 space-y-2">
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
                                    {{--                                    the cards yuhh vv--}}
                                    @foreach($items as $item)
                                        <a href="{{ route('cards.show', $item->id) }}">
                                            <div @class([
            'bg-yellow-100 border border-yellow-200 rounded-lg p-2 text-center shadow',
            'shiny' => optional($item->pivot)->is_shiny,
        ])>
                                                <img src="{{ $item->image_url }}" alt="{{ $item->title }}"
                                                     class="mx-auto mb-2 rounded">
                                                <span
                                                    class="block text-xs font-bold text-gray-500">{{ $item->number }}</span>
                                                <span
                                                    class="block text-sm font-semibold text-gray-800">{{ $item->title }}</span>
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
    </div>
</x-app-layout>
