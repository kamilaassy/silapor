<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SiLapor' }} — Lapor Masalah Lingkungan Sekitarmu</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cream text-ink font-sans antialiased">

    <div x-data="{ mobileMenuOpen: false }" class="min-h-screen flex flex-col">

        {{-- ============ NAVBAR ============ --}}
        <header class="sticky top-0 z-40 border-b border-stone-200 bg-cream/90 backdrop-blur-sm">
            <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">

                    {{-- Logo --}}
                    <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-moss text-cream">
                            <i class="ti ti-map-pin-filled text-lg"></i>
                        </span>
                        <span class="font-display text-xl font-semibold tracking-tight text-ink">SiLapor</span>
                    </a>

                    {{-- Desktop nav --}}
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('home') }}"
                           class="px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('home') ? 'text-moss bg-moss/10' : 'text-stone-600 hover:text-ink hover:bg-stone-100' }}">
                            Beranda
                        </a>
                        <a href="{{ route('map.index') }}"
                           class="px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('map.*') ? 'text-moss bg-moss/10' : 'text-stone-600 hover:text-ink hover:bg-stone-100' }}">
                            Peta Laporan
                        </a>

                        @auth
                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}"
                                   class="px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.*') ? 'text-moss bg-moss/10' : 'text-stone-600 hover:text-ink hover:bg-stone-100' }}">
                                    Dashboard Admin
                                </a>
                            @elseif(auth()->user()->hasRole('petugas'))
                                <a href="{{ route('petugas.dashboard') }}"
                                   class="px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('petugas.*') ? 'text-moss bg-moss/10' : 'text-stone-600 hover:text-ink hover:bg-stone-100' }}">
                                    Dashboard Petugas
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}"
                                   class="px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('dashboard') ? 'text-moss bg-moss/10' : 'text-stone-600 hover:text-ink hover:bg-stone-100' }}">
                                    Laporan Saya
                                </a>
                            @endif
                        @endauth
                    </div>

                    {{-- Right side: CTA + user menu --}}
                    <div class="hidden md:flex items-center gap-3">
                        @auth
                            @unless(auth()->user()->hasAnyRole(['admin', 'petugas']))
                                <a href="{{ route('laporan.create') }}"
                                   class="inline-flex items-center gap-1.5 rounded-md bg-clay px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-clay-dark transition-colors">
                                    <i class="ti ti-plus text-base"></i>
                                    Buat Laporan
                                </a>
                            @endunless

                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.outside="open = false"
                                        class="flex items-center gap-2 rounded-full pl-1 pr-3 py-1 hover:bg-stone-100 transition-colors">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-moss text-cream text-sm font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                    <span class="text-sm font-medium text-ink">{{ explode(' ', auth()->user()->name)[0] }}</span>
                                    <i class="ti ti-chevron-down text-sm text-stone-400"></i>
                                </button>

                                <div x-show="open" x-transition
                                     class="absolute right-0 mt-2 w-48 rounded-lg border border-stone-200 bg-white py-1 shadow-lg"
                                     style="display: none;">
                                    <div class="px-3 py-2 border-b border-stone-100">
                                        <p class="text-sm font-medium text-ink truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-stone-500 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-stone-600 hover:bg-stone-50">
                                        <i class="ti ti-settings text-base"></i> Pengaturan Akun
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-rust hover:bg-stone-50">
                                            <i class="ti ti-logout text-base"></i> Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-stone-600 hover:text-ink">Masuk</a>
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center rounded-md bg-moss px-4 py-2 text-sm font-semibold text-cream shadow-sm hover:bg-moss-dark transition-colors">
                                Daftar
                            </a>
                        @endauth
                    </div>

                    {{-- Mobile menu button --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-stone-600">
                        <i class="ti ti-menu-2 text-2xl" x-show="!mobileMenuOpen"></i>
                        <i class="ti ti-x text-2xl" x-show="mobileMenuOpen" style="display:none"></i>
                    </button>
                </div>
            </nav>

            {{-- Mobile menu --}}
            <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-stone-200 bg-cream" style="display: none;">
                <div class="px-4 py-3 space-y-1">
                    <a href="{{ route('home') }}" class="block px-3 py-2 text-sm font-medium rounded-md text-stone-700 hover:bg-stone-100">Beranda</a>
                    <a href="{{ route('map.index') }}" class="block px-3 py-2 text-sm font-medium rounded-md text-stone-700 hover:bg-stone-100">Peta Laporan</a>

                    @auth
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-sm font-medium rounded-md text-stone-700 hover:bg-stone-100">Dashboard Admin</a>
                        @elseif(auth()->user()->hasRole('petugas'))
                            <a href="{{ route('petugas.dashboard') }}" class="block px-3 py-2 text-sm font-medium rounded-md text-stone-700 hover:bg-stone-100">Dashboard Petugas</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm font-medium rounded-md text-stone-700 hover:bg-stone-100">Laporan Saya</a>
                            <a href="{{ route('laporan.create') }}" class="block px-3 py-2 text-sm font-semibold rounded-md text-clay">+ Buat Laporan</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-stone-200 mt-2 pt-2">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-rust">Keluar</button>
                        </form>
                    @else
                        <div class="border-t border-stone-200 mt-2 pt-2 flex flex-col gap-2 px-3">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-stone-700">Masuk</a>
                            <a href="{{ route('register') }}" class="inline-flex justify-center rounded-md bg-moss px-4 py-2 text-sm font-semibold text-cream">Daftar</a>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        {{-- ============ FLASH MESSAGES ============ --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
                 class="mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 mt-4">
                <div class="flex items-center gap-2 rounded-lg bg-moss/10 border border-moss/30 px-4 py-3 text-sm text-moss-dark">
                    <i class="ti ti-circle-check text-lg shrink-0"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
                 class="mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 mt-4">
                <div class="flex items-center gap-2 rounded-lg bg-rust/10 border border-rust/30 px-4 py-3 text-sm text-rust">
                    <i class="ti ti-alert-circle text-lg shrink-0"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- ============ MAIN CONTENT ============ --}}
        <main class="flex-1">
            {{ $slot }}
        </main>

        {{-- ============ FOOTER ============ --}}
        <footer class="border-t border-stone-200 bg-white mt-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-md bg-moss text-cream">
                            <i class="ti ti-map-pin-filled text-sm"></i>
                        </span>
                        <span class="font-display text-base font-semibold text-ink">SiLapor</span>
                    </div>
                    <p class="text-sm text-stone-500 text-center">
                        Platform pelaporan masalah lingkungan perkotaan untuk warga.
                    </p>
                    <p class="text-xs text-stone-400">
                        Made With Love &middot; {{ date('Y') }}
                    </p>
                </div>
            </div>
        </footer>
    </div>

</body>
</html>