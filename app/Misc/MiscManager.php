<?php
namespace App\Misc;

class MiscManager{
    protected $additionalFeatures=[];
    public function __construct(){
        $this->additionalFeatures = [
            'pusher' => new pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'), config('broadcasting.connections.pusher.options')),
            'imageManager' => new imageManager()
        ];
    }
    public function getMisc($name){
        if (!isset($this->additionalFeatures[$name])) {
            throw new \Exception("Feature {$name} not found.");
        }
        return $this->additionalFeatures[$name];
    }
}