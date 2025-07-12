<?php

class Rol {
    public $id;
    public $nombre;

    private $conn;
    private $table_name = "roles";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lee todos los roles de la base de datos.
     * @return Retorna el statement con los resultados.
     */
    public function readAll() {
        $query = "SELECT id, nombre FROM " . $this->table_name . " ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>