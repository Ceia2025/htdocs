<?php
require_once __DIR__ . '/../../models/reportes/ReporteAsistencia.php';
require_once __DIR__ . '/../../libs/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteController
{
    private ReporteAsistencia $model;

    public function __construct()
    {
        $this->model = new ReporteAsistencia();
    }

    /* ==========================================
       VISTA PRINCIPAL DE REPORTES
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

        $mesKey = $_GET['mes'] ?? null;

        $cursos = $anioId ? $this->model->getCursos($anioId) : [];
        $meses = $anioId ? $this->model->getMesesConAsistencia($anioId, $cursoId) : [];
        $resumenCursos = $anioId ? $this->model->getResumenCursos($anioId, $mesKey) : [];

        $datosReporte = [];
        if ($anioId && $cursoId) {
            $datosReporte = $this->model->getReportePorCurso($anioId, $cursoId, $mesKey);
        } elseif ($anioId) {
            $datosReporte = $this->model->getReporteGeneral($anioId, $mesKey);
        }

        require __DIR__ . '/../../views/reportes/asistencia/index.php';
    }

    /* ==========================================
       EXPORTAR CSV — POR CURSO
    ========================================== */
    public function csvCurso()
    {
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $cursoId = (int) ($_GET['curso_id'] ?? 0);
        $mesKey = $_GET['mes'] ?? null;

        if (!$anioId || !$cursoId) {
            die("Faltan parámetros.");
        }

        $datos = $this->model->getReportePorCurso($anioId, $cursoId, $mesKey);

        $nombreMes = $mesKey ? str_replace('-', '_', $mesKey) : 'acumulado';
        $cursosInfo = $this->model->getCursos($anioId);
        $cursoNombre = 'curso';
        foreach ($cursosInfo as $c) {
            if ($c['id'] == $cursoId) {
                $cursoNombre = $c['nombre'];
                break;
            }
        }

        $filename = "Asistencia_{$cursoNombre}_{$nombreMes}.csv";

        header('Content-Type: text/csv; charset=UTF-8');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Pragma: no-cache');

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM para Excel

        // Cabecera
        $cabecera = ['Curso', 'RUN', 'Nombre del Estudiante', 'Asistencia Acumulada (%)'];
        if ($mesKey) {
            $cabecera[] = 'Asistencia Mes (%)';
            $cabecera[] = 'Días registrados mes';
            $cabecera[] = 'Días presentes mes';
        }
        $cabecera[] = 'Total clases';
        $cabecera[] = 'Total presentes';

        fputcsv($out, $cabecera, ';');

        foreach ($datos as $al) {
            $fila = [
                $al['curso'],
                $al['run'],
                $al['apepat'] . ' ' . $al['apemat'] . ' ' . $al['nombre'],
                $al['pct_acumulado'] !== null ? $al['pct_acumulado'] . '%' : '—',
            ];
            if ($mesKey) {
                $fila[] = $al['pct_mes'] !== null ? $al['pct_mes'] . '%' : '—';
                $fila[] = $al['total_mes'] ?? '—';
                $fila[] = $al['presentes_mes'] ?? '—';
            }
            $fila[] = $al['total_clases'];
            $fila[] = $al['presentes_acum'];

            fputcsv($out, $fila, ';');
        }

        fclose($out);
        exit;
    }

    /* ==========================================
       EXPORTAR CSV — GENERAL (todos los cursos)
    ========================================== */
    public function csvGeneral()
    {
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $mesKey = $_GET['mes'] ?? null;

        if (!$anioId)
            die("Falta anio_id.");

        $general = $this->model->getReporteGeneral($anioId, $mesKey);
        $nombreMes = $mesKey ? str_replace('-', '_', $mesKey) : 'acumulado';
        $filename = "Asistencia_General_{$nombreMes}.csv";

        header('Content-Type: text/csv; charset=UTF-8');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Pragma: no-cache');

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

        $cabecera = ['Curso', 'RUN', 'Nombre del Estudiante', 'Asistencia Acumulada (%)'];
        if ($mesKey) {
            $cabecera[] = 'Asistencia Mes (%)';
        }
        fputcsv($out, $cabecera, ';');

        foreach ($general as $cursoNombre => $alumnos) {
            foreach ($alumnos as $al) {
                $fila = [
                    $cursoNombre,
                    $al['run'],
                    $al['apepat'] . ' ' . $al['apemat'] . ' ' . $al['nombre'],
                    $al['pct_acumulado'] !== null ? $al['pct_acumulado'] . '%' : '—',
                ];
                if ($mesKey) {
                    $fila[] = $al['pct_mes'] !== null ? $al['pct_mes'] . '%' : '—';
                }
                fputcsv($out, $fila, ';');
            }
        }

        fclose($out);
        exit;
    }

    /* ==========================================
       EXPORTAR PDF — POR CURSO
    ========================================== */
    public function pdfCurso()
    {
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $cursoId = (int) ($_GET['curso_id'] ?? 0);
        $mesKey = $_GET['mes'] ?? null;

        if (!$anioId || !$cursoId)
            die("Faltan parámetros.");

        $datos = $this->model->getReportePorCurso($anioId, $cursoId, $mesKey);

        $cursosInfo = $this->model->getCursos($anioId);
        $cursoNombre = 'Curso';
        foreach ($cursosInfo as $c) {
            if ($c['id'] == $cursoId) {
                $cursoNombre = $c['nombre'];
                break;
            }
        }

        $anios = $this->model->getAnios();
        $anioNombre = '';
        foreach ($anios as $a) {
            if ($a['id'] == $anioId) {
                $anioNombre = $a['anio'];
                break;
            }
        }

        $meses = $this->model->getMesesConAsistencia($anioId, $cursoId);
        $mesNombre = 'Acumulado anual';
        foreach ($meses as $m) {
            if ($m['mes_key'] === $mesKey) {
                $mesNombre = $m['mes_nombre'];
                break;
            }
        }

        ob_start();
        require __DIR__ . '/../../views/reportes/asistencia/pdf_curso.php';
        $html = ob_get_clean();

        $this->generarPDF($html, "Asistencia_{$cursoNombre}.pdf");
    }

    /* ==========================================
       EXPORTAR PDF — GENERAL
    ========================================== */
    public function pdfGeneral()
    {
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $mesKey = $_GET['mes'] ?? null;

        if (!$anioId)
            die("Falta anio_id.");

        $general = $this->model->getReporteGeneral($anioId, $mesKey);
        $resumen = $this->model->getResumenCursos($anioId, $mesKey);

        $anios = $this->model->getAnios();
        $anioNombre = '';
        foreach ($anios as $a) {
            if ($a['id'] == $anioId) {
                $anioNombre = $a['anio'];
                break;
            }
        }

        $meses = $this->model->getMesesConAsistencia($anioId);
        $mesNombre = 'Acumulado anual';
        foreach ($meses as $m) {
            if ($m['mes_key'] === $mesKey) {
                $mesNombre = $m['mes_nombre'];
                break;
            }
        }

        ob_start();
        require __DIR__ . '/../../views/reportes/asistencia/pdf_general.php';
        $html = ob_get_clean();

        $this->generarPDF($html, "Asistencia_General_{$anioNombre}.pdf");
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