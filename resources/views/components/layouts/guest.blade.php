<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Masuk' }} — SiLapor</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cream text-ink font-sans antialiased">

    <div class="min-h-screen grid lg:grid-cols-2">

        {{-- ============ SISI KIRI: BRANDING ============ --}}
        <div class="hidden lg:flex flex-col justify-between bg-ink p-10 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 24px 24px;"></div>

            <a href="{{ route('home') }}" class="flex items-center gap-2 relative">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-clay text-white">
                    <i class="ti ti-map-pin-filled text-lg"></i>
                </span>
                <span class="font-display text-xl font-semibold text-cream">SiLapor</span>
            </a>

            <div class="relative">
                <p class="font-display text-3xl font-semibold text-cream leading-tight">
                    "Satu laporan dari kamu, bisa jadi awal perubahan untuk lingkungan sekitar."
                </p>
                <div class="flex items-center gap-4 mt-8">
                    <div class="flex items-center gap-1.5 text-stone-400 text-sm">
                        <i class="ti ti-map-pin text-base"></i> GPS & Peta Interaktif
                    </div>
                    <div class="flex items-center gap-1.5 text-stone-400 text-sm">
                        <i class="ti ti-mail text-base"></i> Notifikasi Email
                    </div>
                </div>
            </div>

            <p class="text-xs text-stone-500 relative">&copy; {{ date('Y') }} SiLapor</p>
        </div>

        {{-- ============ SISI KANAN: FORM ============ --}}
        <div class="flex items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-sm">

                {{-- Logo mobile only --}}
                <a href="{{ route('home') }}" class="flex lg:hidden items-center gap-2 mb-8 justify-center">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-moss text-cream">
                        <i class="ti ti-map-pin-filled text-lg"></i>
                    </span>
                    <span class="font-display text-xl font-semibold text-ink">SiLapor</span>
                </a>

                @if(session('status'))
                    <div class="mb-4 rounded-lg bg-moss/10 border border-moss/30 px-4 py-3 text-sm text-moss-dark">
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>