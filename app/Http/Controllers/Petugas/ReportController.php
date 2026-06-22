<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Status;
use App\Mail\ReportStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    // Daftar semua laporan untuk petugas (publik + yang ditugaskan ke dia)
    public function index(Request $request)
    {
        $query = Report::with(['category', 'status', 'user', 'images'])->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->boolean('assigned_to_me')) {
            $query->where('assigned_to', auth()->id());
        }

        $reports    = $query->paginate(15)->withQueryString();
        $statuses   = Status::orderBy('order')->get();

        return view('petugas.reports.index', compact('reports', 'statuses'));
    }

    public function show(Request $request, $laporan)
    {
        $report = Report::with([
            'category', 'status', 'images',
            'histories.status', 'histories.changedBy', 'user'
        ])->findOrFail($laporan);

        $statuses = Status::orderBy('order')->get();

        return view('petugas.reports.show', compact('report', 'statuses'));
    }

    // Update status laporan + kirim email otomatis ke pelapor
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

        // Kirim email notifikasi otomatis ke pelapor
        $report->load(['status', 'user']);
        Mail::to($report->user->email)->send(new ReportStatusUpdated($report));

        return back()->with('success', 'Status laporan berhasil diperbarui dan notifikasi telah dikirim.');
    }
}
