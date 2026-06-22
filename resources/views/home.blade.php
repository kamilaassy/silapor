<x-layouts.app title="Beranda">

    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden bg-ink">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-stone-200 mb-6">
                        <i class="ti ti-leaf text-sm"></i>
                        Untuk lingkungan yang lebih baik
                    </span>
                    <h1 class="font-display text-4xl sm:text-5xl font-semibold tracking-tight text-cream leading-[1.1]">
                        Lihat masalah di sekitarmu?
                        <span class="text-clay">Laporkan.</span>
                    </h1>
                    <p class="mt-5 text-lg text-stone-300 max-w-xl leading-relaxed">
                        Sampah liar, jalan berlubang, atau fasilitas umum yang rusak — satu laporan dari kamu bisa jadi awal perubahan untuk lingkungan sekitar.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('laporan.create') }}"
                               class="inline-flex items-center gap-2 rounded-md bg-clay px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-clay-dark transition-colors">
                                <i class="ti ti-camera text-base"></i>
                                Buat Laporan Sekarang
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center gap-2 rounded-md bg-clay px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-clay-dark transition-colors">
                                <i class="ti ti-camera text-base"></i>
                                Mulai Melapor
                            </a>
                        @endauth
                        <a href="{{ route('map.index') }}"
                           class="inline-flex items-center gap-2 rounded-md bg-white/10 px-6 py-3 text-sm font-semibold text-cream hover:bg-white/15 transition-colors">
                            <i class="ti ti-map-2 text-base"></i>
                            Lihat Peta Laporan
                        </a>
                    </div>
                </div>

                {{-- Stat panel --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-xl bg-white/5 border border-white/10 p-5">
                        <p class="font-display text-3xl font-semibold text-cream">{{ $stats['total'] }}</p>
                        <p class="text-sm text-stone-400 mt-1">Total laporan warga</p>
                    </div>
                    <div class="rounded-xl bg-white/5 border border-white/10 p-5">
                        <p class="font-display text-3xl font-semibold text-moss-light" style="color: #8DBE91">{{ $stats['selesai'] }}</p>
                        <p class="text-sm text-stone-400 mt-1">Sudah selesai ditangani</p>
                    </div>
                    <div class="rounded-xl bg-white/5 border border-white/10 p-5">
                        <p class="font-display text-3xl font-semibold" style="color: #E8A766">{{ $stats['proses'] }}</p>
                        <p class="text-sm text-stone-400 mt-1">Sedang diproses</p>
                    </div>
                    <div class="rounded-xl bg-white/5 border border-white/10 p-5">
                        <p class="font-display text-3xl font-semibold" style="color: #7FAEDD">{{ $stats['baru'] }}</p>
                        <p class="text-sm text-stone-400 mt-1">Baru masuk</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ KATEGORI ============ --}}
    <section class="py-16 bg-cream">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="font-display text-2xl sm:text-3xl font-semibold text-ink">Apa yang bisa kamu laporkan?</h2>
                <p class="text-stone-500 mt-2">Pilih kategori yang paling sesuai dengan masalah yang kamu temukan</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($categories as $category)
                    <div class="group rounded-xl border border-stone-200 bg-white p-5 hover:border-stone-300 hover:shadow-sm transition-all">
                        <div class="h-11 w-11 rounded-lg flex items-center justify-center mb-3"
                             style="background-color: {{ $category->color }}1A">
                            <i class="ti {{ $category->icon }} text-xl" style="color: {{ $category->color }}"></i>
                        </div>
                        <p class="text-sm font-medium text-ink">{{ $category->name }}</p>
                        <p class="text-xs text-stone-400 mt-0.5">{{ $category->reports_count }} laporan</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ LAPORAN TERBARU ============ --}}
    <section class="py-16 bg-stone-50 border-y border-stone-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="font-display text-2xl sm:text-3xl font-semibold text-ink">Laporan terbaru</h2>
                    <p class="text-stone-500 mt-2">Dilaporkan langsung oleh warga sekitarmu</p>
                </div>
                <a href="{{ route('map.index') }}" class="hidden sm:flex items-center gap-1 text-sm font-semibold text-moss hover:text-moss-dark shrink-0">
                    Lihat semua <i class="ti ti-arrow-right text-base"></i>
                </a>
            </div>

            @if($laporanTerbaru->isEmpty())
                <div class="text-center py-16 bg-white rounded-xl border border-stone-200">
                    <i class="ti ti-clipboard-text text-4xl text-stone-300"></i>
                    <p class="text-stone-500 mt-3">Belum ada laporan publik. Jadilah yang pertama!</p>
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($laporanTerbaru as $report)
                        <a href="{{ route('reports.show', $report->report_number) }}"
                           class="group rounded-xl border border-stone-200 bg-white overflow-hidden hover:shadow-md hover:border-stone-300 transition-all">

                            <div class="h-40 bg-stone-100 overflow-hidden">
                                @if($report->images->first())
                                    <img src="{{ $report->images->first()->url }}"
                                         class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
                                         alt="{{ $report->title }}">
                                @else
                                    <div class="h-full w-full flex items-center justify-center" style="background-color: {{ $report->category->color }}14">
                                        <i class="ti {{ $report->category->icon }} text-3xl" style="color: {{ $report->category->color }}"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                                          style="color: {{ $report->category->color }}; background-color: {{ $report->category->color }}14">
                                        {{ $report->category->name }}
                                    </span>
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                                          style="color: {{ $report->status->color_hex }}; background-color: {{ $report->status->bg_hex }}">
                                        {{ $report->status->name }}
                                    </span>
                                </div>
                                <h3 class="text-sm font-medium text-ink line-clamp-2 leading-snug">{{ $report->title }}</h3>
                                <p class="text-xs text-stone-400 mt-2 flex items-center gap-1">
                                    <i class="ti ti-map-pin text-sm"></i>
                                    {{ $report->kelurahan ?? 'Lokasi tidak diketahui' }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ============ CARA KERJA ============ --}}
    <section class="py-16 bg-cream">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-display text-2xl sm:text-3xl font-semibold text-ink">Cara kerja SiLapor</h2>
                <p class="text-stone-500 mt-2">Tiga langkah sederhana dari laporan sampai selesai</p>
            </div>

            <div class="grid sm:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="mx-auto h-14 w-14 rounded-full bg-moss/10 flex items-center justify-center mb-4">
                        <i class="ti ti-camera text-2xl text-moss"></i>
                    </div>
                    <h3 class="font-display text-lg font-semibold text-ink">1. Foto & tandai lokasi</h3>
                    <p class="text-sm text-stone-500 mt-2 leading-relaxed">Ambil foto masalahnya, tandai lokasi di peta atau pakai GPS otomatis.</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto h-14 w-14 rounded-full bg-clay/10 flex items-center justify-center mb-4">
                        <i class="ti ti-send text-2xl text-clay"></i>
                    </div>
                    <h3 class="font-display text-lg font-semibold text-ink">2. Kirim laporan</h3>
                    <p class="text-sm text-stone-500 mt-2 leading-relaxed">Pilih kategori, tulis keterangan, dan tentukan privat atau publik.</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto h-14 w-14 rounded-full bg-moss/10 flex items-center justify-center mb-4">
                        <i class="ti ti-mail-check text-2xl text-moss"></i>
                    </div>
                    <h3 class="font-display text-lg font-semibold text-ink">3. Pantau progres</h3>
                    <p class="text-sm text-stone-500 mt-2 leading-relaxed">Dapatkan notifikasi email setiap kali status laporan berubah.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ CTA ============ --}}
    @guest
        <section class="py-16 bg-moss">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="font-display text-2xl sm:text-3xl font-semibold text-cream">Siap jadi bagian dari perubahan?</h2>
                <p class="text-cream/80 mt-3">Daftar sekarang, laporan pertamamu bisa dikirim dalam hitungan menit.</p>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 mt-6 rounded-md bg-cream px-6 py-3 text-sm font-semibold text-moss-dark shadow-sm hover:bg-white transition-colors">
                    Daftar Gratis
                    <i class="ti ti-arrow-right text-base"></i>
                </a>
            </div>
        </section>
    @endguest

</x-layouts.app>