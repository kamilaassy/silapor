<x-layouts.guest title="Daftar">

    <h1 class="font-display text-2xl font-semibold text-ink mb-1">Buat akun baru</h1>
    <p class="text-sm text-stone-500 mb-6">Mulai laporkan masalah lingkungan di sekitarmu.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-ink mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                   placeholder="Nama sesuai KTP"
                   class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
            @error('name') <p class="text-xs text-rust mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-ink mb-1.5">Nomor HP <span class="text-red-400">*</span></label>
            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                   placeholder="08xxxxxxxxxx"
                   class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
            @error('phone') <p class="text-xs text-rust mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="address" class="block text-sm font-medium text-ink mb-1.5">
                Alamat <span class="text-stone-400 text-xs font-normal">(opsional)</span>
            </label>
            <textarea name="address" id="address" rows="2"
                      placeholder="Jl. nama jalan, No. xx, Kelurahan, Kecamatan"
                      class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay resize-none">{{ old('address') }}</textarea>
            @error('address') <p class="text-xs text-rust mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-ink mb-1.5">Email <span class="text-red-400">*</span></label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                   placeholder="contoh@email.com"
                   class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
            @error('email') <p class="text-xs text-rust mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-ink mb-1.5">Kata Sandi <span class="text-red-400">*</span></label>
            <input type="password" name="password" id="password" required autocomplete="new-password"
                   class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
            @error('password') <p class="text-xs text-rust mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-ink mb-1.5">Konfirmasi Kata Sandi <span class="text-red-400">*</span></label>
            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                   class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
            @error('password_confirmation') <p class="text-xs text-rust mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full rounded-lg bg-clay py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-clay-dark transition-colors">
            Daftar Sekarang
        </button>
    </form>

    <p class="text-sm text-stone-500 text-center mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-moss hover:text-moss-dark">Masuk di sini</a>
    </p>

</x-layouts.guest>