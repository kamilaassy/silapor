<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportImage;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ReportImageController extends Controller
{
    public function __construct(private ImageService $imageService) {}

    // Upload satu gambar via AJAX (dipanggil dari form sebelum/sesudah submit)
    public function store(Request $request, Report $report)
    {
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($report->images()->count() >= 5) {
            return response()->json(['message' => 'Maksimal 5 foto per laporan.'], 422);
        }

        $order = $report->images()->max('order') + 1;
        $image = $this->imageService->storeForReport($report, $request->file('image'), $order);

        return response()->json([
            'id'            => $image->id,
            'url'           => $image->url,
            'thumbnail_url' => $image->thumbnail_url,
            'size_kb'       => $image->size_kb,
        ]);
    }

    // Hapus satu gambar
    public function destroy(ReportImage $image)
    {
        if ($image->report->user_id !== auth()->id()) {
            abort(403);
        }

        $this->imageService->delete($image);

        return response()->json(['message' => 'Gambar dihapus.']);
    }
}
