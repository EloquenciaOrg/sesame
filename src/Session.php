<?php
class Session
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create($user_id, $email, $expires_at, $ip_address, $user_agent)
    {
        $id = bin2hex(random_bytes(32));
        $stmt = $this->db->prepare('INSERT INTO sessions (id, user_id, email, expires_at, ip_address, user_agent) VALUES (:id, :user_id, :email, :expires_at, :ip_address, :user_agent)');
        return $stmt->execute([
            'id' => $id,
            'user_id' => $user_id,
            'email' => $email,
            'expires_at' => $expires_at,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent
        ]) ? $id : false;
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM sessions WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM sessions WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function isValid($id)
    {
        $session = $this->findById($id);
        if ($session && strtotime($session['expires_at']) >= time()) {
            return $session['email'];
        }
        return false;
    }

    public function getUserBySessionId($sessionId)
    {
        $stmt = $this->db->prepare('SELECT u.* FROM users u INNER JOIN sessions s ON u.id = s.user_id WHERE s.id = :session_id LIMIT 1');
        $stmt->execute(['session_id' => $sessionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
