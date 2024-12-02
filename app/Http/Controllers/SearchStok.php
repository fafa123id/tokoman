<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokBarang; // Model untuk jenis barang

class SearchStok extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $type = $request->input('type');

        $barangs = StokBarang::query();

        if (!empty($query)) {
            $barangs->where('nama_barang', 'like', '%' . $query . '%');
        }
        if (!empty($type)) {
            $barangs->where('jenis_tutup', $type);
        }
        $barangs = $barangs->paginate(9)->appends(['search' => $query, 'type' => $type]);
        
        return view('stok.index', compact('barangs'));
        
        
    }
}