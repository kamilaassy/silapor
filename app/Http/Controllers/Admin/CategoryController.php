<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('reports')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'icon'  => 'required|string|max:50',
            'color' => 'required|string|max:7',
        ]);

        $validated['slug'] = \Str::slug($validated['name']);

        Category::create($validated);

        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'icon'      => 'required|string|max:50',
            'color'     => 'required|string|max:7',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return back()->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->reports()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih dipakai laporan.');
        }

        $category->delete();

        return back()->with('success', 'Kategori dihapus.');
    }
}
