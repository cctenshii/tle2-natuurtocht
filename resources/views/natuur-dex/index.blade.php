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

                       <span>{{ $season }}</span>

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
                                        <a href="{{ route('cards.show', $item->id) }}">
                                            <div @class([
            'bg-yellow-100 border border-yellow-200 rounded-lg p-2 text-center shadow',
            'shiny' => optional($item->pivot)->is_shiny,
        ])>
                                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                                     class="mx-auto mb-2 rounded">
                                                <span
                                                    class="block text-xs font-bold text-gray-500">{{ $item->number }}</span>
                                                <span
                                                    class="block text-sm font-semibold text-gray-800">{{ $item->name }}</span>
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

                <!-- Profiel -->
                <a href="#" class="flex flex-col items-center gap-1 text-sm font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd"
                              d="M10.22 1.22a.75.75 0 00-1.44 0l-6.5 12.5c-.11.21-.023.48.184.59l6.5 3.5c.21.11.48.023.59-.184l6.5-12.5c.11-.21.023-.48-.184-.59l-6.5-3.5zM9.25 4.5a.75.75 0 01.75.75v5a.75.75 0 01-1.5 0v-5a.75.75 0 01.75-.75z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span>Profiel</span>
                </a>

                <!-- Invite (kopieer link) -->
                <button
                    type="button"
                    id="inviteBtn"
                    class="flex flex-col items-center gap-1 text-sm font-semibold focus:outline-none"
                >
                    <!-- users/invite icoon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7">
                        <path d="M16 11c1.66 0 2.99-1.57 2.99-3.5S17.66 4 16 4s-3 1.57-3 3.5S14.34 11 16 11Zm-8 0c1.66 0 2.99-1.57 2.99-3.5S9.66 4 8 4 5 5.57 5 7.5 6.34 11 8 11Zm0 2c-2.33 0-7 1.17-7 3.5V20h14v-3.5C15 14.17 10.33 13 8 13Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V20h7v-3.5c0-2.33-4.67-3.5-7-3.5Z"/>
                    </svg>
                    <span>Invite</span>
                </button>

                <!-- Collectie -->
                <a href="#" class="flex flex-col items-center gap-1 text-sm font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
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

            <!-- kleine toast -->
            <div id="inviteToast" class="hidden absolute bottom-16 left-1/2 -translate-x-1/2 bg-black/70 text-white text-xs px-3 py-2 rounded">
                Link gekopieerd!
            </div>

            <script>
                (function () {
                    const btn = document.getElementById('inviteBtn');
                    const toast = document.getElementById('inviteToast');

                    // Zet hier jouw App Store / Play Store link
                    const INVITE_LINK = "betalen kankerboef!!"; // <-- vervang

                    btn?.addEventListener('click', async () => {
                        try {
                            await navigator.clipboard.writeText(INVITE_LINK);
                        } catch (e) {
                            // fallback voor oudere browsers
                            const tmp = document.createElement('input');
                            tmp.value = INVITE_LINK;
                            document.body.appendChild(tmp);
                            tmp.select();
                            document.execCommand('copy');
                            document.body.removeChild(tmp);
                        }

                        toast.classList.remove('hidden');
                        setTimeout(() => toast.classList.add('hidden'), 1400);
                    });
                })();
            </script>
        </footer>

    </div>
</x-app-layout>
