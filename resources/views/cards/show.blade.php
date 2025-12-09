<x-app-layout>

    @push('styles')
        <style>
            .safe-area-padding {
                padding-top: 100px; /* Hoogte van de header */
                padding-bottom: 80px; /* Hoogte van de footer */
            }
        </style>
    @endpush

    <div
        class="w-full max-w-sm mx-auto h-screen relative overflow-hidden flex flex-col"
        x-data="camera('{{ csrf_token() }}', '{{ $card->id }}')"
        x-init="init()"
    >
        <x-slot:header>
            <header class="absolute top-0 left-0 right-0 bg-white z-10 shadow-md">
                <div class="p-4 border-b border-gray-200">
                    <h1 class="text-2xl font-extrabold text-blue-600 text-center">Natuur kaart dex</h1>
                </div>
                <div class="p-2 flex justify-between items-center bg-gray-50">
                    <span class="font-bold text-gray-700">{{ $location }}</span>
                    <div class="flex items-center gap-1 text-orange-600 font-semibold">
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

                @if (session('success'))
                    <div class="bg-green-500 text-white p-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('natuur-dex.index') }}" class="text-blue-600 underline text-sm">&larr; Terug</a>

                @php
                    $props = $card->properties ?? [];
                    $locatieText = $props['locatie_text'] ?? null;
                    $feitje = $props['feitje'] ?? null;
                @endphp

                <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                    <img
                        src="{{ $owned && optional($ownedCard->pivot)->image_url ? asset($ownedCard->pivot->image_url) : $card->image_url }}"
                        class="rounded mb-4"
                        alt="{{ $card->title }}"
                    >

                    <h1 class="text-2xl font-bold">{{ $card->title }}</h1>
                    <p class="text-gray-500 text-sm">Kaartnummer: {{ $card->id }}</p>

                    <ul class="list-disc pl-5 mt-4 text-gray-700">
                        <li class="font-bold">
                            Seizoen: {{ $card->seasons->pluck('name')->join(', ') ?: 'Onbekend' }}
                        </li>
                        <li>{{ $card->description ?? 'Geen beschrijving beschikbaar.' }}</li>
                    </ul>

                    @if($owned)
                        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded">
                            <h2 class="font-semibold text-green-800">Extra informatie</h2>
                            <p class="text-gray-700 mt-2">{{ $locatieText ?? 'Geen extra informatie beschikbaar.' }}</p>

                            <h3 class="font-semibold text-green-800 mt-4">Leuk weetje</h3>
                            <p class="text-gray-700 mt-2">{{ $feitje ?? 'Geen weetje beschikbaar.' }}</p>
                        </div>
                    @else
                        <button
                            @click="startCamera()"
                            class="mt-6 w-full bg-cyan-800 hover:bg-cyan-900 text-white font-semibold py-2 px-4 rounded-lg shadow"
                        >
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
        </footer>

        <!-- Camera Modal (alleen frontend; backend route mag later gefixt worden) -->
        <div
            x-show="cameraOpen"
            x-transition
            class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4"
            style="display:none;"
        >
            <div class="bg-white p-4 rounded-lg shadow-xl w-full max-w-2xl relative" @click.away="stopCamera()">
                <h3 class="text-xl font-semibold mb-4">Maak een foto</h3>

                <div class="relative">
                    <video x-show="!photoPreview" x-ref="video" autoplay playsinline class="w-full h-auto rounded"></video>
                    <canvas x-show="photoPreview" x-ref="canvas" class="w-full h-auto rounded"></canvas>

                    <div x-show="loading" class="absolute inset-0 bg-white bg-opacity-80 flex flex-col items-center justify-center">
                        <p class="text-lg font-semibold">Uploaden...</p>
                    </div>

                    <div x-show="error" x-cloak class="absolute bottom-4 left-4 right-4 bg-red-500 text-white p-3 rounded-lg text-center">
                        <p x-text="error"></p>
                    </div>
                </div>

                <div class="mt-4 flex justify-center space-x-4">
                    <button x-show="!photoPreview && !loading" @click="takePhoto()"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Foto maken
                    </button>

                    <button x-show="photoPreview && !loading" @click="retakePhoto()"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Opnieuw
                    </button>

                    <button x-show="photoPreview && !loading" @click="uploadPhoto()"
                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Gebruik foto
                    </button>
                </div>

                <button @click="stopCamera()" class="absolute top-2 right-3 text-gray-600 hover:text-gray-900 text-2xl">
                    &times;
                </button>
            </div>
        </div>

    </div>

    <script>
        function camera(csrfToken, cardId) {
            return {
                cameraOpen: false,
                photoPreview: false,
                loading: false,
                error: '',
                stream: null,
                csrfToken: csrfToken,
                cardId: cardId,

                startCamera() {
                    this.cameraOpen = true;
                    this.error = '';
                    this.photoPreview = false;

                    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                        .then(stream => {
                            this.stream = stream;
                            this.$refs.video.srcObject = stream;
                        })
                        .catch(err => {
                            console.error("Camera Error: ", err);
                            this.error = 'Kon camera niet openen. Check permissies.';
                            this.cameraOpen = false;
                        });
                },

                stopCamera() {
                    if (this.stream) {
                        this.stream.getTracks().forEach(track => track.stop());
                    }
                    this.cameraOpen = false;
                },

                takePhoto() {
                    const video = this.$refs.video;
                    const canvas = this.$refs.canvas;

                    const maxWidth = 800;
                    const scale = maxWidth / video.videoWidth;

                    canvas.width = maxWidth;
                    canvas.height = video.videoHeight * scale;

                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                    this.photoPreview = true;
                },

                retakePhoto() {
                    this.photoPreview = false;
                    this.error = '';
                },

                uploadPhoto() {
                    this.loading = true;
                    this.error = '';

                    this.$refs.canvas.toBlob(blob => {
                        const formData = new FormData();
                        formData.append('photo', blob, 'card_photo.jpg');

                        fetch(`/cards/${this.cardId}/upload-photo`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                            .then(response => {
                                if (response.status === 422) {
                                    return response.json().then(err => {
                                        throw new Error(err.message || 'Validatie mislukt.');
                                    });
                                }
                                if (!response.ok) {
                                    throw new Error('Serverfout. Probeer later opnieuw.');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.redirect_url) {
                                    window.location.href = data.redirect_url;
                                } else {
                                    // fallback: refresh
                                    window.location.reload();
                                }
                            })
                            .catch(error => {
                                console.error('Upload Error:', error);
                                this.error = error.message;
                            })
                            .finally(() => {
                                this.loading = false;
                            });
                    }, 'image/jpeg', 0.9);
                },

                init() {
                    this.$watch('cameraOpen', open => {
                        if (!open) this.stopCamera();
                    });
                }
            }
        }
    </script>
</x-app-layout>
