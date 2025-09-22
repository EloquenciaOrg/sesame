<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\LoginController;

// Configuration de Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/View/templates');
$twig = new \Twig\Environment($loader);


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
        echo $twig->render('home.twig');
        break;
    case '/login':
        $loginController = new LoginController($twig);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginController->login();
        } else {
            $loginController->showLoginForm();
        }
        break;
    default:
        http_response_code(404);
        echo $twig->render('404.twig');
        break;
}