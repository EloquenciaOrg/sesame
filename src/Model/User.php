<?php
namespace App\Model;

class User
{
    public $id;
    public $email;
    public $login;

    public $firstname;

    public $lastname;

    public $password; // HASHED PASSWORD

    public function __construct($id, $email, $login, $firstname, $lastname, $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->login = $login;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->password = $password;
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

}
