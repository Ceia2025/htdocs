<?php
require_once __DIR__ . '/../config/Connection.php';

class Alumno
{
    private $conn;
    private $table = "alumnos2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Obtener todos los alumnos
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un alumno por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($term)
    {
        $sql = "SELECT * FROM {$this->table}
            WHERE LOWER(CONCAT(nombre, ' ', apepat, ' ', apemat)) LIKE :term
               OR run LIKE :term
            ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":term" => "%" . strtolower($term) . "%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchAlumnoEmergencia($term)
    {
        $sql = "SELECT id, nombre, apepat, apemat, run, codver
            FROM alumnos2
            WHERE nombre LIKE :term OR apepat LIKE :term OR apemat LIKE :term OR run LIKE :term
            ORDER BY nombre
            LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':term' => "%$term%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    // Crear un alumno
    public function create($data)
    {
        $data['fechanac'] = !empty($data['fechanac']) ? $data['fechanac'] : null;
        $data['deleted_at'] = $data['deleted_at'] ?? null;

        $numerohijos = isset($data['numerohijos']) && $data['numerohijos'] !== ''
            ? (int) $data['numerohijos']
            : null;

        $sql = "INSERT INTO {$this->table} 
            (run, codver, nombre, apepat, apemat, fechanac, mayoredad, numerohijos, telefono, email, sexo, nacionalidades, region, ciudad, direccion, cod_etnia, deleted_at) 
        VALUES 
            (:run, :codver, :nombre, :apepat, :apemat, :fechanac, :mayoredad, :numerohijos, :telefono, :email, :sexo, :nacionalidades, :region, :ciudad, :direccion, :cod_etnia, :deleted_at)";

        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute([
            ":run" => $data['run'],
            ":codver" => $data['codver'] ?? null,
            ":nombre" => $data['nombre'] ?? null,
            ":apepat" => $data['apepat'] ?? null,
            ":apemat" => $data['apemat'] ?? null,
            ":fechanac" => $data['fechanac'] ?? null,
            ":mayoredad" => $data['mayoredad'] ?? "No",
            ":numerohijos" => $numerohijos,
            ":telefono" => $data['telefono'] ?? null,
            ":email" => $data['email'] ?? null,
            ":sexo" => $data['sexo'] ?? null,
            ":nacionalidades" => $data['nacionalidades'] ?? null,
            ":region" => $data['region'] ?? null,
            ":ciudad" => $data['ciudad'] ?? null,
            ":direccion" => $data['direccion'] ?? null,
            ":cod_etnia" => $data['cod_etnia'] ?? 'No pertenece a ningún Pueblo Originario',
            ":deleted_at" => $data['deleted_at'] ?? null,
        ]);

        // ✅ Devolver ID del alumno insertado
        return $success ? $this->conn->lastInsertId() : false;
    }

    // Obtener alumno junto con su antecedente escolar
    public function getWithAntecedente($id)
    {
        $sql = "SELECT 
                a.*,
                ae.id AS antecedente_id,
                ae.procedencia_colegio,
                ae.comuna,
                ae.ultimo_curso,
                ae.ultimo_anio_cursado,
                ae.cursos_repetidos,
                ae.pertenece_20,
                ae.informe_20,
                ae.embarazo,
                ae.semanas,
                ae.info_salud,
                ae.eva_psico,
                ae.prob_apren,
                ae.pie,
                ae.chile_solidario,
                ae.chile_solidario_cual,
                ae.fonasa,
                ae.grupo_fonasa,
                ae.isapre,
                ae.seguro_salud
            FROM {$this->table} a
            LEFT JOIN antecedente_escolar ae ON a.id = ae.alumno_id
            WHERE a.id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    // Actualizar un alumno
    public function update($id, $data)
    {
        $data['fechanac'] = !empty($data['fechanac']) ? $data['fechanac'] : null;
        $data['deleted_at'] = $data['deleted_at'] ?? null;


        $numerohijos = !empty($data['numerohijos']) ? (int) $data['numerohijos'] : null;

        // Asegurar null si no viene en el formulario
        $data['deleted_at'] = $data['deleted_at'] ?? null;

        $sql = "UPDATE {$this->table} 
                SET run = :run, codver = :codver, nombre = :nombre, apepat = :apepat, apemat = :apemat,
                    fechanac = :fechanac, mayoredad = :mayoredad, numerohijos = :numerohijos,
                    telefono = :telefono, email = :email, sexo = :sexo,
                    nacionalidades = :nacionalidades, region = :region,
                    ciudad = :ciudad, direccion = :direccion, cod_etnia = :cod_etnia, deleted_at = :deleted_at
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":run" => $data['run'],
            ":codver" => $data['codver'],
            ":nombre" => $data['nombre'],
            ":apepat" => $data['apepat'],
            ":apemat" => $data['apemat'],
            ":fechanac" => $data['fechanac'],
            ":mayoredad" => $data['mayoredad'],
            ":numerohijos" => $numerohijos,
            ":telefono" => $data['telefono'],
            ":email" => $data['email'],
            ":sexo" => $data['sexo'],
            ":nacionalidades" => $data['nacionalidades'],
            ":region" => $data['region'],
            ":ciudad" => $data['ciudad'],
            ":direccion" => $data['direccion'] ?? null, // <-- NUEVO
            ":cod_etnia" => $data['cod_etnia'],
            ":deleted_at" => $data['deleted_at'],
        ]);
    }


    // 🔹 Marcar alumno como retirado (soft delete)
    public function markAsRetired($id, $fecha)
    {
        $sql = "UPDATE {$this->table} SET deleted_at = :fecha WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute([
            ':id' => $id,
            ':fecha' => $fecha
        ]);

        if (!$success) {
            error_log("❌ Error al actualizar deleted_at para ID: $id");
            error_log(print_r($stmt->errorInfo(), true));
        } else {
            error_log("✅ Se actualizó deleted_at correctamente para ID: $id");
        }

        return $success;
    }

    // 🔹 Reintegrar alumno (anular retiro)
    public function restore($id)
    {
        $sql = "UPDATE {$this->table} SET deleted_at = NULL WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }


    //Verificar existencia de rut
    public function runExists($run)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE run = :run";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':run' => $run]);
        return $stmt->fetchColumn() > 0;
    }

    public function existsByRun($run)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE run = :run";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':run' => $run]);
        return $stmt->fetchColumn() > 0;
    }

    // Eliminar un alumno
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
