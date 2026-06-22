<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Status;
use App\Models\Category;
use App\Models\User;
use App\Mail\ReportStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    // Admin bisa lihat SEMUA laporan termasuk privat
    public function index(Request $request)
    {
        $query = Report::with(['category', 'status', 'user', 'assignedTo'])->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('visibility')) {
            $query->where('is_public', $request->visibility === 'public');
        }

        $reports    = $query->paginate(20)->withQueryString();
        $statuses   = Status::orderBy('order')->get();
        $categories = Category::all();

        return view('admin.reports.index', compact('reports', 'statuses', 'categories'));
    }

    public function show(Request $request, $laporan)
    {
        $report = Report::with([
            'category', 'status', 'images',
            'histories.status', 'histories.changedBy',
            'user', 'assignedTo'
        ])->findOrFail($laporan);

        $statuses = Status::orderBy('order')->get();
        $petugas  = User::role('petugas')->get();

        return view('admin.reports.show', compact('report', 'statuses', 'petugas'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status_id' => 'required|exists:statuses,id',
            'note'      => 'nullable|string|max:1000',
        ]);

        $report->update(['status_id' => $validated['status_id']]);

        $report->histories()->create([
            'status_id'  => $validated['status_id'],
            'changed_by' => auth()->id(),
            'note'       => $validated['note'] ?? null,
        ]);

        $report->load(['status', 'user']);
        Mail::to($report->user->email)->send(new ReportStatusUpdated($report));

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    // Tugaskan laporan ke petugas tertentu
    public function assign(Request $request, Report $report)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $report->update(['assigned_to' => $validated['assigned_to']]);

        return back()->with('success', 'Laporan berhasil ditugaskan.');
    }

    public function destroy(Report $report)
    {
        foreach ($report->images as $image) {
            \Storage::disk('public')->delete([$image->path, $image->thumbnail_path]);
        }

        $report->delete();

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan dihapus.');
    }

    // Export laporan ke CSV
    public function export()
    {
        $reports = Report::with(['category', 'status', 'user'])->get();

        $filename = 'laporan-silapor-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($reports) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No Laporan', 'Judul', 'Kategori', 'Status', 'Pelapor', 'Alamat', 'Tanggal']);

            foreach ($reports as $r) {
                fputcsv($file, [
                    $r->report_number,
                    $r->title,
                    $r->category->name,
                    $r->status->name,
                    $r->user->name,
                    $r->address,
                    $r->created_at->format('d-m-Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
