<x-layouts.dashboard title="Semua Laporan" :pageTitle="'Semua Laporan'">

    <div class="flex items-center justify-end mb-4">
        <a href="{{ route('admin.laporan.export') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-stone-300 px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition-colors">
            <i class="ti ti-download text-sm"></i> Ekspor CSV
        </a>
    </div>

    <form method="GET" class="rounded-xl border border-stone-200 bg-white p-4 mb-5">
        <div class="grid sm:grid-cols-[1fr_auto_auto_auto] gap-3">
            <div class="relative">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-stone-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari laporan..."
                       class="w-full rounded-lg border-stone-300 pl-9 text-sm focus:border-clay focus:ring-clay">
            </div>
            <select name="status" class="rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->slug }}" {{ request('status') == $status->slug ? 'selected' : '' }}>{{ $status->name }}</option>
                @endforeach
            </select>
            <select name="visibility" class="rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                <option value="">Semua Visibilitas</option>
                <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Publik</option>
                <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Privat</option>
            </select>
            <button type="submit" class="rounded-lg bg-ink px-4 py-2 text-xs font-semibold text-white hover:bg-stone-800">Filter</button>
        </div>
    </form>

    <div class="rounded-xl border border-stone-200 bg-white overflow-hidden">
        @forelse($reports as $report)
            <a href="{{ route('admin.laporan.show', $report) }}"
               class="flex items-center gap-4 px-5 py-4 border-b border-stone-100 last:border-0 hover:bg-stone-50 transition-colors">
                <div class="h-10 w-10 rounded-lg shrink-0 flex items-center justify-center" style="background-color: {{ $report->category->color }}14">
                    <i class="ti {{ $report->category->icon }} text-lg" style="color: {{ $report->category->color }}"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-medium text-ink truncate">{{ $report->title }}</p>
                        @if(!$report->is_public) <i class="ti ti-lock text-sm text-stone-400"></i> @endif
                    </div>
                    <p class="text-xs text-stone-500 mt-0.5">
                        {{ $report->report_number }} &middot; {{ $report->user->name }}
                        @if($report->assignedTo) &middot; ditugaskan ke {{ $report->assignedTo->name }} @endif
                    </p>
                </div>
                <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                      style="color: {{ $report->status->color_hex }}; background-color: {{ $report->status->bg_hex }}">
                    {{ $report->status->name }}
                </span>
            </a>
        @empty
            <div class="px-5 py-16 text-center text-sm text-stone-500">Tidak ada laporan.</div>
        @endforelse

        @if($reports->hasPages())
            <div class="px-5 py-4 border-t border-stone-200">{{ $reports->links() }}</div>
        @endif
    </div>
</x-layouts.dashboard>
