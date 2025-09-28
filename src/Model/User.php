<?php
namespace App\Model;

use DateTime;

class User
{
    public $id;
    public $email;
    public $login;

    public $firstname;

    public $lastname;

    public $password; // HASHED PASSWORD

    public $isAdmin;

    public $newsletter;

    public $lmsAccessExpiration;

    public function __construct($id, $email, $login, $firstname, $lastname, $password, $isAdmin, $newsletter, $lmsAccessExpiration)
    {
        $this->id = $id;
        $this->email = $email;
        $this->login = $login;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->password = $password;
        $this->isAdmin = $isAdmin;
        $this->newsletter = $newsletter;
        $this->lmsAccessExpiration = $lmsAccessExpiration;
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function verifyLmsAccess()
    {
        if ($this->lmsAccessExpiration === null) {
            return true; // No expiration date means unlimited access
        }
        $currentDate = new DateTime();
        $expirationDate = new DateTime($this->lmsAccessExpiration);
        return $currentDate <= $expirationDate;
    }

}
