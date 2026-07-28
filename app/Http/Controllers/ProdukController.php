<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Rating;
use App\Models\UserProductAction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori');

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->whereRaw('LOWER(nama_produk) like ?', ['%' . $search . '%']);
        }

        $products = $query->latest()->get();
        $categories = Kategori::latest()->get();

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
        $data['status_produk'] = ($request->status_produk == "ada");

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

    public function toggleFavorite(Request $request, $productId)
    {   
        $user = Auth::user();

        // Logika hapus atau tambah (seperti sebelumnya)
        $exists = $user->userProductActions()
                    ->where('product_id', $productId)
                    ->where('tipe', 'favorit')
                    ->first();

        if ($exists) {
            $exists->delete();
            return response()->json(['status' => 'removed']);
        } else {
            $user->userProductActions()->create([
                'product_id' => $productId,
                'tipe' => 'favorit'
            ]);
            return response()->json(['status' => 'added']);
        }
    }

    public function toggleWishlist(Request $request, $productId)
    { 
        // Keamanan: Cek login
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Silakan login terlebih dahulu'], 401);
        }

        $user = Auth::user();
        
        $exists = $user->userProductActions()
                    ->where('product_id', $productId)
                    ->where('tipe', 'wishlist')
                    ->first();

        if ($exists) {
            $exists->delete();
            return response()->json(['status' => 'removed']);
        } else {
            $user->userProductActions()->create([
                'product_id' => $productId,
                'tipe' => 'wishlist'
            ]);
            return response()->json(['status' => 'added']);
        }
    }

    public function indexFavorit(){
        $favorites = Auth::user()->userProductActions()
                    ->where('tipe', 'favorit')
                    ->with('product')
                    ->get();
        
        $kategoriList = Kategori::all();

        return view('produk.favorit', compact('favorites', 'kategoriList'));
    }

    public function hapusKeranjang($id){
        try {
            // Menggunakan firstOrFail() untuk memastikan item ditemukan
            $item = UserProductAction::where('product_id', $id)
                                    ->where('tipe', 'wishlist') // Pastikan tipenya sesuai dengan keranjang
                                    ->where('user_id', Auth::id()) // Penting: Hanya hapus milik user sendiri
                                    ->firstOrFail();
            $item->delete();
                
            return response()->json(['success' => true]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan', 'id' => $id], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus item'], 500);
        }
    }

    public function hapusSemuaKeranjang(Request $request){
        
        try {
            $ids = $request->ids;
            // Hapus berdasarkan array ID
            UserProductAction::whereIn('product_id', $ids)->where('tipe', 'wishlist')->where('user_id', Auth::id())->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e){
            return response()->json(['success' => false, 'message' => 'Gagal menghapus item'], 500);
        }
        
    }



    public function indexKeranjang(){
        $keranjangItems = Auth::user()->userProductActions()
                    ->where('tipe', 'wishlist')
                    ->with('product')
                    ->get();
        
        $kategoriList = Kategori::all();

        return view('produk.keranjang', compact('keranjangItems', 'kategoriList'));
    }

    public function detail_produk($id)
    {
        $produk = Produk::with(['kategori', 'ratings.user'])->findOrFail($id);

        // --- TAMBAHAN: Cek status favorit untuk user yang login ---
        $isFavorited = false;
        $inWishlist = false;
        if (Auth::user()) {
            $isFavorited = UserProductAction::where('user_id', auth()->id())
                            ->where('product_id', $produk->id)
                            ->where('tipe', 'favorit')
                            ->exists();
            $inWishlist = UserProductAction::where('user_id', auth()->id())
                    ->where('product_id', $produk->id)
                    ->where('tipe', 'wishlist')
                    ->exists();
        }
        // ---------------------------------------------------------

        $ratingStats = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingStats[$i] = $produk->ratings()->where('rating', $i)->count();
        }

        $produkSerupa = Produk::where('id_kategori', $produk->id_kategori)
                ->where('id', '!=', $id)
                ->withAvg('ratings', 'rating')
                ->limit(6)
                ->get();
                
        // Sertakan $isFavorited ke dalam compact
        return view('produk.detail_produk', compact('produk', 'ratingStats', 'produkSerupa', 'isFavorited', 'inWishlist'));
    }

    public function storeReview(Request $request, $id){
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string',
            'foto.*' => 'nullable|image|max:2048'
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $fotoPaths[] = $file->store('reviews', 'public');
            }
        }

       $rating = Rating::create([
            'produk_id' => $id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'ulasan' => $request->ulasan,
            'foto' => $fotoPaths,
        ]);

        return response()->json([
            'success' => true,
            'review' => [
                'user_name' => Auth::user()->name,
                'email_name' => Auth::user()->email,
                'rating' => $rating->rating,
                'ulasan' => $rating->ulasan,
                'foto' => $fotoPaths,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $data = $request->except('images');
        $data['spec_produk'] = json_decode($request->spec_produk, true);
        $data['status_produk'] = ($request->status_produk == "ada");

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
        return response()->json(['message' => 'Produk berhasil diupdate!', 'data' => $data]);
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