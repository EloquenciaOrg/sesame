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
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="icon" type="image/png" href="images/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="text-center">
    <img src="images/logo.png" alt="logo" style="height:200px;">
</div>
<div class="container d-flex justify-content-center align-items-center mt-4">
    <div class="card shadow-lg border-0 rounded-4" style="max-width: 420px; width: 100%;">
        <div class="card-header bg-warning text-dark text-center rounded-top-4">
            <h3 class="mb-0 fw-semibold">Connexion</h3>
        </div>
        <div class="card-body p-4">
            <form method="POST" autocomplete="off">
                <div class="d-flex align-items-center p-2">
                    <div class="ml-2 p-2 rounded" style="background-color: #ffffef">
                        <h5>Vous allez vous connecter au service :</h5>
                        <p><b><?php echo $_GET['redirect']?></b></p>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control rounded-end " autofocus required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password"
                               class="form-control rounded-end" required>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning text-dark fw-bold rounded-pill">Se connecter</button>
<!--                    <a href="/password_forgot" class="text-primary text-decoration-none text-center">Mot de passe oublié ?</a>-->
                </div>
            </form>
        </div>
    </div>
<!--    <form method="post">-->
<!--        --><?php //echo "Redirection prévue vers : " . htmlspecialchars($redirect) . "<br>"; ?>
<!--        <label for="email">Email :</label>-->
<!--        <input type="email" name="email" id="email" required>-->
<!--        <br>-->
<!--        <label for="password">Mot de passe :</label>-->
<!--        <input type="password" name="password" id="password" required>-->
<!--        <br>-->
<!--        <input type="hidden" name="redirect" value="--><?php //echo htmlspecialchars($redirect); ?><!--">-->
<!--        <button type="submit">Se connecter</button>-->
<!--    </form>-->
<!--    --><?php //if ($result !== null): ?>
<!--        <p>--><?php //echo $result; ?><!--</p>-->
<!--    --><?php //endif; ?>
</body>
</html>