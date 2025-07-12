<?php

class Area {
    public $id;
    public $nombre;

    private $conn;
    private $table_name = "areas";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lee todas las áreas de la base de datos.
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