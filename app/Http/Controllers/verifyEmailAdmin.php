<?php

namespace App\Http\Controllers;

use App\Mail\newUserVerified;
use App\Models\unverifiedUser;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Pusher\Pusher;

class verifyEmailAdmin extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $forchange = false;
        $emailuser = '';
        $unverif = unverifiedUser::all();
        if ($unverif[0]??false) {
            foreach ($unverif as $unverifiedUser) {
                if (\Hash::check($id, $unverifiedUser->token)) {
                    $forchange = true;
                    $emailuser = $unverifiedUser->email;
                }
            }
            if ($forchange == true) {
                $unverifnew = unverifiedUser::where("email", $emailuser)->first();
                $unverifnew->delete();
                $name = User::where("email", $emailuser)->first();
                $name->adminVerified = now();
                $name->save();
                $name->sendEmailVerificationNotification();
                $pusher = new Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'), config('broadcasting.connections.pusher.options'));
                $pusher->trigger('admin-channel', 'my-event', [
                    'massage' => 'User ' . $name->name . ' telah diverifikasi Admin',
                    'user' => 'special',
                    'id' => 'special',
                ]);
                $pegawaicontroll = new pegawaiController();
                $string = $pegawaicontroll->generateDataPusher($name);
                $pusher->trigger(preg_replace('/\s+/', '', $string), 'my-event', [
                    'massage' => 'Kamu Telah Diverifikasi Oleh Admin',
                    'id' => $name->id,
                ]);

                return response()->json('Action Successfull');
            }
            return abort(403);
        }
        return abort(401);
    }
}
