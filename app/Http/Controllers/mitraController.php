<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Auth;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Storage;
use Validator;
use App\Misc\MiscManager;
use App\Repositories\RepositoryManager;

class mitraController extends Controller
{
    private $pusher;
    private $imageManager;
    private $repository;

    public function __construct(RepositoryManager $repositoryManager, MiscManager $miscFeature )
    {
        $this->repository = $repositoryManager->getRepository('mitra');
        $this->pusher = $miscFeature->getMisc('pusher');
        $this->imageManager = $miscFeature->getMisc('imageManager');
    }
    public function index()
    {
        $mitra = $this->repository->all()::paginate(6);
        return view('mitra.index', compact('mitra'));
    }

    public function add()
    {
        return view('mitra.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'address' => 'required',
                'images' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'gmaps' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (strpos($value, "www.google.com/maps") === false && strpos($value, "maps.app.goo.gl") === false) {
                            $fail('Masukkan link Google Maps yang valid');
                        }
                    },
                ],
                'noTelp' => 'required|numeric',
            ],
            [
                'noTelp.numeric' => 'No.Telepon harus angka',
                'name.required' => 'Nama wajib diisi.',
                'address.required' => 'Alamat wajib diisi.',
                'images.required' => 'Gambar wajib diunggah.',
                'noTelp.required' => 'Nomor telepon wajib diisi.',
                'images.image' => 'File harus berupa gambar.',
                'images.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
                'max.max' => 'Ukuran gambar tidak boleh lebih dari 2048 kilobytes.'
            ]
        );
        if ($validator->fails()) {
            return redirect('/mitra')->with('error', $validator->errors()->first());
        }

        $file = $request->file('images');
        $filename = '-' . time() . '.' . $file->getClientOriginalExtension();
        Storage::disk('s3')->put('mitra/' . $filename, file_get_contents($file));

        $result=$this->repository->insert([
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'images' => $filename,
            'noTelp' => $request->get('noTelp'),
            'gmaps'=>$request->gmaps,
        ]);
        $this->pusher->doPush('mitra-channel',['massage' => (Auth::user()->role_id == 0 ? 'Admin ' : 'Pegawai ') . Auth::user()->name .
                ' berhasil menambahkan mitra ' . $request->name,
            'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
            'id' => $result['id'],
            'excepturl'=>''
            ]);

        return redirect('/mitra')->with('success', 'Mitra '.$result['name'].' Berhasil Ditambahkan!');
    }

    public function show(Mitra $mitra)
    {
        return view('mitra.show', compact('mitra'));
    }

    public function edit($id)
    {
        $mitra=$this->repository->find($id);
        if(!$mitra)
        return redirect()->route('mitra.')->with('error','Mitra Tidak Ditemukan');
        return view('mitra.edit', compact('mitra'));
    }

    public function update(Request $request, Mitra $mitra)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'address' => 'required',
                'images' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'noTelp' => 'required|numeric',
                'gmaps' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (strpos($value, "www.google.com/maps") === false && strpos($value, "maps.app.goo.gl") === false) {
                            $fail('Masukkan link Google Maps yang valid');
                        }
                    },
                ],
            ],
            [
                'noTelp.numeric' => 'No.Telepon harus angka',
                'name.required' => 'Nama wajib diisi.',
                'address.required' => 'Alamat wajib diisi.',
                'noTelp.required' => 'Nomor telepon wajib diisi.',
                'images.image' => 'File harus berupa gambar.',
                'images.mimes' => 'Gambar harus berformat jpeg, png, atau jpg.',
                'images.max' => 'Ukuran gambar tidak boleh lebih dari 2048 kilobytes.'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $oldNama = $mitra->name;
        $this->repository->update($mitra->id,
        [
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'noTelp' => $request->get('noTelp'),
            'gmaps'=> $request->gmaps
        ]);

        if ($request->hasFile('images')) {
            $nameold = $mitra->images;
            $file = $request->file('images');
            Storage::disk('s3')->delete('mitra/' . $nameold);
            $filename = '-' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('s3')->put('mitra/' . $filename, file_get_contents($file));
            $mitra->images = $filename;
            $mitra->save();
        }

        //begin pusher
        $this->pusher->doPush('mitra-channel',['massage' => (Auth::user()->role_id == 0 ? 'Admin ' : 'Pegawai ') . Auth::user()->name .
                ' berhasil mengubah mitra ' . $request->name,
            'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
            'id' => $mitra->id,
            'excepturl'=>''
        ]);

        return redirect()->route('mitra.index')->with('success', 'Mitra '.$oldNama.' Terupdate!');
    }

    public function destroy(Mitra $mitra)
    {
        Storage::disk('s3')->delete('mitra/'.$mitra->images);
        $this->repository->delete($mitra->id);
        $this->pusher->doPush('mitra-channel',['massage' => (Auth::user()->role_id == 0 ? 'Admin ' : 'Pegawai ') . Auth::user()->name .
                ' berhasil menghapus mitra ' . $mitra->name,
            'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
            'id' => $mitra->id,
            'excepturl'=>''
        ]);
        return redirect('/mitra')->with('success', 'Mitra '.$mitra->name.' Terhapus!');
    }
}

