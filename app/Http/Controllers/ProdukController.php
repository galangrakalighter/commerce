<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori');

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->get();
        $categories = Kategori::latest()->get(); // Untuk dropdown di modal

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('produk.table_rows', compact('products'))->render(),
                'stats' => [
                    'total' => Produk::count(),
                    'total_harga' => Produk::sum('harga')
                ]
            ]);
        }

        return view('produk.produk', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id',
            'nama_produk' => 'required|string',
            'harga'       => 'required|numeric',
            'spec_produk' => 'required|json',
            'images.*'    => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except('images');
        $data['spec_produk'] = json_decode($request->spec_produk, true);

        // Proses Multi-Upload Gambar
        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $file) {
                $paths[] = $file->store('produk', 'public');
            }
            $data['gambar'] = $paths;
        }

        Produk::create($data);
        return response()->json(['message' => 'Produk berhasil disimpan!']);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $data = $request->except('images');
        $data['spec_produk'] = json_decode($request->spec_produk, true);

        // Jika ada gambar baru, ganti total
        if ($request->hasFile('images')) {
            // Hapus gambar lama
            if ($produk->gambar) {
                foreach ($produk->gambar as $img) Storage::disk('public')->delete($img);
            }
            $paths = [];
            foreach ($request->file('images') as $file) {
                $paths[] = $file->store('produk', 'public');
            }
            $data['gambar'] = $paths;
        }

        $produk->update($data);
        return response()->json(['message' => 'Produk berhasil diupdate!']);
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        if ($produk->gambar) {
            foreach ($produk->gambar as $img) Storage::disk('public')->delete($img);
        }
        $produk->delete();
        return response()->json(['message' => 'Produk berhasil dihapus.']);
    }
}