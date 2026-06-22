<x-layouts.dashboard title="Dashboard Petugas" :pageTitle="'Dashboard'">

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-xl border border-stone-200 bg-white p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-stone-500 uppercase tracking-wide">Baru Masuk</p>
                <i class="ti ti-inbox text-lg text-blue-600"></i>
            </div>
            <p class="font-display text-3xl font-semibold text-blue-700">{{ $stats['baru'] }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-stone-500 uppercase tracking-wide">Dalam Proses</p>
                <i class="ti ti-clock-hour-4 text-lg text-clay"></i>
            </div>
            <p class="font-display text-3xl font-semibold text-clay">{{ $stats['proses'] }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-stone-500 uppercase tracking-wide">Di Lapangan</p>
                <i class="ti ti-map-pin text-lg text-purple-600"></i>
            </div>
            <p class="font-display text-3xl font-semibold text-purple-700">{{ $stats['lapangan'] }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-stone-500 uppercase tracking-wide">Selesai Bulan Ini</p>
                <i class="ti ti-circle-check text-lg text-moss"></i>
            </div>
            <p class="font-display text-3xl font-semibold text-moss">{{ $stats['selesai'] }}</p>
        </div>
    </div>

    <div class="rounded-xl border border-stone-200 bg-white overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-stone-200">
            <h2 class="font-display text-lg font-semibold text-ink">Perlu Ditindaklanjuti</h2>
            <a href="{{ route('petugas.reports.index') }}" class="text-sm font-semibold text-moss hover:text-moss-dark">
                Lihat semua &rarr;
            </a>
        </div>

        @forelse($laporanTerbaru as $report)
            <a href="{{ route('petugas.reports.show', $report) }}"
               class="flex items-center gap-4 px-5 py-4 border-b border-stone-100 last:border-0 hover:bg-stone-50 transition-colors">

                <div class="h-12 w-12 rounded-lg shrink-0 overflow-hidden bg-stone-100 flex items-center justify-center">
                    @if($report->images->first())
                        <img src="{{ $report->images->first()->thumbnail_url }}" class="h-full w-full object-cover" alt="">
                    @else
                        <i class="ti {{ $report->category->icon }} text-xl" style="color: {{ $report->category->color }}"></i>
                    @endif
                </div>

                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-ink truncate">{{ $report->title }}</p>
                    <p class="text-xs text-stone-500 mt-0.5">
                        {{ $report->report_number }} &middot; {{ $report->user->name }} &middot; {{ $report->created_at->diffForHumans() }}
                    </p>
                </div>

                <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                      style="color: {{ $report->status->color_hex }}; background-color: {{ $report->status->bg_hex }}">
                    {{ $report->status->name }}
                </span>

                <i class="ti ti-chevron-right text-stone-400 shrink-0"></i>
            </a>
        @empty
            <div class="px-5 py-12 text-center">
                <i class="ti ti-checklist text-4xl text-stone-300"></i>
                <p class="text-sm text-stone-500 mt-3">Tidak ada laporan yang perlu ditindaklanjuti saat ini.</p>
            </div>
        @endforelse
    </div>
</x-layouts.dashboard>
