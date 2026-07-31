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

    // Normaliza el sexo a un valor válido del ENUM o null
    private function normalizarSexo($sexo)
    {
        $sexo = strtoupper(trim((string) $sexo));
        return in_array($sexo, ['F', 'M', 'X'], true) ? $sexo : null;
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

        // tiene_run: 1 = tiene RUN chileno, 0 = extranjero con documento libre
        $tieneRun = isset($data['tiene_run']) ? (int) $data['tiene_run'] : 1;

        $sql = "INSERT INTO {$this->table} 
            (run, codver, tiene_run, nombre, apepat, apemat, fechanac, mayoredad, numerohijos, telefono, email, sexo, nacionalidades, region, ciudad, direccion, cod_etnia, deleted_at) 
        VALUES 
            (:run, :codver, :tiene_run, :nombre, :apepat, :apemat, :fechanac, :mayoredad, :numerohijos, :telefono, :email, :sexo, :nacionalidades, :region, :ciudad, :direccion, :cod_etnia, :deleted_at)";

        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute([
            ":run" => $data['run'],
            ":codver" => $data['codver'] ?? null,
            ":tiene_run" => $tieneRun,
            ":nombre" => $data['nombre'] ?? null,
            ":apepat" => $data['apepat'] ?? null,
            ":apemat" => $data['apemat'] ?? null,
            ":fechanac" => $data['fechanac'] ?? null,
            ":mayoredad" => $data['mayoredad'] ?? "No",
            ":numerohijos" => $numerohijos,
            ":telefono" => $data['telefono'] ?? null,
            ":email" => $data['email'] ?? null,
            ":sexo" => $this->normalizarSexo($data['sexo'] ?? null),
            ":nacionalidades" => $data['nacionalidades'] ?? null,
            ":region" => $data['region'] ?? null,
            ":ciudad" => $data['ciudad'] ?? null,
            ":direccion" => $data['direccion'] ?? null,
            ":cod_etnia" => $data['cod_etnia'] ?? 'No pertenece a ningún Pueblo Originario',
            ":deleted_at" => $data['deleted_at'] ?? null,
        ]);

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

        $tieneRun = isset($data['tiene_run']) ? (int) $data['tiene_run'] : 1;

        $sql = "UPDATE {$this->table} 
                SET run = :run, codver = :codver, tiene_run = :tiene_run, nombre = :nombre, apepat = :apepat, apemat = :apemat,
                    fechanac = :fechanac, mayoredad = :mayoredad, numerohijos = :numerohijos,
                    telefono = :telefono, email = :email, sexo = :sexo,
                    nacionalidades = :nacionalidades, region = :region,
                    ciudad = :ciudad, direccion = :direccion, cod_etnia = :cod_etnia,
                    deleted_at = :deleted_at, created_at = :created_at
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":run" => $data['run'],
            ":codver" => $data['codver'],
            ":tiene_run" => $tieneRun,
            ":nombre" => $data['nombre'],
            ":apepat" => $data['apepat'],
            ":apemat" => $data['apemat'],
            ":fechanac" => $data['fechanac'],
            ":mayoredad" => $data['mayoredad'],
            ":numerohijos" => $numerohijos,
            ":telefono" => $data['telefono'],
            ":email" => $data['email'],
            ":sexo" => $this->normalizarSexo($data['sexo'] ?? null),
            ":nacionalidades" => $data['nacionalidades'],
            ":region" => $data['region'],
            ":ciudad" => $data['ciudad'],
            ":direccion" => $data['direccion'] ?? null,
            ":cod_etnia" => $data['cod_etnia'],
            ":deleted_at" => $data['deleted_at'],
            ":created_at" => !empty($data['created_at']) ? $data['created_at'] : null,
        ]);
    }

    // Marcar alumno como retirado (soft delete)
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

    // Reintegrar alumno (anular retiro)
    public function restore($id)
    {
        $sql = "UPDATE {$this->table} SET deleted_at = NULL WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Verificar existencia de RUN / documento (único método de verdad)
    public function runExists($run)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE run = :run";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':run' => $run]);
        return $stmt->fetchColumn() > 0;
    }

    // Alias mantenido por compatibilidad (usado en storeStepper)
    public function existsByRun($run)
    {
        return $this->runExists($run);
    }

    // Búsqueda para crear matrículas: incluye retirados, no eliminados del sistema
    public function searchForMatricula($term)
    {
        $cleanRut = preg_replace('/[^0-9kK]/', '', $term);
        $words = array_filter(array_map('trim', explode(' ', $term)));

        $params = [];
        $nameConditions = [];

        foreach ($words as $i => $word) {
            $key = ":word{$i}";
            $params[$key] = "%$word%";
            $nameConditions[] = "(
            LOWER(nombre) LIKE LOWER($key) OR 
            LOWER(apepat) LIKE LOWER($key) OR 
            LOWER(apemat) LIKE LOWER($key)
        )";
        }

        $nameWhere = !empty($nameConditions)
            ? implode(' AND ', $nameConditions)
            : '1=0';

        $rutWhere = '';
        $rutParams = [];
        if (!empty($cleanRut) && strlen($cleanRut) >= 2) {
            $rutWhere = "OR run LIKE :rut_raw OR REPLACE(REPLACE(run, '.', ''), '-', '') LIKE :rut_clean";
            $rutParams[':rut_raw'] = "%$term%";
            $rutParams[':rut_clean'] = "%$cleanRut%";
        }

        $sql = "
    SELECT id, nombre, apepat, apemat, run, codver, deleted_at
    FROM alumnos2
    WHERE (
        ($nameWhere)
        $rutWhere
    )
    ORDER BY apepat, apemat, nombre
    LIMIT 10
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array_merge($params, $rutParams));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Búsqueda para autocomplete: solo alumnos activos
    public function searchForAutocomplete($term)
    {
        $cleanRut = preg_replace('/[^0-9kK]/', '', $term);
        $words = array_filter(array_map('trim', explode(' ', $term)));

        $params = [];
        $nameConditions = [];

        foreach ($words as $i => $word) {
            $key = ":word{$i}";
            $params[$key] = "%$word%";
            $nameConditions[] = "(
            LOWER(nombre) LIKE LOWER($key) OR 
            LOWER(apepat) LIKE LOWER($key) OR 
            LOWER(apemat) LIKE LOWER($key)
        )";
        }

        $nameWhere = !empty($nameConditions)
            ? implode(' AND ', $nameConditions)
            : '1=0';

        $rutWhere = '';
        $rutParams = [];
        if (!empty($cleanRut) && strlen($cleanRut) >= 2) {
            $rutWhere = "OR run LIKE :rut_raw OR REPLACE(REPLACE(run, '.', ''), '-', '') LIKE :rut_clean";
            $rutParams[':rut_raw'] = "%$term%";
            $rutParams[':rut_clean'] = "%$cleanRut%";
        }

        $sql = "
    SELECT id, nombre, apepat, apemat, run, codver
    FROM alumnos2
    WHERE deleted_at IS NULL
    AND (
        ($nameWhere)
        $rutWhere
    )
    ORDER BY apepat, apemat, nombre
    LIMIT 10
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array_merge($params, $rutParams));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener años que tienen matrículas registradas
    public function getAniosConMatriculas()
    {
        $sql = "SELECT DISTINCT an.id, an.anio, an.descripcion
            FROM anios2 an
            INNER JOIN matriculas2 m ON m.anio_id = an.id
            ORDER BY an.anio DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener alumnos de un año con su curso
    public function getByAnioConCurso($anio_id)
    {
        $sql = "SELECT 
                a.id, a.run, a.codver, a.nombre, a.apepat, a.apemat,
                a.fechanac, a.sexo, a.email, a.telefono,
                a.created_at, a.deleted_at,
                c.id AS curso_id,
                c.nombre AS curso_nombre
            FROM alumnos2 a
            INNER JOIN matriculas2 m ON m.alumno_id = a.id
            INNER JOIN cursos2 c    ON c.id = m.curso_id
            WHERE m.anio_id = :anio_id
            ORDER BY c.nombre ASC, a.apepat ASC, a.apemat ASC, a.nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':anio_id' => $anio_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar un alumno
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}