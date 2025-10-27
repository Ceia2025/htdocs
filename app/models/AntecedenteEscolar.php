<?php
require_once __DIR__ . '/../config/Connection.php';

class AntecedenteEscolar
{
    private $conn;
    private $table = "antecedente_escolar";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Obtener todos
    public function getAll()
    {
        $stmt = $this->conn->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    /*
        public function getByAlumnoId($alumnoId)
        {
            $sql = "SELECT * FROM {$this->table} WHERE alumno_id = :alumno_id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':alumno_id' => $alumnoId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }*/
    public function getByAlumnoId($alumno_id)
    {
        $sql = "SELECT * FROM antecedente_escolar WHERE alumno_id = :alumno_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumno_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo registro
    public function create($data)
    {
        $sql = "INSERT INTO antecedente_escolar (
                alumno_id,
                procedencia_colegio,
                comuna,
                ultimo_curso,
                ultimo_anio_cursado,
                cursos_repetidos,
                pertenece_20,
                informe_20,
                embarazo,
                semanas,
                info_salud,
                eva_psico,
                prob_apren,
                pie,
                chile_solidario,
                chile_solidario_cual,
                fonasa,
                grupo_fonasa,
                isapre,
                seguro_salud
            ) VALUES (
                :alumno_id,
                :procedencia_colegio,
                :comuna,
                :ultimo_curso,
                :ultimo_anio_cursado,
                :cursos_repetidos,
                :pertenece_20,
                :informe_20,
                :embarazo,
                :semanas,
                :info_salud,
                :eva_psico,
                :prob_apren,
                :pie,
                :chile_solidario,
                :chile_solidario_cual,
                :fonasa,
                :grupo_fonasa,
                :isapre,
                :seguro_salud
            )";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':alumno_id' => $data['alumno_id'],
            ':procedencia_colegio' => $data['procedencia_colegio'] ?? null,
            ':comuna' => $data['comuna'] ?? null,
            ':ultimo_curso' => $data['ultimo_curso'] ?? null,
            ':ultimo_anio_cursado' => $data['ultimo_anio_cursado'] ?? null,
            ':cursos_repetidos' => $data['cursos_repetidos'] ?? 0,
            ':pertenece_20' => $data['pertenece_20'] ?? 0,
            ':informe_20' => $data['informe_20'] ?? 0,
            ':embarazo' => $data['embarazo'] ?? 0,
            ':semanas' => $data['semanas'] ?? null,
            ':info_salud' => $data['info_salud'] ?? null,
            ':eva_psico' => $data['eva_psico'] ?? null,
            ':prob_apren' => $data['prob_apren'] ?? null,
            ':pie' => $data['pie'] ?? null,
            ':chile_solidario' => $data['chile_solidario'] ?? 0,
            ':chile_solidario_cual' => $data['chile_solidario_cual'] ?? null,
            ':fonasa' => $data['fonasa'] ?? null,
            ':grupo_fonasa' => $data['grupo_fonasa'] ?? null,
            ':isapre' => $data['isapre'] ?? null,
            ':seguro_salud' => $data['seguro_salud'] ?? null
        ]);
    }





    // Actualizar
    public function update($id, $data)
    {
        $sql = "UPDATE antecedente_escolar SET
                procedencia_colegio = :procedencia_colegio,
                comuna = :comuna,
                ultimo_curso = :ultimo_curso,
                ultimo_anio_cursado = :ultimo_anio_cursado,
                cursos_repetidos = :cursos_repetidos,
                pertenece_20 = :pertenece_20,
                informe_20 = :informe_20,
                embarazo = :embarazo,
                semanas = :semanas,
                info_salud = :info_salud,
                eva_psico = :eva_psico,
                prob_apren = :prob_apren,
                pie = :pie,
                chile_solidario = :chile_solidario,
                chile_solidario_cual = :chile_solidario_cual,
                fonasa = :fonasa,
                grupo_fonasa = :grupo_fonasa,
                isapre = :isapre,
                seguro_salud = :seguro_salud
            WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':procedencia_colegio' => $data['procedencia_colegio'] ?? null,
            ':comuna' => $data['comuna'] ?? null,
            ':ultimo_curso' => $data['ultimo_curso'] ?? null,
            ':ultimo_anio_cursado' => $data['ultimo_anio_cursado'] ?? null,
            ':cursos_repetidos' => $data['cursos_repetidos'] ?? 0,
            ':pertenece_20' => $data['pertenece_20'] ?? 0,
            ':informe_20' => $data['informe_20'] ?? 0,
            ':embarazo' => $data['embarazo'] ?? 0,
            ':semanas' => $data['semanas'] ?? null,
            ':info_salud' => $data['info_salud'] ?? null,
            ':eva_psico' => $data['eva_psico'] ?? null,
            ':prob_apren' => $data['prob_apren'] ?? null,
            ':pie' => $data['pie'] ?? null,
            ':chile_solidario' => $data['chile_solidario'] ?? 0,
            ':chile_solidario_cual' => $data['chile_solidario_cual'] ?? null,
            ':fonasa' => $data['fonasa'] ?? null,
            ':grupo_fonasa' => $data['grupo_fonasa'] ?? null,
            ':isapre' => $data['isapre'] ?? null,
            ':seguro_salud' => $data['seguro_salud'] ?? null
        ]);
    }


    // Eliminar
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
