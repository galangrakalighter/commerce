<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Promo;
use App\Models\ArticleCategory;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::with('promo');

        if ($request->filled('search-produk')) {
            $search_produk = strtolower($request->input('search-produk'));

            $query->whereRaw('LOWER(nama_kategori) LIKE ?', ["%{$search_produk}%"]);
        }

        $categories = $query->latest()->paginate(10)->withQueryString();
        $totalCategories = Kategori::count();

        $promos = Promo::latest()->get();

        $queryArticle = ArticleCategory::query();

        if ($request->filled('search-artikel')) {
            $search_artikel = strtolower($request->input('search-artikel'));

            $queryArticle->whereRaw('LOWER(name) LIKE ?', ["%{$search_artikel}%"]);
        }

        $articleCategories = $queryArticle->latest()->paginate(10)->withQueryString();
        $totalArticleCategories = ArticleCategory::count();

        if ($request->ajax()) {

            if ($request->has('search-produk')) {

                return response()->json([
                    'html' => view('kategori.table_rows', compact('categories'))->render(),
                    'pagination' => $categories->links()->render(),
                    'stats' => [
                        'total' => $totalCategories,
                    ]
                ]);

            }

            if ($request->has('search-artikel')) {

                return response()->json([
                    'html' => view('articles.categori_table_rows', compact('articleCategories'))->render(),
                    'pagination' => $articleCategories->links()->render(),
                    'stats' => [
                        'total' => $totalArticleCategories,
                    ]
                ]);

            }
        }

        return view('kategori.kategori', compact('categories', 'promos', 'totalCategories', 'totalArticleCategories', 'articleCategories'));
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