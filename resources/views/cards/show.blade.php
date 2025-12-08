<x-app-layout>

    @push('styles')
        <style>
            .safe-area-padding {
                padding-top: 100px; /* Hoogte van de header */
                padding-bottom: 80px; /* Hoogte van de footer */
            }
        </style>
    @endpush

    <div class="w-full max-w-sm mx-auto h-screen relative overflow-hidden flex flex-col">
        <x-slot:header>
            <header class="absolute top-0 left-0 right-0 bg-white z-10 shadow-md">
                <div class="p-4 border-b border-gray-200">
                    <h1 class="text-2xl font-extrabold text-blue-600 text-center">Natuur kaart dex</h1>
                </div>
                <div class="p-2 flex justify-between items-center bg-gray-50">
                    <span class="font-bold text-gray-700">{{ $location }}</span>
                    <div x-show="openAccordion" x-transition
                         class="flex items-center gap-1 text-orange-600 font-semibold">
                        <span>{{ $season }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd"
                                  d="M15.312 11.424a5.5 5.5 0 01-9.201-4.458 5.5 5.5 0 018.904-4.923.75.75 0 01.393 1.285l-3.203 3.203a.75.75 0 001.06 1.06l3.204-3.203a.75.75 0 011.286.393 5.5 5.5 0 01-2.443 6.643z"
                                  clip-rule="evenodd"/>
                            <path fill-rule="evenodd"
                                  d="M11.424 15.312a5.5 5.5 0 01-4.458-9.201 5.5 5.5 0 01-4.923 8.904.75.75 0 011.285-.393l3.203-3.203a.75.75 0 001.06 1.06l-3.203 3.204a.75.75 0 01.393 1.286 5.5 5.5 0 016.643-2.443z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </header>
        </x-slot:header>


        <main class="flex-1 overflow-y-auto safe-area-padding">
            <div class="p-4 space-y-2">

                <a href="{{ route('natuur-dex.index') }}" class="text-blue-600 underline text-sm">&larr; Terug</a>

                <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                    <img src="{{ $card->image_url }}" class="rounded mb-4" alt="{{ $card->name }}">

                    <h1 class="text-2xl font-bold">{{ $card->name }}</h1>
                    <p class="text-gray-500 text-sm">Kaartnummer: {{ $card->id }}</p>

                    <ul class="list-disc pl-5 mt-4 text-gray-700">
                        <li class="font-bold">Seizoen: {{ $card->season->name ?? 'Onbekend' }}</li>
                        <li>{{ $card->description ?? 'Geen beschrijving beschikbaar.' }}</li>
                    </ul>

                    @if($owned)
                        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded">
                            <h2 class="font-semibold text-green-800">Extra informatie</h2>
                            <p class="text-gray-700 mt-2">{{ $card->locatie_text ?? 'Geen extra informatie beschikbaar.' }}</p>

                            <h3 class="font-semibold text-green-800 mt-4">Leuk weetje</h3>
                            <p class="text-gray-700 mt-2">{{ $card->feitje ?? 'Geen weetje beschikbaar.' }}</p>
                        </div>
                    @else
                        <button class="mt-6 w-full bg-cyan-800 hover:bg-cyan-900 text-white font-semibold py-2 px-4 rounded-lg shadow">
                            Maak foto
                        </button>
                    @endif

                </div>


            </div>
        </main>

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
