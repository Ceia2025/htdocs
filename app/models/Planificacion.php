<?php
require_once __DIR__ . '/../config/Connection.php';

class Planificacion
{
    public const TOTAL_MODULOS = 6;
    public const MODULOS_OBLIGATORIOS = 4;

    private PDO $db;

    public function __construct()
    {
        $conexion = new Connection();
        $this->db = $conexion->open();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /* ---------- Años (tabla maestra) ---------- */

    public function anios(): array
    {
        $st = $this->db->query(
            "SELECT id, anio, descripcion, sem1_inicio, sem1_fin, sem2_inicio, sem2_fin
             FROM anios2
             ORDER BY anio DESC"
        );
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function anioPorId(int $id): ?array
    {
        $st = $this->db->prepare(
            "SELECT id, anio, descripcion, sem1_inicio, sem1_fin, sem2_inicio, sem2_fin
             FROM anios2
             WHERE id = :id"
        );
        $st->execute([':id' => $id]);
        $fila = $st->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    public function anioPorDefecto(): ?int
    {
        $id = $this->db->query("SELECT id FROM anios2 WHERE anio = YEAR(CURDATE()) LIMIT 1")->fetchColumn();
        if (!$id) {
            $id = $this->db->query("SELECT id FROM anios2 ORDER BY anio DESC LIMIT 1")->fetchColumn();
        }
        return $id ? (int) $id : null;
    }

    public function semanasDelAnio(array $anio): ?array
    {
        $s1 = $this->semanasEntre($anio['sem1_inicio'] ?? null, $anio['sem1_fin'] ?? null);
        $s2 = $this->semanasEntre($anio['sem2_inicio'] ?? null, $anio['sem2_fin'] ?? null);
        if ($s1 === null && $s2 === null) {
            return null;
        }
        return ['s1' => $s1 ?? 0, 's2' => $s2 ?? 0, 'total' => ($s1 ?? 0) + ($s2 ?? 0)];
    }

    private function semanasEntre(?string $inicio, ?string $fin): ?int
    {
        if (!$inicio || !$fin) {
            return null;
        }
        $d1 = new DateTimeImmutable($inicio);
        $d2 = new DateTimeImmutable($fin);
        if ($d2 < $d1) {
            return null;
        }
        return (int) ceil(($d1->diff($d2)->days + 1) / 7);
    }

    /* ---------- Consultas ---------- */

    public function listar(int $anioId, ?int $usuarioId): array
    {
        $sql = "SELECT p.id, p.catalogo_id, p.avance, p.updated_at, p.usuario_id,
                       ca.id AS curso_asignatura_id,
                       c.nombre AS curso,
                       a.nombre AS asignatura,
                       CONCAT_WS(' ', u.nombre, u.ape_paterno, u.ape_materno) AS docente
                FROM planificaciones2 p
                JOIN usuarios2 u ON u.id = p.usuario_id
                JOIN curso_asignaturas2 ca ON ca.id = p.curso_asignatura_id
                JOIN cursos2 c ON c.id = ca.curso_id
                JOIN asignaturas2 a ON a.id = ca.asignatura_id
                WHERE p.anio_id = :anio";
        $params = [':anio' => $anioId];
        if ($usuarioId !== null) {
            $sql .= " AND p.usuario_id = :usuario";
            $params[':usuario'] = $usuarioId;
        }
        $sql .= " ORDER BY docente, c.nombre, a.nombre";

        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function docentesConPlanificaciones(int $anioId): array
    {
        $st = $this->db->prepare(
            "SELECT DISTINCT u.id, CONCAT_WS(' ', u.nombre, u.ape_paterno, u.ape_materno) AS nombre
             FROM planificaciones2 p
             JOIN usuarios2 u ON u.id = p.usuario_id
             WHERE p.anio_id = :anio
             ORDER BY nombre"
        );
        $st->execute([':anio' => $anioId]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cursoAsignaturas(int $anioId, int $usuarioId): array
    {
        $st = $this->db->prepare(
            "SELECT ca.id, c.nombre AS curso, a.nombre AS asignatura,
                    (p.id IS NOT NULL) AS planificada
             FROM curso_asignaturas2 ca
             JOIN cursos2 c ON c.id = ca.curso_id
             JOIN asignaturas2 a ON a.id = ca.asignatura_id
             LEFT JOIN planificaciones2 p
                    ON p.curso_asignatura_id = ca.id
                   AND p.anio_id = :anio_plan
                   AND p.usuario_id = :usuario
             ORDER BY c.nombre, a.nombre"
        );
        $st->execute([':anio_plan' => $anioId, ':usuario' => $usuarioId]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener(int $id, ?int $usuarioId): ?array
    {
        $sql = "SELECT id, anio_id, usuario_id, curso_asignatura_id, catalogo_id, avance
                FROM planificaciones2
                WHERE id = :id";
        $params = [':id' => $id];
        if ($usuarioId !== null) {
            $sql .= " AND usuario_id = :usuario";
            $params[':usuario'] = $usuarioId;
        }
        $st = $this->db->prepare($sql);
        $st->execute($params);
        $fila = $st->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    private function cursoAsignaturaLibre(int $caId, int $anioId, int $usuarioId): bool
    {
        $st = $this->db->prepare(
            "SELECT 1
             FROM curso_asignaturas2 ca
             WHERE ca.id = :ca
               AND NOT EXISTS (
                   SELECT 1 FROM planificaciones2 p
                   WHERE p.curso_asignatura_id = ca.id
                     AND p.anio_id = :anio_plan
                     AND p.usuario_id = :usuario
               )"
        );
        $st->execute([
            ':ca'        => $caId,
            ':anio_plan' => $anioId,
            ':usuario'   => $usuarioId,
        ]);
        return (bool) $st->fetchColumn();
    }

    /* ---------- Escritura ---------- */

    public function crear(int $anioId, int $usuarioId, int $cursoAsignaturaId, string $catalogoId): int
    {
        if (!$this->cursoAsignaturaLibre($cursoAsignaturaId, $anioId, $usuarioId)) {
            throw new RuntimeException('Ese curso y asignatura no está disponible o ya tiene una planificación tuya.');
        }

        $this->db->beginTransaction();
        try {
            $st = $this->db->prepare(
                "INSERT INTO planificaciones2 (anio_id, usuario_id, curso_asignatura_id, catalogo_id)
                 VALUES (:anio, :usuario, :ca, :catalogo)"
            );
            $st->execute([
                ':anio'     => $anioId,
                ':usuario'  => $usuarioId,
                ':ca'       => $cursoAsignaturaId,
                ':catalogo' => $catalogoId,
            ]);
            $id = (int) $this->db->lastInsertId();
            $this->sembrarModulos($id);
            $this->db->commit();
            return $id;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    private function sembrarModulos(int $planificacionId): void
    {
        $st = $this->db->prepare(
            "INSERT INTO planificacion_modulos2 (planificacion_id, numero, activo)
             VALUES (:plan, :numero, :activo)"
        );
        for ($n = 0; $n < self::TOTAL_MODULOS; $n++) {
            $st->execute([
                ':plan'   => $planificacionId,
                ':numero' => $n,
                ':activo' => $n < self::MODULOS_OBLIGATORIOS ? 1 : 0,
            ]);
        }
    }

    public function duplicar(int $id, ?int $alcanceUsuarioId, int $nuevoCursoAsignaturaId): int
    {
        $origen = $this->obtener($id, $alcanceUsuarioId);
        if (!$origen) {
            throw new RuntimeException('La planificación no existe o no te pertenece.');
        }
        $anioId = (int) $origen['anio_id'];
        $usuarioId = (int) $origen['usuario_id'];
        if (!$this->cursoAsignaturaLibre($nuevoCursoAsignaturaId, $anioId, $usuarioId)) {
            throw new RuntimeException('El curso elegido no está disponible o ya tiene una planificación tuya.');
        }

        $this->db->beginTransaction();
        try {
            $st = $this->db->prepare(
                "INSERT INTO planificaciones2 (anio_id, usuario_id, curso_asignatura_id, catalogo_id, avance)
                 VALUES (:anio, :usuario, :ca, :catalogo, :avance)"
            );
            $st->execute([
                ':anio'     => $anioId,
                ':usuario'  => $usuarioId,
                ':ca'       => $nuevoCursoAsignaturaId,
                ':catalogo' => $origen['catalogo_id'],
                ':avance'   => $origen['avance'],
            ]);
            $nuevoId = (int) $this->db->lastInsertId();

            $st = $this->db->prepare(
                "INSERT INTO planificacion_modulos2
                    (planificacion_id, numero, nombre, horas_semana, semanas, actitudes, transversal, activo)
                 SELECT :nuevo, numero, nombre, horas_semana, semanas, actitudes, transversal, activo
                 FROM planificacion_modulos2
                 WHERE planificacion_id = :origen"
            );
            $st->execute([':nuevo' => $nuevoId, ':origen' => $id]);

            $st = $this->db->prepare(
                "INSERT INTO planificacion_clases2
                    (modulo_id, orden, clase, fecha_desde, fecha_hasta, horas, oa_id,
                     hab21, conocimientos, estrategias, indicadores, instrumentos, tipo)
                 SELECT nm.id, c.orden, c.clase, c.fecha_desde, c.fecha_hasta, c.horas, c.oa_id,
                        c.hab21, c.conocimientos, c.estrategias, c.indicadores, c.instrumentos, c.tipo
                 FROM planificacion_clases2 c
                 JOIN planificacion_modulos2 om ON om.id = c.modulo_id
                 JOIN planificacion_modulos2 nm
                      ON nm.planificacion_id = :nuevo AND nm.numero = om.numero
                 WHERE om.planificacion_id = :origen"
            );
            $st->execute([':nuevo' => $nuevoId, ':origen' => $id]);

            $this->db->commit();
            return $nuevoId;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function eliminar(int $id, ?int $usuarioId): bool
    {
        $sql = "DELETE FROM planificaciones2 WHERE id = :id";
        $params = [':id' => $id];
        if ($usuarioId !== null) {
            $sql .= " AND usuario_id = :usuario";
            $params[':usuario'] = $usuarioId;
        }
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->rowCount() > 0;
    }

    /* ---------- Editor ---------- */

    public function obtenerParaEditar(int $id, ?int $alcanceUsuarioId): ?array
    {
        $sql = "SELECT p.id, p.anio_id, p.usuario_id, p.catalogo_id, p.avance, p.updated_at,
                       c.nombre AS curso, a.nombre AS asignatura,
                       CONCAT_WS(' ', u.nombre, u.ape_paterno, u.ape_materno) AS docente,
                       an.anio, an.sem1_inicio, an.sem1_fin, an.sem2_inicio, an.sem2_fin, an.sem2_fin_4medio
                FROM planificaciones2 p
                JOIN usuarios2 u ON u.id = p.usuario_id
                JOIN anios2 an ON an.id = p.anio_id
                JOIN curso_asignaturas2 ca ON ca.id = p.curso_asignatura_id
                JOIN cursos2 c ON c.id = ca.curso_id
                JOIN asignaturas2 a ON a.id = ca.asignatura_id
                WHERE p.id = :id";
        $params = [':id' => $id];
        if ($alcanceUsuarioId !== null) {
            $sql .= " AND p.usuario_id = :usuario";
            $params[':usuario'] = $alcanceUsuarioId;
        }
        $st = $this->db->prepare($sql);
        $st->execute($params);
        $plan = $st->fetch(PDO::FETCH_ASSOC);
        if (!$plan) {
            return null;
        }

        $modulos = [];
        for ($n = 0; $n < self::TOTAL_MODULOS; $n++) {
            $modulos[$n] = [
                'numero'       => $n,
                'nombre'       => '',
                'horas_semana' => '',
                'semanas'      => '6',
                'actitudes'    => [],
                'transversal'  => '',
                'activo'       => $n < self::MODULOS_OBLIGATORIOS,
                'clases'       => [],
            ];
        }

        $st = $this->db->prepare(
            "SELECT id, numero, nombre, horas_semana, semanas, actitudes, transversal, activo
             FROM planificacion_modulos2
             WHERE planificacion_id = :plan
             ORDER BY numero"
        );
        $st->execute([':plan' => $id]);
        $idsModulo = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $n = (int) $r['numero'];
            if (!isset($modulos[$n])) {
                continue;
            }
            $modulos[$n] = [
                'numero'       => $n,
                'nombre'       => (string) $r['nombre'],
                'horas_semana' => $r['horas_semana'] === null ? '' : (string) $r['horas_semana'],
                'semanas'      => (int) $r['semanas'] > 0 ? (string) (int) $r['semanas'] : '',
                'actitudes'    => $this->listaJson($r['actitudes']),
                'transversal'  => (string) $r['transversal'],
                'activo'       => $n < self::MODULOS_OBLIGATORIOS ? true : (bool) $r['activo'],
                'clases'       => [],
            ];
            $idsModulo[(int) $r['id']] = $n;
        }

        if ($idsModulo) {
            $in = implode(',', array_map('intval', array_keys($idsModulo)));
            $st = $this->db->query(
                "SELECT modulo_id, clase, fecha_desde, fecha_hasta, horas, oa_id, hab21,
                        conocimientos, estrategias, indicadores, instrumentos, tipo
                 FROM planificacion_clases2
                 WHERE modulo_id IN ($in)
                 ORDER BY modulo_id, orden, id"
            );
            foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $c) {
                $modulos[$idsModulo[(int) $c['modulo_id']]]['clases'][] = [
                    'clase'         => (string) $c['clase'],
                    'fecha_desde'   => (string) ($c['fecha_desde'] ?? ''),
                    'fecha_hasta'   => (string) ($c['fecha_hasta'] ?? ''),
                    'horas'         => $c['horas'] === null ? '' : (string) $c['horas'],
                    'oa_id'         => (string) $c['oa_id'],
                    'hab21'         => $this->listaJson($c['hab21']),
                    'conocimientos' => $this->listaJson($c['conocimientos']),
                    'estrategias'   => (string) $c['estrategias'],
                    'indicadores'   => (string) $c['indicadores'],
                    'instrumentos'  => $this->listaJson($c['instrumentos']),
                    'tipo'          => (string) ($c['tipo'] ?? ''),
                ];
            }
        }

        $plan['modulos'] = array_values($modulos);
        return $plan;
    }

    public function guardarModulo(int $planId, int $numero, array $modulo, array $clases): array
    {
        $this->db->beginTransaction();
        try {
            $st = $this->db->prepare(
                "SELECT id FROM planificacion_modulos2 WHERE planificacion_id = :plan AND numero = :numero"
            );
            $st->execute([':plan' => $planId, ':numero' => $numero]);
            $moduloId = (int) $st->fetchColumn();

            if (!$moduloId) {
                $st = $this->db->prepare(
                    "INSERT INTO planificacion_modulos2 (planificacion_id, numero) VALUES (:plan, :numero)"
                );
                $st->execute([':plan' => $planId, ':numero' => $numero]);
                $moduloId = (int) $this->db->lastInsertId();
            }

            $st = $this->db->prepare(
                "UPDATE planificacion_modulos2
                 SET nombre = :nombre, horas_semana = :horas, semanas = :semanas,
                     actitudes = :actitudes, transversal = :transversal, activo = :activo
                 WHERE id = :id"
            );
            $st->execute([
                ':nombre'      => $modulo['nombre'],
                ':horas'       => $modulo['horas_semana'],
                ':semanas'     => $modulo['semanas'],
                ':actitudes'   => $this->jsonLista($modulo['actitudes']),
                ':transversal' => $modulo['transversal'],
                ':activo'      => $modulo['activo'],
                ':id'          => $moduloId,
            ]);

            $st = $this->db->prepare("DELETE FROM planificacion_clases2 WHERE modulo_id = :modulo");
            $st->execute([':modulo' => $moduloId]);

            $st = $this->db->prepare(
                "INSERT INTO planificacion_clases2
                    (modulo_id, orden, clase, fecha_desde, fecha_hasta, horas, oa_id, hab21,
                     conocimientos, estrategias, indicadores, instrumentos, tipo)
                 VALUES
                    (:modulo, :orden, :clase, :desde, :hasta, :horas, :oa, :hab21,
                     :conocimientos, :estrategias, :indicadores, :instrumentos, :tipo)"
            );
            foreach (array_values($clases) as $i => $c) {
                $st->execute([
                    ':modulo'        => $moduloId,
                    ':orden'         => $i + 1,
                    ':clase'         => $c['clase'],
                    ':desde'         => $c['fecha_desde'],
                    ':hasta'         => $c['fecha_hasta'],
                    ':horas'         => $c['horas'],
                    ':oa'            => $c['oa_id'],
                    ':hab21'         => $this->jsonLista($c['hab21']),
                    ':conocimientos' => $this->jsonLista($c['conocimientos']),
                    ':estrategias'   => $c['estrategias'],
                    ':indicadores'   => $c['indicadores'],
                    ':instrumentos'  => $this->jsonLista($c['instrumentos']),
                    ':tipo'          => $c['tipo'],
                ]);
            }

            $avance = $this->recalcularAvance($planId);
            $st = $this->db->prepare("UPDATE planificaciones2 SET avance = :avance, updated_at = NOW() WHERE id = :id");
            $st->execute([':avance' => $avance, ':id' => $planId]);

            $this->db->commit();
            return ['avance' => $avance];
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    /** Misma regla que el planificador: campos completados / campos totales de los módulos en uso. */
    private function recalcularAvance(int $planId): int
    {
        $st = $this->db->prepare(
            "SELECT id, numero, nombre, horas_semana, semanas, activo
             FROM planificacion_modulos2
             WHERE planificacion_id = :plan"
        );
        $st->execute([':plan' => $planId]);
        $modulos = $st->fetchAll(PDO::FETCH_ASSOC);

        $clasesPorModulo = [];
        if ($modulos) {
            $in = implode(',', array_map(fn($m) => (int) $m['id'], $modulos));
            $q = $this->db->query(
                "SELECT modulo_id, fecha_desde, horas, oa_id, conocimientos, estrategias,
                        indicadores, instrumentos, tipo
                 FROM planificacion_clases2
                 WHERE modulo_id IN ($in)"
            );
            foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $c) {
                $clasesPorModulo[(int) $c['modulo_id']][] = $c;
            }
        }

        $hecho = 0;
        $total = 0;
        foreach ($modulos as $m) {
            $enUso = (int) $m['numero'] < self::MODULOS_OBLIGATORIOS || (int) $m['activo'] === 1;
            if (!$enUso) {
                continue;
            }
            $hecho += (trim((string) $m['nombre']) !== '' ? 1 : 0)
                    + ((int) $m['horas_semana'] > 0 ? 1 : 0)
                    + ((int) $m['semanas'] > 0 ? 1 : 0);
            $total += 3;

            $clases = $clasesPorModulo[(int) $m['id']] ?? [];
            if (!$clases) {
                $total += 8;
                continue;
            }
            foreach ($clases as $c) {
                $hecho += (!empty($c['fecha_desde']) ? 1 : 0)
                        + ($c['horas'] !== null && $c['horas'] !== '' ? 1 : 0)
                        + ((string) $c['oa_id'] !== '' ? 1 : 0)
                        + ($this->listaJson($c['conocimientos']) ? 1 : 0)
                        + (trim((string) $c['estrategias']) !== '' ? 1 : 0)
                        + (trim((string) $c['indicadores']) !== '' ? 1 : 0)
                        + ($this->listaJson($c['instrumentos']) ? 1 : 0)
                        + (!empty($c['tipo']) ? 1 : 0);
                $total += 8;
            }
        }
        return $total ? (int) round($hecho / $total * 100) : 0;
    }

    private function listaJson($valor): array
    {
        $lista = json_decode((string) $valor, true);
        return is_array($lista) ? array_values($lista) : [];
    }

    private function jsonLista(array $lista): string
    {
        return json_encode(array_values($lista), JSON_UNESCAPED_UNICODE);
    }
}