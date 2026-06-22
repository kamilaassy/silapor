<x-layouts.dashboard title="Status Laporan" :pageTitle="'Status Laporan'">

    <div x-data="{ showForm: false }" class="mb-5">
        <button @click="showForm = !showForm"
                class="inline-flex items-center gap-1.5 rounded-lg bg-clay px-4 py-2 text-sm font-semibold text-white hover:bg-clay-dark">
            <i class="ti ti-plus text-base"></i> Tambah Status
        </button>

        <div x-show="showForm" x-transition class="rounded-xl border border-stone-200 bg-white p-5 mt-3" style="display:none">
            <h3 class="text-sm font-semibold text-ink mb-4">Form Tambah Status Baru</h3>
            <form method="POST" action="{{ route('admin.statuses.store') }}" class="space-y-4">
                @csrf

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-stone-600 mb-1.5">
                            Nama Status <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" placeholder="Contoh: Dalam Peninjauan" required
                               class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone-600 mb-1.5">
                            Urutan Tampil <span class="text-red-400">*</span>
                        </label>
                        <input type="number" name="order" value="0" min="0" required
                               class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay"
                               placeholder="0 = paling awal">
                        <p class="text-xs text-stone-400 mt-1">Angka kecil = tampil lebih awal di daftar status</p>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-stone-600 mb-1.5">Warna Teks Badge</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color_hex" value="#1d4ed8"
                                   class="h-10 w-16 rounded-lg border border-stone-300 cursor-pointer"
                                   id="colorHex">
                            <div>
                                <p class="text-xs text-stone-700 font-medium">Warna huruf pada badge status</p>
                                <p class="text-xs text-stone-400">Pilih warna yang kontras dengan background</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone-600 mb-1.5">Warna Background Badge</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="bg_hex" value="#dbeafe"
                                   class="h-10 w-16 rounded-lg border border-stone-300 cursor-pointer"
                                   id="bgHex">
                            <div>
                                <p class="text-xs text-stone-700 font-medium">Warna latar belakang badge status</p>
                                <p class="text-xs text-stone-400">Biasanya warna terang/pastel</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Preview badge --}}
                <div>
                    <label class="block text-xs font-medium text-stone-600 mb-1.5">Preview Badge</label>
                    <div class="flex items-center gap-3">
                        <span id="badgePreview"
                              class="text-xs font-medium px-3 py-1.5 rounded-full"
                              style="color: #1d4ed8; background-color: #dbeafe">
                            Nama Status
                        </span>
                        <p class="text-xs text-stone-400">Preview akan berubah saat kamu memilih warna</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-stone-600 mb-1.5">Deskripsi <span class="text-stone-400 font-normal">(opsional)</span></label>
                    <textarea name="description" rows="2"
                              placeholder="Jelaskan arti status ini, contoh: Laporan sedang ditinjau oleh petugas"
                              class="w-full rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay resize-none"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="rounded-lg bg-ink px-5 py-2 text-sm font-semibold text-white hover:bg-stone-800">
                        Simpan Status
                    </button>
                    <button type="button" @click="showForm = false"
                            class="rounded-lg border border-stone-300 px-5 py-2 text-sm font-medium text-stone-600 hover:bg-stone-50">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="rounded-xl border border-stone-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 border-b border-stone-200">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-stone-500 uppercase">Urutan</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-stone-500 uppercase">Badge Status</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-stone-500 uppercase">Deskripsi</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-stone-500 uppercase">Jumlah Laporan</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-stone-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statuses as $status)
                        <tr class="border-b border-stone-100 last:border-0">
                            <td class="px-5 py-3.5 text-stone-400 font-mono">{{ $status->order }}</td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full"
                                      style="color: {{ $status->color_hex }}; background-color: {{ $status->bg_hex }}">
                                    {{ $status->name }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-stone-500 text-xs max-w-xs">
                                {{ $status->description ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-stone-600">{{ $status->reports_count }}</td>
                            <td class="px-5 py-3.5 text-right">
                                @if($status->reports_count == 0)
                                    <form method="POST" action="{{ route('admin.statuses.destroy', $status) }}" class="inline"
                                          onsubmit="return confirm('Hapus status {{ $status->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-rust hover:text-rust/80">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-xs text-stone-300" title="Tidak bisa dihapus karena masih ada laporan">Terkunci</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        const colorHex = document.getElementById('colorHex');
        const bgHex    = document.getElementById('bgHex');
        const preview  = document.getElementById('badgePreview');

        function updatePreview() {
            if (!preview) return;
            preview.style.color           = colorHex.value;
            preview.style.backgroundColor = bgHex.value;
        }

        colorHex?.addEventListener('input', updatePreview);
        bgHex?.addEventListener('input', updatePreview);
    </script>
    @endpush

</x-layouts.dashboard>
