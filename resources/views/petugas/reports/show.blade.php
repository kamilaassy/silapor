<x-layouts.dashboard title="{{ $report->title }}" :pageTitle="$report->report_number">

    <a href="{{ route('petugas.reports.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-stone-500 hover:text-ink mb-4">
        <i class="ti ti-arrow-left text-base"></i> Kembali ke daftar laporan
    </a>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- ============ KONTEN UTAMA ============ --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Header --}}
            <div class="rounded-xl border border-stone-200 bg-white p-5">
                <div class="flex items-start justify-between gap-4 flex-wrap mb-3">
                    <div>
                        <span class="text-xs font-mono text-stone-400">{{ $report->report_number }}</span>
                        <h1 class="font-display text-xl font-semibold text-ink mt-1">{{ $report->title }}</h1>
                    </div>
                    <span class="shrink-0 inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold"
                          style="color: {{ $report->status->color_hex }}; background-color: {{ $report->status->bg_hex }}">
                        {{ $report->status->name }}
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-4 text-sm text-stone-500 mb-4">
                    <span class="inline-flex items-center gap-1.5">
                        <i class="ti {{ $report->category->icon }} text-base" style="color: {{ $report->category->color }}"></i>
                        {{ $report->category->name }}
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <i class="ti ti-user text-base"></i> {{ $report->user->name }}
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <i class="ti ti-calendar text-base"></i> {{ $report->created_at->translatedFormat('d M Y, H:i') }}
                    </span>
                    @if(!$report->is_public)
                        <span class="inline-flex items-center gap-1.5 text-stone-400">
                            <i class="ti ti-lock text-base"></i> Privat
                        </span>
                    @endif
                </div>

                <p class="text-sm text-stone-600 leading-relaxed whitespace-pre-line">{{ $report->description }}</p>
            </div>

            {{-- Foto --}}
            @if($report->images->isNotEmpty())
                <div class="rounded-xl border border-stone-200 bg-white p-5">
                    <h2 class="text-sm font-semibold text-ink mb-3">Foto Bukti</h2>
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                        @foreach($report->images as $image)
                            <a href="{{ $image->url }}" target="_blank" class="aspect-square rounded-lg overflow-hidden bg-stone-100">
                                <img src="{{ $image->thumbnail_url }}" class="h-full w-full object-cover hover:scale-105 transition-transform">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Form update status --}}
            <div class="rounded-xl border border-stone-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-ink mb-3">Update Status</h2>
                <form method="POST" action="{{ route('petugas.reports.update-status', $report) }}">
                    @csrf @method('PATCH')

                    <div class="grid sm:grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-medium text-stone-500 mb-1.5">Status Baru</label>
                            <select name="status_id" required class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ $report->status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-medium text-stone-500 mb-1.5">Catatan untuk Pelapor (opsional)</label>
                        <textarea name="note" rows="3" placeholder="Contoh: Petugas sudah menuju lokasi, estimasi selesai 2 hari kerja..."
                                  class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay"></textarea>
                    </div>

                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-clay px-4 py-2.5 text-sm font-semibold text-white hover:bg-clay-dark transition-colors">
                        <i class="ti ti-send text-base"></i>
                        Update & Kirim Notifikasi Email
                    </button>
                </form>
            </div>
        </div>

        {{-- ============ SIDEBAR ============ --}}
        <div class="space-y-5">

            {{-- Lokasi --}}
            <div class="rounded-xl border border-stone-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-ink mb-3">Lokasi</h2>
                <div id="petugas-map" class="h-40 rounded-lg overflow-hidden mb-3"></div>
                <p class="text-xs text-stone-600 flex items-start gap-1.5">
                    <i class="ti ti-map-pin text-sm text-clay shrink-0 mt-0.5"></i>
                    {{ $report->address ?? 'Alamat tidak tersedia' }}
                </p>
                <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" target="_blank"
                   class="inline-flex items-center gap-1.5 mt-3 text-xs font-semibold text-moss hover:text-moss-dark">
                    <i class="ti ti-external-link text-sm"></i> Buka di Google Maps
                </a>
            </div>

            {{-- Riwayat --}}
            <div class="rounded-xl border border-stone-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-ink mb-3">Riwayat Status</h2>
                <div class="space-y-3">
                    @forelse($report->histories as $history)
                        <div class="flex gap-2.5">
                            <div class="h-2 w-2 rounded-full mt-1.5 shrink-0" style="background-color: {{ $history->status->color_hex }}"></div>
                            <div>
                                <p class="text-xs font-medium text-ink">{{ $history->status->name }}</p>
                                <p class="text-xs text-stone-400">{{ $history->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-stone-400">Belum ada perubahan status.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const lat = {{ $report->latitude ?? 0 }};
            const lng = {{ $report->longitude ?? 0 }};
            const map = L.map('petugas-map', { zoomControl: false, dragging: false, scrollWheelZoom: false }).setView([lat, lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
            L.marker([lat, lng]).addTo(map);
        });
    </script>
</x-layouts.dashboard>
