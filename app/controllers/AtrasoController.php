<?php
require_once __DIR__ . '/../models/Atraso.php';

class AtrasoController
{
    private Atraso $model;

    public function __construct()
    {
        $this->model = new Atraso();
    }

    /* ==========================================
       FORMULARIO REGISTRO RÁPIDO (portería)
    ========================================== */
    public function formRegistro()
    {
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $atrasos = $this->model->getDelDia($fecha);

        require "../views/atrasos/form_registro.php";
    }

    /* ==========================================
       GUARDAR ATRASO (POST)
    ========================================== */
    public function guardar()
    {
        $matriculaId = (int) $_POST['matricula_id'];
        $fecha = $_POST['fecha'] ?? date('Y-m-d');
        $horaLlegada = $_POST['hora_llegada'] ?? date('H:i');
        $justificado = (int) ($_POST['justificado'] ?? 0);
        $observacion = $_POST['observacion'] ?? '';
        $registradoPor = $_SESSION['user']['id'] ?? null;

        $this->model->registrar(
            $matriculaId,
            $fecha,
            $horaLlegada,
            $justificado,
            $observacion,
            $registradoPor
        );

        header("Location: index.php?action=atrasos_registro&fecha=$fecha&guardado=1");
        exit;
    }

    /* ==========================================
       BUSCAR ALUMNO POR RUN (AJAX)
    ========================================== */
    public function buscarAlumno()
    {
        header('Content-Type: application/json');
        $run = trim($_GET['run'] ?? '');

        if (!$run) {
            echo json_encode(['error' => 'RUN requerido']);
            exit;
        }

        $alumno = $this->model->buscarAlumnoPorRun($run);

        if (!$alumno) {
            echo json_encode(['error' => 'Alumno no encontrado o sin matrícula activa este año']);
            exit;
        }

        echo json_encode($alumno);
        exit;
    }

    /* ==========================================
       LISTAR ATRASOS POR CURSO
    ========================================== */
    public function listarPorCurso()
    {
        $anioId = isset($_GET['anio_id']) && $_GET['anio_id'] !== '' ? (int) $_GET['anio_id'] : null;
        $cursoId = isset($_GET['curso_id']) && $_GET['curso_id'] !== '' ? (int) $_GET['curso_id'] : null;
        $semestre = isset($_GET['semestre']) && $_GET['semestre'] !== '' ? (int) $_GET['semestre'] : null;
        $fechaDesde = $_GET['fecha_desde'] ?? null;
        $fechaHasta = $_GET['fecha_hasta'] ?? null;
        $semana = $_GET['semana'] ?? null;

        if (!$anioId)
            $anioId = $this->model->getAnioActualId();

        $anios = $this->model->getAnios();
        $cursos = $this->model->getCursosConMatricula($anioId);

        if ($semana && !$fechaDesde) {
            [$anioSem, $numSem] = explode('-W', $semana);
            $dt = new DateTime();
            $dt->setISODate((int) $anioSem, (int) $numSem, 1);
            $fechaDesde = $dt->format('Y-m-d');
            $dt->modify('+4 days');
            $fechaHasta = $dt->format('Y-m-d');
        }

        $atrasos = $this->model->getByCursoFiltrado($cursoId, $anioId, $semestre, $fechaDesde, $fechaHasta);

        // ← NUEVO: resumen general con los mismos filtros activos
        $resumen = $this->model->getResumenGeneral($anioId, $cursoId, $semestre, $fechaDesde, $fechaHasta);

        $semestres = [
            1 => ['inicio' => '2026-03-04', 'fin' => '2026-06-18'],
            2 => ['inicio' => '2026-07-06', 'fin' => '2026-11-24'],
        ];

        require "../views/atrasos/lista_curso.php";
    }

    /* ==========================================
       LISTAR ATRASOS DE UN ALUMNO
    ========================================== */
    public function listarPorAlumno()
    {
        $matriculaId = (int) ($_GET['matricula_id'] ?? 0);
        if (!$matriculaId)
            die("Falta matricula_id.");

        $atrasos = $this->model->getByMatricula($matriculaId);
        $resumen = $this->model->getResumenAlumno($matriculaId);

        require "../views/atrasos/lista_alumno.php";
    }

    /* ==========================================
       ELIMINAR ATRASO
    ========================================== */
    public function eliminar()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $redirect = ($_GET['redirect'] ?? 'atrasos_registro');

        // Construir query string de vuelta con todos los parámetros de filtro
        $params = http_build_query(array_filter([
            'action' => $redirect,
            'curso_id' => $_GET['curso_id'] ?? '',
            'anio_id' => $_GET['anio_id'] ?? '',
            'semestre' => $_GET['semestre'] ?? '',
            'fecha' => $_GET['fecha'] ?? '',
        ]));

        if ($id)
            $this->model->eliminar($id);

        header("Location: index.php?$params");
        exit;
    }

    /* ==========================================
   BUSCAR ALUMNOS POR TÉRMINO (AJAX autocompletado)
========================================== */
    public function buscarAlumnos()
    {
        header('Content-Type: application/json');
        $termino = trim($_GET['q'] ?? '');

        if (strlen($termino) < 2) {
            echo json_encode([]);
            exit;
        }

        $resultados = $this->model->buscarAlumnosPorTermino($termino);
        echo json_encode($resultados);
        exit;
    }
}