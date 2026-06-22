<x-layouts.app title="Dashboard">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="font-display text-2xl font-semibold text-ink">Halo, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
                <p class="text-stone-500 text-sm mt-1">Berikut ringkasan laporan yang sudah kamu buat.</p>
            </div>
            <a href="{{ route('laporan.create') }}"
               class="hidden sm:inline-flex items-center gap-1.5 rounded-md bg-clay px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-clay-dark transition-colors">
                <i class="ti ti-plus text-base"></i>
                Buat Laporan
            </a>
        </div>

        {{-- Stat cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="rounded-xl border border-stone-200 bg-white p-4">
                <p class="text-xs font-medium text-stone-500 uppercase tracking-wide">Total Laporan</p>
                <p class="font-display text-3xl font-semibold text-ink mt-1">{{ $myStats['total'] }}</p>
            </div>
            <div class="rounded-xl border border-stone-200 bg-white p-4">
                <p class="text-xs font-medium text-stone-500 uppercase tracking-wide">Baru Masuk</p>
                <p class="font-display text-3xl font-semibold text-blue-700 mt-1">{{ $myStats['baru'] }}</p>
            </div>
            <div class="rounded-xl border border-stone-200 bg-white p-4">
                <p class="text-xs font-medium text-stone-500 uppercase tracking-wide">Dalam Proses</p>
                <p class="font-display text-3xl font-semibold text-clay mt-1">{{ $myStats['proses'] }}</p>
            </div>
            <div class="rounded-xl border border-stone-200 bg-white p-4">
                <p class="text-xs font-medium text-stone-500 uppercase tracking-wide">Selesai</p>
                <p class="font-display text-3xl font-semibold text-moss mt-1">{{ $myStats['selesai'] }}</p>
            </div>
        </div>

        {{-- Daftar laporan --}}
        <div class="rounded-xl border border-stone-200 bg-white overflow-hidden">
            <div class="px-5 py-4 border-b border-stone-200">
                <h2 class="font-display text-lg font-semibold text-ink">Laporan Saya</h2>
            </div>

            @forelse($myReports as $report)
                <a href="{{ route('reports.show', $report->report_number) }}"
                   class="flex items-center gap-4 px-5 py-4 border-b border-stone-100 last:border-0 hover:bg-stone-50 transition-colors">

                    <div class="h-12 w-12 rounded-lg shrink-0 overflow-hidden bg-stone-100 flex items-center justify-center">
                        @if($report->images->first())
                            <img src="{{ $report->images->first()->thumbnail_url }}" class="h-full w-full object-cover" alt="">
                        @else
                            <i class="{{ 'ti ' . $report->category->icon }} text-xl" style="color: {{ $report->category->color }}"></i>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-ink truncate">{{ $report->title }}</p>
                        <p class="text-xs text-stone-500 mt-0.5">{{ $report->report_number }} &middot; {{ $report->created_at->translatedFormat('d M Y') }}</p>
                    </div>

                    <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                          style="color: {{ $report->status->color_hex }}; background-color: {{ $report->status->bg_hex }}">
                        {{ $report->status->name }}
                    </span>

                    <i class="ti ti-chevron-right text-stone-400 shrink-0"></i>
                </a>
            @empty
                <div class="px-5 py-12 text-center">
                    <i class="ti ti-clipboard-text text-4xl text-stone-300"></i>
                    <p class="text-sm text-stone-500 mt-3">Kamu belum membuat laporan apa pun.</p>
                    <a href="{{ route('laporan.create') }}" class="inline-flex items-center gap-1.5 mt-4 text-sm font-semibold text-clay hover:text-clay-dark">
                        <i class="ti ti-plus text-base"></i> Buat laporan pertamamu
                    </a>
                </div>
            @endforelse

            @if($myReports->hasPages())
                <div class="px-5 py-4 border-t border-stone-200">
                    {{ $myReports->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>