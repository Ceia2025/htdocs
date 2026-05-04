<?php
require_once __DIR__ . '/../../config/Connection.php';
require_once __DIR__ . '/../../models/CursoDocente.php'; // ← AGREGAR

class AlertaAsistencia
{
    private $conn;

    // Roles que SIEMPRE reciben la alerta (independiente del curso)
    //private array $rolesDestinatarios = [0]; // ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL
    //private array $rolesDestinatarios = [1, 5]; // ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL
    private array $rolesDestinatarios = [5]; // ROL_ADMINISTRADOR, ROL_INSPECTOR_GENERAL

    private int $umbralAusencias = 3;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    /**
     * Emails de roles fijos (sin filtro de curso)
     */
    public function getEmailsDestinatarios(): array
    {
        $placeholders = implode(',', array_fill(0, count($this->rolesDestinatarios), '?'));
        $sql = "SELECT DISTINCT u.email, u.nombre, u.ape_paterno
                FROM usuarios2 u
                WHERE u.rol_id IN ($placeholders)
                  AND u.email IS NOT NULL
                  AND u.email != ''";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($this->rolesDestinatarios);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Emails de roles fijos + docente del curso específico.
     * USAR ESTE en el envío de alertas.
     */
    public function getEmailsDestinatariosDeCurso(int $cursoId, int $anioId): array
    {
        $destinatarios = $this->getEmailsDestinatarios();

        $docente = (new CursoDocente())->getDocenteDeCurso($cursoId, $anioId);

        if ($docente && !empty($docente['email'])) {
            $emailsYaIncluidos = array_column($destinatarios, 'email');
            if (!in_array($docente['email'], $emailsYaIncluidos)) {
                $destinatarios[] = [
                    'email' => $docente['email'],
                    'nombre' => $docente['nombre'],
                    'ape_paterno' => $docente['ape_paterno'],
                ];
            }
        }

        return $destinatarios;
    }

    /**
     * Detecta alumnos del curso con N o más ausencias consecutivas
     * contando desde la fecha dada hacia atrás.
     *
     * Retorna array de alumnos con ausencias consecutivas >= umbral.
     * Cada elemento: [matricula_id, nombre, apepat, apemat, curso, ausencias_consecutivas]
     */
    public function detectarAusenciasConsecutivas(int $cursoId, int $anioId, string $fechaBase): array
    {
        // Obtener TODAS las fechas con asistencia registrada para el curso
        // ordenadas descendente
        $sql = "SELECT DISTINCT a.fecha
            FROM   alum_asistencia2 a
            JOIN   matriculas2 m ON m.id = a.matricula_id
            WHERE  m.curso_id = :curso_id
              AND  m.anio_id  = :anio_id
              AND  a.fecha   <= :fecha_base
            ORDER  BY a.fecha DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':curso_id', $cursoId, PDO::PARAM_INT);
        $stmt->bindValue(':anio_id', $anioId, PDO::PARAM_INT);
        $stmt->bindValue(':fecha_base', $fechaBase, PDO::PARAM_STR);
        $stmt->execute();

        $todasFechas = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'fecha');

        if (count($todasFechas) < $this->umbralAusencias) {
            return [];
        }

        // Tomar solo las últimas N fechas y verificar que sean consecutivas
        // en el calendario (sin contar fines de semana)
        $ultimasN = array_slice($todasFechas, 0, $this->umbralAusencias);

        // Verificar que esas N fechas sean días laborales consecutivos
        if (!$this->sonDiasConsecutivos($ultimasN)) {
            return [];
        }

        // Buscar alumnos que faltaron en TODAS esas fechas
        $placeholders = implode(',', array_fill(0, count($ultimasN), '?'));

        $sql = "SELECT m.id AS matricula_id,
                   a2.nombre, a2.apepat, a2.apemat,
                   c.nombre AS curso,
                   COUNT(*) AS ausencias
            FROM   matriculas2 m
            JOIN   alumnos2          a2 ON a2.id = m.alumno_id
            JOIN   cursos2            c ON c.id  = m.curso_id
            JOIN   alum_asistencia2   a ON a.matricula_id = m.id
            WHERE  m.curso_id      = ?
              AND  m.anio_id       = ?
              AND  a2.deleted_at IS NULL
              AND  a.fecha IN ($placeholders)
              AND  a.presente      = 0
            GROUP  BY m.id, a2.nombre, a2.apepat, a2.apemat, c.nombre
            HAVING COUNT(*) >= ?";

        $params = array_merge(
            [$cursoId, $anioId],
            $ultimasN,
            [$this->umbralAusencias]
        );

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function sonDiasConsecutivos(array $fechasDesc): bool
    {
        // Necesitamos que cada fecha sea exactamente el día hábil anterior a la siguiente
        for ($i = 0; $i < count($fechasDesc) - 1; $i++) {
            $actual = new DateTime($fechasDesc[$i]);     // más reciente
            $anterior = new DateTime($fechasDesc[$i + 1]); // más antigua

            // Calcular cuál sería el día hábil anterior a $actual
            $diaHabilAnterior = clone $actual;
            $diaHabilAnterior->modify('-1 day');

            // Si es lunes, el día hábil anterior es el viernes
            while ($diaHabilAnterior->format('N') >= 6) { // 6=Sáb, 7=Dom
                $diaHabilAnterior->modify('-1 day');
            }

            if ($anterior->format('Y-m-d') !== $diaHabilAnterior->format('Y-m-d')) {
                return false; // hay un día hábil de por medio sin registrar
            }
        }

        return true;
    }

    public function getUmbral(): int
    {
        return $this->umbralAusencias;
    }
}