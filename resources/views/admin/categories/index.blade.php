<x-layouts.dashboard title="Kategori" :pageTitle="'Kategori Laporan'">

    <div x-data="{ showForm: false }" class="mb-5">
        <button @click="showForm = !showForm" class="inline-flex items-center gap-1.5 rounded-lg bg-clay px-4 py-2 text-sm font-semibold text-white hover:bg-clay-dark">
            <i class="ti ti-plus text-base"></i> Tambah Kategori
        </button>

        <div x-show="showForm" x-transition class="rounded-xl border border-stone-200 bg-white p-5 mt-3" style="display:none">
            <form method="POST" action="{{ route('admin.categories.store') }}" class="grid sm:grid-cols-4 gap-3">
                @csrf
                <input type="text" name="name" placeholder="Nama kategori" required class="rounded-lg border-stone-300 text-sm focus:border-clay focus:ring-clay">
                <div x-data="iconPicker()" class="relative">
                <input type="hidden" name="icon" :value="selected">
                <button type="button" @click="open = !open"
                        class="flex items-center gap-2 border border-stone-300 rounded-lg px-3 py-2 text-sm bg-white hover:bg-stone-50 min-w-32">
                    <i :class="'ti ' + selected" class="text-lg"></i>
                    <span x-text="selected" class="text-stone-600 text-xs"></span>
                    <i class="ti ti-chevron-down ml-auto text-stone-400"></i>
                </button>
                <div x-show="open" x-cloak @click.outside="open = false"
                    class="absolute z-50 top-10 left-0 bg-white border border-stone-200 rounded-xl shadow-xl p-3 w-72">
                    <input type="text" x-model="search" placeholder="Cari icon..." 
                        class="w-full border border-stone-200 rounded-lg px-3 py-1.5 text-sm mb-2 outline-none focus:border-amber-400">
                    <div class="grid grid-cols-6 gap-1 max-h-48 overflow-y-auto">
                        <template x-for="icon in filtered" :key="icon">
                            <button type="button" @click="selected = icon; open = false"
                                    :class="selected === icon ? 'bg-amber-100 text-amber-700' : 'hover:bg-stone-100'"
                                    class="flex items-center justify-center h-9 w-9 rounded-lg transition-colors"
                                    :title="icon">
                                <i :class="'ti ' + icon" class="text-lg"></i>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <script>
            function iconPicker() {
                return {
                    open: false,
                    search: '',
                    selected: 'ti-alert-circle',
                    icons: [
                        'ti-trash','ti-road','ti-building','ti-bulb','ti-droplet',
                        'ti-tree','ti-writing','ti-dots-circle-horizontal',
                        'ti-alert-circle','ti-map-pin','ti-home','ti-car',
                        'ti-tool','ti-hammer','ti-bucket','ti-flame',
                        'ti-flood','ti-leaf','ti-recycle','ti-barrier-block',
                        'ti-wall','ti-stairs','ti-parking','ti-playground',
                        'ti-swimming','ti-toilet-paper','ti-fence','ti-container',
                        'ti-antenna','ti-solar-panel','ti-building-bridge',
                        'ti-building-community','ti-plant','ti-shovel',
                    ],
                    get filtered() {
                        if (!this.search) return this.icons;
                        return this.icons.filter(i => i.includes(this.search));
                    }
                }
            }
            </script>
                <input type="color" name="color" value="#6366f1" class="rounded-lg border-stone-300 h-10">
                <button type="submit" class="rounded-lg bg-ink text-white text-sm font-semibold hover:bg-stone-800">Simpan</button>
            </form>
        </div>
    </div>

    <div class="rounded-xl border border-stone-200 bg-white overflow-hidden">
        <div class="overflow-x-auto"><table class="w-full text-sm">
            <thead class="bg-stone-50 border-b border-stone-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-stone-500 uppercase">Kategori</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-stone-500 uppercase">Jumlah Laporan</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-stone-500 uppercase">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-stone-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr class="border-b border-stone-100 last:border-0">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <i class="ti {{ $category->icon }} text-lg" style="color: {{ $category->color }}"></i>
                                <span class="font-medium text-ink">{{ $category->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-stone-600">{{ $category->reports_count }}</td>
                        <td class="px-5 py-3.5">
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $category->is_active ? 'bg-moss/10 text-moss' : 'bg-stone-100 text-stone-500' }}">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline"
                                  onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-rust hover:text-rust/80">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.dashboard>
