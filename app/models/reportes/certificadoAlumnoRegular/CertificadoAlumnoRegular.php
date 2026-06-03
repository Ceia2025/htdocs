<?php

require_once __DIR__ . '/../../../config/Connection.php';

class CertificadoAlumnoRegular
{
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    /**
     * Busca alumnos activos (con matrícula vigente) por nombre o RUN.
     * Retorna id, nombre completo, run, curso y año.
     */
    public function buscarAlumnos(string $term): array
    {
        $cleanRut = preg_replace('/[^0-9kK]/', '', $term);
        $words = array_filter(array_map('trim', explode(' ', $term)));

        $params = [];
        $nameConditions = [];

        foreach ($words as $i => $word) {
            $key = ":word{$i}";
            $params[$key] = "%$word%";
            $nameConditions[] = "(LOWER(a.nombre) LIKE LOWER($key)
                                   OR LOWER(a.apepat)  LIKE LOWER($key)
                                   OR LOWER(a.apemat)  LIKE LOWER($key))";
        }

        $nameWhere = !empty($nameConditions)
            ? implode(' AND ', $nameConditions)
            : '1=0';

        $rutWhere = '';
        $rutParams = [];
        if (!empty($cleanRut) && strlen($cleanRut) >= 2) {
            $rutWhere = "OR a.run LIKE :rut_raw
                                    OR REPLACE(REPLACE(a.run,'.',''),'-','') LIKE :rut_clean";
            $rutParams[':rut_raw'] = "%$term%";
            $rutParams[':rut_clean'] = "%$cleanRut%";
        }

        $sql = "
            SELECT
                a.id,
                CONCAT(a.nombre,' ',a.apepat,' ',IFNULL(a.apemat,'')) AS nombre_completo,
                a.run,
                a.codver,
                c.nombre  AS curso,
                an.anio   AS anio,
                m.id      AS matricula_id,
                an.id     AS anio_id
            FROM alumnos2 a
            INNER JOIN matriculas2 m  ON m.alumno_id = a.id
            INNER JOIN cursos2 c      ON c.id = m.curso_id
            INNER JOIN anios2 an      ON an.id = m.anio_id
            WHERE a.deleted_at IS NULL
              AND m.fecha_retiro IS NULL
              AND (
                    ($nameWhere)
                    $rutWhere
                  )
            ORDER BY a.apepat, a.apemat, a.nombre
            LIMIT 15
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array_merge($params, $rutParams));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Datos completos de un alumno para el certificado,
     * incluyendo porcentaje de asistencia del año en curso.
     */
    public function getDatosAlumno(int $alumno_id): ?array
    {
        // 1) Datos base del alumno + matrícula activa
        $sql = "
            SELECT
                a.id,
                a.run,
                a.codver,
                a.nombre,
                a.apepat,
                a.apemat,
                a.fechanac,
                a.sexo,
                c.nombre  AS curso,
                an.anio   AS anio,
                m.id      AS matricula_id,
                an.id     AS anio_id
            FROM alumnos2 a
            INNER JOIN matriculas2 m  ON m.alumno_id = a.id
            INNER JOIN cursos2 c      ON c.id = m.curso_id
            INNER JOIN anios2 an      ON an.id = m.anio_id
            WHERE a.id = :id
              AND a.deleted_at IS NULL
              AND m.fecha_retiro IS NULL
            ORDER BY an.anio DESC
            LIMIT 1
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $alumno_id]);
        $alumno = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$alumno)
            return null;

        // 2) Asistencia acumulada para esa matrícula
        $asistencia = $this->calcularAsistencia((int) $alumno['matricula_id']);

        return array_merge($alumno, $asistencia);
    }

    /**
     * Calcula % asistencia, días presentes y días totales
     * para una matrícula específica.
     */
    public function calcularAsistencia(int $matricula_id): array
    {
        $sql = "
            SELECT
                COUNT(*)                        AS total_dias,
                SUM(CASE WHEN presente = 1 THEN 1 ELSE 0 END) AS dias_presentes
            FROM alum_asistencia2
            WHERE matricula_id = :mid
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':mid' => $matricula_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $total = (int) ($row['total_dias'] ?? 0);
        $presente = (int) ($row['dias_presentes'] ?? 0);
        $pct = $total > 0 ? round(($presente / $total) * 100, 1) : 0;

        return [
            'total_dias' => $total,
            'dias_presentes' => $presente,
            'dias_ausentes' => $total - $presente,
            'porcentaje' => $pct,
        ];
    }

    /**
     * Asistencia mensual desglosada para el certificado con asistencia.
     */
    public function getAsistenciaMensual(int $matricula_id): array
    {
        $sql = "
            SELECT
                MONTH(fecha)  AS mes_num,
                MONTHNAME(fecha) AS mes_nombre,
                COUNT(*)      AS total,
                SUM(CASE WHEN presente = 1 THEN 1 ELSE 0 END) AS presentes
            FROM alum_asistencia2
            WHERE matricula_id = :mid
            GROUP BY MONTH(fecha), MONTHNAME(fecha)
            ORDER BY MONTH(fecha)
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':mid' => $matricula_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
