<?php
namespace App\Controller;

use App\Model\User;
use App\Database;
use App\Database2;

class UserController
{
    // Fetch all users
    public function getAll()
    {
        $db = Database2::getInstance()->getConnection();
        $stmt = $db->query('SELECT id, email, moodle_login, firstname, name, password FROM members');
        $users = [];
        while ($row = $stmt->fetch()) {
            $users[] = new User($row['id'], $row['email'], $row['moodle_login'], $row['firstname'], $row['name'], $row['password']);
        }
        return $users;
    }

    // Fetch a user by ID
    public function getById($id)
    {
        $db = Database2::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT id, email, moodle_login, firstname, name, password FROM members WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if ($row) {
            return new User($row['id'], $row['email'], $row['moodle_login'], $row['firstname'], $row['name'], $row['password']);
        }
        return null;
    }


    // Fetch user by email
    public function getByEmail($email)
    {
        $db = Database2::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT id, email, moodle_login, firstname, name, password, moodle_login, newsletter, lmsAccessExpiration, registrationDate, expirationDate, isAdmin FROM members WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        if ($row) {
            return new User($row['id'], $row['email'], $row['moodle_login'], $row['firstname'], $row['name'], $row['password'], $row['isAdmin'], $row['newsletter'], $row['lmsAccessExpiration'], $row['registrationDate'], $row['expirationDate']);
        }
        return null;
    }
}
