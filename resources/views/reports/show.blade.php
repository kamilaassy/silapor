<x-layouts.app title="{{ $report->title }}">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8"
         x-data="{ activePhoto: 0, lightbox: false, photos: {{ Js::from($report->images->pluck('url')) }} }">

        {{-- ============ HEADER ============ --}}
        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-1 text-sm font-medium text-stone-500 hover:text-ink mb-4">
                <i class="ti ti-arrow-left text-base"></i>
                Kembali
            </a>

            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-mono text-stone-400">{{ $report->report_number }}</span>
                        @if(!$report->is_public)
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-stone-500">
                                <i class="ti ti-lock text-sm"></i> Privat
                            </span>
                        @endif
                    </div>
                    <h1 class="font-display text-2xl sm:text-3xl font-semibold text-ink">{{ $report->title }}</h1>
                </div>

                <span class="shrink-0 inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold"
                      style="color: {{ $report->status->color_hex }}; background-color: {{ $report->status->bg_hex }}">
                    {{ $report->status->name }}
                </span>
            </div>

            <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-stone-500">
                <span class="inline-flex items-center gap-1.5">
                    <i class="ti {{ $report->category->icon }} text-base" style="color: {{ $report->category->color }}"></i>
                    {{ $report->category->name }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <i class="ti ti-user text-base"></i>
                    {{ $report->user->name }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <i class="ti ti-calendar text-base"></i>
                    {{ $report->created_at->translatedFormat('d M Y, H:i') }}
                </span>
            </div>

            {{-- Tombol hapus, hanya untuk pemilik laporan --}}
            @if((int) $report->user_id === (int) auth()->id())
                <form method="POST" action="{{ route('laporan.destroy', $report) }}" class="inline"
                      onsubmit="return confirm('Yakin ingin menghapus laporan ini? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 mt-3 text-xs font-medium text-rust hover:text-rust/80">
                        <i class="ti ti-trash text-sm"></i> Hapus Laporan
                    </button>
                </form>
            @endif
        </div>

        {{-- ============ GALERI FOTO ============ --}}
        @if($report->images->isNotEmpty())
            <div class="mb-6">
                <div class="rounded-xl overflow-hidden bg-stone-100 aspect-video cursor-pointer" @click="lightbox = true">
                    <template x-for="(img, i) in photos" :key="i">
                        <img :src="img" x-show="activePhoto === i" class="h-full w-full object-cover">
                    </template>
                </div>

                @if($report->images->count() > 1)
                    <div class="flex gap-2 mt-2 overflow-x-auto pb-1">
                        @foreach($report->images as $i => $image)
                            <button @click="activePhoto = {{ $i }}"
                                    class="h-16 w-16 rounded-lg overflow-hidden shrink-0 border-2 transition-colors"
                                    :class="activePhoto === {{ $i }} ? 'border-clay' : 'border-transparent'">
                                <img src="{{ $image->thumbnail_url }}" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Lightbox sederhana --}}
            <div x-show="lightbox" x-cloak @click="lightbox = false"
                 class="fixed inset-0 z-50 bg-ink/90 flex items-center justify-center p-4" style="display:none">
                <template x-for="(img, i) in photos" :key="i">
                    <img :src="img" x-show="activePhoto === i" class="max-h-[90vh] max-w-full rounded-lg object-contain">
                </template>
                <button @click="lightbox = false" class="absolute top-4 right-4 text-white">
                    <i class="ti ti-x text-3xl"></i>
                </button>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6">

            {{-- ============ KONTEN UTAMA ============ --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Deskripsi --}}
                <div class="rounded-xl border border-stone-200 bg-white p-5">
                    <h2 class="text-sm font-semibold text-ink mb-2">Deskripsi</h2>
                    <p class="text-sm text-stone-600 leading-relaxed whitespace-pre-line">{{ $report->description }}</p>
                </div>

                {{-- Timeline status --}}
                <div class="rounded-xl border border-stone-200 bg-white p-5">
                    <h2 class="text-sm font-semibold text-ink mb-4">Riwayat Status</h2>

                    <div class="space-y-0">
                        {{-- Status awal: laporan dibuat --}}
                        <div class="flex gap-3 pb-4 relative">
                            @if($report->histories->isNotEmpty())
                                <div class="absolute left-[11px] top-6 bottom-0 w-px bg-stone-200"></div>
                            @endif
                            <div class="h-6 w-6 rounded-full bg-stone-200 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="ti ti-flag text-xs text-stone-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-ink">Laporan dibuat</p>
                                <p class="text-xs text-stone-400 mt-0.5">{{ $report->created_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        @foreach($report->histories->sortBy('created_at') as $i => $history)
                            <div class="flex gap-3 pb-4 relative">
                                @if(!$loop->last)
                                    <div class="absolute left-[11px] top-6 bottom-0 w-px bg-stone-200"></div>
                                @endif
                                <div class="h-6 w-6 rounded-full flex items-center justify-center shrink-0 mt-0.5"
                                     style="background-color: {{ $history->status->bg_hex }}">
                                    <i class="ti ti-circle-check text-xs" style="color: {{ $history->status->color_hex }}"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-ink">{{ $history->status->name }}</p>
                                    <p class="text-xs text-stone-400 mt-0.5">
                                        {{ $history->created_at->translatedFormat('d M Y, H:i') }}
                                        @if($history->changedBy) &middot; oleh {{ $history->changedBy->name }} @endif
                                    </p>
                                    @if($history->note)
                                        <p class="text-xs text-stone-600 mt-1.5 bg-stone-50 rounded-lg px-3 py-2">{{ $history->note }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ============ SIDEBAR ============ --}}
            <div class="space-y-6">

                {{-- Lokasi --}}
                <div class="rounded-xl border border-stone-200 bg-white p-5">
                    <h2 class="text-sm font-semibold text-ink mb-3">Lokasi</h2>
                    <div id="detail-map" class="h-40 rounded-lg overflow-hidden mb-3"></div>
                    <p class="text-xs text-stone-600 flex items-start gap-1.5">
                        <i class="ti ti-map-pin text-sm text-clay shrink-0 mt-0.5"></i>
                        {{ $report->address ?? 'Alamat tidak tersedia' }}
                    </p>
                </div>

                {{-- Cuaca saat lapor --}}
                @if($report->weather_condition)
                    <div class="rounded-xl border border-stone-200 bg-white p-5">
                        <h2 class="text-sm font-semibold text-ink mb-3">Cuaca Saat Dilaporkan</h2>
                        <div class="flex items-center gap-3">
                            @if($report->weather_icon)
                                <img src="https://openweathermap.org/img/wn/{{ $report->weather_icon }}@2x.png" class="h-12 w-12" alt="">
                            @else
                                <i class="ti ti-cloud text-3xl text-stone-400"></i>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-ink capitalize">{{ $report->weather_condition }}</p>
                                <p class="text-xs text-stone-400">{{ round($report->weather_temp) }}°C</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const lat = {{ $report->latitude ?? 0 }};
            const lng = {{ $report->longitude ?? 0 }};

            const map = L.map('detail-map', {
                zoomControl: false,
                dragging: false,
                scrollWheelZoom: false,
            }).setView([lat, lng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            L.marker([lat, lng]).addTo(map);
        });
    </script>
</x-layouts.app>