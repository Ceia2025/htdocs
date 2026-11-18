<?php
require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/CatalogoCurricular.php';
require_once __DIR__ . '/../models/Planificacion.php';

class PlanificacionController
{
    private ?Planificacion $modelo = null;
    private array $usuario = [];
    private bool $esAdmin = false;

    /**
     * El router instancia todos los controladores en cada petición,
     * por eso el constructor no hace nada: la sesión y la conexión
     * se preparan recién cuando se ejecuta una acción de planificaciones.
     */
    private function iniciar(): void
    {
        if ($this->modelo !== null) {
            return;
        }

        $auth = new AuthController();
        $auth->checkAuth();

        $this->usuario = $_SESSION['user'];

        $idAdmin = defined('ROL_ADMINISTRADOR') ? ROL_ADMINISTRADOR : 1;
        $rolActual = $this->usuario['rol_id'] ?? $this->usuario['rol'] ?? null;
        $this->esAdmin = is_numeric($rolActual)
            ? (int) $rolActual === $idAdmin
            : mb_strtolower(trim((string) $rolActual)) === 'administrador';

        $this->modelo = new Planificacion();
    }

    /** null = sin restricción (administrador); id = solo lo propio. */
    private function alcance(): ?int
    {
        return $this->esAdmin ? null : (int) $this->usuario['id'];
    }

    public function index(): void
    {
        $this->iniciar();

        $user = $this->usuario;
        $nombre = $user['nombre'];
        $rol = $user['rol'];
        $usuarioId = (int) $user['id'];
        $esAdmin = $this->esAdmin;

        $anios = $this->modelo->anios();
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $anio = $anioId ? $this->modelo->anioPorId($anioId) : null;
        if (!$anio) {
            $defecto = $this->modelo->anioPorDefecto();
            $anio = $defecto ? $this->modelo->anioPorId($defecto) : null;
        }
        $anioId = $anio ? (int) $anio['id'] : 0;

        $planificaciones = [];
        $opciones = [];
        $docentes = [];
        $docenteId = 0;
        $semanas = null;

        if ($anio) {
            $docenteId = $esAdmin ? (int) ($_GET['docente_id'] ?? 0) : 0;
            $filtro = $esAdmin ? ($docenteId ?: null) : $usuarioId;

            foreach ($this->modelo->listar($anioId, $filtro) as $p) {
                $catalogo = CatalogoCurricular::porId($p['catalogo_id']);
                $p['nivel'] = $catalogo['nivel'] ?? null;
                $p['catalogo_ok'] = $catalogo !== null;
                $planificaciones[] = $p;
            }
            if ($esAdmin) {
                $docentes = $this->modelo->docentesConPlanificaciones($anioId);
            }
            $opciones = $this->modelo->cursoAsignaturas($anioId, $usuarioId);
            $semanas = $this->modelo->semanasDelAnio($anio);
        }

        $catalogo = CatalogoCurricular::agrupadasPorNivel();
        $csrf = $this->tokenCsrf();
        $flash = $_SESSION['flash_planif'] ?? null;
        unset($_SESSION['flash_planif']);

        require __DIR__ . '/../views/planificaciones/index.php';
    }

    public function crear(): void
    {
        $this->iniciar();
        $this->validarPost();

        $anioId = (int) ($_POST['anio_id'] ?? 0);
        $cursoAsignaturaId = (int) ($_POST['curso_asignatura_id'] ?? 0);
        $catalogoId = (string) ($_POST['catalogo_id'] ?? '');

        $this->ejecutar($anioId, function () use ($anioId, $cursoAsignaturaId, $catalogoId) {
            if (!$this->modelo->anioPorId($anioId)) {
                throw new RuntimeException('El año seleccionado no existe.');
            }
            if (!CatalogoCurricular::existe($catalogoId)) {
                throw new RuntimeException('Selecciona un plan curricular válido.');
            }
            $this->modelo->crear($anioId, (int) $this->usuario['id'], $cursoAsignaturaId, $catalogoId);
        }, 'Planificación creada correctamente.');
    }

    public function duplicar(): void
    {
        $this->iniciar();
        $this->validarPost();

        $id = (int) ($_POST['id'] ?? 0);
        $destinoId = (int) ($_POST['curso_asignatura_id'] ?? 0);
        $origen = $this->modelo->obtener($id, $this->alcance());
        $anioId = $origen ? (int) $origen['anio_id'] : 0;

        $this->ejecutar($anioId, function () use ($id, $destinoId) {
            $this->modelo->duplicar($id, $this->alcance(), $destinoId);
        }, 'Planificación duplicada en el curso seleccionado.');
    }

