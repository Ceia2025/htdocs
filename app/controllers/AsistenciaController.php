<?php
require_once __DIR__ . '/../models/Asistencia.php';

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

        if (!$cursoId || !$anioId) {
            die("Faltan parámetros.");
        }

        if ($fecha > date("Y-m-d")) {
            die("No se puede registrar asistencia futura.");
        }

        $alumnos = $this->model->getAlumnosPorCurso($cursoId, $anioId);
        $curso = $this->model->getCurso($cursoId);
        $fechasAnio = $this->model->getFechasAnio($anioId);
        $fechasConAsistencia = $this->model->getFechasConAsistencia($cursoId, $anioId);

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

        header("Location: index.php?action=form_asistencia_masiva&curso_id=$cursoId&anio_id=$anioId&guardado=1");
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
}