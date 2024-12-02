<?php

namespace App\Http\Controllers;

use App\Models\Agents;
use App\Models\Mitra;
use Illuminate\Http\Request;
use App\Models\StokBarang; // Model untuk jenis barang

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $stock = $request->input('stock');
        $type = $request->input('type');

        $barangs = StokBarang::query();

        if (!empty($query)) {
            $barangs->where('nama_barang', 'like', '%' . $query . '%');
        }

        if ($stock === 'high') {
            $barangs->where('stok', '>', 20);
        }

        if (!empty($type)) {
            $barangs->where('jenis_tutup', $type);
        }
        $agents = Agents::paginate(6);
        $mitra = Mitra::paginate(6);
        $barangs = $barangs->paginate(6);
        $barangs->appends(['search' => $query, 'stock' => $stock, 'type' => $type]);
        // Ambil 3 jenis barang acak yang berbeda
        $types = StokBarang::select('jenis_tutup')->distinct()->inRandomOrder()->get();

        return view('welcome', compact('barangs', 'types','mitra','agents'));
    }
}