    public function eliminar(): void
    {
        $this->iniciar();
        $this->validarPost();

        $id = (int) ($_POST['id'] ?? 0);
        $origen = $this->modelo->obtener($id, $this->alcance());
        $anioId = $origen ? (int) $origen['anio_id'] : 0;

        $this->ejecutar($anioId, function () use ($id) {
            if (!$this->modelo->eliminar($id, $this->alcance())) {
                throw new RuntimeException('La planificación no existe o no te pertenece.');
            }
        }, 'Planificación eliminada.');
    }

    public function editar(): void
    {
        $this->iniciar();

        $id = (int) ($_GET['id'] ?? 0);
        $plan = $this->modelo->obtenerParaEditar($id, $this->alcance());
        if (!$plan) {
            $this->volver(0, 'danger', 'La planificación no existe o no te pertenece.');
        }

        $actual = CatalogoCurricular::porId($plan['catalogo_id']);
        if (!$actual) {
            $this->volver((int) $plan['anio_id'], 'danger', 'El plan curricular de esta planificación ya no existe en el catálogo.');
        }
        $previa = CatalogoCurricular::fuenteModulo($plan['catalogo_id'], 0) ?? $actual;

        $fechas = array_filter([$plan['sem1_inicio'], $plan['sem1_fin'], $plan['sem2_inicio'], $plan['sem2_fin'], $plan['sem2_fin_4medio']]);
        $datos = [
            'plan' => [
                'id'         => (int) $plan['id'],
                'asignatura' => $plan['asignatura'],
                'curso'      => $plan['curso'],
                'docente'    => $plan['docente'],
                'anio'       => (int) $plan['anio'],
                'anio_id'    => (int) $plan['anio_id'],
                'nivel'      => $actual['nivel'],
                'avance'     => (int) $plan['avance'],
            ],
            'csrf'         => $this->tokenCsrf(),
            'bases'        => CatalogoCurricular::bases(),
            'instrumentos' => CatalogoCurricular::instrumentos(),
            'tipos'        => CatalogoCurricular::tipos(),
            'catalogo'     => ['actual' => $actual, 'previa' => $previa],
            'rango'        => ['min' => $fechas ? min($fechas) : '', 'max' => $fechas ? max($fechas) : ''],
            'urls'         => [
                'guardar' => 'index.php?action=planificacion_guardar',
                'volver'  => 'index.php?action=planificaciones&anio_id=' . (int) $plan['anio_id'],
            ],
            'modulos'      => $plan['modulos'],
        ];

        $user = $this->usuario;
        $nombre = $user['nombre'];
        $rol = $user['rol'];
        $esAdmin = $this->esAdmin;
        $jsonDatos = json_encode(
            $datos,
            JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        );

        require __DIR__ . '/../views/planificaciones/editor.php';
    }

