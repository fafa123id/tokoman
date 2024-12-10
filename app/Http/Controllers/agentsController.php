<?php

namespace App\Http\Controllers;

use App\Models\Agents;
use Auth;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Storage;
use Validator;
use App\Misc\MiscManager;
use App\Repositories\RepositoryManager;

class agentsController extends Controller
{
    private $pusher;
    private $imageManager;
    private $repository;

    public function __construct(RepositoryManager $repositoryManager, MiscManager $miscFeature )
    {
        $this->repository = $repositoryManager->getRepository('agent');
        $this->pusher = $miscFeature->getMisc('pusher');
        $this->imageManager = $miscFeature->getMisc('imageManager');
    }
    public function index()
    {
        $agents = $this->repository->all()::paginate(6);
        return view('agents.index', compact('agents'));
    }

    public function add()
    {
        return view('agents.add');
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
            return redirect('/agents')->with('error', $validator->errors()->first());
        }

        $file = $request->file('images');
        $filename = '-' . time() . '.' . $file->getClientOriginalExtension();
        Storage::disk('s3')->put('agents/' . $filename, file_get_contents($file));

        $result=$this->repository->insert([
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'images' => $filename,
            'noTelp' => $request->get('noTelp'),
            'gmaps'=>$request->gmaps,
        ]);
        $this->pusher->doPush('agent-channel',['massage' => (Auth::user()->role_id == 0 ? 'Admin ' : 'Pegawai ') . Auth::user()->name .
                ' berhasil menambahkan agent ' . $request->name,
            'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
            'id' => $result['id'],
            'excepturl'=>''
            ]);

        return redirect('/agents')->with('success', 'Agent '.$result['name'].' Berhasil Ditambahkan!');
    }

    public function show(Agents $agents)
    {
        return view('agents.show', compact('agents'));
    }

    public function edit($id)
    {
        $agents=$this->repository->find($id);
        if(!$agents)
        return redirect()->route('agents.')->with('error','Agent Tidak Ditemukan');
        return view('agents.edit', compact('agents'));
    }

    public function update(Request $request, Agents $agents)
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
        $oldNama = $agents->name;
        $this->repository->update($agents->id,
        [
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'noTelp' => $request->get('noTelp'),
            'gmaps'=> $request->gmaps
        ]);

        if ($request->hasFile('images')) {
            $nameold = $agents->images;
            $file = $request->file('images');
            Storage::disk('s3')->delete('agents/' . $nameold);
            $filename = '-' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('s3')->put('agents/' . $filename, file_get_contents($file));
            $agents->images = $filename;
            $agents->save();
        }

        //begin pusher
        $this->pusher->doPush('agent-channel',['massage' => (Auth::user()->role_id == 0 ? 'Admin ' : 'Pegawai ') . Auth::user()->name .
                ' berhasil mengubah agent ' . $request->name,
            'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
            'id' => $agents->id,
            'excepturl'=>''
        ]);

        return redirect()->route('agents.index')->with('success', 'Agent '.$oldNama.' Terupdate!');
    }

    public function destroy(Agents $agents)
    {
        Storage::disk('s3')->delete('agents/'.$agents->images);
        $this->repository->delete($agents->id);
        $this->pusher->doPush('agent-channel',['massage' => (Auth::user()->role_id == 0 ? 'Admin ' : 'Pegawai ') . Auth::user()->name .
                ' berhasil menghapus agent ' . $agents->name,
            'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
            'id' => $agents->id,
            'excepturl'=>''
        ]);
        return redirect('/agents')->with('success', 'Agent '.$agents->name.' Terhapus!');
    }
}

