<?php
require_once __DIR__ . '/../config/db.php';

class Database {
    private $host = DB_SERVER;
    private $db_name = DB_NAME;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $port = DB_PORT;
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8mb4");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            error_log("Error de conexión a la base de datos: " . $exception->getMessage());
            die("Error de conexión a la base de datos. Por favor, intente más tarde.");
        }
        return $this->conn;
    }
}
?>