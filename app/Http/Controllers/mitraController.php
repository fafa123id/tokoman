<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Auth;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Storage;
use Validator;

class mitraController extends Controller
{
    public function index()
    {
        $mitra = Mitra::paginate(6);
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

        $mitra = new Mitra([
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'images' => $filename,
            'noTelp' => $request->get('noTelp'),
            'gmaps'=>$request->gmaps,
        ]);

        //begin pusher
        $generatePusher = new pegawaiController();
        $pusher = new Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'), config('broadcasting.connections.pusher.options'));
        $pusher->trigger('mitra-channel', 'my-event', [
            'massage' => (Auth::user()->role_id == 0 ? 'Admin ' : 'Pegawai ') . Auth::user()->name .
                ' berhasil menambahkan mitra ' . $mitra->name,
            'user' => $generatePusher->generateDataPusher(Auth::user()),
            'id' => $mitra->id,
        ]);

        $mitra->save();

        return redirect('/mitra')->with('success', 'Mitra ' . $mitra->name . ' Berhasil Ditambahkan!');
    }

    public function show(Mitra $mitra)
    {
        return view('mitra.show', compact('mitra'));
    }

    public function edit($id)
    {
        $mitra = Mitra::where('id', $id)->first();
        if (!$mitra)
            return redirect()->route('mitra.')->with('error', 'Mitra Tidak Ditemukan');
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
        $mitra->update([
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
        $generatePusher = new pegawaiController();
        $pusher = new Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'), config('broadcasting.connections.pusher.options'));
        $pusher->trigger('mitra-channel', 'my-event', [
            'massage' => (Auth::user()->role_id == 0 ? 'Admin ' : 'Pegawai ') . Auth::user()->name .
                ' berhasil mengubah mitra ' . $oldNama,
            'user' => $generatePusher->generateDataPusher(Auth::user()),
            'id' => $mitra->id,
        ]);

        return redirect()->route('mitra.index')->with('success', 'Mitra ' . $oldNama . ' Terupdate!');
    }

    public function destroy(Mitra $mitra)
    {
        Storage::disk('s3')->delete('mitra/' . $mitra->images);
        $mitra->delete();
        $generatePusher = new pegawaiController();
        $pusher = new Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'), config('broadcasting.connections.pusher.options'));
        $pusher->trigger('mitra-channel', 'my-event', [
            'massage' => (Auth::user()->role_id == 0 ? 'Admin ' : 'Pegawai ') . Auth::user()->name .
                ' berhasil menghapus mitra ' . $mitra->name,
            'user' => $generatePusher->generateDataPusher(Auth::user()),
            'id' => $mitra->id,
        ]);
        return redirect('/mitra')->with('success', 'Mitra ' . $mitra->name . ' Terhapus!');
    }
}
