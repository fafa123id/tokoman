<?php

namespace App\Mail;

use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class userDeletion extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $admin;
    public function __construct($user)
    {
        $this->user = $user;
        $this->admin = null;
    }

    public function build()
    {
        return $this->view('email.deleteUser')
            ->with([
                'user' => $this->user,
                'admin'=> $this->user->role_id==0&&Auth::user()->id!=$this->user->id?'admin':$this->admin,
            ]);
    }
}
