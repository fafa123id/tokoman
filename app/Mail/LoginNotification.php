<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $sessionData;

    public function __construct($user, $sessionData)
    {
        $this->user = $user;
        $this->sessionData = $sessionData;
    }

    public function build()
    {
        return $this->view('email.index')
                    ->with([
                        'username' => $this->user->name,
                        'email'=> $this->user->email,
                        'sessionData' => $this->sessionData,
                        'token'=> $this->user->remember_token,
                    ]);
    }
}