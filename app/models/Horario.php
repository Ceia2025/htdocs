<?php
require_once __DIR__ . '/../config/Connection.php';

class Horario
{
    private $conn;
    private $table = "horarios2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    public function getByPca($pca_id)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE pca_id = :pca_id
                ORDER BY FIELD(dia_semana,'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'),
                         hora_inicio";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':pca_id' => $pca_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (pca_id, dia_semana, hora_inicio, hora_fin, sala)
                VALUES
                (:pca_id, :dia_semana, :hora_inicio, :hora_fin, :sala)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':pca_id'     => $data['pca_id'],
            ':dia_semana' => $data['dia_semana'],
            ':hora_inicio'=> $data['hora_inicio'],
            ':hora_fin'   => $data['hora_fin'],
            ':sala'       => $data['sala'] ?? null,
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
