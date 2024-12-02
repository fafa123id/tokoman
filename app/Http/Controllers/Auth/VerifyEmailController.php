<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\pegawaiController;
use App\Mail\newUserVerified;
use App\Mail\WelcomeUser;
use App\Models\User;
use Auth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Mail;
use Pusher\Pusher;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {

            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }
        if (Auth::check()) {
            Mail::to(Auth::user()->email)->send(new newUserVerified(Auth::user()));
            $pusher = new Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'), config('broadcasting.connections.pusher.options'));
            $pusher->trigger('admin-channel', 'my-event', [
                'massage' => 'User ' . Auth::user()->name . ' telah memverifikasi emailnya',
                'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
                'id' => Auth::user()->id,
            ]);
            $pegawaicontroll=new pegawaiController();
            $string=$pegawaicontroll->generateDataPusher(Auth::user());
            $pusher->trigger(preg_replace('/\s+/', '', $string), 'my-event', [
                'massage' => 'Kamu Telah Memverifikasi Email',
                'id' => Auth::user()->id,
            ]);
            
        }
        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
