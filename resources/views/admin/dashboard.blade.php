<x-layouts.dashboard title="Dashboard Admin" :pageTitle="'Dashboard'">

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-xl border border-stone-200 bg-white p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-stone-500 uppercase tracking-wide">Total Laporan</p>
                <i class="ti ti-clipboard-list text-lg text-ink"></i>
            </div>
            <p class="font-display text-3xl font-semibold text-ink">{{ $stats['total_laporan'] }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-stone-500 uppercase tracking-wide">Laporan Bulan Ini</p>
                <i class="ti ti-calendar-stats text-lg text-clay"></i>
            </div>
            <p class="font-display text-3xl font-semibold text-clay">{{ $stats['bulan_ini'] }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-stone-500 uppercase tracking-wide">Total Warga</p>
                <i class="ti ti-users text-lg text-moss"></i>
            </div>
            <p class="font-display text-3xl font-semibold text-moss">{{ $stats['total_warga'] }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-stone-500 uppercase tracking-wide">Total Petugas</p>
                <i class="ti ti-user-check text-lg text-blue-600"></i>
            </div>
            <p class="font-display text-3xl font-semibold text-blue-700">{{ $stats['total_petugas'] }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-5 mb-6">
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-ink mb-4">Laporan per Status</h2>
            <canvas id="statusChart" height="220"></canvas>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-ink mb-4">Laporan per Kategori</h2>
            <canvas id="categoryChart" height="220"></canvas>
        </div>
    </div>

    <div class="rounded-xl border border-stone-200 bg-white overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-stone-200">
            <h2 class="font-display text-lg font-semibold text-ink">Laporan Terbaru</h2>
            <a href="{{ route('admin.laporan.index') }}" class="text-sm font-semibold text-moss hover:text-moss-dark">
                Lihat semua &rarr;
            </a>
        </div>

        @forelse($laporanTerbaru as $report)
            <a href="{{ route('admin.laporan.show', $report) }}"
               class="flex items-center gap-4 px-5 py-4 border-b border-stone-100 last:border-0 hover:bg-stone-50 transition-colors">
                <div class="h-10 w-10 rounded-lg shrink-0 flex items-center justify-center" style="background-color: {{ $report->category->color }}14">
                    <i class="ti {{ $report->category->icon }} text-lg" style="color: {{ $report->category->color }}"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-ink truncate">{{ $report->title }}</p>
                    <p class="text-xs text-stone-500 mt-0.5">{{ $report->user->name }} &middot; {{ $report->created_at->diffForHumans() }}</p>
                </div>
                <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                      style="color: {{ $report->status->color_hex }}; background-color: {{ $report->status->bg_hex }}">
                    {{ $report->status->name }}
                </span>
            </a>
        @empty
            <div class="px-5 py-12 text-center text-sm text-stone-500">Belum ada laporan masuk.</div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        const statusData = {
            labels: {!! json_encode($perStatus->pluck('status.name')) !!},
            colors: {!! json_encode($perStatus->pluck('status.color_hex')) !!},
            values: {!! json_encode($perStatus->pluck('total')) !!},
        };

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: statusData.labels,
                datasets: [{ data: statusData.values, backgroundColor: statusData.colors, borderWidth: 2, borderColor: '#fff' }],
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 10 } } },
            },
        });

        const categoryData = {
            labels: {!! json_encode($perKategori->pluck('category.name')) !!},
            colors: {!! json_encode($perKategori->pluck('category.color')) !!},
            values: {!! json_encode($perKategori->pluck('total')) !!},
        };

        new Chart(document.getElementById('categoryChart'), {
            type: 'bar',
            data: {
                labels: categoryData.labels,
                datasets: [{ data: categoryData.values, backgroundColor: categoryData.colors, borderRadius: 6 }],
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            },
        });
    </script>
</x-layouts.dashboard>
