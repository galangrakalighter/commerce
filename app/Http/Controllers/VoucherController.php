<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->get();
        // Jika request via AJAX, return partial view
        if ($request->ajax()) {
            return response()->json([
                'html' => view('voucher.table_rows', compact('vouchers'))->render()
            ]);
        }

        return view('voucher.voucher', compact('vouchers'));
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
            'kode' => 'required|unique:voucher,kode|max:20',
            'potongan' => 'required|integer|min:0',
        ]);

        Voucher::create($request->all());

        return response()->json(['message' => 'Voucher berhasil ditambahkan!']);
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
        $request->validate([
            'kode' => 'required|unique:voucher,kode,' . $id,
            'potongan' => 'required|integer|min:0',
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->update($request->all());

        return response()->json(['message' => 'Voucher berhasil diperbarui!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return response()->json(['message' => 'Voucher berhasil dihapus!']);
    }
}
