<?php
require_once __DIR__ . '/../../../models/reportes/matricula/ReporteMatricula.php';
require_once __DIR__ . '/../../../controllers/AuthController.php';
require_once __DIR__ . '/../../../helpers/PdfHelper.php';

class ReporteMatriculaController
{
    private ReporteMatricula $model;

    public function __construct()
    {
        $this->model = new ReporteMatricula();
    }

    public function index()
    {
        $anios = $this->model->getAnios();

        $anioId = isset($_GET['anio_id']) && $_GET['anio_id'] !== ''
            ? (int) $_GET['anio_id']
            : ($anios[0]['id'] ?? null);

        $cursoId = isset($_GET['curso_id']) && $_GET['curso_id'] !== ''
            ? (int) $_GET['curso_id']
            : null;

        $cursos = $anioId ? $this->model->getCursos($anioId) : [];

        $reporte = [];
        $resumenCursos = [];
        $resumenGlobal = ['total_matriculados' => 0, 'total_activos' => 0, 'porcentaje' => 0.0];

        if ($anioId) {
            $reporte = $this->model->getReporteGeneral($anioId, $cursoId);
            $resumenCursos = $this->model->getResumenPorCurso($anioId, $cursoId);
            $resumenGlobal = $this->model->getResumenGlobal($anioId, $cursoId);
        }

        require __DIR__ . '/../../../views/reportes/matricula/reportesMatricula.php';
    }

    public function pdfMatricula()
    {
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $cursoId = isset($_GET['curso_id']) && $_GET['curso_id'] !== ''
            ? (int) $_GET['curso_id']
            : null;
        $inline = ($_GET['preview'] ?? '') === '1';

        if (!$anioId)
            die("Falta anio_id.");

        $reporte = $this->model->getReporteGeneral($anioId, $cursoId);
        $resumenCursos = $this->model->getResumenPorCurso($anioId, $cursoId);
        $resumenGlobal = $this->model->getResumenGlobal($anioId, $cursoId);

        $anios = $this->model->getAnios();
        $anioNombre = '';
        foreach ($anios as $a) {
            if ($a['id'] == $anioId) {
                $anioNombre = $a['anio'];
                break;
            }
        }

        $cursos = $this->model->getCursos($anioId);
        $cursoNombre = 'Todos los cursos';
        foreach ($cursos as $c) {
            if ($c['id'] == $cursoId) {
                $cursoNombre = $c['nombre'];
                break;
            }
        }

        ob_start();
        require __DIR__ . '/../../../views/reportes/matricula/reportesMatricula_pdf.php';
        $html = ob_get_clean();

        $slug = $cursoId ? $cursoNombre : 'General';
        PdfHelper::stream($html, "ReporteMatricula_{$slug}_{$anioNombre}.pdf", $inline, 'letter', 'portrait');
    }
}