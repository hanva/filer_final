<?php

class DBManager
{
    private $pdo;

    private function __construct()
    {
        global $config;

        $address = $config['db']['host'];
        if ($config['db']['port']) {
            $address .= ':' . $config['db']['port'];
        }

        $dsn = 'mysql:dbname=' . $config['db']['name'] . ';host=' . $address;
        $user = $config['db']['user'];
        $password = $config['db']['password'];
        $pdo = new PDO($dsn, $user, $password);
        $this->setPdo($pdo);
    }

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DBManager();
        }
        return self::$instance;
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    private function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }
}
