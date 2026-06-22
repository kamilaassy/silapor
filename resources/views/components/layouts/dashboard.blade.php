<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — SiLapor</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-stone-50 text-ink font-sans antialiased">

    @php
        $isAdmin = auth()->user()->hasRole('admin');
        $prefix  = $isAdmin ? 'admin' : 'petugas';
    @endphp

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

        {{-- ============ SIDEBAR ============ --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:sticky top-0 left-0 z-40 h-screen w-64 shrink-0 bg-ink text-cream flex flex-col transition-transform duration-200 ease-in-out">

            <div class="flex items-center gap-2 px-5 h-16 border-b border-white/10 shrink-0">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-clay text-white">
                    <i class="ti ti-map-pin-filled text-lg"></i>
                </span>
                <div>
                    <p class="font-display text-lg font-semibold leading-none">SiLapor</p>
                    <p class="text-xs text-stone-400 mt-0.5">{{ $isAdmin ? 'Panel Admin' : 'Panel Petugas' }}</p>
                </div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

                <a href="{{ route($prefix.'.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs($prefix.'.dashboard') ? 'bg-white/10 text-white' : 'text-stone-300 hover:bg-white/5 hover:text-white' }}">
                    <i class="ti ti-layout-dashboard text-lg"></i>
                    Dashboard
                </a>

                <a href="{{ $isAdmin ? route('admin.laporan.index') : route('petugas.reports.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ (request()->routeIs('admin.laporan.*') || request()->routeIs('petugas.reports.*')) ? 'bg-white/10 text-white' : 'text-stone-300 hover:bg-white/5 hover:text-white' }}">
                    <i class="ti ti-clipboard-list text-lg"></i>
                    Semua Laporan
                </a>

                <a href="{{ $isAdmin ? route('admin.map') : route('petugas.map') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ (request()->routeIs('admin.map') || request()->routeIs('petugas.map')) ? 'bg-white/10 text-white' : 'text-stone-300 hover:bg-white/5 hover:text-white' }}">
                    <i class="ti ti-map-2 text-lg"></i>
                    Peta Laporan
                </a>
                @if($isAdmin)
                    <p class="px-3 pt-5 pb-1.5 text-xs font-semibold text-stone-500 uppercase tracking-wider">Kelola Sistem</p>

                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white' : 'text-stone-300 hover:bg-white/5 hover:text-white' }}">
                        <i class="ti ti-users text-lg"></i>
                        Pengguna
                    </a>

                    <a href="{{ route('admin.categories.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-white/10 text-white' : 'text-stone-300 hover:bg-white/5 hover:text-white' }}">
                        <i class="ti ti-category text-lg"></i>
                        Kategori
                    </a>

                    <a href="{{ route('admin.statuses.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.statuses.*') ? 'bg-white/10 text-white' : 'text-stone-300 hover:bg-white/5 hover:text-white' }}">
                        <i class="ti ti-status-change text-lg"></i>
                        Status Laporan
                    </a>

                    <a href="{{ route('admin.laporan.export') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-stone-300 hover:bg-white/5 hover:text-white transition-colors">
                        <i class="ti ti-download text-lg"></i>
                        Ekspor Data
                    </a>
                @endif
            </nav>

            {{-- User info + logout --}}
            <div class="border-t border-white/10 p-3 shrink-0">
                <div class="flex items-center gap-3 px-2 py-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-clay text-white text-sm font-semibold shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-stone-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-stone-300 hover:bg-white/5 hover:text-white transition-colors">
                        <i class="ti ti-logout text-lg"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
             class="fixed inset-0 z-30 bg-ink/50 lg:hidden" style="display: none;"></div>

        {{-- ============ MAIN AREA ============ --}}
        <div class="flex-1 min-w-0 flex flex-col">

            {{-- Top bar (mobile menu trigger + page title) --}}
            <header class="sticky top-0 z-20 flex items-center gap-3 h-16 px-4 sm:px-6 border-b border-stone-200 bg-white/90 backdrop-blur-sm">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 text-stone-600">
                    <i class="ti ti-menu-2 text-2xl"></i>
                </button>
                <h1 class="font-display text-lg font-semibold text-ink">{{ $pageTitle ?? 'Dashboard' }}</h1>
            </header>

            {{-- Flash messages --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
                     class="px-4 sm:px-6 pt-4">
                    <div class="flex items-center gap-2 rounded-lg bg-moss/10 border border-moss/30 px-4 py-3 text-sm text-moss-dark">
                        <i class="ti ti-circle-check text-lg shrink-0"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
                     class="px-4 sm:px-6 pt-4">
                    <div class="flex items-center gap-2 rounded-lg bg-rust/10 border border-rust/30 px-4 py-3 text-sm text-rust">
                        <i class="ti ti-alert-circle text-lg shrink-0"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <main class="flex-1 p-4 sm:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>