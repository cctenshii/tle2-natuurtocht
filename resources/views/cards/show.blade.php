<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $card->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-lg mb-2">Card Details</h3>
                            <p><strong>Description:</strong> {{ $card->description }}</p>
                            <p><strong>Category:</strong> {{ $card->category->name }}</p>
                            <p><strong>Rarity:</strong> {{ $card->rarity }}</p>
                            <p><strong>Created
                                    At:</strong> {{ $card->created_at?->format('d-m-Y H:i') ?? 'Not available' }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2">Card Image</h3>
                            @if ($card->image_url)
                                <img
                                    src="{{ str_starts_with($card->image_url, 'http') ? $card->image_url : asset('storage/' . $card->image_url) }}"
                                    alt="{{ $card->title }}" class="w-full h-auto rounded-lg">
                            @else
                                <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg">
                                    <p class="text-gray-500">No image available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Alpine.js Camera Component -->
                    <div x-data="camera('{{ csrf_token() }}', '{{ $card->id }}')" x-init="init()" class="mt-6">
                        <!-- Camera UI will be shown here -->
                        <div x-show="cameraOpen"
                             class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
                            <div class="bg-white p-4 rounded-lg shadow-xl w-full max-w-2xl" @click.away="stopCamera()">
                                <h3 class="text-xl font-semibold mb-4">Take a Photo</h3>
                                <div class="relative">
                                    <!-- Video Stream -->
                                    <video x-show="!photoPreview" x-ref="video" autoplay playsinline
                                           class="w-full h-auto rounded"></video>
                                    <!-- Photo Preview -->
                                    <canvas x-show="photoPreview" x-ref="canvas" class="w-full h-auto rounded"></canvas>
                                    <!-- Loading Indicator -->
                                    <div x-show="loading"
                                         class="absolute inset-0 bg-white bg-opacity-80 flex flex-col items-center justify-center">
                                        <p class="text-lg font-semibold">Uploading photo...</p>
                                    </div>
                                    <!-- Error Message -->
                                    <div x-show="error" x-cloak
                                         class="absolute bottom-4 left-4 right-4 bg-red-500 text-white p-3 rounded-lg text-center">
                                        <p x-text="error"></p>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-center space-x-4">
                                    <button x-show="!photoPreview && !loading" @click="takePhoto()"
                                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Take
                                        Photo
                                    </button>
                                    <button x-show="photoPreview && !loading" @click="retakePhoto()"
                                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Retake
                                    </button>
                                    <button x-show="photoPreview && !loading" @click="uploadPhoto()"
                                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Use
                                        This Photo
                                    </button>
                                </div>
                                <button @click="stopCamera()"
                                        class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-2xl">
                                    &times;
                                </button>
                            </div>
                        </div>

                        <!-- Trigger Button -->
                        <div class="mt-6 text-center">
                            <button @click="startCamera()"
                                    class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75">
                                Maak foto
                            </button>
                        </div>
                    </div>

                </div>
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
                    navigator.mediaDevices.getUserMedia({video: {facingMode: 'environment'}})
                        .then(stream => {
                            this.stream = stream;
                            this.$refs.video.srcObject = stream;
                        })
                        .catch(err => {
                            console.error("Camera Error: ", err);
                            this.error = 'Could not access the camera. Please check permissions.';
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
                                    // Handle validation errors
                                    return response.json().then(err => {
                                        // Use the user-friendly message from the controller
                                        throw new Error(err.message || 'Validation failed.');
                                    });
                                }
                                if (!response.ok) {
                                    // Handle other server errors (e.g., 500)
                                    throw new Error('A server error occurred. Please try again later.');
                                }
                                return response.json();
                            })
                            .then(data => {
                                // On success, the controller provides a redirect URL
                                if (data.redirect_url) {
                                    window.location.href = data.redirect_url;
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
                        if (!open) {
                            this.stopCamera();
                        }
                    });
                }
            }
        }
    </script>
</x-app-layout>
