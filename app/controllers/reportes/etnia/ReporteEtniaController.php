<?php
require_once __DIR__ . '/../../../models/reportes/etnia/ReporteEtnia.php';
require_once __DIR__ . '/../../../libs/dompdf/vendor/autoload.php';

require_once __DIR__ . '/../../../config/Connection.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteEtniaController
{
    private ReporteEtnia $model;

    public function __construct()
    {
        $this->model = new ReporteEtnia();
    }

    /* ==========================================
       VISTA PRINCIPAL
    ========================================== */
    public function index()
    {
        $anios = $this->model->getAnios();

        $anioId = isset($_GET['anio_id']) && $_GET['anio_id'] !== ''
            ? (int) $_GET['anio_id']
            : ($anios[0]['id'] ?? null);

        $cursoId = isset($_GET['curso_id']) && $_GET['curso_id'] !== ''
            ? (int) $_GET['curso_id']
            : null;

        $etniaFiltro = $_GET['etnia'] ?? null;
        if ($etniaFiltro === '') $etniaFiltro = null;

        $cursos  = $anioId ? $this->model->getCursos($anioId) : [];
        $etnias  = $this->model->getEtnias();

        $reporte       = [];
        $resumenCursos = [];
        $resumenGlobal = [];

        if ($anioId) {
            $reporte       = $this->model->getReporteGeneral($anioId, $cursoId, $etniaFiltro);
            $resumenCursos = $this->model->getResumenPorCurso($anioId, $cursoId, $etniaFiltro);
            $resumenGlobal = $this->model->getResumenGlobal($anioId, $cursoId, $etniaFiltro);
        }

        require __DIR__ . '/../../../views/reportes/etnia/index.php';
    }

    /* ==========================================
       EXPORTAR PDF
    ========================================== */
    public function pdfEtnia()
    {
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $cursoId = isset($_GET['curso_id']) && $_GET['curso_id'] !== ''
            ? (int) $_GET['curso_id']
            : null;
        $etniaFiltro = $_GET['etnia'] ?? null;
        if ($etniaFiltro === '') $etniaFiltro = null;

        if (!$anioId) die("Falta anio_id.");

        $reporte       = $this->model->getReporteGeneral($anioId, $cursoId, $etniaFiltro);
        $resumenCursos = $this->model->getResumenPorCurso($anioId, $cursoId, $etniaFiltro);
        $resumenGlobal = $this->model->getResumenGlobal($anioId, $cursoId, $etniaFiltro);

        $anios = $this->model->getAnios();
        $anioNombre = '';
        foreach ($anios as $a) {
            if ($a['id'] == $anioId) { $anioNombre = $a['anio']; break; }
        }

        $cursos = $this->model->getCursos($anioId);
        $cursoNombre = 'Todos los cursos';
        foreach ($cursos as $c) {
            if ($c['id'] == $cursoId) { $cursoNombre = $c['nombre']; break; }
        }

        $etnias = $this->model->getEtnias();

        ob_start();
        require __DIR__ . '/../../../views/reportes/etnia/reporteEtnia_pdf.php';
        $html = ob_get_clean();

        $slug = $cursoId ? $cursoNombre : 'General';
        $this->generarPDF($html, "ReporteEtnia_{$slug}_{$anioNombre}.pdf");
    }

    /* ==========================================
       EXPORTAR CSV
    ========================================== */
    public function csvEtnia()
    {
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $cursoId = isset($_GET['curso_id']) && $_GET['curso_id'] !== ''
            ? (int) $_GET['curso_id']
            : null;
        $etniaFiltro = $_GET['etnia'] ?? null;
        if ($etniaFiltro === '') $etniaFiltro = null;

        if (!$anioId) die("Falta anio_id.");

        $reporte = $this->model->getReporteGeneral($anioId, $cursoId, $etniaFiltro);

        $anios = $this->model->getAnios();
        $anioNombre = '';
        foreach ($anios as $a) {
            if ($a['id'] == $anioId) { $anioNombre = $a['anio']; break; }
        }

        $filename = "ReporteEtnia_{$anioNombre}.csv";

        header('Content-Type: text/csv; charset=UTF-8');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Pragma: no-cache');

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM para Excel

        fputcsv($out, ['Curso', 'RUN', 'Nombre del Estudiante', 'Sexo', 'Etnia / Pueblo Originario'], ';');

        foreach ($reporte as $cursoNombreLoop => $alumnos) {
            foreach ($alumnos as $al) {
                fputcsv($out, [
                    $cursoNombreLoop,
                    $al['run'],
                    $al['apepat'] . ' ' . $al['apemat'] . ' ' . $al['nombre'],
                    $al['sexo'] === 'F' ? 'Femenino' : ($al['sexo'] === 'M' ? 'Masculino' : '—'),
                    $al['cod_etnia'],
                ], ';');
            }
        }

        fclose($out);
        exit;
    }

    /* ==========================================
       HELPER: generar y enviar PDF
    ========================================== */
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
}