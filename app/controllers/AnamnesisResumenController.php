<?php
require_once __DIR__ . '/../models/ResumenAnamnesis.php';
require_once __DIR__ . '/../models/Alumno.php';
require_once __DIR__ . '/../models/Anio.php';
require_once __DIR__ . '/ControlModificacionAlumnoController.php';
require_once __DIR__ . '/AuthController.php';

class AnamnesisResumenController
{
    private const CSRF_KEY  = 'csrf_anamnesis';
    private const FLASH_KEY = 'flash_anamnesis';
    private const OLD_KEY   = 'old_anamnesis';

    private $model;

    public function __construct()
    {
        $this->model = new ResumenAnamnesis();
    }

    /* ================= Helpers para las vistas ================= */

    /** Token CSRF para el formulario del modal */
    public static function csrfToken(): string
    {
        if (empty($_SESSION[self::CSRF_KEY])) {
            $_SESSION[self::CSRF_KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::CSRF_KEY];
    }

    /** Mensaje de resultado (['tipo' => 'ok'|'error', 'mensaje' => '...']) — se lee una sola vez */
    public static function consumirFlash(): ?array
    {
        $flash = $_SESSION[self::FLASH_KEY] ?? null;
        unset($_SESSION[self::FLASH_KEY]);
        return $flash;
    }

    /** Datos enviados que fallaron validación, para reabrir el modal con lo escrito */
    public static function consumirOld(): ?array
    {
        $old = $_SESSION[self::OLD_KEY] ?? null;
        unset($_SESSION[self::OLD_KEY]);
        return $old;
    }

    /* ================= Acciones ================= */

    public function formAnamnesis()
    {
        $alumnoId = $_GET['alumno_id'] ?? null;
        $anioId   = $_GET['anio_id'] ?? null;

        if (!$alumnoId) die("Falta alumno_id.");

        $alumnoModel = new Alumno();
        $anioModel   = new Anio();

        $alumno = $alumnoModel->getById($alumnoId);
        $anios  = $anioModel->getAll();

        if (!$anioId && !empty($anios)) {
            $anioId = $anios[0]['id'];
        }

        $anamnesis = $this->model->getCompletaByAlumnoYAnio($alumnoId, $anioId);
        $historial = $this->model->getAllByAlumno($alumnoId);

        $esMayor = !empty($alumno['fechanac'])
            ? (new DateTime())->diff(new DateTime($alumno['fechanac']))->y >= 18
            : false;

        require __DIR__ . '/../views/anamnesis/form.php';
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=alumnos");
            exit;
        }

        $alumnoId = filter_input(INPUT_POST, 'alumno_id', FILTER_VALIDATE_INT);
        $anioId   = filter_input(INPUT_POST, 'anio_id', FILTER_VALIDATE_INT);
        $origen   = ($_POST['origen'] ?? '') === 'perfil' ? 'perfil' : 'form';

        if (!$alumnoId || !$anioId) {
            header("Location: index.php?action=alumnos");
            exit;
        }

        $volver = $this->urlRetorno($origen, $alumnoId, $anioId);

        // 1) Permiso
        if (!AuthController::puede('anamnesis_guardar')) {
            $this->fallar($volver, 'No tienes permiso para modificar la anamnesis.', false);
        }

        // 2) CSRF (el modal lo envía; el formulario antiguo no, y se retira en el paso 5)
        if ($origen === 'perfil') {
            $token = (string) ($_POST['csrf'] ?? '');
            if (!hash_equals($_SESSION[self::CSRF_KEY] ?? '', $token)) {
                $this->fallar($volver, 'El formulario expiró. Vuelve a intentarlo.');
            }
        }

        // 3) Usuario logueado = entrevistador / modificador
        $usuarioId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($usuarioId <= 0) {
            $this->fallar($volver, 'Tu sesión no es válida. Inicia sesión nuevamente.', false);
        }

        // 4) Alumno y año deben existir
        if (!(new Alumno())->getById($alumnoId)) {
            $this->fallar("index.php?action=alumnos", 'El alumno no existe.', false);
        }
        $aniosValidos = array_column((new Anio())->getAll(), 'id');
        if (!in_array($anioId, array_map('intval', $aniosValidos), true)) {
            $this->fallar($volver, 'El año académico seleccionado no es válido.');
        }

        // 5) Validaciones de negocio
        $errores = $this->validar($_POST);
        if ($errores) {
            $this->fallar($volver, implode(' ', $errores));
        }

        // 6) Armar datos (solo columnas permitidas)
        $data = [];
        foreach (ResumenAnamnesis::CAMPOS as $campo) {
            $data[$campo] = $_POST[$campo] ?? null;
        }
        $data['alumno_id']  = $alumnoId;
        $data['anio_id']    = $anioId;
        $data['usuario_id'] = $usuarioId;

        // null = el formulario no trae la sección de integrantes → no se tocan
        $integrantes = isset($_POST['seccion_integrantes']) ? $this->leerIntegrantes() : null;

        if (($data['total_integrantes'] ?? '') === '' && $integrantes !== null) {
            $data['total_integrantes'] = count(array_filter(
                $integrantes,
                fn($i) => trim((string) ($i['nombre'] ?? '')) !== ''
            ));
        }

        $yaExistia = $this->model->getByAlumnoYAnio($alumnoId, $anioId);

        if (empty($data['fecha_entrevista'])) {
            $data['fecha_entrevista'] = $yaExistia['fecha_entrevista'] ?? date('Y-m-d');
        }

        // 7) Guardar
        try {
            $this->model->save($data, $integrantes);
        } catch (Throwable $e) {
            error_log('[Anamnesis] Error al guardar alumno ' . $alumnoId . ': ' . $e->getMessage());
            $this->fallar($volver, 'No se pudo guardar la anamnesis. Intenta nuevamente.');
        }

        // 8) Registro de modificación
        $ctrl    = new ControlModificacionAlumnoController();
        $accion  = $yaExistia ? 'editar' : 'crear';
        $detalle = $yaExistia ? 'Anamnesis actualizada' : 'Anamnesis registrada por primera vez';
        $ctrl->registrar((int) $alumnoId, 'resumen_anamnesis', $accion, $detalle);

        unset($_SESSION[self::OLD_KEY]);
        $_SESSION[self::FLASH_KEY] = ['tipo' => 'ok', 'mensaje' => 'Anamnesis guardada correctamente.'];

        header("Location: " . $this->urlRetorno($origen, $alumnoId, $anioId, true));
        exit;
    }

