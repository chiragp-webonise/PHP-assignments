<?php
class Database{
    private $host = "localhost";
    private $db_name = "shopping_cart";
    private $username = "postgres";
    private $password = "postgres";
    private static $conn;

    public function connect() {
        $pdo = new \PDO("pgsql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
    public static function get() {
        if (null === static::$conn) {
            static::$conn = new static();
        }
 
        return static::$conn;
    }
 
    protected function __construct() {
        
    }
 
    private function __clone() {
        
    }
 
    private function __wakeup() {
        
    }
 
}
?>