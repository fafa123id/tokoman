<?php
namespace App\Misc;
use App\Models\StokBarang;
use Storage;
class imageManager{
    private $model;
    public function __construct() {
        $this->model = new StokBarang();
    }
    private function getBarang($id){
        return $this->model->where('id',$id)->first();
    }
    public function delete($id){
        $barang = $this->getBarang($id);
        $file1 = $barang->fileName2;
        Storage::disk('s3')->delete('images/' . $file1);
        $barang->pathImg2 = '';
        $barang->fileName2 = '';
        $barang->save();
        return [$barang->nama_barang,$barang->id_barang];
    }
    public function deleteAll($id){
        $barang = $this->getBarang($id);
        $file1 = $barang->fileName1;
        $file2 = $barang->fileName2;
        Storage::disk('s3')->delete('images/' . $file1);
        if ($file2 != '' || $file2!=null){
            Storage::disk('s3')->delete('images/' . $file2);
        }
        return [$barang->nama_barang,$barang->id_barang];
    }
    public function getUrlImg($value)
    {
        $url = 'https://' . env('AWS_BUCKET') . '.s3-' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/images/';
        return $url . $value;
    }
    function timpaGambar1($barang)
    {
        $pathImg1 = $barang->pathImg1;
        if ($pathImg1 != '') {
            Storage::disk('s3')->delete('images/' . $barang->fileName1);
        }

    }
    function timpaGambar2($barang)
    {
        $pathImg2 = $barang->pathImg2;
        if ($pathImg2 != '') {
            Storage::disk('s3')->delete('images/' . $barang->fileName2);
        }
    }
    public function update($id,$file){
        $barang = $this->getBarang($id);
        $path1 = $barang->pathImg1;
        $path2 = $barang->pathImg2;
        $filename1 = $barang->fileName1;
        $filename2 = $barang->fileName2;
        if ($file['img1'] != null) {
            $this->timpaGambar1($barang);
            $file = $file['img1'];
            $filename1 = '-' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('s3')->put('images/' . $filename1, file_get_contents($file));
            $path1 = $this->getUrlImg($filename1);
        }
        if ($file['img2'] != null) {
            $this->timpaGambar2($barang);
            $file = $file['img2'];
            $filename2 = '-' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('s3')->put('images/' . $filename2, file_get_contents($file));
            $path2 = $this->getUrlImg($filename2);
        }
        $barang->pathImg1 = $path1;
        $barang->pathImg2 = $path2;
        $barang->fileName1 = $filename1;
        $barang->fileName2 = $filename2;
        $barang->save();
    }
    public function insert($file){
        $path1 = '';
        $path2 = '';
        $filename1 = '';
        $filename2 = '';
        if ($file['gambar1'] != null) {
            $file1 = $file['gambar1'];
            $filename1 = '-' . time() . '.' . $file1->getClientOriginalExtension();
            Storage::disk('s3')->put('images/' . $filename1, file_get_contents($file1));
            $path1 = $this->getUrlImg($filename1);
        }
        if ($file['gambar2'] != null) {
            $file2 = $file['gambar2'];
            $filename2 = '-' . time() . '.' . $file2->getClientOriginalExtension();
            Storage::disk('s3')->put('images/' . $filename2, file_get_contents($file2));
            $path2 = $this->getUrlImg($filename2);
        }
        return [
            'path1'=>$path1,
            'path2'=>$path2,
            'filename1'=>$filename1,
            'filename2'=>$filename2
        ];
    }
}