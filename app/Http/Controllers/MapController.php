<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\GeoService;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function __construct(
        private GeoService $geoService,
        private WeatherService $weatherService,
    ) {}

    // Halaman peta interaktif (publik)
    public function index()
    {
        return view('map.index');
    }

    // JSON data semua laporan publik untuk ditampilkan sebagai pin di Leaflet
    public function data(Request $request)
    {
        $query = Report::with(['category', 'status'])
            ->public()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $reports = $query->get()->map(fn($r) => [
            'id'         => $r->id,
            'number'     => $r->report_number,
            'title'      => $r->title,
            'lat'        => $r->latitude,
            'lng'        => $r->longitude,
            'category'   => $r->category->name,
            'color'      => $r->category->color,
            'icon'       => $r->category->icon,
            'status'     => $r->status->name,
            'status_color' => $r->status->color_hex,
            'url'        => route('reports.show', $r->report_number),
        ]);

        return response()->json($reports);
    }

    // Reverse geocode dari koordinat ke alamat (dipanggil via fetch dari form laporan)
    public function reverseGeocode(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $result = $this->geoService->reverse($request->lat, $request->lng);

        return response()->json($result);
    }

    // Cuaca saat ini dari koordinat (dipanggil via fetch dari form laporan)
    public function weather(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $result = $this->weatherService->current($request->lat, $request->lng);

        return response()->json($result);
    }
}
