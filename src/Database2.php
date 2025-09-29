<?php
namespace App;

class Database2
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $config = require __DIR__ . '/config.php';
        $dsn = "mysql:host={$config['db_users']['db_host']};dbname={$config['db_users']['db_name']};charset=utf8";
        $this->pdo = new \PDO($dsn, $config['db_users']['db_user'], $config['db_users']['db_pass']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
