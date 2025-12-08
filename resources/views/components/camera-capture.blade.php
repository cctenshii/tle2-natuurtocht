<div x-data="camera()" class="relative">
    <h2 class="text-2xl font-bold mb-4">Maak een foto</h2>

    <!-- Camera View -->
    <div x-show="!photoPreview" class="relative">
        <video x-ref="video" width="640" height="480" autoplay playsinline class="rounded-md shadow-md"></video>
        <button @click="capturePhoto()"
                class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-full focus:outline-none focus:shadow-outline"
                style="width: 60px; height: 60px;"></button>
    </div>

    <!-- Preview View -->
    <div x-show="photoPreview" class="relative">
        <canvas x-ref="canvas" width="640" height="480" class="rounded-md shadow-md"></canvas>
        <div class="absolute bottom-4 w-full flex justify-center space-x-4">
            <button @click="retakePhoto()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Opnieuw
            </button>
            <button @click="usePhoto()" :disabled="loading"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                <span x-show="!loading">Gebruik deze foto</span>
                <span x-show="loading">Foto uploaden...</span>
            </button>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div x-show="loading"
         class="absolute inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center rounded-md">
        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-white"></div>
    </div>

    <!-- Error Message -->
    <div x-show="error" class="mt-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded" x-text="error"></div>
</div>

<script>
    function camera() {
        return {
            stream: null,
            photoPreview: false,
            loading: false,
            error: '',

            init() {
                this.startCamera();
            },

            startCamera() {
                navigator.mediaDevices.getUserMedia({video: {facingMode: 'environment'}})
                    .then(stream => {
                        this.stream = stream;
                        this.$refs.video.srcObject = stream;
                    })
                    .catch(err => {
                        console.error("Camera error:", err);
                        this.error = 'Kon geen toegang krijgen tot de camera. Geef toestemming in je browser.';
                    });
            },

            capturePhoto() {
                this.photoPreview = true;
                const context = this.$refs.canvas.getContext('2d');
                context.drawImage(this.$refs.video, 0, 0, 640, 480);
                this.stream.getTracks().forEach(track => track.stop());
            },

            retakePhoto() {
                this.photoPreview = false;
                this.error = '';
                this.startCamera();
            },

            usePhoto() {
                this.loading = true;
                this.error = '';
                this.$refs.canvas.toBlob(blob => {
                    const formData = new FormData();
                    formData.append('image', blob, 'capture.jpg');

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch('{{ route('cards.upload') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                        .then(response => response.json().then(data => ({status: response.status, body: data})))
                        .then(res => {
                            if (res.status >= 400) {
                                throw new Error(res.body.message || 'Er is een onbekende fout opgetreden.');
                            }
                            // Success! Redirect to the URL provided by the backend.
                            window.location.href = res.body.redirect_url;
                        })
                        .catch(error => {
                            console.error('Upload error:', error);
                            this.error = `Upload mislukt: ${error.message}`;
                            this.loading = false;
                        });
                }, 'image/jpeg', 0.8); // Comprimeer naar 80% kwaliteit
            },
        }
    }
</script>
