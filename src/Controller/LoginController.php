<?php
namespace App\Controller\Controller;

use App\Controller\Model\eRedirect;
use App\Controller\Model\User;
use App\Controller\Controller\UserController;

class LoginController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function showLoginForm()
    {
        // Render the login form using Twig
        $redirect = $_GET['redirect'] ?? '';
        if (!$redirect) {
            throw new \Exception('Redirect URL not found.');
        }

        $redirectLabel = (new RedirectController())->getByName($redirect)->label ?? 'Default';
        echo $this->twig->render('login.twig', ['redirect' => $redirect, 'redirectLabel' => $redirectLabel]);

    }

    private function redirect($redirect, $user)
    {
        switch ($redirect->id) {
            case eRedirect::LMS:
                $this->redirectToLMS($redirect, $user);
                break;
            default:
                $this->redirectToDefault($redirect);
                break;
        }
    }

    private function redirectToLMS($redirect, $user)
    {
        if (new \DateTime($user->lmsAccessExpiration) < new \DateTime()) {
            throw new \Exception('Your access to the LMS has expired.');
        }
        $config = require __DIR__ . '/../config.php';
        $url = $redirect->url . "?wstoken=" . $config['moodle_creds']['token'] . "&wsfunction=auth_userkey_request_login_url&moodlewsrestformat=json";
        $body = [
            'user' => [
                'email' => $user->email,
                'username' => $user->moodleLogin,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname
            ]
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        $resp = curl_exec($ch);
        if ($resp === false) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
        $resp = json_decode($resp);

        if ($resp && !empty($resp->loginurl)) {
            header('Location: ' . $resp->loginurl);
            exit;
        } else {
            throw new \Exception('Failed to get Moodle login URL.');
        }
    }

    private function redirectToDefault($redirect)
    {
        header('Location: ' . $redirect->url);
        exit;
    }

    public function login()
    {
        // Start session
        session_start();

        // Get POST data
        $email = htmlspecialchars($_POST['email']) ?? '';
        $password = htmlspecialchars($_POST['password']) ?? '';

        // Récupère l'argument redirect
        $redirect = htmlspecialchars($_POST['redirect']) ?? '';
        $redirectController = new RedirectController();
        $redirect = $redirectController->getByName($redirect);

        if (!$redirect) {
            throw new \Exception('Redirect URL not found.');
        }

        // Validate input
        if (empty($email) || empty($password)) {
            $this->showLoginFormWithError('Please enter email and password.');
            return;
        }

        // Authenticate user
        $userController = new UserController();
        $user = $userController->getByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            if ($redirect) {
                $this->redirect($redirect, $user);
            }
        } else {
            $this->showLoginFormWithError('Invalid email or password.');
        }
    }

    private function showLoginFormWithError($error)
    {
        echo $this->twig->render('login.twig', ['error' => $error]);
    }
}