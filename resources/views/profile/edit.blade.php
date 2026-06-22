<x-layouts.app title="Profil Saya">

    <div class="max-w-2xl mx-auto space-y-5">

        <h1 class="font-display text-2xl font-semibold text-ink">Profil Saya</h1>

        {{-- Update profile --}}
        <div class="rounded-xl border border-stone-200 bg-white p-6">
            <h2 class="text-base font-semibold text-ink mb-1">Informasi Pribadi</h2>
            <p class="text-sm text-stone-500 mb-5">Perbarui nama, kontak, dan alamat kamu.</p>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="block text-sm font-medium text-ink mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                    @error('name') <p class="text-xs text-rust mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-ink mb-1.5">Nomor HP</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
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
                              class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay resize-none">{{ old('address', $user->address) }}</textarea>
                    @error('address') <p class="text-xs text-rust mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-ink mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                    @error('email') <p class="text-xs text-rust mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="submit"
                            class="rounded-lg bg-clay px-5 py-2.5 text-sm font-semibold text-white hover:bg-clay-dark transition-colors">
                        Simpan Perubahan
                    </button>
                    @if(session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                           class="text-sm text-moss font-medium">
                            <i class="ti ti-circle-check"></i> Tersimpan!
                        </p>
                    @endif
                </div>
            </form>
        </div>

        {{-- Update password --}}
        <div class="rounded-xl border border-stone-200 bg-white p-6">
            <h2 class="text-base font-semibold text-ink mb-1">Ubah Kata Sandi</h2>
            <p class="text-sm text-stone-500 mb-5">Gunakan kata sandi yang kuat dan unik.</p>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-ink mb-1.5">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                           class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                    @error('current_password', 'updatePassword')
                        <p class="text-xs text-rust mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-ink mb-1.5">Kata Sandi Baru</label>
                    <input type="password" name="password" id="new_password" autocomplete="new-password"
                           class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                    @error('password', 'updatePassword')
                        <p class="text-xs text-rust mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-ink mb-1.5">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                           class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="submit"
                            class="rounded-lg bg-ink px-5 py-2.5 text-sm font-semibold text-white hover:bg-stone-800 transition-colors">
                        Ubah Kata Sandi
                    </button>
                    @if(session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                           class="text-sm text-moss font-medium">
                            <i class="ti ti-circle-check"></i> Kata sandi diperbarui!
                        </p>
                    @endif
                </div>
            </form>
        </div>

        {{-- Hapus akun --}}
        <div class="rounded-xl border border-red-100 bg-white p-6">
            <h2 class="text-base font-semibold text-red-700 mb-1">Hapus Akun</h2>
            <p class="text-sm text-stone-500 mb-5">Akun yang dihapus tidak dapat dipulihkan kembali.</p>

            <form method="POST" action="{{ route('profile.destroy') }}"
                  onsubmit="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('delete')
                <div class="mb-4">
                    <label for="del_password" class="block text-sm font-medium text-ink mb-1.5">Konfirmasi dengan Kata Sandi</label>
                    <input type="password" name="password" id="del_password"
                           class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay max-w-sm">
                    @error('password', 'userDeletion')
                        <p class="text-xs text-rust mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition-colors">
                    Hapus Akun Saya
                </button>
            </form>
        </div>

    </div>

</x-layouts.app>
