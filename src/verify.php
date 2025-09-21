<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/Session.php';
$config = require __DIR__ . '/config.php';
$db = Database::getInstance($config)->getPdo();
$sessionModel = new Session($db);

// content is json
header('Content-Type: application/json');

// Route /verify/{id}
if (preg_match('#^/verify/([a-f0-9]{64})$#', $_SERVER['REQUEST_URI'], $matches)) {
    $id = $matches[1];
    $result = $sessionModel->isValid($id);
    if ($result) {
        // Supposons que la méthode getUserBySessionId existe et retourne l'utilisateur lié à la session
        $user = $sessionModel->getUserBySessionId($id);
        echo json_encode([
            'valid' => true,
            'email' => $user['email'] ?? null
        ]);
    } else {
        echo json_encode(['valid' => false]);
    }
    exit;
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
    exit;
}
