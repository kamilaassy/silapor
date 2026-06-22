<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Category;
use App\Models\Status;
use App\Models\ReportImage;
use App\Services\ImageService;
use App\Services\WeatherService;
use App\Services\GeoService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReportController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private ImageService   $imageService,
        private WeatherService $weatherService,
        private GeoService     $geoService,
    ) {}

    // Daftar laporan milik warga yang login
    public function index(Request $request)
    {
        $query = Report::with(['category', 'status', 'images'])
            ->where('user_id', auth()->id())
            ->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $reports    = $query->paginate(10)->withQueryString();
        $statuses   = Status::orderBy('order')->get();
        $categories = Category::where('is_active', true)->get();

        return view('reports.index', compact('reports', 'statuses', 'categories'));
    }

    // Form buat laporan baru
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('reports.create', compact('categories'));
    }

    // Simpan laporan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category_id' => 'required|exists:categories,id',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
            'is_public'   => 'boolean',
            'images'      => 'nullable|array|max:5',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp|max:5120', // max 5MB per file
        ]);

        // Ambil data lokasi & cuaca
        $geo     = $this->geoService->reverse($validated['latitude'], $validated['longitude']);
        $weather = $this->weatherService->current($validated['latitude'], $validated['longitude']);

        $report = Report::create([
            'title'              => $validated['title'],
            'description'        => $validated['description'],
            'category_id'        => $validated['category_id'],
            'latitude'           => $validated['latitude'],
            'longitude'          => $validated['longitude'],
            'user_id'           => auth()->id(),
            'is_public'         => $request->boolean('is_public', true),
            'address'           => $geo['address'] ?? null,
            'kelurahan'         => $geo['kelurahan'] ?? null,
            'kecamatan'         => $geo['kecamatan'] ?? null,
            'weather_condition' => $weather['condition'] ?? null,
            'weather_temp'      => $weather['temp'] ?? null,
            'weather_icon'      => $weather['icon'] ?? null,
        ]);

        // Proses upload gambar
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $this->imageService->storeForReport($report, $file, $index);
            }
        }

        return redirect()
            ->route('reports.show', $report->report_number)
            ->with('success', 'Laporan berhasil dikirim! Nomor laporan: ' . $report->report_number);
    }

    // Detail laporan (publik bisa lihat, privat hanya pemilik)
    public function show(Report $report)
    {
        if (! $report->is_public && $report->user_id !== auth()->id()) {
            if (! auth()->user()?->hasAnyRole(['petugas', 'admin'])) {
                abort(403);
            }
        }

        $report->load(['category', 'status', 'images', 'histories.status', 'histories.changedBy', 'user']);

        return view('reports.show', compact('report'));
    }

    // Hapus laporan milik sendiri
    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);

        // Hapus semua gambar dari storage
        foreach ($report->images as $image) {
            $this->imageService->delete($image);
        }

        $report->delete();

        return redirect()
            ->route('laporan.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}