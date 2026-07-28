<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Promo::query();

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));

            $query->whereRaw(
                'LOWER(nama_promo) LIKE ?',
                ["%{$search}%"]
            );
        }

        $promos = $query->latest()->get();

        if ($request->ajax() || $request->wantsJson()) {
            $today = now()->format('Y-m-d');

            return response()->json([
                'html' => view('promo.table_rows', compact('promos'))->render(),
                'stats' => [
                    'berjalan' => Promo::where('tgl_akhir', '>=', $today)->count(),
                    'tunggu'   => Promo::where('tgl_mulai', '>', $today)->count(),
                    'berakhir' => Promo::where('tgl_akhir', '<', $today)->count(),
                    'total'    => Promo::count(),
                ]
            ]);
        }

        return view('promo.promo', compact('promos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_promo' => 'required|string|max:255',
            'potongan'   => 'required|numeric|min:0',
            'tgl_mulai'  => 'required|date',
            'tgl_akhir'  => 'required|date|after_or_equal:tgl_mulai',
        ]);

        Promo::create($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Promo baru berhasil diaktifkan!']);
        }

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);

        $request->validate([
            'nama_promo' => 'required|string|max:255',
            'potongan'   => 'required|numeric|min:0',
            'tgl_mulai'  => 'required|date',
            'tgl_akhir'  => 'required|date|after_or_equal:tgl_mulai',
        ]);

        $promo->update($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Data promo berhasil diperbarui!']);
        }

        return redirect()->back();
    }

    public function destroy(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Promo telah dihapus permanen.']);
        }

        return redirect()->back();
    }
}