<?php
require_once __DIR__ . '/../../../models/reportes/pie/ReportePie.php';
require_once __DIR__ . '/../../../controllers/AuthController.php';
require_once __DIR__ . '/../../../helpers/PdfHelper.php';

class ReportePieController
{
    private ReportePie $model;

    public function __construct()
    {
        $this->model = new ReportePie();
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
        $resumenCategorias = [];
        $resumenGlobal = ['total_matriculados' => 0, 'total_pie' => 0, 'porcentaje' => 0.0];

        if ($anioId) {
            $reporte = $this->model->getReporteGeneral($anioId, $cursoId);
            $resumenCursos = $this->model->getResumenPorCurso($anioId, $cursoId);
            $resumenCategorias = $this->model->getResumenPorCategoria($anioId, $cursoId);
            $resumenGlobal = $this->model->getResumenGlobal($anioId, $cursoId);
        }

        require __DIR__ . '/../../../views/reportes/pie/reportesPie.php';
    }

    public function pdfPie()
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
        $resumenCategorias = $this->model->getResumenPorCategoria($anioId, $cursoId);
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
        require __DIR__ . '/../../../views/reportes/pie/reportesPie_pdf.php';
        $html = ob_get_clean();

        $slug = $cursoId ? $cursoNombre : 'General';
        PdfHelper::stream($html, "ReportePie_{$slug}_{$anioNombre}.pdf", $inline, 'letter', 'portrait');
    }

    public function pdfPieNotas()
    {
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $cursoId = isset($_GET['curso_id']) && $_GET['curso_id'] !== ''
            ? (int) $_GET['curso_id']
            : null;
        $inline = ($_GET['preview'] ?? '') === '1';

        if (!$anioId)
            die("Falta anio_id.");

        $reporteNotas = $this->model->getNotasPie($anioId, $cursoId);
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
        require __DIR__ . '/../../../views/reportes/pie/reportesPieNotas_pdf.php';
        $html = ob_get_clean();

        $slug = $cursoId ? $cursoNombre : 'General';
        PdfHelper::stream($html, "ReportePieNotas_{$slug}_{$anioNombre}.pdf", $inline, 'letter', 'portrait');
    }
}