<?php

namespace App\Repositories;
use App\Models\Riwayat;
use App\Models\StokBarang;

class BarangRepository implements RepositoryInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new StokBarang();
    }

    public function all()
    {
        return $this->model;
    }
    private function makeHistory($paramHistory,$traffic){
        $riwayat = new Riwayat();
        $riwayat->nama_barang = $paramHistory['nama_barang'];
        $riwayat->jenis_riwayat = $traffic;
        $riwayat->jumlah = abs($paramHistory['stok']);
        $riwayat->tanggal = now();
        $riwayat->id_barang = $paramHistory['id_barang'];
        $riwayat->save();
    }
    public function find($id)
    {
        return $this->model->where('id_barang',$id)->first();
    }
    public function update($id, $paramupdate)
    {
        $barang = $this->model->where('id',$id)->first();
        $oldName = $barang->nama_barang;
        $oldStok = $barang->stok;
        $barang->nama_barang = $paramupdate->nama;
        $barang->stok = abs($paramupdate->stok);
        $barang->bal = abs($paramupdate->bal);
        $barang->jenis_tutup = $paramupdate->jenis;
        $barang->harga_beli = $paramupdate->buy;
        $barang->harga_jual = $paramupdate->sell;
        $barang->ukuran = $paramupdate->ukuran;
        $barang->save();

        if($paramupdate->stok>$oldStok)
            $this->makeHistory([
            'nama_barang'=>$barang->nama_barang,
            'stok'=>$barang->stok-$oldStok,
            'id_barang'=>$barang->id
            ],'masuk');
        return [$oldName,$barang->id_barang];
    }
    public function updateStok($id, $paramupdate)
    {
        $data = $this->model->where('id_barang', $id)->first();
        $data->stok+=abs($paramupdate['stok']);
        $data->save();
        $this->makeHistory([
            'nama_barang'=>$data->nama_barang,
            'stok'=>$paramupdate['stok'],
            'id_barang'=>$data->id
            ],'masuk');
    }
    public function insert($paraminsert)
    {
        $barang=$this->model;
        $barang->id_barang = $paraminsert['id'];
        $barang->nama_barang = $paraminsert['nama_barang'];
        $barang->stok = $paraminsert['stok'];
        $barang->bal = $paraminsert['bal'];
        $barang->jenis_tutup = $paraminsert['jenis_tutup'];
        $barang->harga_beli = $paraminsert['harga_beli'];
        $barang->harga_jual = $paraminsert['harga_jual'];
        $barang->ukuran = $paraminsert['ukuran'];
        $barang->pathImg1 = $paraminsert['path1'];
        $barang->pathImg2 = $paraminsert['path2'];
        $barang->fileName1 = $paraminsert['filename1'];
        $barang->fileName2 = $paraminsert['filename2'];
        $barang->save();

        $this->makeHistory([
            'nama_barang'=>$barang->nama_barang,
            'stok'=>$barang->stok,
            'id_barang'=>$barang->id
            ],'masuk');
    }
    public function delete($id)
    {
        return $this->model::destroy($id);
    }
}
    
