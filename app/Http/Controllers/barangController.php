<?php

namespace App\Http\Controllers;

use App\Misc\imageManager;
use App\Misc\MiscManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\StokBarang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Repositories\RepositoryManager;

class barangController extends Controller
{
    private $repository;
    private $pusher;
    private $imageManager;

    public function __construct(RepositoryManager $repositoryManager, MiscManager $miscFeature )
    {
        $this->repository = $repositoryManager->getRepository('barang');
        $this->pusher = $miscFeature->getMisc('pusher');
        $this->imageManager = $miscFeature->getMisc('imageManager');
    }
    public $needPush = true;
    public function apiRecieve()
    {
        $user = (Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'));
        return response()->json($user);
    }
    public function channelRecieve($id)
    {
        $permission = 'inactive';
        if (str_contains($id, 'stokedit')) {
            $permission = substr_replace($id, '', 0, 8);
        }
        $acceptedUrl = ['dashboard', 'stok', 'riwayat', 'stoksearch', 'riwayatfilter'];
        if (in_array($id, $acceptedUrl)) {
            $permission = 'active';
        }
        return response()->json($permission);
    }
    public function index()
    {
        $barang = $this->repository->all()::paginate(9);
        return view("stok.index", ["barangs" => $barang]);

    }
    public function add()
    {
        return view("stok.add");
    }
    public function tambahStok(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'stok' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return redirect('/stok')->with('error', 'Stok harus angka');
        }
        $paramupdate = [
            'stok' =>$request->stok
        ];
        //Melakuan updateStok melalui repository
        $this->repository->updateStok($id,$paramupdate);
        $this->pusher->doPush('my-channel',['massage' => 'Stok Barang ' . $this->repository->find($id)->nama_barang . ' Berhasil Ditambahkan Sebanyak ' . $request->stok . ' oleh user ' . Auth::user()->name,
            'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
            'id' => $this->repository->find($id)->id_barang,
            'excepturl' => '']);

        return redirect('/stok')->with('success', 'Stok Berhasil Ditambahkan');
    }
    public function apiSeeder(Request $request)
    {
        $this->needPush = false;
        return $this->addSave($request);
    }
    public function addSave(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'jenis' => 'required',
                'nama' => 'required',
                'stok' => 'required|integer',
                'bal' => 'required|integer',
                'buy' => 'required',
                'sell' => 'required',
                'ukuran' => 'required',
                'gambar1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'gambar2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'stok.integer' => 'Stok harus berupa angka.',
                'bal.integer' => 'Jumlah bal harus berupa angka.',
                'gambar1.image' => 'File harus berupa gambar.',
                'gambar1.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
                'gambar1.max' => 'Ukuran gambar tidak boleh lebih dari 2048 kilobytes.',
                'gambar2.image' => 'File harus berupa gambar.',
                'gambar2.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
                'gambar2.max' => 'Ukuran gambar tidak boleh lebih dari 2048 kilobytes.'
            ]
        );

        if ($validator->fails()) {
            return redirect('/stok/add')->with('error', $validator->errors()->first());
        }
        $id = '';
        if (strlen($request->nama) >= 3) {
            $id .= strtoupper(substr($request->nama, 0, 2)); // Mengambil 2 huruf depan
            $id .= strtoupper(substr($request->nama, -1)); // Mengambil 1 huruf belakang
        } else {
            $id .= strtoupper(substr($request->nama, 0, 1)); // Mengambil 1 huruf depan
        }

        $id .= $request->ukuran;
        $id .= $request->jenis == 'tinggi' ? 'H' : 'L';
        $id .= $request->bal;
        $result = $this->imageManager->insert([
            'gambar1'=>$request->file('gambar1'),
            'gambar2'=>$request->file('gambar2')
        ]);

        $paraminsert=[
            'id'=>$id,
            'nama_barang'=> $request->nama,
            'ukuran' => $request->ukuran,
            'jenis_tutup' => $request->jenis,
            'stok'=>abs($request->stok),
            'bal'=>abs($request->bal),
            'harga_beli'=>$request->buy,
            'harga_jual'=>$request->sell,
            'path1'=>$result['path1'],
            'path2'=>$result['path2'],
            'filename1'=>$result['filename1'],
            'filename2'=>$result['filename2']
        ];
        $this->repository->insert($paraminsert);
        $this->pusher->doPush('my-channel',[
            'massage' => 'Barang ' . $request->nama . ' Berhasil Ditambahkan oleh user ' . Auth::user()->name,
            'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
            'id' => $id,
            'excepturl' => ''
        ]);
        return redirect('/stok')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $barang = $this->repository->find($id);
        
        return view("stok.edit", ["brg" => $barang]);
        
    }
    public function deleteImg($id)
    {
        $result=$this->imageManager->delete($id);
        $this->pusher->doPush('my-channel',[
            'massage' => 'Gambar 2 Barang ' . $result[0] . ' Berhasil Dihapus oleh user ' . Auth::user()->name,
            'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
            'id' => $result[1],
            'excepturl' => 'dashboard,riwayat,riwayatfilter,stok,stoksearch'
        ]);
        return redirect('/stok')->with('success', 'Gambar Berhasil Dihapus');
    }

    public function editSave(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'jenis' => 'required',
                'nama' => 'required',
                'stok' => 'required|integer',
                'bal' => 'required|integer',
                'buy' => 'required',
                'sell' => 'required',
                'ukuran' => 'required',
                'gambar1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'gambar2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'stok.integer' => 'Stok harus berupa angka.',
                'bal.integer' => 'Jumlah bal harus berupa angka.',
                'gambar1.image' => 'File harus berupa gambar.',
                'gambar1.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
                'gambar1.max' => 'Ukuran gambar tidak boleh lebih dari 2048 kilobytes.',
                'gambar2.image' => 'File harus berupa gambar.',
                'gambar2.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
                'gambar2.max' => 'Ukuran gambar tidak boleh lebih dari 2048 kilobytes.'
            ]

        );

        if ($validator->fails()) {
            return redirect('/stok/edit/' . $id)->with('error', $validator->errors()->first());
        }
        
        $result=$this->repository->update($id,$request);
        $this->imageManager->update($id,['img1'=>$request->gambar1??null,'img2'=>$request->gambar2??null]);
        $this->pusher->doPush('my-channel',[
            'massage' => 'Barang ' . $result[0] . ' Berhasil Diubah oleh user ' . Auth::user()->name,
            'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
            'id' => $result[1],
            'excepturl' => ''
        ]);
       
        return redirect('/stok')->with('success', 'Data Berhasil Diubah');
    }

    public function delete($id)
    {
        $result=$this->imageManager->deleteAll($id);
        $this->repository->delete($id);
        $this->pusher->doPush('my-channel',[
            'massage' => 'Barang ' . $result[0] . ' Berhasil Dihapus oleh user ' . Auth::user()->name,
            'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
            'id' => $result[1],
            'excepturl' => 'dashboard,riwayat,riwayatfilter'
        ]);
        return redirect('/stok')->with('success', 'Data Berhasil Dihapus');
    }
}

