<?php
require_once __DIR__ . '/../config/Connection.php';

class Asistencia
{
    private $conn;
    private $table = "alum_asistencia2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    public function getFechasAnio($anioId)
    {
        $sql = "SELECT anio, sem1_inicio, sem1_fin, sem2_inicio, sem2_fin
            FROM anios2
            WHERE id = :id
            LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $anioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fechas que ya tienen asistencia registrada para un curso
    public function getFechasConAsistencia($cursoId, $anioId)
    {
        $sql = "SELECT DISTINCT a.fecha
            FROM alum_asistencia2 a
            JOIN matriculas2 m ON m.id = a.matricula_id
            WHERE m.curso_id = :curso_id
            AND m.anio_id = :anio_id
            ORDER BY a.fecha ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':curso_id' => $cursoId, ':anio_id' => $anioId]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'fecha');
    }

    /* ==========================================
       OBTENER ALUMNOS POR CURSO (PARA MASIVA)
    ========================================== */
    public function getAlumnosPorCurso($cursoId, $anioId)
    {
        $sql = "SELECT 
                m.id as matricula_id,
                m.fecha_matricula,
                m.fecha_retiro,
                m.numero_lista,
                a.nombre,
                a.apepat,
                a.apemat
            FROM matriculas2 m
            JOIN alumnos2 a ON a.id = m.alumno_id
            WHERE m.curso_id = :curso_id
            AND m.anio_id = :anio_id
            ORDER BY 
                CASE WHEN m.numero_lista IS NULL THEN 1 ELSE 0 END,
                m.numero_lista ASC,
                a.apepat ASC, 
                a.apemat ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /* ==========================================
       CURSOS CON MATRÍCULAS
    ========================================== */
    public function getCursosConMatricula($anioId)
    {
        $sql = "SELECT DISTINCT c.id, c.nombre
            FROM cursos2 c
            JOIN matriculas2 m ON m.curso_id = c.id
            WHERE m.anio_id = :anio_id
            ORDER BY c.nombre";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':anio_id' => $anioId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==========================================
       PORCENTAJE CURSO
    ========================================== */
    public function porcentajeCurso($cursoId, $anioId, $fechaInicio, $fechaFin)
    {
        $sql = "SELECT 
                    COUNT(*) as total_clases,
                    SUM(a.presente) as total_presentes
                FROM {$this->table} a
                JOIN matriculas2 m ON m.id = a.matricula_id
                WHERE m.curso_id = :curso_id
                AND m.anio_id = :anio_id
                AND a.fecha BETWEEN :inicio AND :fin";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId,
            ':inicio' => $fechaInicio,
            ':fin' => $fechaFin
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data || $data['total_clases'] == 0) {
            return 0;
        }

        return round(($data['total_presentes'] / $data['total_clases']) * 100, 2);
    }

    public function getAnioActualId()
    {
        $anioActual = (int) date("Y");

        $sql = "SELECT id FROM anios2 WHERE anio = :anio LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':anio' => $anioActual]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['id'] ?? null;
    }

    public function getAnios()
    {
        $sql = "SELECT id, anio 
            FROM anios2 
            WHERE anio >= 2025
            ORDER BY anio DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCurso($cursoId)
    {
        $sql = "SELECT id, nombre 
            FROM cursos2
            WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $cursoId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardarAsistencia($matriculaId, $fecha, $presente, $usuarioId = null)
    {
        $sql = "INSERT INTO alum_asistencia2 (matricula_id, fecha, presente, usuario_id, updated_at)
            VALUES (:matricula_id, :fecha, :presente, :usuario_id, NOW())
            ON DUPLICATE KEY UPDATE
                presente    = :presente,
                usuario_id  = :usuario_id,
                updated_at  = NOW()";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':matricula_id' => $matriculaId,
            ':fecha' => $fecha,
            ':presente' => $presente,
            ':usuario_id' => $usuarioId,
        ]);
    }


    // LIBRO DE CLASES DIGITAL
    public function getResumenAsistenciaCurso($cursoId, $anioId)
    {
        $sql = "
    SELECT 
        m.id as matricula_id,
        m.numero_lista,
        m.fecha_matricula,
        m.fecha_retiro,
        al.nombre,
        al.apepat,
        al.apemat,

        COUNT(a.id) as total_clases,

        SUM(CASE 
            WHEN a.presente = 1 THEN 1 
            ELSE 0 
        END) as presentes

    FROM matriculas2 m

    JOIN alumnos2 al
        ON al.id = m.alumno_id

    LEFT JOIN alum_asistencia2 a
        ON a.matricula_id = m.id
        AND (m.fecha_matricula IS NULL OR a.fecha >= m.fecha_matricula)

    WHERE m.curso_id = :curso_id
    AND m.anio_id = :anio_id
    

    GROUP BY m.id

    ORDER BY 
        CASE WHEN m.numero_lista IS NULL THEN 1 ELSE 0 END,
        m.numero_lista ASC,
        al.apepat ASC,
        al.apemat ASC
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAsistenciaLibro($cursoId, $anioId)
    {
        $sql = "SELECT
        a.matricula_id,
        a.fecha,
        a.presente
        FROM alum_asistencia2 a

        JOIN matriculas2 m
        ON m.id = a.matricula_id

        WHERE m.curso_id = :curso
        AND m.anio_id = :anio";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':curso' => $cursoId,
            ':anio' => $anioId
        ]);

        $data = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {

            $data[$row['matricula_id']][$row['fecha']] = $row['presente'];

        }

        return $data;

    }

    public function getDetalleAsistenciaPorAnio($anioId)
    {
        $sql = "SELECT 
                m.id as matricula_id,
                al.nombre,
                al.apepat,
                al.apemat,
                c.nombre as nombre_curso,
                a.fecha,
                a.presente
            FROM alum_asistencia2 a
            JOIN matriculas2 m ON m.id = a.matricula_id
            JOIN alumnos2 al ON al.id = m.alumno_id
            JOIN cursos2 c ON c.id = m.curso_id
            WHERE m.anio_id = :anio_id
            AND al.deleted_at IS NULL
            ORDER BY c.nombre ASC, al.apepat ASC, a.fecha ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':anio_id' => $anioId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // En Asistencia.php
    public function getAsistenciaPorFecha($cursoId, $anioId, $fecha)
    {
        $sql = "SELECT a.matricula_id, a.presente
            FROM alum_asistencia2 a
            JOIN matriculas2 m ON m.id = a.matricula_id
            WHERE m.curso_id = :curso_id
            AND m.anio_id = :anio_id
            AND a.fecha = :fecha";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId,
            ':fecha' => $fecha
        ]);

        // Retorna un mapa: matricula_id => presente (1 o 0)
        $resultado = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $resultado[$row['matricula_id']] = (int) $row['presente'];
        }
        return $resultado;
    }


    public function getUltimaFechaAsistenciaPorCurso(int $anioId): array
    {
        $sql = "SELECT 
                m.curso_id,
                MAX(a.fecha) as ultima_fecha
            FROM alum_asistencia2 a
            JOIN matriculas2 m ON m.id = a.matricula_id
            WHERE m.anio_id = :anio_id
            GROUP BY m.curso_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':anio_id' => $anioId]);

        // Retorna mapa: curso_id => ultima_fecha
        $resultado = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $resultado[$row['curso_id']] = $row['ultima_fecha'];
        }
        return $resultado;
    }


    public function getAsistenciaCalendarioAlumno(int $matriculaId): array
    {
        $sql = "SELECT 
                fecha,
                presente
            FROM alum_asistencia2
            WHERE matricula_id = :matricula_id
            ORDER BY fecha ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':matricula_id' => $matriculaId]);

        // Retorna mapa: fecha => presente (1 o 0)
        $resultado = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $resultado[$row['fecha']] = (int) $row['presente'];
        }
        return $resultado;
    }

    public function eliminarAsistenciaDia(int $cursoId, int $anioId, string $fecha): int
    {
        $sql = "DELETE a
            FROM alum_asistencia2 a
            JOIN matriculas2 m ON m.id = a.matricula_id
            WHERE m.curso_id = :curso_id
              AND m.anio_id  = :anio_id
              AND a.fecha    = :fecha";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId,
            ':fecha' => $fecha,
        ]);

        return $stmt->rowCount(); // filas eliminadas
    }

    public function getAuditoriaAsistencia(int $cursoId, int $anioId): array
    {
        $sql = "SELECT
                a.fecha,
                u.nombre        AS usuario_nombre,
                u.email         AS usuario_email,
                MAX(a.updated_at) AS updated_at
            FROM alum_asistencia2 a
            JOIN matriculas2 m  ON m.id = a.matricula_id
            LEFT JOIN usuarios2 u ON u.id = a.usuario_id
            WHERE m.curso_id = :curso_id
              AND m.anio_id  = :anio_id
              AND a.usuario_id IS NOT NULL
            GROUP BY a.fecha, a.usuario_id
            ORDER BY a.fecha DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId,
        ]);

        $resultado = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $resultado[$row['fecha']] = [
                'usuario' => $row['usuario_nombre'] ?? 'Desconocido',
                'email' => $row['usuario_email'] ?? '',
                'updated_at' => $row['updated_at'],
            ];
        }
        return $resultado;
    }

    /**
     * Proyección de asistencia de un alumno: cuánto puede llegar a subir o
     * bajar su % con los días hábiles que quedan, y si ya está matemáticamente
     * decidido (asegurado o imposible de recuperar).
     *
     * El denominador es (clases registradas del alumno + días hábiles restantes),
     * de modo que el % proyectado es coherente con el % "hasta la fecha".
     *
     * @param array       $alumno                Fila de getResumenAsistenciaCurso()
     *                                           (requiere: total_clases, presentes, fecha_matricula, fecha_retiro)
     * @param array       $fechasAnio            Resultado de getFechasAnio()
     * @param float       $umbral                % mínimo exigido
     * @param string|null $ultimaFechaRegistrada Última fecha con asistencia tomada en el curso (Y-m-d).
     *                                           Si es null se usa la fecha de hoy.
     */
    public function calcularProyeccion(
        array $alumno,
        array $fechasAnio,
        float $umbral = 85.0,
        ?string $ultimaFechaRegistrada = null
    ): array {
        $ultimaFecha = $ultimaFechaRegistrada ?: date('Y-m-d');

        $inicioAlumno = !empty($alumno['fecha_matricula']) && $alumno['fecha_matricula'] > $fechasAnio['sem1_inicio']
            ? $alumno['fecha_matricula']
            : $fechasAnio['sem1_inicio'];

        // Último día en que el alumno pudo tener asistencia registrada
        $finAlumno = $fechasAnio['sem2_fin'];
        if (!empty($alumno['fecha_retiro'])) {
            $ultimoDiaActivo = date('Y-m-d', strtotime($alumno['fecha_retiro'] . ' -1 day'));
            if ($ultimoDiaActivo < $finAlumno) {
                $finAlumno = $ultimoDiaActivo;
            }
        }

        // Días hábiles que aún faltan: desde el día siguiente a la última asistencia registrada
        $desde = date('Y-m-d', strtotime($ultimaFecha . ' +1 day'));
        if ($desde < $inicioAlumno) {
            $desde = $inicioAlumno;
        }
        $diasHabilesRestantes = $this->diasHabilesEnSemestres($desde, $finAlumno, $fechasAnio);

        $clasesRegistradas = (int) ($alumno['total_clases'] ?? 0);
        $presentes = (int) ($alumno['presentes'] ?? 0);
        $totalProyectado = $clasesRegistradas + $diasHabilesRestantes;

        $pctMinRaw = $totalProyectado > 0
            ? ($presentes / $totalProyectado) * 100
            : 0;

        $pctMaxRaw = $totalProyectado > 0
            ? (($presentes + $diasHabilesRestantes) / $totalProyectado) * 100
            : 0;

        // Cuántas faltas más puede tener sin caer bajo el umbral
        $maxFaltasPermitidas = (int) floor(
            round($presentes + $diasHabilesRestantes - ($umbral / 100) * $totalProyectado, 6)
        );

        if ($diasHabilesRestantes === 0) {
            $estado = $pctMinRaw >= $umbral ? 'aprobado' : 'repite';
        } elseif ($pctMinRaw >= $umbral) {
            $estado = 'asegurado';   // ya no importa lo que pase de aquí a fin de año
        } elseif ($pctMaxRaw < $umbral) {
            $estado = 'critico';     // aunque asista a todo lo que queda, no llega al mínimo
        } else {
            $estado = 'en_riesgo';   // depende de la asistencia futura
        }

        return [
            'dias_habiles_totales' => $totalProyectado,
            'dias_habiles_restantes' => $diasHabilesRestantes,
            'pct_max_posible' => round($pctMaxRaw, 1),
            'pct_min_garantizado' => round($pctMinRaw, 1),
            'max_faltas_permitidas' => max(0, $maxFaltasPermitidas),
            'estado' => $estado,
        ];
    }

    /**
     * Días hábiles (lun-vie) dentro de los rangos de semestre de anios2,
     * intersectados con [$inicio, $fin]. Excluye automáticamente el receso
     * de invierno porque ese tramo no cae en ningún semestre.
     */
    private function diasHabilesEnSemestres(string $inicio, string $fin, array $fechasAnio): int
    {
        if ($inicio > $fin)
            return 0;

        $total = $this->diasHabilesEntre(max($inicio, $fechasAnio['sem1_inicio']), min($fin, $fechasAnio['sem1_fin']));
        $total += $this->diasHabilesEntre(max($inicio, $fechasAnio['sem2_inicio']), min($fin, $fechasAnio['sem2_fin']));

        return $total;
    }

    private function diasHabilesEntre(string $inicio, string $fin): int
    {
        if ($inicio > $fin)
            return 0;

        $desde = new DateTime($inicio);
        $hasta = new DateTime($fin);

        $dias = 0;
        $periodo = new DatePeriod($desde, new DateInterval('P1D'), (clone $hasta)->modify('+1 day'));
        foreach ($periodo as $fecha) {
            if ((int) $fecha->format('N') <= 5) {
                $dias++;
            }
        }

        return $dias;
    }

}