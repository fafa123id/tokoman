<?php

namespace App\Http\Controllers;

use App\Models\Agents;
use App\Models\kontak;
use App\Models\Mitra;
use App\Models\StokBarang;
use Illuminate\Http\Request;

class welcomeController extends Controller
{
    public function index()
    {
        $types = StokBarang::select('jenis_tutup')->distinct()->inRandomOrder()->get();
        $barang = StokBarang::paginate(6);
        $agents = Agents::paginate(6);
        $mitra = Mitra::paginate(6);
        return view("welcome", ["barangs" => $barang,"agents"=>$agents,"mitra"=>$mitra], compact('types'));
    }
    public function reqWa($name)
    {
        $phone = kontak::where('name', 'admin')->first();
        return redirect("https://wa.me/$phone->noHp?text=Halo%20Tokoman,%20Saya%20Ingin%20Order%20Botol%20$name");
    }
}
