<?php
namespace App\Controller\Controller;

use App\Controller\Model\User;
use App\Controller\Database;

class UserController
{
    // Fetch all users
    public function getAll()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query('SELECT id, email, moodle_login, firstname, lastname, password_hash FROM users');
        $users = [];
        while ($row = $stmt->fetch()) {
            $users[] = new User($row['id'], $row['email'], $row['moodleLogin'], $row['firstname'], $row['lastname'], $row['password_hash']);
        }
        return $users;
    }

    // Fetch a user by ID
    public function getById($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT id, email, moodle_login, firstname, lastname, password_hash FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if ($row) {
            return new User($row['id'], $row['email'], $row['moodle_login'], $row['firstname'], $row['lastname'], $row['password_hash']);
        }
        return null;
    }


    // Fetch user by email
    public function getByEmail($email)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT id, email, moodle_login, firstname, lastname, password_hash, moodle_login, newsletter, lms_access_expiration, registration_date, expiration_date FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        if ($row) {
            return new User($row['id'], $row['email'], $row['moodle_login'], $row['firstname'], $row['lastname'], $row['password_hash'], $row['moodle_login'], $row['newsletter'], $row['lms_access_expiration'], $row['registration_date'], $row['expiration_date']);
        }
        return null;
    }

    // Create a new user
    public function create($email, $firstname, $lastname, $password, $login, $newsletter, $lmsAccessExpiration, $registrationDate)
    {
        $db = Database::getInstance()->getConnection();
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $db->prepare('INSERT INTO users (email, firstname, lastname, password_hash, moodle_login, newsletter, lms_access_expiration, registration_date, expiration_date) VALUES (:email, :firstname, :lastname, :password_hash, :login, :newsletter, :lms_access_expiration, :registration_date, :expiration_date)');
        $stmt->execute([
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'password_hash' => $passwordHash,
            'moodle_login' => $login,
            'newsletter' => $newsletter,
            'lms_access_expiration' => $lmsAccessExpiration,
            'registration_date' => $registrationDate,
            'expiration_date' => (new \DateTime('now'))->modify('+1 year')->format('Y-m-d H:i:s')
        ]);
        return $db->lastInsertId();
    }
}
