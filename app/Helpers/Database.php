<?php

namespace App\Helpers;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $config = require_once '../config/database.php';
        $default_connection = $config['default_connection'];

        $url = "$default_connection:host=" . $config[$default_connection]['host'] . ";dbname=" . $config[$default_connection]['database'];
        try {
            $this->pdo = new PDO($url, $config[$default_connection]['user'], $config[$default_connection]['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
