<?php
namespace App\Controller\Controller;

use App\Controller\Model\Session;
use App\Controller\Database;

class SessionController
{
    // Fetch all sessions
    public function getAll()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query('SELECT id, user_id, email, expires_at, ip_address, user_agent FROM sessions');
        $sessions = [];
        while ($row = $stmt->fetch()) {
            $sessions[] = new Session($row['id'], $row['user_id'], $row['email'], $row['expires_at'], $row['ip_address'], $row['user_agent']);
        }
        return $sessions;
    }

    // Fetch a session by ID
    public function getById($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT id, user_id, email, expires_at, ip_address, user_agent FROM sessions WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if ($row) {
            return new Session($row['id'], $row['user_id'], $row['email'], $row['expires_at'], $row['ip_address'], $row['user_agent']);
        }
        return null;
    }


    // Fetch session by email
    public function getByEmail($email)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT id, user_id, email, expires_at, ip_address, user_agent FROM sessions WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        if ($row) {
            return new Session($row['id'], $row['user_id'], $row['email'], $row['expires_at'], $row['ip_address'], $row['user_agent']);
        }
        return null;
    }
}
