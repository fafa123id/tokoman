<?php

namespace App\Http\Controllers;

use App\Http\Controllers\emailVerifyController as verify;
use App\Http\Requests\ProfileUpdateRequest;
use App\Mail\userDeletion;
use App\Mail\userUpdation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Pusher\Pusher;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $old = Auth::user()->name;
        $oldEmail = Auth::user()->email;
        $changed = false;
        $kirimemail = false;
        $request->user()->fill($request->validated());
        if (Auth::user()->role_id != 0) {
            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
                $kirimemail=true;
            }
        }
        $request->user()->save();

        Mail::to($oldEmail)->send(new userUpdation($request->user(), $changed));

        if(Auth::user()->role_id != 0) {
            if ($kirimemail==true) {
                Auth::user()->sendEmailVerificationNotification();
            }
        }
        $pusher = new Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'), config('broadcasting.connections.pusher.options'));
        $pusher->trigger('admin-channel', 'my-event', [
            'massage' => 'User ' . $old . ' Telah mengubah data diri',
            'user' => Auth::user()->name . Auth::user()->role_id . Auth::user()->id . (Auth::user()->id < 10 ? 'Asxzw' : 'asd2'),
            'id' => Auth::user()->id,
        ]);
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $pusher = new Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'), config('broadcasting.connections.pusher.options'));
        $pusher->trigger('admin-channel', 'my-event', [
            'massage' => 'User ' . $user->name . ' Telah Menghapus Akunnya',
            'user' => $user->name . $user->role_id . $user->id . ($user->id < 10 ? 'Asxzw' : 'asd2'),
            'id' => $user->id,
        ]);
        Mail::to($user->email)->send(new userDeletion($user));


        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
