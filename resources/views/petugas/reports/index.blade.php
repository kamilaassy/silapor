<x-layouts.dashboard title="Semua Laporan" :pageTitle="'Semua Laporan'">

    <form method="GET" class="rounded-xl border border-stone-200 bg-white p-4 mb-5">
        <div class="grid sm:grid-cols-[1fr_auto_auto_auto] gap-3">
            <div class="relative">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-stone-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari judul, nomor laporan..."
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

            <label class="flex items-center gap-2 px-3 rounded-lg border border-stone-300 text-sm text-stone-600 cursor-pointer">
                <input type="checkbox" name="assigned_to_me" value="1" {{ request('assigned_to_me') ? 'checked' : '' }}
                       class="rounded border-stone-300 text-clay focus:ring-clay">
                Ditugaskan ke saya
            </label>

            <button type="submit" class="rounded-lg bg-ink px-4 py-2 text-xs font-semibold text-white hover:bg-stone-800 transition-colors">
                Filter
            </button>
        </div>
    </form>

    <div class="rounded-xl border border-stone-200 bg-white overflow-hidden">
        @forelse($reports as $report)
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
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-medium text-ink truncate">{{ $report->title }}</p>
                        @if(!$report->is_public)
                            <i class="ti ti-lock text-sm text-stone-400 shrink-0"></i>
                        @endif
                    </div>
                    <p class="text-xs text-stone-500 mt-0.5">
                        {{ $report->report_number }} &middot; {{ $report->user->name }} &middot; {{ $report->created_at->translatedFormat('d M Y') }}
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
                <p class="text-sm text-stone-500 mt-3">Tidak ada laporan yang cocok.</p>
            </div>
        @endforelse

        @if($reports->hasPages())
            <div class="px-5 py-4 border-t border-stone-200">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</x-layouts.dashboard>
