<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyAdmin;
use App\Models\unverifiedUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\tokenRegister;
use App\Rules\Recaptca;
use App\Rules\TokenHashCheck;
use Mail;
use Pusher\Pusher;
use Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => ['required', new Recaptca],
            'token' => ['required', new TokenHashCheck],
        ]);
        $tokenreq = $request->input('token');
        $tokenadmin = tokenRegister::where('role_id', 0)->first();
        $tokenemp = tokenRegister::where('role_id', 1)->first();
       
            if(Hash::check($tokenreq, $tokenadmin->token)) {
                $role_id = 0;
            }
            if(Hash::check($tokenreq, $tokenemp->token)) {
                $role_id = 1;
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $role_id,
            ]);
            event(new Registered($user));
            $credentials=[
                'email'=>$request->email,
                'password'=>$request->password,
            ];
            Auth::attempt($credentials);
            $this->makeVerify($user);
            
            if(Auth::check()){
                $pusher = new Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'), config('broadcasting.connections.pusher.options'));
                $pusher->trigger('admin-channel', 'my-event', [
                    'massage' => 'User ' . Auth::user()->name . ' telah berhasil mendaftarkan diri ke Tokoman App sebagai ' . (Auth::user()->role_id == 0 ? 'Admin' : 'Employee'),
                    'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
                    'id' => Auth::user()->id,
                ]);
            }
    
            return redirect(route('verification.notice', absolute: false));
    }
    public function makeVerify($param){
        $tokenForVerify =Str::random(60);
        unverifiedUser::create([
            'email'=> $param->email,
            'token'=> Hash::make($tokenForVerify),
            'id_user'=>$param->id,
        ]);
        Mail::to('tokomananekabotolplastik@gmail.com')->send(new VerifyAdmin($param, $tokenForVerify ));
    }
}
