<?php

namespace App\Mail;

use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class userUpdation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $admin;
    public $change;
    public function __construct($user, $a)
    {
        $this->user = $user;
        $this->admin = null;
        if ((Auth::user()->role_id == 0 && Auth::user()->id != $this->user->id))
            $this->admin = "admin";
        $this->change = $a;
    }

    public function build()
    {
        return $this->view('email.updateUser')
            ->with([
                'user' => $this->user,
                'admin' => $this->admin,
                'change' => $this->change
            ]);
    }
}
