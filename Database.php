<?php 

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $this->connection = new PDO("mysql:host=".self::host().";dbname=".self::dbname(), self::username(), self::password());
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    private static function dbname() {
        return 'ecf';
    }

    private static function host() {
        return 'localhost';
    }

    private static function username() {
        return 'root';
    }

    private static function password() {
        return '';
    }

    public function getConnection() {
        return $this->connection;
    }

    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

}
