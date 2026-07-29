<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Voucher;
use App\Models\Banner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Produk::query();

        // Filter Kategori
        if ($request->has('kategori_id')) {
            $query->where('id_kategori', $request->kategori_id);
        }

        // Sortir
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'terbaru': $query->latest(); break;
                case 'terlaris': $query->orderBy('sold', 'desc'); break;
                case 'termurah': $query->orderBy('harga', 'asc'); break;
            }
        }

        // Gunakan withQueryString() agar parameter filter/sort terbawa ke halaman berikutnya
        $katalog = $query->paginate(20)->withQueryString();
        $kategoriList = Kategori::all();
        $banner_home = Banner::where('is_active', true)->where('tipe', 'home')->orderBy('urutan', 'asc')->get();
        $banners = Banner::where('is_active', true)->where('tipe', 'keduanya')->orderBy('urutan', 'asc')->get();
        $rekomendasi = Produk::latest()->take(6)->get();

        return view('home', compact('katalog', 'kategoriList', 'rekomendasi', 'banners', 'request', 'banner_home'));
    }

    public function promo(){
        $gambar_promo = Banner::where('is_active', true)->where('tipe', 'promo')->first();
        return view('promo', compact('gambar_promo'));
    }

    // pu\blic function filter($kategori_id = null) {
    //     $produk = Produk::when($kategori_id, function($query) use ($kategori_id) {
    //         return $query->where('id_kategori', $kategori_id);
    //     })->get();

    //     return view('home', compact('produk'))->render();
    // }

    public function beranda()
    {
        return view('beranda');
    }

    public function tentangKami(){
        return view('tentang');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $categoryId = $request->input('category');

        // Mulai query produk
        $query = Produk::query();

        // Jika user memasukkan keyword pencarian
        if (!empty($keyword)) {
            $query->where('nama_produk', 'LIKE', '%' . $keyword . '%');
            // Tambahkan orWhere jika ingin mencari berdasarkan deskripsi juga:
            // ->orWhere('description', 'LIKE', '%' . $keyword . '%');
        }

        // Jika user memilih kategori (tidak kosong)
        if (!empty($categoryId)) {
            $query->where('id_kategori', $categoryId);
        }

        // Ambil data dengan pagination (misal 12 data per halaman)
        $products = $query->paginate(12)->appends($request->all());

        // Kembalikan ke view hasil pencarian
        return view('search_result', compact('products', 'keyword', 'categoryId'));
    }

    public function markAsRead(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Update atau buat data kapan terakhir user ini membuka notifikasi
            DB::table('user_notification_reads')->updateOrInsert(
                ['user_id' => $user->id],
                ['last_read_at' => Carbon::now()]
            );
        }

        return response()->json(['success' => true]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
