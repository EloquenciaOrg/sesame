<?php
namespace App\Model;

class Session
{
    public $id;

    public $user;
    public $email;
    public $expires_at; // TIMESTAMP
    public $ipAddress; // IP ADDRESS
    public $userAgent; 

    public function __construct($id, $email, $user, $expires_at, $ipAddress, $userAgent)
    {
        $this->id = $id;
        $this->email = $email;
        $this->user = $user;
        $this->expires_at = $expires_at;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    public function isExpired()
    {
        return strtotime($this->expires_at) < time();
    }

}
