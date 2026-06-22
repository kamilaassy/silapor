<x-layouts.app title="Laporan Saya">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="font-display text-2xl font-semibold text-ink">Laporan Saya</h1>
                <p class="text-stone-500 text-sm mt-1">Semua laporan yang pernah kamu buat.</p>
            </div>
            <a href="{{ route('laporan.create') }}"
               class="inline-flex items-center gap-1.5 rounded-md bg-clay px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-clay-dark transition-colors">
                <i class="ti ti-plus text-base"></i>
                Buat Laporan
            </a>
        </div>

        {{-- ============ FILTER & SEARCH ============ --}}
        <form method="GET" class="rounded-xl border border-stone-200 bg-white p-4 mb-6">
            <div class="grid sm:grid-cols-[1fr_auto_auto] gap-3">
                <div class="relative">
                    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-stone-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari judul, deskripsi, atau nomor laporan..."
                           class="w-full rounded-lg border-stone-300 pl-9 text-sm focus:border-clay focus:ring-clay">
                </div>

                <select name="status" class="rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->slug }}" {{ request('status') == $status->slug ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>

                <select name="category" class="rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2 mt-3">
                <button type="submit" class="rounded-lg bg-ink px-4 py-2 text-xs font-semibold text-white hover:bg-stone-800 transition-colors">
                    Terapkan Filter
                </button>
                @if(request()->anyFilled(['search', 'status', 'category']))
                    <a href="{{ route('laporan.index') }}" class="text-xs font-medium text-stone-500 hover:text-ink">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- ============ DAFTAR LAPORAN ============ --}}
        <div class="rounded-xl border border-stone-200 bg-white overflow-hidden">
            @forelse($reports as $report)
                <a href="{{ route('reports.show', $report->report_number) }}"
                   class="flex items-center gap-4 px-5 py-4 border-b border-stone-100 last:border-0 hover:bg-stone-50 transition-colors">

                    <div class="h-14 w-14 rounded-lg shrink-0 overflow-hidden bg-stone-100 flex items-center justify-center">
                        @if($report->images->first())
                            <img src="{{ $report->images->first()->thumbnail_url }}" class="h-full w-full object-cover" alt="">
                        @else
                            <i class="ti {{ $report->category->icon }} text-2xl" style="color: {{ $report->category->color }}"></i>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-medium text-ink truncate">{{ $report->title }}</p>
                            @if(!$report->is_public)
                                <i class="ti ti-lock text-sm text-stone-400 shrink-0" title="Privat"></i>
                            @endif
                        </div>
                        <p class="text-xs text-stone-500 mt-0.5">
                            {{ $report->report_number }} &middot;
                            {{ $report->category->name }} &middot;
                            {{ $report->created_at->translatedFormat('d M Y, H:i') }}
                        </p>
                    </div>

                    <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                          style="color: {{ $report->status->color_hex }}; background-color: {{ $report->status->bg_hex }}">
                        {{ $report->status->name }}
                    </span>

                    <i class="ti ti-chevron-right text-stone-400 shrink-0"></i>
                </a>
            @empty
                <div class="px-5 py-16 text-center">
                    <i class="ti ti-clipboard-text text-4xl text-stone-300"></i>
                    @if(request()->anyFilled(['search', 'status', 'category']))
                        <p class="text-sm text-stone-500 mt-3">Tidak ada laporan yang cocok dengan filter.</p>
                        <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-1.5 mt-4 text-sm font-semibold text-moss hover:text-moss-dark">
                            Reset filter
                        </a>
                    @else
                        <p class="text-sm text-stone-500 mt-3">Kamu belum membuat laporan apa pun.</p>
                        <a href="{{ route('laporan.create') }}" class="inline-flex items-center gap-1.5 mt-4 text-sm font-semibold text-clay hover:text-clay-dark">
                            <i class="ti ti-plus text-base"></i> Buat laporan pertamamu
                        </a>
                    @endif
                </div>
            @endforelse

            @if($reports->hasPages())
                <div class="px-5 py-4 border-t border-stone-200">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>