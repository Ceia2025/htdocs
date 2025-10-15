<?php
require_once __DIR__ . '/../config/Connection.php';

class AntecedenteFamiliar
{
    private $conn;
    private $table = "antecedentes_familiares";

    public function __construct() {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Obtener todos los registros
    public function getAll() {
        $sql = "SELECT af.id, af.alumno_id, a.nombre AS alumno_nombre,
                       af.padre, af.nivel_ciclo_p, af.madre, af.nivel_ciclo_m
                FROM {$this->table} af
                JOIN alumnos2 a ON af.alumno_id = a.id
                ORDER BY af.id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener por ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear
    public function create($alumno_id, $padre, $nivel_ciclo_p, $madre, $nivel_ciclo_m) {
        $sql = "INSERT INTO {$this->table} (alumno_id, padre, nivel_ciclo_p, madre, nivel_ciclo_m)
                VALUES (:alumno_id, :padre, :nivel_ciclo_p, :madre, :nivel_ciclo_m)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":alumno_id" => $alumno_id,
            ":padre" => $padre,
            ":nivel_ciclo_p" => $nivel_ciclo_p,
            ":madre" => $madre,
            ":nivel_ciclo_m" => $nivel_ciclo_m
        ]);
    }

    // Actualizar
    public function update($id, $alumno_id, $padre, $nivel_ciclo_p, $madre, $nivel_ciclo_m) {
        $sql = "UPDATE {$this->table}
                SET alumno_id = :alumno_id,
                    padre = :padre,
                    nivel_ciclo_p = :nivel_ciclo_p,
                    madre = :madre,
                    nivel_ciclo_m = :nivel_ciclo_m
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":alumno_id" => $alumno_id,
            ":padre" => $padre,
            ":nivel_ciclo_p" => $nivel_ciclo_p,
            ":madre" => $madre,
            ":nivel_ciclo_m" => $nivel_ciclo_m
        ]);
    }

    // Eliminar
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
