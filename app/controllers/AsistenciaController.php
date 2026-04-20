<?php
require_once __DIR__ . '/../models/Asistencia.php';
require_once __DIR__ . '/../libs/dompdf/vendor/autoload.php';
require_once __DIR__ . '/../email/controller/AlertaController.php';

use Dompdf\Dompdf;
use Dompdf\Options;


class AsistenciaController
{
    private $model;

    public function __construct()
    {
        $this->model = new Asistencia();
    }

    /* ==========================================
       FORMULARIO ASISTENCIA MASIVA
    ========================================== */
    public function formMasiva()
    {
        $cursoId = $_GET['curso_id'] ?? null;
        $anioId = $_GET['anio_id'] ?? null;
        $fecha = $_GET['fecha'] ?? date("Y-m-d");

        if (!$cursoId || !$anioId)
            die("Faltan parámetros.");
        if ($fecha > date("Y-m-d"))
            die("No se puede registrar asistencia futura.");

        $alumnos = $this->model->getAlumnosPorCurso($cursoId, $anioId);
        $curso = $this->model->getCurso($cursoId);
        $fechasAnio = $this->model->getFechasAnio($anioId);
        $fechasConAsistencia = $this->model->getFechasConAsistencia($cursoId, $anioId);

        // ── NUEVO: cargar asistencia previa si ya existe para esta fecha ──
        $asistenciaExistente = $this->model->getAsistenciaPorFecha($cursoId, $anioId, $fecha);
        $esEdicion = !empty($asistenciaExistente);

        require "../views/asistencia/masiva.php";
    }

    /* ==========================================
       RESUMEN CURSO
    ========================================== */
    public function resumenCurso()
    {
        $cursoId = $_GET['curso_id'] ?? null;
        $anioId = $_GET['anio_id'] ?? null;

        if (!$cursoId || !$anioId) {
            die("Faltan parámetros para el resumen.");
        }

        $inicio = date("Y-03-01");
        $fin = date("Y-m-d");

        $porcentaje = $this->model->porcentajeCurso(
            $cursoId,
            $anioId,
            $inicio,
            $fin
        );

        $detalle = $this->model->getResumenAsistenciaCurso(
            $cursoId,
            $anioId
        );

        require "../views/asistencia/resumen_curso.php";
    }

    /* ==========================================
   LISTAR CURSOS PARA ASISTENCIA
========================================== */
    public function listarCursos()
    {
        $anioId = $_GET['anio_id'] ?? $this->model->getAnioActualId();

        if (!$anioId) {
            die("No existe año académico configurado.");
        }

        $anios = $this->model->getAnios();
        $cursos = $this->model->getCursosConMatricula($anioId);
        $ultimasFechas = $this->model->getUltimaFechaAsistenciaPorCurso($anioId);

        require "../views/asistencia/cursos.php";
    }

    /* ==========================================
       GUARDAR ASISTENCIA MASIVA
    ========================================== */
    public function guardarAsistenciaMasiva()
    {
        $cursoId = $_POST['curso_id'];
        $anioId = $_POST['anio_id'];
        $fecha = $_POST['fecha'];
        $presentes = $_POST['presentes'] ?? [];

        $alumnos = $this->model->getAlumnosPorCurso($cursoId, $anioId);

        foreach ($alumnos as $alumno) {
            $presente = in_array($alumno['matricula_id'], $presentes) ? 1 : 0;
            $this->model->guardarAsistencia($alumno['matricula_id'], $fecha, $presente);
        }

        // Verificar y enviar alerta
        $alertaCtrl = new AlertaController();
        $resultadoAlerta = $alertaCtrl->verificarYEnviarAlertaAusencias((int) $cursoId, (int) $anioId, $fecha);

        header("Location: index.php?action=form_asistencia_masiva&curso_id=$cursoId&anio_id=$anioId&guardado=1&alerta=$resultadoAlerta");
        exit;
    }

