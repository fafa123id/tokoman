<?php
namespace App\misc;
use Pusher\Pusher as instance;
class pusher{

    private $instancePusher;

    public function __construct($authkey,$secret,$appid,$opt){
        $this->instancePusher = new instance($authkey,$secret,$appid,$opt);
    }
    public function doPush($channel,$paramMassage){
        $this->instancePusher->trigger($channel, 'my-event', [
            'massage' => $paramMassage['massage'],
            'user' => $paramMassage['user'],
            'id' => $paramMassage['id'],
            'excepturl' => $paramMassage['excepturl']
        ]);
    } 
}