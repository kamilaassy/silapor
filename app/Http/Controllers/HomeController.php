<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Halaman landing publik
    public function index()
    {
        $stats = [
            'total'    => Report::public()->count(),
            'selesai'  => Report::public()->byStatus('selesai')->count(),
            'proses'   => Report::public()->byStatus('dalam-proses')->count(),
            'baru'     => Report::public()->byStatus('baru-masuk')->count(),
        ];

        $laporanTerbaru = Report::with(['category', 'status', 'images'])
            ->public()
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::withCount('reports')->where('is_active', true)->get();

        return view('home', compact('stats', 'laporanTerbaru', 'categories'));
    }

    // Dashboard warga setelah login
    public function dashboard()
    {
        $user = auth()->user();

        $myReports = Report::with(['category', 'status', 'images'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $myStats = [
            'total'   => Report::where('user_id', $user->id)->count(),
            'baru'    => Report::where('user_id', $user->id)->byStatus('baru-masuk')->count(),
            'proses'  => Report::where('user_id', $user->id)->byStatus('dalam-proses')->count(),
            'selesai' => Report::where('user_id', $user->id)->byStatus('selesai')->count(),
        ];

        return view('dashboard', compact('myReports', 'myStats'));
    }
}
