<?php
namespace App\Controller\Controller;

use App\Controller\Model\Redirect;
use App\Controller\Database;

class RedirectController
{
    // Fetch all redirects
    public function getAll()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query('SELECT id, name, label, url, active FROM redirects');
        $redirects = [];
        while ($row = $stmt->fetch()) {
            $redirects[] = new Redirect($row['id'], $row['name'], $row['label'], $row['url'], $row['active']);
        }
        return $redirects;
    }

    // Fetch a redirect by ID
    public function getById($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT id, name, label, url, active FROM redirects WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if ($row) {
            return new Redirect($row['id'], $row['name'], $row['label'], $row['url'], $row['active']);
        }
        return null;
    }

    // Fetch a redirect by name
    public function getByName($name)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT id, name, label, url, active FROM redirects WHERE name = :name AND active = 1');
        $stmt->execute(['name' => $name]);
        $row = $stmt->fetch();
        if ($row) {
            return new Redirect($row['id'], $row['name'], $row['label'], $row['url'], $row['active']);
        }
        return null;
    }
}
