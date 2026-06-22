<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::withCount('reports')->orderBy('order')->get();
        return view('admin.statuses.index', compact('statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'color_hex'   => 'required|string|max:7',
            'bg_hex'      => 'required|string|max:7',
            'description' => 'nullable|string',
            'order'       => 'required|integer',
        ]);

        $validated['slug'] = \Str::slug($validated['name']);

        Status::create($validated);

        return back()->with('success', 'Status ditambahkan.');
    }

    public function update(Request $request, Status $status)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'color_hex'   => 'required|string|max:7',
            'bg_hex'      => 'required|string|max:7',
            'description' => 'nullable|string',
            'order'       => 'required|integer',
        ]);

        $status->update($validated);

        return back()->with('success', 'Status diperbarui.');
    }

    public function destroy(Status $status)
    {
        if ($status->reports()->exists()) {
            return back()->with('error', 'Status tidak bisa dihapus karena masih dipakai laporan.');
        }

        $status->delete();

        return back()->with('success', 'Status dihapus.');
    }
}
