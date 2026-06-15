<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Promo;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::with('promo');

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->get();
        $promos = Promo::latest()->get(); // Untuk pilihan dropdown di form modal

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('kategori.table_rows', compact('categories'))->render(),
                'stats' => [
                    'total' => Kategori::count(),
                    'berpromo' => Kategori::whereNotNull('id_promo')->count()
                ]
            ]);
        }

        return view('kategori.kategori', compact('categories', 'promos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'id_promo'      => 'nullable|exists:promo,id',
        ]);

        Kategori::create($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Kategori baru berhasil ditambahkan!']);
        }

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'id_promo'      => 'nullable|exists:promo,id',
        ]);

        $kategori->update($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Kategori berhasil diperbarui!']);
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['message' => 'Kategori telah dihapus permanen.']);
        }

        return redirect()->back();
    }
}