<?php
class RecordModel {
    private $conn;
    private $table_name = "records";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Verificar si ya existe por ID o RUT
    public function exists($id, $rut) {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE id = :id OR rut = :rut";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":rut", $rut);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Insertar un registro
    public function insert($id, $rut, $nombre, $desc, $fecha) {
        $query = "INSERT INTO " . $this->table_name . " (id, rut, nombre, descp, fecha) 
                  VALUES (:id, :rut, :nombre, :descp, :fecha)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":rut", $rut);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":descp", $desc);
        $stmt->bindParam(":fecha", $fecha);

        return $stmt->execute();
    }
}
