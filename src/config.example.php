<?php
return [
    // Paramètres de connexion à la base de données
    'db' => [
        'host'     => 'mariadb',
        'dbname'   => 'sesame',
        'user'     => 'user',
        'password' => 'password',
        'charset'  => 'utf8mb4',
    ],

    'allowed_redirects' => [
        'lms' => 'https://lms.eloquencia.org/auth/userkey/login.php?key='
    ],

    // Mode debug (true pour afficher les erreurs, false pour les masquer)
    'debug' => true,

    // Mode maintenance (true pour activer la maintenance, false pour désactiver)
    'maintenance' => false,
];