<?php
require_once __DIR__ . '/../config/Connection.php';

class ResumenAnamnesis
{
    private $conn;
    private $table = "resumen_anamnesis";
    private $tableIntegrantes = "resumen_anamnesis_integrantes";

    // Columnas editables desde el formulario (whitelist).
    // El entrevistador NO va aquí: se toma de la sesión (created_by / updated_by).
    public const CAMPOS = [
        'jornada', 'realizado_por', 'relacion',
        'apoderado_run', 'apoderado_domicilio', 'apoderado_edad', 'apoderado_estado_civil',
        'apoderado_nivel_educacional', 'apoderado_ocupacion', 'apoderado_telefono', 'apoderado_email',
        'total_integrantes',
        'sit_laboral_apoderado', 'sit_laboral_estudiante', 'observaciones',
        'motivo_retoma',
        'salud_problemas', 'salud_problemas_cuales', 'salud_tratamientos', 'salud_tratamientos_detalle',
        'consumo_sustancias', 'red_apoyo',
        'vivienda_tipo', 'vivienda_comentarios',
        'obs_entrevistador', 'fecha_entrevista',
    ];

    public const ENUMS = [
        'jornada'            => ['diurna', 'vespertina'],
        'consumo_sustancias' => ['si', 'no', 'no_sabe'],
        'vivienda_tipo'      => ['propia', 'arrendada', 'cedida_allegado'],
    ];

    private const ENTEROS   = ['apoderado_edad', 'total_integrantes'];
    private const BOOLEANOS = ['salud_problemas', 'salud_tratamientos'];

    // Largo máximo de columnas VARCHAR (evita errores en modo estricto)
    private const LARGOS = [
        'relacion'                    => 100,
        'apoderado_run'               => 20,
        'apoderado_domicilio'         => 255,
        'apoderado_estado_civil'      => 50,
        'apoderado_nivel_educacional' => 100,
        'apoderado_ocupacion'         => 150,
        'apoderado_telefono'          => 30,
        'apoderado_email'             => 150,
        'sit_laboral_apoderado'       => 255,
        'sit_laboral_estudiante'      => 255,
    ];

    private const MAX_INTEGRANTES = 20;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    /* ======================== LECTURA ======================== */

    /** SELECT base: anamnesis + año + entrevistador (nombre y rol) + último que modificó */
    private function selectBase(): string
    {
        return "SELECT r.*,
                       an.anio AS anio_escolar,
                       NULLIF(CONCAT_WS(' ', ue.nombre, ue.ape_paterno, ue.ape_materno), '') AS entrevistador_nombre,
                       re.nombre AS entrevistador_rol,
                       NULLIF(CONCAT_WS(' ', um.nombre, um.ape_paterno), '') AS modificado_por_nombre
                FROM {$this->table} r
                JOIN anios2 an      ON an.id = r.anio_id
                LEFT JOIN usuarios2 ue ON ue.id = r.created_by
                LEFT JOIN roles2 re    ON re.id = ue.rol_id
                LEFT JOIN usuarios2 um ON um.id = r.updated_by";
    }