    // Ejemplo simplificado dentro de tu controlador
    public function asistencia_pdf($anio_id, $curso_id)
    {
        if (empty($anio_id) || empty($curso_id)) {
            die("Faltan parámetros: Año ID o Curso ID");
        }

        $asistenciaModel = new Asistencia();
        $detalle = $asistenciaModel->getResumenAsistenciaCurso($curso_id, $anio_id);


        if (!$detalle) {
            die("No hay datos de asistencia para el Curso ID: $curso_id en el Año ID: $anio_id");
        }

        ob_start();
        require __DIR__ . '/../views/asistencia/asistencia_reporte_pdf.php';
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
            $dompdf->stream("Reporte_Asistencia.pdf", ["Attachment" => true]);
            exit;
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    /*
    LIBRO DE CLASS
    */
    public function libroClases()
    {
        $cursoId = $_GET['curso_id'] ?? null;
        $anioId = $_GET['anio_id'] ?? null;

        if (!$cursoId || !$anioId) {
            die("Faltan parámetros.");
        }

        $curso = $this->model->getCurso($cursoId);
        $alumnos = $this->model->getAlumnosPorCurso($cursoId, $anioId);
        $asistencia = $this->model->getAsistenciaLibro($cursoId, $anioId);
        $fechasAnio = $this->model->getFechasAnio($anioId);

        // Validar que el año tenga fechas configuradas
        if (empty($fechasAnio['sem1_inicio'])) {
            die("⚠️ El año seleccionado no tiene fechas de semestres configuradas. 
             <a href='index.php?action=anio_edit&id=$anioId'>Configurar ahora</a>");
        }

        require "../views/asistencia/libro_clases.php";
    }

    public function calendarioAlumno()
    {
        $matriculaId = (int) ($_GET['matricula_id'] ?? 0);

        if (!$matriculaId) {
            die("Falta matricula_id.");
        }

        $asistenciaMap = $this->model->getAsistenciaCalendarioAlumno($matriculaId);

        // Pasar como variable que espera la vista del calendario
        return $asistenciaMap;
    }

    public function libroClasesPdf(): void
    {
        $curso_id = (int) ($_GET['curso_id'] ?? 0);
        $anio_id = (int) ($_GET['anio_id'] ?? 0);
        $mes = $_GET['mes'] ?? date('Y-m');

        if (!$curso_id || !$anio_id)
            die('Parámetros inválidos.');

        $asistenciaModel = new Asistencia();

        $curso = $asistenciaModel->getCurso($curso_id);
        $anio = $asistenciaModel->getFechasAnio($anio_id);
        $alumnos = $asistenciaModel->getAlumnosPorCurso($curso_id, $anio_id);
        $asistencia = $asistenciaModel->getAsistenciaLibro($curso_id, $anio_id);

        // Calcular fechas hábiles del mes solicitado (solo días pasados)
        [$anioMes, $numMes] = explode('-', $mes);
        $inicio = new DateTime("$anioMes-$numMes-01");
        $finMes = new DateTime($inicio->format('Y-m-t'));
        $hoy = new DateTime('today');
        $finReal = $finMes < $hoy ? $finMes : $hoy;

        $periodo = new DatePeriod(
            $inicio,
            new DateInterval('P1D'),
            $finReal->modify('+1 day')
        );

        $fechas = [];
        foreach ($periodo as $fecha) {
            if ($fecha->format('N') <= 5) {
                $fechas[] = clone $fecha;
            }
        }

        if (empty($fechas)) {
            die('No hay días hábiles registrados para el mes seleccionado.');
        }

        // Calcular stats por alumno
        $statsAlumnos = [];
        foreach ($alumnos as $alumno) {
            $pres = 0;
            $aus = 0;
            $total = 0;
            $fechaMatricula = !empty($alumno['fecha_matricula'])
                ? new DateTime($alumno['fecha_matricula'])
                : null;

            foreach ($fechas as $fecha) {
                if ($fechaMatricula && $fecha < $fechaMatricula)
                    continue;
                $f = $fecha->format('Y-m-d');
                $v = $asistencia[$alumno['matricula_id']][$f] ?? null;
                if ($v !== null) {
                    $total++;
                    $v == 1 ? $pres++ : $aus++;
                }
            }

            $statsAlumnos[$alumno['matricula_id']] = [
                'presentes' => $pres,
                'ausentes' => $aus,
                'total' => $total,
                'pct' => $total > 0 ? round($pres / $total * 100) : null,
            ];
        }

        $nombresMeses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];
        $nombreMes = $nombresMeses[$numMes] . ' ' . $anioMes;
        $diasCortos = ['Lu', 'Ma', 'Mi', 'Ju', 'Vi'];

        ob_start();
        require __DIR__ . '/../views/asistencia/libro_clases_pdf.php';
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream(
            "libro_clases_{$curso['nombre']}_{$mes}.pdf",
            ['Attachment' => true]
        );
        exit;
    }
}