<x-layouts.app title="Buat Laporan">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-6">
            <h1 class="font-display text-2xl font-semibold text-ink">Buat Laporan Baru</h1>
            <p class="text-stone-500 text-sm mt-1">Lengkapi informasi di bawah agar laporanmu cepat ditindaklanjuti.</p>
        </div>

        <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data"
              x-data="reportForm()" @submit="onSubmit">
            @csrf

            <div class="space-y-6">

                {{-- ============ FOTO ============ --}}
                <div class="rounded-xl border border-stone-200 bg-white p-5">
                    <label class="block text-sm font-semibold text-ink mb-1">Foto Bukti</label>
                    <p class="text-xs text-stone-500 mb-4">Ambil foto langsung dari kamera atau upload dari galeri. Maksimal 5 foto, masing-masing 5MB.</p>

                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-3 mb-3">
                        <template x-for="(photo, index) in photos" :key="index">
                            <div class="relative aspect-square rounded-lg overflow-hidden bg-stone-100 group">
                                <img :src="photo.preview" class="h-full w-full object-cover">
                                <button type="button" @click="removePhoto(index)"
                                        class="absolute top-1 right-1 h-6 w-6 rounded-full bg-ink/70 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="ti ti-x text-sm"></i>
                                </button>
                            </div>
                        </template>

                        <button type="button" @click="$refs.cameraInput.click()"
                                x-show="photos.length < 5"
                                class="aspect-square rounded-lg border-2 border-dashed border-stone-300 flex flex-col items-center justify-center gap-1 text-stone-400 hover:border-clay hover:text-clay transition-colors">
                            <i class="ti ti-camera text-2xl"></i>
                            <span class="text-xs font-medium">Kamera</span>
                        </button>

                        <button type="button" @click="$refs.galleryInput.click()"
                                x-show="photos.length < 5"
                                class="aspect-square rounded-lg border-2 border-dashed border-stone-300 flex flex-col items-center justify-center gap-1 text-stone-400 hover:border-clay hover:text-clay transition-colors">
                            <i class="ti ti-photo-plus text-2xl"></i>
                            <span class="text-xs font-medium">Galeri</span>
                        </button>
                    </div>

                    {{-- Input kamera: capture="environment" buka kamera belakang langsung di HP --}}
                    <input type="file" accept="image/*" capture="environment" x-ref="cameraInput"
                           @change="addPhotos($event.target.files)" class="hidden">

                    {{-- Input galeri: bisa pilih banyak sekaligus --}}
                    <input type="file" accept="image/*" multiple x-ref="galleryInput"
                           @change="addPhotos($event.target.files)" class="hidden">

                    <p class="text-xs text-stone-400" x-show="photos.length > 0">
                        <span x-text="photos.length"></span>/5 foto dipilih
                    </p>
                </div>

                {{-- ============ KATEGORI ============ --}}
                <div class="rounded-xl border border-stone-200 bg-white p-5">
                    <label class="block text-sm font-semibold text-ink mb-3">Kategori Masalah</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach($categories as $category)
                            <label class="relative">
                                <input type="radio" name="category_id" value="{{ $category->id }}" class="peer sr-only" required
                                       {{ old('category_id') == $category->id ? 'checked' : '' }}>
                                <div class="flex flex-col items-center gap-1.5 rounded-lg border-2 border-stone-200 p-3 cursor-pointer
                                            peer-checked:border-clay peer-checked:bg-clay/5 transition-colors hover:border-stone-300">
                                    <i class="ti {{ $category->icon }} text-xl" style="color: {{ $category->color }}"></i>
                                    <span class="text-xs font-medium text-stone-700 text-center leading-tight">{{ $category->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('category_id') <p class="text-xs text-rust mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- ============ JUDUL & DESKRIPSI ============ --}}
                <div class="rounded-xl border border-stone-200 bg-white p-5 space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-ink mb-1.5">Judul Laporan</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required maxlength="255"
                               placeholder="Contoh: Tumpukan sampah di pinggir Jl. Mawar"
                               class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                        @error('title') <p class="text-xs text-rust mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-ink mb-1.5">Deskripsi</label>
                        <textarea name="description" id="description" rows="4" required minlength="20"
                                  placeholder="Jelaskan kondisi masalah secara detail, sejak kapan, dan dampaknya..."
                                  class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">{{ old('description') }}</textarea>
                        <p class="text-xs text-stone-400 mt-1">Minimal 20 karakter</p>
                        @error('description') <p class="text-xs text-rust mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- ============ LOKASI / PETA ============ --}}
                <div class="rounded-xl border border-stone-200 bg-white p-5">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-semibold text-ink">Lokasi Kejadian</label>
                        <button type="button" @click="useCurrentLocation()"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-moss hover:text-moss-dark">
                            <i class="ti ti-current-location text-base" :class="locating && 'animate-pulse'"></i>
                            <span x-text="locating ? 'Mencari lokasi...' : 'Gunakan lokasi saat ini'"></span>
                        </button>
                    </div>

                    <p class="text-xs text-stone-500 mb-3">Geser pin pada peta atau klik tombol di atas untuk menandai lokasi otomatis.</p>

                    <div id="report-map" class="h-72 rounded-lg overflow-hidden border border-stone-200"></div>

                    <input type="hidden" name="latitude" x-model="lat">
                    <input type="hidden" name="longitude" x-model="lng">

                    <div class="mt-3 flex items-start gap-2 text-sm text-stone-600 bg-stone-50 rounded-lg px-3 py-2.5" x-show="address">
                        <i class="ti ti-map-pin text-base text-clay shrink-0 mt-0.5"></i>
                        <span x-text="address"></span>
                    </div>

                    <div x-show="weather" class="mt-2 flex items-center gap-2 text-xs text-stone-500">
                        <i class="ti ti-cloud text-base"></i>
                        <span x-text="weather"></span>
                    </div>

                    @error('latitude') <p class="text-xs text-rust mt-2">Silakan tentukan lokasi pada peta.</p> @enderror
                </div>

                {{-- ============ VISIBILITAS ============ --}}
                <div class="rounded-xl border border-stone-200 bg-white p-5">
                    <label class="block text-sm font-semibold text-ink mb-3">Visibilitas Laporan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative">
                            <input type="radio" name="is_public" value="1" class="peer sr-only" checked>
                            <div class="flex items-start gap-3 rounded-lg border-2 border-stone-200 p-3.5 cursor-pointer
                                        peer-checked:border-moss peer-checked:bg-moss/5 transition-colors">
                                <i class="ti ti-eye text-lg text-moss mt-0.5"></i>
                                <div>
                                    <p class="text-sm font-medium text-ink">Publik</p>
                                    <p class="text-xs text-stone-500 mt-0.5">Terlihat di peta & beranda</p>
                                </div>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="is_public" value="0" class="peer sr-only">
                            <div class="flex items-start gap-3 rounded-lg border-2 border-stone-200 p-3.5 cursor-pointer
                                        peer-checked:border-stone-500 peer-checked:bg-stone-100 transition-colors">
                                <i class="ti ti-lock text-lg text-stone-500 mt-0.5"></i>
                                <div>
                                    <p class="text-sm font-medium text-ink">Privat</p>
                                    <p class="text-xs text-stone-500 mt-0.5">Hanya kamu & petugas</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- ============ SUBMIT ============ --}}
                <button type="submit" :disabled="submitting"
                        class="w-full rounded-lg bg-clay py-3.5 text-sm font-semibold text-white shadow-sm hover:bg-clay-dark disabled:opacity-60 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2">
                    <i class="ti ti-loader-2 text-lg animate-spin" x-show="submitting"></i>
                    <span x-text="submitting ? 'Mengirim laporan...' : 'Kirim Laporan'"></span>
                </button>
            </div>
        </form>
    </div>

    {{-- Leaflet CSS & JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        function reportForm() {
            return {
                photos: [],
                lat: null,
                lng: null,
                address: '',
                weather: '',
                locating: false,
                submitting: false,
                map: null,
                marker: null,

                init() {
                    // Default center: Surabaya (sesuaikan dengan lokasi target aplikasi)
                    const defaultLat = -7.2575, defaultLng = 112.7521;

                    this.map = L.map('report-map').setView([defaultLat, defaultLng], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors',
                        maxZoom: 19,
                    }).addTo(this.map);

                    this.marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(this.map);

                    this.marker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        this.setLocation(pos.lat, pos.lng);
                    });

                    this.map.on('click', (e) => {
                        this.marker.setLatLng(e.latlng);
                        this.setLocation(e.latlng.lat, e.latlng.lng);
                    });

                    // Coba ambil lokasi otomatis saat halaman dibuka
                    this.useCurrentLocation();
                },

                useCurrentLocation() {
                    if (!navigator.geolocation) {
                        alert('Browser kamu tidak mendukung GPS.');
                        return;
                    }

                    this.locating = true;
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            const { latitude, longitude } = pos.coords;
                            this.map.setView([latitude, longitude], 16);
                            this.marker.setLatLng([latitude, longitude]);
                            this.setLocation(latitude, longitude);
                            this.locating = false;
                        },
                        () => {
                            this.locating = false;
                            alert('Tidak bisa mengambil lokasi. Pastikan izin GPS diaktifkan, atau tandai lokasi manual di peta.');
                        },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                },

                async setLocation(lat, lng) {
                    this.lat = lat;
                    this.lng = lng;

                    // Reverse geocode via backend (proxy ke Nominatim)
                    try {
                        const res = await fetch(`/api/geocode?lat=${lat}&lng=${lng}`);
                        const data = await res.json();
                        this.address = data.address || 'Alamat tidak ditemukan';
                    } catch (e) {
                        this.address = '';
                    }

                    // Ambil cuaca saat ini via backend (proxy ke OpenWeather)
                    try {
                        const res = await fetch(`/api/weather?lat=${lat}&lng=${lng}`);
                        const data = await res.json();
                        if (data.condition) {
                            this.weather = `Cuaca saat ini: ${data.condition}, ${Math.round(data.temp)}°C`;
                        }
                    } catch (e) {
                        this.weather = '';
                    }
                },

                addPhotos(fileList) {
                    const remaining = 5 - this.photos.length;
                    const files = Array.from(fileList).slice(0, remaining);

                    files.forEach((file) => {
                        if (!file.type.startsWith('image/')) return;
                        this.photos.push({
                            file,
                            preview: URL.createObjectURL(file),
                        });
                    });

                    // Reset input supaya bisa pilih file yang sama lagi kalau dihapus
                    this.$refs.cameraInput.value = '';
                    this.$refs.galleryInput.value = '';
                },

                removePhoto(index) {
                    URL.revokeObjectURL(this.photos[index].preview);
                    this.photos.splice(index, 1);
                },

                onSubmit(e) {
                    if (!this.lat || !this.lng) {
                        e.preventDefault();
                        alert('Silakan tentukan lokasi kejadian pada peta terlebih dahulu.');
                        return;
                    }

                    this.submitting = true;

                    // Inject file foto ke form sebagai input file sebelum submit
                    const form = e.target;
                    const dt = new DataTransfer();
                    this.photos.forEach(p => dt.items.add(p.file));

                    let fileInput = form.querySelector('input[name="images[]"]');
                    if (!fileInput) {
                        fileInput = document.createElement('input');
                        fileInput.type = 'file';
                        fileInput.name = 'images[]';
                        fileInput.multiple = true;
                        fileInput.classList.add('hidden');
                        form.appendChild(fileInput);
                    }
                    fileInput.files = dt.files;
                },
            }
        }
    </script>
</x-layouts.app>