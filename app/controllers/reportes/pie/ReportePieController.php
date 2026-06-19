<?php
require_once __DIR__ . '/../../../models/reportes/pie/ReportePie.php';
require_once __DIR__ . '/../../../controllers/AuthController.php';
require_once __DIR__ . '/../../../libs/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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
        $this->generarPDF($html, "ReportePie_{$slug}_{$anioNombre}.pdf");
    }


    private function generarPDF(string $html, string $filename): void
    {
        try {
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('letter', 'portrait');
            $dompdf->render();

            header('Content-Type: application/pdf');
            $dompdf->stream($filename, ['Attachment' => true]);
            exit;
        } catch (Exception $e) {
            die("Error al generar PDF: " . $e->getMessage());
        }
    }

    public function pdfPieNotas()
    {
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $cursoId = isset($_GET['curso_id']) && $_GET['curso_id'] !== ''
            ? (int) $_GET['curso_id']
            : null;

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
        $this->generarPDF($html, "ReportePieNotas_{$slug}_{$anioNombre}.pdf");
    }

}