    /* ================= Privados ================= */

    private function validar(array $p): array
    {
        $errores = [];

        if (trim((string) ($p['realizado_por'] ?? '')) === '') {
            $errores[] = 'Debe indicar el nombre del apoderado o responsable.';
        }

        $email = trim((string) ($p['apoderado_email'] ?? ''));
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El correo del apoderado no es válido.';
        }

        $fecha = trim((string) ($p['fecha_entrevista'] ?? ''));
        if ($fecha !== '') {
            $f = DateTime::createFromFormat('Y-m-d', $fecha);
            if (!$f || $f->format('Y-m-d') !== $fecha) {
                $errores[] = 'La fecha de la entrevista no es válida.';
            } elseif ($fecha > date('Y-m-d')) {
                $errores[] = 'La fecha de la entrevista no puede ser futura.';
            }
        }

        if (($p['salud_problemas'] ?? '') === '1' && trim((string) ($p['salud_problemas_cuales'] ?? '')) === '') {
            $errores[] = 'Indique cuáles son los problemas de salud.';
        }

        if (($p['salud_tratamientos'] ?? '') === '1' && trim((string) ($p['salud_tratamientos_detalle'] ?? '')) === '') {
            $errores[] = 'Indique el detalle de los tratamientos.';
        }

        return $errores;
    }

    private function leerIntegrantes(): array
    {
        $raw = $_POST['integrantes'] ?? [];
        if (!is_array($raw)) {
            return [];
        }
        return array_values(array_filter($raw, 'is_array'));
    }

    private function urlRetorno(string $origen, int $alumnoId, int $anioId, bool $ok = false): string
    {
        if ($origen === 'perfil') {
            return "index.php?action=alumno_profile&id={$alumnoId}#anamnesis";
        }
        return "index.php?action=anamnesis_form&alumno_id={$alumnoId}&anio_id={$anioId}" . ($ok ? '&guardado=1' : '');
    }

    /** Guarda el error (y opcionalmente lo escrito) en sesión y redirige */
    private function fallar(string $url, string $mensaje, bool $conservarDatos = true): void
    {
        $_SESSION[self::FLASH_KEY] = ['tipo' => 'error', 'mensaje' => $mensaje];

        if ($conservarDatos) {
            $old = $_POST;
            unset($old['csrf']);
            $_SESSION[self::OLD_KEY] = $old;
        }

        header("Location: " . $url);
        exit;
    }
}