<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportImageController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboard;
use App\Http\Controllers\Petugas\ReportController as PetugasReport;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Middleware\RoleMiddleware;

// ============================================================
// PUBLIC
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/peta', [MapController::class, 'index'])->name('map.index');
Route::get('/peta/data', [MapController::class, 'data'])->name('map.data');       // JSON untuk Leaflet

// ============================================================
// WARGA (login required)
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard warga
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Profile (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Laporan — warga bisa buat & lihat milik sendiri
    Route::resource('laporan', ReportController::class)
        ->parameters(['laporan' => 'report'])
        ->only(['index', 'create', 'store', 'destroy']);

    // Upload gambar (AJAX dari form laporan)
    Route::post('/laporan/{report}/gambar', [ReportImageController::class, 'store'])
        ->name('report-images.store');
    Route::delete('/gambar/{image}', [ReportImageController::class, 'destroy'])
        ->name('report-images.destroy');

    // API geocode & cuaca (dipanggil dari JS)
    Route::get('/api/geocode', [MapController::class, 'reverseGeocode'])->name('api.geocode');
    Route::get('/api/weather', [MapController::class, 'weather'])->name('api.weather');
});

// ============================================================
// PETUGAS
// ============================================================
Route::middleware(['auth', RoleMiddleware::class . ':petugas,admin'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        Route::get('/dashboard', [PetugasDashboard::class, 'index'])->name('dashboard');
        Route::get('/laporan', [PetugasReport::class, 'index'])->name('reports.index');
        Route::get('/laporan/{report}', [PetugasReport::class, 'show'])->name('reports.show');
        Route::patch('/laporan/{report}/status', [PetugasReport::class, 'updateStatus'])
            ->name('reports.update-status');
        Route::get('/peta', [PetugasDashboard::class, 'map'])->name('map');
});

// ============================================================
// ADMIN
// ============================================================
Route::middleware(['auth', RoleMiddleware::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Laporan — admin bisa lihat semua termasuk privat
        Route::resource('laporan', AdminReport::class)
            ->only(['index', 'show', 'destroy']);
        Route::patch('/laporan/{report}/status', [AdminReport::class, 'updateStatus'])
            ->name('laporan.update-status');
        Route::patch('/laporan/{report}/assign', [AdminReport::class, 'assign'])
            ->name('laporan.assign');

        // User management
        Route::resource('users', UserController::class)
            ->only(['index', 'show', 'edit', 'update', 'destroy']);
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])
            ->name('users.update-role');

        // Kategori & Status
        Route::resource('categories', CategoryController::class)
            ->only(['index', 'store', 'update', 'destroy']);
        Route::resource('statuses', StatusController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        // Export laporan
        Route::get('/export/laporan', [AdminReport::class, 'export'])
            ->name('laporan.export');

        Route::get('/peta', [AdminDashboard::class, 'map'])->name('map');
});

require __DIR__ . '/auth.php';

// Detail laporan publik — diletakkan di akhir file, dengan constraint regex
// supaya path seperti /laporan/create tidak pernah ketangkep sebagai {report}
Route::get('/laporan/{report:report_number}', [ReportController::class, 'show'])
    ->where('report', 'SL-[0-9\-]+')
    ->name('reports.show');