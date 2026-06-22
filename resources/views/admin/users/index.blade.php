<x-layouts.dashboard title="Pengguna" :pageTitle="'Pengguna'">

    <form method="GET" class="rounded-xl border border-stone-200 bg-white p-4 mb-5">
        <div class="grid sm:grid-cols-[1fr_auto_auto] gap-3">
            <div class="relative">
                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-stone-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                       class="w-full rounded-lg border-stone-300 pl-9 text-sm focus:border-clay focus:ring-clay">
            </div>
            <select name="role" class="rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                <option value="">Semua Role</option>
                <option value="warga" {{ request('role') == 'warga' ? 'selected' : '' }}>Warga</option>
                <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button type="submit" class="rounded-lg bg-ink px-4 py-2 text-xs font-semibold text-white hover:bg-stone-800">Filter</button>
        </div>
    </form>

    <div class="rounded-xl border border-stone-200 bg-white overflow-hidden">
        <div class="overflow-x-auto"><table class="w-full text-sm">
            <thead class="bg-stone-50 border-b border-stone-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-stone-500 uppercase">Nama</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-stone-500 uppercase">Email</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-stone-500 uppercase">Role</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-stone-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b border-stone-100 last:border-0 hover:bg-stone-50">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <span class="h-8 w-8 rounded-full bg-moss text-cream text-xs font-semibold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                                <span class="font-medium text-ink">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-stone-600">{{ $user->email }}</td>
                        <td class="px-5 py-3.5">
                            <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="inline">
                                @csrf @method('PATCH')
                                <select name="role" onchange="this.form.submit()"
                                        class="rounded-md border-stone-300 text-xs py-1 focus:border-clay focus:ring-clay"
                                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <option value="warga" {{ $user->hasRole('warga') ? 'selected' : '' }}>Warga</option>
                                    <option value="petugas" {{ $user->hasRole('petugas') ? 'selected' : '' }}>Petugas</option>
                                    <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                      onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-medium text-rust hover:text-rust/80">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center text-stone-500">Tidak ada pengguna.</td></tr>
                @endforelse
            </tbody>
        </table></div>

        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-stone-200">{{ $users->links() }}</div>
        @endif
    </div>
</x-layouts.dashboard>
