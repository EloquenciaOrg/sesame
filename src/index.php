<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Session.php';
$config = require __DIR__ . '/config.php';
$db = Database::getInstance($config)->getPdo();
$userModel = new User($db);
$sessionModel = new Session($db);

$result = null;
$redirect = $_GET['redirect'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = $userModel->findByEmail($email);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Création de la session valide 3h
        $expires_at = date('Y-m-d H:i:s', time() + 3 * 3600);
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $result = $sessionModel->create($user['id'], $user['email'], $expires_at, $ip_address, $user_agent);

        // Redirection si le paramètre existe dans POST
        if (!empty($_POST['redirect'])) {
            header('Location: ' . $_POST['redirect']);
            exit;
        }
    } else {
        $result = 'faux';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <form method="post">
        <?php echo "Redirection prévue vers : " . htmlspecialchars($redirect) . "<br>"; ?>
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
        <button type="submit">Se connecter</button>
    </form>
    <?php if ($result !== null): ?>
        <p><?php echo $result; ?></p>
    <?php endif; ?>
</body>
</html>