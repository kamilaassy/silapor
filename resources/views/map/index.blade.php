<x-layouts.app title="Peta Laporan">
    <div x-data="reportMap()" class="flex flex-col h-[calc(100vh-4rem)]">

        {{-- ============ FILTER BAR ============ --}}
        <div class="border-b border-stone-200 bg-white px-4 sm:px-6 py-3 z-10">
            <div class="flex flex-wrap items-center gap-3">
                <div>
                    <h1 class="font-display text-lg font-semibold text-ink">Peta Laporan</h1>
                    <p class="text-xs text-stone-500">
                        <span x-text="reports.length"></span> laporan ditampilkan
                    </p>
                </div>

                <div class="flex-1"></div>

                <select x-model="filterCategory" @change="loadData()"
                        class="rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                    <option value="">Semua Kategori</option>
                    @foreach(\App\Models\Category::where('is_active', true)->get() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <select x-model="filterStatus" @change="loadData()"
                        class="rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Status::orderBy('order')->get() as $status)
                        <option value="{{ $status->slug }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ============ PETA ============ --}}
        <div class="flex-1 relative">
            <div id="public-map" class="absolute inset-0"></div>

            {{-- Loading indicator --}}
            <div x-show="loading" class="absolute top-4 left-1/2 -translate-x-1/2 bg-white rounded-full px-4 py-2 shadow-md text-sm font-medium text-stone-600 z-[1000]">
                <i class="ti ti-loader-2 animate-spin"></i> Memuat laporan...
            </div>

            {{-- Legend --}}
            <div class="absolute bottom-4 left-4 bg-white rounded-lg shadow-md p-3 z-[1000] max-w-[200px]">
                <p class="text-xs font-semibold text-ink mb-2">Status Laporan</p>
                <div class="space-y-1">
                    @foreach(\App\Models\Status::orderBy('order')->get() as $status)
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full shrink-0" style="background-color: {{ $status->color_hex }}"></span>
                            <span class="text-xs text-stone-600">{{ $status->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        function reportMap() {
            return {
                map: null,
                markers: [],
                reports: [],
                loading: false,
                filterCategory: '',
                filterStatus: '',

                init() {
                    this.map = L.map('public-map').setView([-7.2575, 112.7521], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors',
                        maxZoom: 19,
                    }).addTo(this.map);

                    this.loadData();
                },

                async loadData() {
                    this.loading = true;

                    const params = new URLSearchParams();
                    if (this.filterCategory) params.append('category', this.filterCategory);
                    if (this.filterStatus) params.append('status', this.filterStatus);

                    try {
                        const res = await fetch(`{{ route('map.data') }}?${params.toString()}`);
                        const data = await res.json();
                        this.reports = data;
                        this.renderMarkers(data);
                    } catch (e) {
                        console.error('Gagal memuat data peta:', e);
                    } finally {
                        this.loading = false;
                    }
                },

                renderMarkers(reports) {
                    // Hapus marker lama
                    this.markers.forEach(m => this.map.removeLayer(m));
                    this.markers = [];

                    reports.forEach(r => {
                        const icon = L.divIcon({
                            html: `<div style="background-color:${r.status_color}; width:14px; height:14px; border-radius:50%; border:2px solid white; box-shadow:0 1px 4px rgba(0,0,0,0.4)"></div>`,
                            className: '',
                            iconSize: [14, 14],
                            iconAnchor: [7, 7],
                        });

                        const marker = L.marker([r.lat, r.lng], { icon }).addTo(this.map);

                        marker.bindPopup(`
                            <div style="font-family: Inter, sans-serif; min-width: 180px">
                                <span style="display:inline-block; font-size:10px; font-weight:600; color:${r.color}; background:${r.color}1A; padding:2px 8px; border-radius:99px; margin-bottom:6px">${r.category}</span>
                                <p style="font-size:13px; font-weight:600; color:#2A2620; margin:0 0 4px">${r.title}</p>
                                <p style="font-size:11px; color:#827A68; margin:0 0 8px">${r.number}</p>
                                <a href="${r.url}" style="display:inline-block; font-size:12px; font-weight:600; color:#C2682F; text-decoration:none">Lihat detail &rarr;</a>
                            </div>
                        `);

                        this.markers.push(marker);
                    });
                },
            }
        }
    </script>
</x-layouts.app>