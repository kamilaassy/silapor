<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_laporan' => Report::count(),
            'total_warga'   => User::role('warga')->count(),
            'total_petugas' => User::role('petugas')->count(),
            'bulan_ini'     => Report::whereMonth('created_at', now()->month)->count(),
        ];

        // Laporan per status (untuk chart)
        $perStatus = Report::selectRaw('status_id, count(*) as total')
            ->with('status:id,name,color_hex')
            ->groupBy('status_id')
            ->get();

        // Laporan per kategori (untuk chart)
        $perKategori = Report::selectRaw('category_id, count(*) as total')
            ->with('category:id,name,color')
            ->groupBy('category_id')
            ->get();

        $laporanTerbaru = Report::with(['category', 'status', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'perStatus', 'perKategori', 'laporanTerbaru'));
    }

    public function map()
    {
        return view('admin.map');
    }
}
