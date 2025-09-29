<?php
namespace App\Controller\Model;

use DateTime;

class User
{
    public $id;
    public $email;
    public $moodleLogin;

    public $firstname;

    public $lastname;

    public $password; // HASHED PASSWORD

    public $isAdmin;

    public $newsletter;

    public $lmsAccessExpiration;

    public $registrationDate;

    public $expirationDate; // Calculated as registrationDate + 1 year

    public function __construct($id, $email, $moodleLogin, $firstname, $lastname, $password, $isAdmin, $newsletter, $lmsAccessExpiration, $registrationDate, $expirationDate)
    {
        $this->id = $id;
        $this->email = $email;
        $this->moodleLogin = $moodleLogin;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->password = $password;
        $this->isAdmin = $isAdmin;
        $this->newsletter = $newsletter;
        $this->lmsAccessExpiration = $lmsAccessExpiration;
        $this->registrationDate = $registrationDate;
        $this->expirationDate = $expirationDate;
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
