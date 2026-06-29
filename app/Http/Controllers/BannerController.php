<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderBy('urutan', 'asc')->get();
        return view('banner.banner', compact('banners'));
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
        $request->validate([
            'judul' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp',
            'link_tujuan' => 'nullable|url',
            'tipe' => 'nullable',
            'urutan' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'judul'       => $request->judul,
            'tipe'        => $request->tipe,
            'image_path'  => $path,
            'link_tujuan' => $request->link_tujuan,
            'urutan'      => $request->urutan,
            'is_active'   => $request->is_active,
        ]);

        return back()->with('success', 'Banner berhasil ditambahkan!');
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
        $banner = Banner::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link_tujuan' => 'nullable|url',
            'urutan' => 'required|integer',
            'tipe' => 'nullable',
            'is_active' => 'required|boolean',
        ]);

        // Jika admin mengupload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            Storage::disk('public')->delete($banner->image_path);
            // Upload gambar baru
            $banner->image_path = $request->file('image')->store('banners', 'public');
        }

        $banner->update([
            'judul' => $request->judul,
            'tipe'        => $request->tipe,
            'link_tujuan' => $request->link_tujuan,
            'urutan' => $request->urutan,
            'is_active' => $request->is_active,
        ]);

        return back()->with('success', 'Banner berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        // 1. Hapus file gambar dari storage
        if (Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }

        // 2. Hapus data dari database
        $banner->delete();

        return back()->with('success', 'Banner berhasil dihapus!');
    }
}