    public function guardar(): void
    {
        $this->iniciar();

        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            $this->responderJson(['ok' => false, 'error' => 'Método no permitido.'], 405);
        }
        $cuerpo = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($cuerpo)) {
            $this->responderJson(['ok' => false, 'error' => 'Solicitud inválida.'], 400);
        }
        if (!hash_equals($_SESSION['csrf_planif'] ?? '', (string) ($cuerpo['csrf'] ?? ''))) {
            $this->responderJson(['ok' => false, 'error' => 'Sesión expirada. Recarga la página.'], 403);
        }

        $id = (int) ($cuerpo['plan_id'] ?? 0);
        $numero = (int) ($cuerpo['numero'] ?? -1);
        if ($numero < 0 || $numero >= Planificacion::TOTAL_MODULOS) {
            $this->responderJson(['ok' => false, 'error' => 'Módulo inválido.'], 422);
        }

        $plan = $this->modelo->obtener($id, $this->alcance());
        if (!$plan) {
            $this->responderJson(['ok' => false, 'error' => 'La planificación no existe o no te pertenece.'], 404);
        }
        $fuente = CatalogoCurricular::fuenteModulo($plan['catalogo_id'], $numero);
        if (!$fuente) {
            $this->responderJson(['ok' => false, 'error' => 'El plan curricular ya no existe.'], 422);
        }

        try {
            $modulo = $this->sanearModulo(is_array($cuerpo['modulo'] ?? null) ? $cuerpo['modulo'] : [], $numero);
            $clases = $this->sanearClases(is_array($cuerpo['clases'] ?? null) ? $cuerpo['clases'] : [], $fuente);
            $resultado = $this->modelo->guardarModulo($id, $numero, $modulo, $clases);
            $this->responderJson(['ok' => true, 'avance' => $resultado['avance']]);
        } catch (Throwable $e) {
            error_log('[Planificacion] ' . $e->getMessage());
            $this->responderJson(['ok' => false, 'error' => 'No se pudo guardar. Intenta nuevamente.'], 500);
        }
    }

    private function sanearModulo(array $m, int $numero): array
    {
        $actitudes = array_merge(...array_values(CatalogoCurricular::bases()['actitudes']));

        return [
            'nombre'       => $this->texto($m['nombre'] ?? '', 150),
            'horas_semana' => $this->entero($m['horas_semana'] ?? null),
            'semanas'      => $this->entero($m['semanas'] ?? null) ?? 0,
            'actitudes'    => $this->subconjunto($m['actitudes'] ?? [], $actitudes),
            'transversal'  => $this->texto($m['transversal'] ?? '', 255),
            'activo'       => $numero < Planificacion::MODULOS_OBLIGATORIOS ? 1 : (empty($m['activo']) ? 0 : 1),
        ];
    }

    private function sanearClases(array $clases, array $fuente): array
    {
        $habilidades = array_merge(...array_values(CatalogoCurricular::bases()['ambitos']));
        $oasValidos = array_column($fuente['oas'] ?? [], 'id');
        $conocimientos = $fuente['conocimientos'] ?? [];
        $instrumentos = CatalogoCurricular::instrumentos();
        $tipos = CatalogoCurricular::tipos();

        $limpias = [];
        foreach (array_slice(array_values($clases), 0, 80) as $c) {
            if (!is_array($c)) {
                continue;
            }
            $oa = (string) ($c['oa_id'] ?? '');
            $tipo = (string) ($c['tipo'] ?? '');
            $limpias[] = [
                'clase'         => $this->texto($c['clase'] ?? '', 60),
                'fecha_desde'   => $this->fecha($c['fecha_desde'] ?? ''),
                'fecha_hasta'   => $this->fecha($c['fecha_hasta'] ?? ''),
                'horas'         => $this->entero($c['horas'] ?? null),
                'oa_id'         => in_array($oa, $oasValidos, true) ? $oa : '',
                'hab21'         => $this->subconjunto($c['hab21'] ?? [], $habilidades),
                'conocimientos' => $this->subconjunto($c['conocimientos'] ?? [], $conocimientos),
                'estrategias'   => $this->texto($c['estrategias'] ?? '', 5000),
                'indicadores'   => $this->texto($c['indicadores'] ?? '', 5000),
                'instrumentos'  => $this->subconjunto($c['instrumentos'] ?? [], $instrumentos),
                'tipo'          => in_array($tipo, $tipos, true) ? $tipo : null,
            ];
        }
        return $limpias;
    }

    private function texto($valor, int $max): string
    {
        $texto = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', (string) $valor) ?? '';
        return mb_substr(trim($texto), 0, $max);
    }

    private function entero($valor): ?int
    {
        if ($valor === null || $valor === '' || !is_numeric($valor)) {
            return null;
        }
        return max(0, min(255, (int) $valor));
    }

    private function fecha($valor): ?string
    {
        $valor = (string) $valor;
        if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor, $p) || !checkdate((int) $p[2], (int) $p[3], (int) $p[1])) {
            return null;
        }
        return $valor;
    }

    private function subconjunto($valores, array $validos): array
    {
        if (!is_array($valores)) {
            return [];
        }
        $resultado = [];
        foreach ($valores as $v) {
            if (is_string($v) && in_array($v, $validos, true) && !in_array($v, $resultado, true)) {
                $resultado[] = $v;
            }
        }
        return $resultado;
    }

    private function responderJson(array $datos, int $codigo = 200): void
    {
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function ejecutar(int $anioId, callable $accion, string $mensajeOk): void
    {
        try {
            $accion();
            $this->volver($anioId, 'success', $mensajeOk);
        } catch (RuntimeException $e) {
            $this->volver($anioId, 'danger', $e->getMessage());
        } catch (Throwable $e) {
            error_log('[Planificacion] ' . $e->getMessage());
            $this->volver($anioId, 'danger', 'No se pudo completar la acción. Intenta nuevamente.');
        }
    }

    private function volver(int $anioId, string $tipo, string $mensaje): void
    {
        $_SESSION['flash_planif'] = ['tipo' => $tipo, 'msg' => $mensaje];
        $query = $anioId > 0 ? '&anio_id=' . $anioId : '';
        header('Location: index.php?action=planificaciones' . $query);
        exit;
    }

    private function validarPost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: index.php?action=planificaciones');
            exit;
        }
        $token = (string) ($_POST['csrf'] ?? '');
        if (!hash_equals($_SESSION['csrf_planif'] ?? '', $token)) {
            http_response_code(403);
            exit('Solicitud no válida. Recarga la página e inténtalo de nuevo.');
        }
    }

    private function tokenCsrf(): string
    {
        if (empty($_SESSION['csrf_planif'])) {
            $_SESSION['csrf_planif'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_planif'];
    }
}