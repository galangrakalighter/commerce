<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Banner;
use App\Models\ArticleCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::with('category');

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));

            $query->whereRaw(
                'LOWER(title) LIKE ?',
                ["%{$search}%"]
            );
        }

        $articles = $query->latest()->get();
        $categories = ArticleCategory::all();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('articles.table_rows', compact('articles'))->render()
            ]);
        }

        return view('articles.articles', compact('articles', 'categories'));
    }

    public function indexArtikel(){
        $featured = Article::with('category')
                ->where('status', 'published')
                ->latest()
                ->first();

        $articles = Article::with('category')
                    ->where('status', 'published')
                    ->latest()
                    ->skip(1)
                    ->take(5)
                    ->get();

        $categories = ArticleCategory::withCount('articles')->get();

        $popular = Article::with('category')
                    ->where('status', 'published')
                    ->limit(3)
                    ->get();

        $banner_artikel = Banner::where('is_active', true)->where('tipe', 'artikel')->orderBy('urutan', 'asc')->get();
        $banners = Banner::where('is_active', true)->where('tipe', 'keduanya')->orderBy('urutan', 'asc')->get();

        return view('articles.list', compact('featured', 'articles', 'categories', 'popular', 'banners', 'banner_artikel'));
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
        try {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'category_id' => 'required|exists:articles_categories,id',
                'content' => 'required',
                'status' => 'required|in:draft,published',
                'image' => 'nullable|image'
            ]);

            $data = $validated;
            $data['slug'] = \Illuminate\Support\Str::slug($request->title);
            $data['excerpt'] = \Illuminate\Support\Str::limit(strip_tags($request->content), 150);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('articles', 'public');
            }

            Article::create($data);
            return response()->json(['message' => 'Artikel berhasil dibuat!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika validasi gagal, kirim pesan error sebagai JSON
            return response()->json(['message' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function storeCategory(Request $request){
       $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // 2. Simpan data
        ArticleCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        // 3. Kembalikan respons JSON
        return response()->json(['message' => 'Kategori berhasil ditambahkan!'], 200);
    }

    public function detailArtikel($slug){
        $article = Article::where('slug', $slug)->firstOrFail();
        $relatedArticles = Article::where('id', '!=', $article->id) // Jangan tampilkan artikel yang sedang dibaca
                    ->latest()
                    ->take(4)
                    ->get();
    
        return view('articles.details', compact('article', 'relatedArticles'));
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            
            // Simpan file
            $path = $file->store('articles', 'public');
            
            // FORMAT JSON INI WAJIB SESUAI DOKUMENTASI CKEDITOR
            return response()->json([
                'uploaded' => true,
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json([
            'uploaded' => false,
            'error' => ['message' => 'Upload gagal']
        ]);
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
    public function update(Request $request, $id)
    {
        try {
            $article = \App\Models\Article::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|max:255',
                'category_id' => 'required|exists:articles_categories,id', // Sesuaikan nama tabel kategori Anda
                'content' => 'required',
                'status' => 'required|in:draft,published',
                'image' => 'nullable|image'
            ]);

            $data = $validated;
            $data['slug'] = \Illuminate\Support\Str::slug($request->title);
            $data['excerpt'] = \Illuminate\Support\Str::limit(strip_tags($request->content), 150);

            // Jika ada gambar baru, hapus gambar lama dan simpan yang baru
            if ($request->hasFile('image')) {
                if ($article->image) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($article->image);
                }
                $data['image'] = $request->file('image')->store('articles', 'public');
            }

            $article->update($data);

            return response()->json(['message' => 'Artikel berhasil diperbarui!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }
        
        $article->delete();
        return redirect()->back();
    }

    public function fetch()
    {
        $articles = \App\Models\Article::latest()->get();
        // Mengembalikan view khusus baris tabel
        return view('articles.table_rows', compact('articles'))->render();
    }
}
