<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Status;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'baru'    => Report::byStatus('baru-masuk')->count(),
            'proses'  => Report::byStatus('dalam-proses')->count(),
            'lapangan'=> Report::byStatus('petugas-ke-lapangan')->count(),
            'selesai' => Report::byStatus('selesai')->whereMonth('updated_at', now()->month)->count(),
        ];

        $laporanTerbaru = Report::with(['category', 'status', 'user', 'images'])
            ->whereHas('status', fn($q) => $q->whereIn('slug', ['baru-masuk', 'diverifikasi']))
            ->latest()
            ->take(8)
            ->get();

        return view('petugas.dashboard', compact('stats', 'laporanTerbaru'));
    }

    public function map()
    {
        return view('petugas.map');
    }
}
