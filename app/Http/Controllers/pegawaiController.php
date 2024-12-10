<?php

namespace App\Http\Controllers;

use App\Mail\userUpdation;
use App\Misc\MiscManager;
use App\Models\StokBarang;
use App\Repositories\RepositoryManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Mail;
use Pusher\Pusher;



class pegawaiController extends Controller
{
    private $pusher;
    private $repository;
    /**
     * Display the user's profile form.
     */
    public function __construct(MiscManager $miscFeature, RepositoryManager $repo){
        $this->pusher=$miscFeature->getMisc('pusher');
        $this->repository=$repo->getRepository('pegawai');
    }
    public function newToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'old' => 'required',
            'new' => 'required',
            'new2' => 'required|same:new'
        ], [
            'new2.same' => 'Token Baru Tidak Sama',
            'required' => 'Semua Field Wajib Diisi',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }
        $change = "";
        switch ($request->token) {
            case 0:
                $theToken = DB::table('token_register')->where('role_id', 0)->first();
                $change = "admin";
                break;
            case 1:
                $theToken = DB::table('token_register')->where('role_id', 1)->first();
                $change = "pegawai";
                break;
            default:
                break;
        }
        if (Hash::check($request->old, $theToken->token)) {
            DB::table('token_register')
            ->where('role_id', $request->token)
            ->update(['token' => Hash::make($request->new)]);
            return back()->with('success', 'Berhasil ubah token register untuk '.$change);
        } else {
            return back()->with('error', 'Token tidak sesuai');
        }
    }
    public function index()
    {
        $user = $this->repository->all()::orderBy('role_id', 'asc')->Paginate(10);

        return view("admin.index", ["users" => $user]);
    }
    public function add()
    {
        return view("admin.add");
    }
    public function addSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email|unique:users',
            'role_id' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ], [
            'password_confirmation.same' => 'Password tidak sesuai',
            'email.unique' => 'Email sudah ada',
            'required' => 'Semua Field Wajib Diisi',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/add')->with('error', $validator->errors()->first());
        }
        $paramInsert=[
            'name' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id
        ];
        $this->repository->insert($paramInsert);
        
        $this->pusher->doPush('admin-channel', [
            'massage' => 'User ' . $request->nama . ' Berhasil Ditambahkan ' . 'oleh Admin ' . Auth::user()->name,
            'user' => $this->generateDataPusher(Auth::user()),
            'id' => 'added',
            'excepturl'=>''
        ]);

        return redirect('/admin')->with('success', 'Data berhasil disimpan!');
    }
    public function edit($id)
    {
        $user = $this->repository->find($id);
        $userauth = Auth::user();
        if ($user) {
            if ($user->role_id == 0 && $userauth->id != $user->id && !$userauth->role_id == 2) {
                return redirect('/admin')->with('error', 'Anda tidak memiliki hak akses untuk mengedit data ini');
            } else {
                return view("admin.edit", ["user" => $user, "userauth" => $userauth]);
            }
        } else {
            return redirect("/admin")->with("error", "Data tidak ditemukan");
        }
    }
    public function editsave(Request $request, $id)
    {
        $userfirst = $this->repository->find($id);
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email',
            'role_id' => 'required',
            'password' => 'nullable',
            'password_confirmation' => 'same:password',
        ], [
            'password_confirmation.same' => 'Password tidak sesuai',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/edit/' . $id)->with('error', $validator->errors()->first());
        }
        $validatedData = $request->validate([
            'nama' => 'required',
            'email' => 'required',
            'password' => 'nullable',
            'password_confirmation' => 'nullable|same:password',
            'role_id' => 'nullable',
        ], [
            'password_confirmation.same' => 'Password tidak sesuai',
        ]);
        $emailold = $userfirst->email;
        $dataToUpdate = [
            'name' => $request->nama,
            'email' => $request->email,
            'updated_at' => now(),
        ];

        if ($request->filled('password') && $request->filled('password_confirmation')) {
            if ($request->password == $request->password_confirmation) {
                $dataToUpdate['password'] = bcrypt($validatedData['password']);
            }
        }
        if ($request->filled('role_id')) {
            $dataToUpdate['role_id'] = $request->role_id;
        }
        $userPush =$this->repository->find($id);
        $string = $userPush->name . $userPush->role_id . $userPush->id . ($userPush->id < 10 ? 'Asxzw' : 'asd2');
        if ($dataToUpdate['email'] != $emailold && $id != \Auth::user()->id) {
            $dataToUpdate['email_verified_at'] = null;
        }
        $this->repository->update($id,$dataToUpdate);
        if ($dataToUpdate['email'] != $emailold && $id != \Auth::user()->id) {
            $this->repository->find($id)->sendEmailVerificationNotification();
        }
        $this->pusher->doPush('admin-channel',[
            'massage' => 'User ' . $userfirst->name . ' Berhasil Diubah oleh Admin ' . Auth::user()->name,
            'user' => $this->generateDataPusher(Auth::user()),
            'id' => $id,
            'excepturl'=>''
        ]);

        if (Auth::user()->id != $userPush->id) {
            $changed = false;
            if ($request->filled('password') && $request->filled('password_confirmation')) {
                $changed = true;
            }
            Mail::to($userPush->email)->send(new userUpdation($userPush, $changed));
            $userPush->edited = "true";
            $userPush->save();
            $this->pusher->doPush(preg_replace('/\s+/', '', $string), [
                'massage' => 'Akun kamu telah diubah oleh admin, silahkan login ulang',
                'user' => $this->generateDataPusher(Auth::user()),
                'id' => \Auth::user()->id,
                'excepturl'=>''
            ]);
        }

        return redirect('/admin')->with('success', 'Data berhasil diubah!');
    }
    public function delete($id)
    {
        $userDelete = $this->repository->find($id);
        $authUser = $this->repository->find(Auth::user()->id);
        if ($userDelete->role_id == 0 && $authUser->id != $userDelete->id && !$authUser->role_id == 2) {
            return redirect('/admin')->with('error', 'Anda tidak memiliki hak akses untuk menghapus data ini');
        }
        if ($userDelete->id == null) {
            return redirect('/admin')->with('error', 'Data Tidak Ditemukan');
        }
        if ($userDelete->name == $authUser->name) {
            return redirect('/admin')->with('error', 'Hapus Akunmu Lewat Mekanisme Profil');
        }
        $this->repository->delete($id);
        $pusher = new Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'), config('broadcasting.connections.pusher.options'));
        $pusher->trigger('admin-channel', 'my-event', [
            'massage' => 'User ' . $userDelete->name . ' Berhasil Dihapus oleh Admin ' . Auth::user()->name,
            'user' => $this->generateDataPusher(Auth::user()),
            'id' => $id,
            'excepturl'=>''
        ]);
        if (Auth::user()->id != $userDelete->id) {
            $string = $this->generateDataPusher($userDelete);
            $userDelete->edited = 'true';
            $userDelete->save();
            $pusher->trigger(preg_replace('/\s+/', '', $string), 'my-event', [
                'massage' => 'Akun kamu telah didelete oleh admin, selamat tinggal! D:',
                'user'=> $this->generateDataPusher(Auth::user()),
                'id' => \Auth::user()->id,
                'excepturl'=>''
            ]);
        }
        DB::table('sessions')->where('user_id', $userDelete->id)->delete();
        return redirect('/admin')->with('success', 'Data berhasil dihapus!');
    }
    public function generateDataPusher($param)
    {
        if ($param)
            return $param->name . $param->role_id . $param->id . ($param->id < 10 ? 'Asxzw' : 'asd2');
    }
    public function getAuthID()
    {
        return response()->json(Auth::user()->id);
    }
}
