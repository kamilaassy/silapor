<x-layouts.dashboard title="{{ $report->title }}" :pageTitle="$report->report_number">

    <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-stone-500 hover:text-ink mb-4">
        <i class="ti ti-arrow-left text-base"></i> Kembali
    </a>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">

            <div class="rounded-xl border border-stone-200 bg-white p-5">
                <div class="flex items-start justify-between gap-4 flex-wrap mb-3">
                    <div>
                        <span class="text-xs font-mono text-stone-400">{{ $report->report_number }}</span>
                        <h1 class="font-display text-xl font-semibold text-ink mt-1">{{ $report->title }}</h1>
                    </div>
                    <span class="shrink-0 inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold"
                          style="color: {{ $report->status?->color_hex ?? '#888888' }}; background-color: {{ $report->status?->bg_hex }}">
                        {{ $report->status?->name }}
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-4 text-sm text-stone-500 mb-4">
                    <span class="inline-flex items-center gap-1.5"><i class="ti {{ $report->category->icon }}" style="color: {{ $report->category->color }}"></i> {{ $report->category->name }}</span>
                    <span class="inline-flex items-center gap-1.5"><i class="ti ti-user"></i> {{ $report->user->name }}</span>
                    <span class="inline-flex items-center gap-1.5"><i class="ti ti-calendar"></i> {{ $report->created_at->translatedFormat('d M Y, H:i') }}</span>
                </div>
                <p class="text-sm text-stone-600 leading-relaxed whitespace-pre-line">{{ $report->description }}</p>
            </div>

            @if($report->images->isNotEmpty())
                <div class="rounded-xl border border-stone-200 bg-white p-5">
                    <h2 class="text-sm font-semibold text-ink mb-3">Foto Bukti</h2>
                    <div class="grid grid-cols-4 gap-2">
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
                <form method="POST" action="{{ route('admin.laporan.update-status', $report) }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <select name="status_id" required class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ $report->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <textarea name="note" rows="2" placeholder="Catatan untuk pelapor (opsional)"
                                  class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay"></textarea>
                    </div>
                    <button type="submit" class="rounded-lg bg-clay px-4 py-2.5 text-sm font-semibold text-white hover:bg-clay-dark">
                        Update Status
                    </button>
                </form>
            </div>

            {{-- Form assign petugas --}}
            <div class="rounded-xl border border-stone-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-ink mb-3">Tugaskan ke Petugas</h2>
                <form method="POST" action="{{ route('admin.laporan.assign', $report) }}" class="flex gap-2">
                    @csrf @method('PATCH')
                    <select name="assigned_to" required class="flex-1 rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                        <option value="">Pilih petugas...</option>
                        @foreach($petugas as $p)
                            <option value="{{ $p->id }}" {{ $report->assigned_to == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white hover:bg-stone-800 shrink-0">
                        Tugaskan
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-xl border border-stone-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-ink mb-3">Lokasi</h2>
                <p class="text-xs text-stone-600">{{ $report->address ?? 'Tidak tersedia' }}</p>
                <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" target="_blank"
                   class="inline-flex items-center gap-1.5 mt-3 text-xs font-semibold text-moss hover:text-moss-dark">
                    <i class="ti ti-external-link text-sm"></i> Buka di Google Maps
                </a>
            </div>

            <div class="rounded-xl border border-stone-200 bg-white p-5">
                <h2 class="text-sm font-semibold text-ink mb-3">Riwayat</h2>
                <div class="space-y-3">
                    @forelse($report->histories as $history)
                        <div class="flex gap-2.5">
                            <div class="h-2 w-2 rounded-full mt-1.5 shrink-0" style="background-color: {{ $history->status?->color_hex }}"></div>
                            <div>
                                <p class="text-xs font-medium text-ink">{{ $history->status?->name }}</p>
                                <p class="text-xs text-stone-400">{{ $history->changedBy?->name }} &middot; {{ $history->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-stone-400">Belum ada riwayat.</p>
                    @endforelse
                </div>
            </div>

            <form method="POST" action="{{ route('admin.laporan.destroy', $report) }}"
                  onsubmit="return confirm('Yakin ingin menghapus laporan ini secara permanen?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full rounded-lg border border-rust/30 text-rust px-4 py-2.5 text-sm font-semibold hover:bg-rust/5">
                    <i class="ti ti-trash text-sm"></i> Hapus Laporan
                </button>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
