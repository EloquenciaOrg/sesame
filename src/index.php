<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Session.php';
require_once('curl.php');

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
            if (array_key_exists($_POST['redirect'], $config['allowed_redirects'])) {
                //make a web request to moodle API to validate key
                if ($_POST['redirect'] == "lms") {
                    try {
                        $body = [
                                'user' => [
//                            'firstname' => $user['firstname'],
//                            'lastname' => $user['lastname'],
                                        'email' => $user['email']
//                            'username' => "test"
                                ]
                        ];

                        $url = "https://lms.eloquencia.org/webservice/rest/server.php?wstoken=" . $config['moodle_creds']['token'] . "&wsfunction=auth_userkey_request_login_url" . "&moodlewsrestformat=json";
                        $curl = new curl;
                        try {
                            $resp     = $curl->post($url, $body);
                            $resp     = json_decode($resp);
                            if ($resp && !empty($resp->loginurl)) {
                                $loginurl = $resp->loginurl;
                                header('Location: ' . $loginurl);
                            } else {
                                echo "Erreur lors de la récupération de l'URL de connexion Moodle : ";
                                echo var_export($resp, true);
                            }
                        } catch (Exception $ex) {
                            return false;
                        }
                    } catch (Exception $e) {
                        echo "Erreur lors de la connexion à Moodle : " . $e->getMessage();
                        exit;
                    }
                }

            } else {
                echo "Redirection non autorisée.";
            }
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