<?php

namespace App\Mail;

use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    public function __construct($user,$token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        return $this->view('email.admin')
            ->with([
                'user',$this->user,
                'token'=>($this->token),
            ]);
    }
}
