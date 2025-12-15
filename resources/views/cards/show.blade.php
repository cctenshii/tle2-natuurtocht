<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-center p-4">
            {{ $card->title }}
        </h2>
    </x-slot>
    <div
        class="w-full max-w-sm mx-auto h-screen relative overflow-hidden flex flex-col"
        x-data="camera('{{ csrf_token() }}', '{{ $card->id }}')"
        x-init="init()"
    >
        <x-slot:header>
            <h1 class="text-center text-3xl font-bold p-6">0{{ $card->id }} - {{ $card->title }}</h1>
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
                            <h2 class="font-semibold text-green-800">Extra Informatie</h2>
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

                    <button x-show="photoPreview && !loading"
                            @click="wizardCorrect = false; uploadPhoto()"
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
                wizardCorrect: false, // Standaard false (dus fout)

                startCamera() {
                    this.cameraOpen = true;
                    this.error = '';
                    this.photoPreview = false;
                    this.wizardCorrect = false; // Resetten bij openen

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

                    // Check of video klaar is
                    if (video.readyState < 2) {
                        return;
                    }

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
                    this.wizardCorrect = false;
                },

                uploadPhoto() {
                    this.loading = true;
                    this.error = '';

                    this.$refs.canvas.toBlob(blob => {
                        const formData = new FormData();
                        formData.append('photo', blob, 'card_photo.jpg');

                        // Stuur de wizard status mee (1 = goed, 0 = fout)
                        // Let op: naam is nu 'wizard_correct' om te matchen met PhotoController
                        formData.append('wizard_correct', this.wizardCorrect ? '1' : '0');

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
                                    window.location.reload();
                                }
                            })
                            .catch(error => {
                                console.error('Upload Error:', error);
                                this.error = error.message;
                            })
                            .finally(() => {
                                this.loading = false;
                                // Reset wizard status na poging
                                this.wizardCorrect = false;
                            });
                    }, 'image/jpeg', 0.8);
                },

                init() {
                    this.$watch('cameraOpen', open => {
                        if (!open) this.stopCamera();
                    });

                    // Luister naar de ENTER toets
                    window.addEventListener('keydown', (e) => {
                        // Alleen actie ondernemen als:
                        // Camera open is
                        // && Er een foto gemaakt is (preview zichtbaar)
                        // && We niet al aan het laden zijn
                        // && De toets Enter is
                        if (this.cameraOpen && this.photoPreview && !this.loading && e.key === 'Enter') {
                            e.preventDefault(); // Voorkom dat enter andere dingen doet
                            this.wizardCorrect = true; // Zet op 'correct'
                            this.uploadPhoto(); // Start upload
                        }
                    });
                }
            }
        }
    </script>

</x-app-layout>
