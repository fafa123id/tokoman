<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\pegawaiController;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Mail\userUpdation;
use App\Http\Controllers\emailVerifyController as verify;
use Illuminate\Support\Facades\Mail;
use Pusher\Pusher;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);
        $user = $request->user();
        $changed = true;
        Mail::to($user->email)->send(new userUpdation($user, $changed));

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);
        return back()->with('status', 'password-updated');
    }
}