    public function getByAlumnoYAnio($alumnoId, $anioId)
    {
        $sql = $this->selectBase() . "
                WHERE r.alumno_id = :alumno_id
                AND r.anio_id = :anio_id
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumnoId, ':anio_id' => $anioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Anamnesis + sus integrantes en la clave 'integrantes' */
    public function getCompletaByAlumnoYAnio($alumnoId, $anioId)
    {
        $anamnesis = $this->getByAlumnoYAnio($alumnoId, $anioId);
        if ($anamnesis) {
            $anamnesis['integrantes'] = $this->getIntegrantes((int) $anamnesis['id']);
        }
        return $anamnesis;
    }

    public function getAllByAlumno($alumnoId)
    {
        $sql = $this->selectBase() . "
                WHERE r.alumno_id = :alumno_id
                ORDER BY an.anio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumnoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIntegrantes(int $anamnesisId): array
    {
        $sql = "SELECT id, nombre, edad, parentesco, ocupacion, centro_salud
                FROM {$this->tableIntegrantes}
                WHERE anamnesis_id = :id
                ORDER BY orden, id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $anamnesisId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ======================== ESCRITURA ======================== */

    /**
     * Upsert por (alumno_id, anio_id).
     * - created_by (entrevistador): se fija al crear con el usuario logueado y no cambia al editar
     *   (si un registro antiguo lo tenía NULL, se completa con quien guarda).
     * - updated_by: siempre el usuario logueado.
     * $integrantes = null  → no toca la tabla de integrantes.
     * $integrantes = array → reemplaza los integrantes por los recibidos.
     * Devuelve el id de la anamnesis.
     */
    public function save(array $data, ?array $integrantes = null): int
    {
        $usuarioId = $data['usuario_id'] ?? $data['created_by'] ?? null;
        if (empty($usuarioId)) {
            throw new InvalidArgumentException('No se puede guardar la anamnesis sin un usuario autenticado.');
        }
        $usuarioId = (int) $usuarioId;

        $fila = $this->normalizar($data);

        $insertCols   = array_merge(['alumno_id', 'anio_id', 'created_by', 'updated_by'], self::CAMPOS);
        $placeholders = array_map(fn($c) => ":$c", $insertCols);

        $updates   = array_map(fn($c) => "`$c` = VALUES(`$c`)", self::CAMPOS);
        $updates[] = "`created_by` = COALESCE(`created_by`, VALUES(`created_by`))";
        $updates[] = "`updated_by` = VALUES(`updated_by`)";
        $updates[] = "`updated_at` = NOW()";
        $updates[] = "`id` = LAST_INSERT_ID(`id`)"; // para que lastInsertId() devuelva el id también al actualizar

        $sql = "INSERT INTO {$this->table} (`" . implode('`, `', $insertCols) . "`)
                VALUES (" . implode(', ', $placeholders) . ")
                ON DUPLICATE KEY UPDATE " . implode(', ', $updates);

        $params = [
            ':alumno_id'  => (int) $data['alumno_id'],
            ':anio_id'    => (int) $data['anio_id'],
            ':created_by' => $usuarioId,
            ':updated_by' => $usuarioId,
        ];
        foreach (self::CAMPOS as $c) {
            $params[":$c"] = $fila[$c];
        }

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $id = (int) $this->conn->lastInsertId();

            if ($integrantes !== null) {
                $this->guardarIntegrantes($id, $integrantes);
            }

            $this->conn->commit();
            return $id;
        } catch (Throwable $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    private function guardarIntegrantes(int $anamnesisId, array $integrantes): void
    {
        $this->conn
            ->prepare("DELETE FROM {$this->tableIntegrantes} WHERE anamnesis_id = :id")
            ->execute([':id' => $anamnesisId]);

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->tableIntegrantes}
                (anamnesis_id, nombre, edad, parentesco, ocupacion, centro_salud, orden)
             VALUES (:anamnesis_id, :nombre, :edad, :parentesco, :ocupacion, :centro_salud, :orden)"
        );

        $orden = 0;
        foreach (array_slice($integrantes, 0, self::MAX_INTEGRANTES) as $i) {
            $nombre = trim((string) ($i['nombre'] ?? ''));
            if ($nombre === '') {
                continue; // filas vacías del formulario se ignoran
            }
            $edad = filter_var($i['edad'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 120]]);

            $stmt->execute([
                ':anamnesis_id' => $anamnesisId,
                ':nombre'       => mb_substr($nombre, 0, 150),
                ':edad'         => $edad === false ? null : $edad,
                ':parentesco'   => $this->textoONull($i['parentesco'] ?? null, 80),
                ':ocupacion'    => $this->textoONull($i['ocupacion'] ?? null, 150),
                ':centro_salud' => $this->textoONull($i['centro_salud'] ?? null, 150),
                ':orden'        => $orden++,
            ]);
        }
    }

    /* ======================== HELPERS ======================== */

    private function normalizar(array $d): array
    {
        $out = [];
        foreach (self::CAMPOS as $c) {
            $v = $d[$c] ?? null;
            if (is_string($v)) {
                $v = trim($v);
            }
            if ($v === '') {
                $v = null;
            }

            if ($v !== null) {
                if (isset(self::ENUMS[$c])) {
                    $v = in_array($v, self::ENUMS[$c], true) ? $v : null;
                } elseif (in_array($c, self::ENTEROS, true)) {
                    $n = filter_var($v, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 255]]);
                    $v = $n === false ? null : $n;
                } elseif (in_array($c, self::BOOLEANOS, true)) {
                    $v = ((string) $v === '1') ? 1 : 0;
                } elseif ($c === 'fecha_entrevista') {
                    $f = DateTime::createFromFormat('Y-m-d', $v);
                    $v = ($f && $f->format('Y-m-d') === $v) ? $v : null;
                } elseif (isset(self::LARGOS[$c])) {
                    $v = mb_substr($v, 0, self::LARGOS[$c]);
                }
            }
            $out[$c] = $v;
        }

        // realizado_por es NOT NULL en la tabla
        $out['realizado_por'] = mb_substr((string) ($out['realizado_por'] ?? ''), 0, 150);

        // Si respondió "No", el detalle no aplica
        if ($out['salud_problemas'] === 0) {
            $out['salud_problemas_cuales'] = null;
        }
        if ($out['salud_tratamientos'] === 0) {
            $out['salud_tratamientos_detalle'] = null;
        }

        return $out;
    }

    private function textoONull($v, int $max): ?string
    {
        $v = trim((string) ($v ?? ''));
        return $v === '' ? null : mb_substr($v, 0, $max);
    }
}