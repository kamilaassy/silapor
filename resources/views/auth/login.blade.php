<x-layouts.guest title="Masuk">

    <h1 class="font-display text-2xl font-semibold text-ink mb-1">Selamat datang kembali</h1>
    <p class="text-sm text-stone-500 mb-6">Masuk untuk melanjutkan melapor masalah lingkungan.</p>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-ink mb-1.5">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
            @error('email') <p class="text-xs text-rust mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-ink mb-1.5">Kata Sandi</label>
            <input type="password" name="password" id="password" required autocomplete="current-password"
                   class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
            @error('password') <p class="text-xs text-rust mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-stone-600">
                <input type="checkbox" name="remember" class="rounded border-stone-300 text-clay focus:ring-clay">
                Ingat saya
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-moss hover:text-moss-dark">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <button type="submit" class="w-full rounded-lg bg-clay py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-clay-dark transition-colors">
            Masuk
        </button>
    </form>

    <p class="text-sm text-stone-500 text-center mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-moss hover:text-moss-dark">Daftar sekarang</a>
    </p>
</x-layouts.guest>