<x-app-layout>
    <div class="max-w-md mx-auto p-6">

        <a href="{{ url()->previous() }}" class="text-blue-600 underline">&larr; Terug</a>

        <div class="mt-4 bg-white p-4 rounded-lg shadow">
            <img src="{{ $card->image_url }}" class="rounded mb-4" alt="{{ $card->name }}">

            <h1 class="text-2xl font-bold">{{ $card->name }}</h1>
            <p class="text-gray-500 text-sm">Kaartnummer: {{ $card->number }}</p>

            <p class="mt-4 text-gray-700">
                {{ $card->description ?? 'Geen beschrijving beschikbaar.' }}
            </p>
        </div>

    </div>
</x-app-layout>
