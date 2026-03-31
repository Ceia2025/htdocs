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

    public function actualizarHora()
    {
        $id = (int) ($_POST['id'] ?? 0);
        $horaLlegada = $_POST['hora_llegada'] ?? '';
        $fecha = $_POST['fecha'] ?? '';

        if ($id && $horaLlegada && $fecha) {
            $this->model->actualizarHora($id, $horaLlegada, $fecha);
        }

        $params = http_build_query(array_filter([
            'action' => 'atrasos_lista_curso',
            'curso_id' => $_POST['curso_id'] ?? '',
            'anio_id' => $_POST['anio_id'] ?? '',
            'semestre' => $_POST['semestre'] ?? '',
        ]));

        header("Location: index.php?$params");
        exit;
    }

    public function atrasos_pdf()
    {
        $anioId = isset($_GET['anio_id']) && $_GET['anio_id'] !== '' ? (int) $_GET['anio_id'] : null;
        $cursoId = isset($_GET['curso_id']) && $_GET['curso_id'] !== '' ? (int) $_GET['curso_id'] : null;
        $semestre = isset($_GET['semestre']) && $_GET['semestre'] !== '' ? (int) $_GET['semestre'] : null;
        $fechaDesde = $_GET['fecha_desde'] ?? null;
        $fechaHasta = $_GET['fecha_hasta'] ?? null;
        $semana = $_GET['semana'] ?? null;

        if (!$anioId)
            $anioId = $this->model->getAnioActualId();

        // Resolver semana → rango de fechas (igual que en listarPorCurso)
        if ($semana && !$fechaDesde) {
            [$anioSem, $numSem] = explode('-W', $semana);
            $dt = new DateTime();
            $dt->setISODate((int) $anioSem, (int) $numSem, 1);
            $fechaDesde = $dt->format('Y-m-d');
            $dt->modify('+4 days');
            $fechaHasta = $dt->format('Y-m-d');
        }

        $atrasos = $this->model->getByCursoFiltrado($cursoId, $anioId, $semestre, $fechaDesde, $fechaHasta);
        $resumen = $this->model->getResumenGeneral($anioId, $cursoId, $semestre, $fechaDesde, $fechaHasta);

        // Construir texto de filtros activos para mostrar en el PDF
        $partesFiltro = [];
        if ($cursoId) {
            $cursos = $this->model->getCursosConMatricula($anioId);
            foreach ($cursos as $c) {
                if ($c['id'] == $cursoId) {
                    $partesFiltro[] = 'Curso: ' . $c['nombre'];
                    break;
                }
            }
        }
        if ($semestre)
            $partesFiltro[] = 'Semestre: ' . $semestre . '°';
        if ($fechaDesde)
            $partesFiltro[] = 'Desde: ' . date('d/m/Y', strtotime($fechaDesde));
        if ($fechaHasta)
            $partesFiltro[] = 'Hasta: ' . date('d/m/Y', strtotime($fechaHasta));
        if ($semana)
            $partesFiltro[] = 'Semana: ' . $semana;
        $filtrosTexto = !empty($partesFiltro) ? implode(' · ', $partesFiltro) : '';

        ob_start();
        require __DIR__ . '/../views/atrasos/atrasos_reporte_pdf.php';
        $html = ob_get_clean();

        if (ob_get_length())
            ob_end_clean();

        try {
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('letter', 'portrait');
            $dompdf->render();

            header('Content-Type: application/pdf');
            $dompdf->stream("Reporte_Atrasos.pdf", ["Attachment" => true]);
            exit;
        } catch (Exception $e) {
            die("Error al generar PDF: " . $e->getMessage());
        }
    }

    public function atraso_alumno_pdf()
    {
        $matriculaId = (int) ($_GET['matricula_id'] ?? 0);
        $anioId = (int) ($_GET['anio_id'] ?? 0);

        if (!$matriculaId || !$anioId)
            die("Faltan parámetros.");

        $atrasos = $this->model->getByMatriculaYAnio($matriculaId, $anioId);

        if (empty($atrasos))
            die("No hay atrasos registrados para este alumno.");

        $alumno = [
            'nombre' => $atrasos[0]['nombre'],
            'apepat' => $atrasos[0]['apepat'],
            'apemat' => $atrasos[0]['apemat'],
            'run' => $atrasos[0]['run'],
            'curso' => $atrasos[0]['curso'],
        ];

        $resumenAlumno = [
            'total' => count($atrasos),
            'justificados' => count(array_filter($atrasos, fn($a) => $a['justificado'] == 1)),
            'injustificados' => count(array_filter($atrasos, fn($a) => $a['justificado'] == 0)),
            'sem1' => count(array_filter($atrasos, fn($a) => $a['semestre'] == 1)),
            'sem2' => count(array_filter($atrasos, fn($a) => $a['semestre'] == 2)),
        ];

        ob_start();
        require __DIR__ . '/../views/atrasos/atraso_alumno_pdf.php';
        $html = ob_get_clean();

        if (ob_get_length())
            ob_end_clean();

        try {
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('letter', 'portrait');
            $dompdf->render();

            $nombreArchivo = 'Atrasos_' . $alumno['apepat'] . '_' . $alumno['apemat'] . '.pdf';
            header('Content-Type: application/pdf');
            $dompdf->stream($nombreArchivo, ["Attachment" => true]);
            exit;
        } catch (Exception $e) {
            die("Error al generar PDF: " . $e->getMessage());
        }
    }
}