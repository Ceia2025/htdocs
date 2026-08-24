<?php
require_once __DIR__ . '/../../../models/reportes/procedencia_alum/ReporteProcedenciaAlumnos.php';
require_once __DIR__ . '/../../../controllers/AuthController.php';
require_once __DIR__ . '/../../../helpers/PdfHelper.php';

class ReporteProcedenciaAlumnosController
{
    private ReporteProcedenciaAlumnos $model;

    public function __construct()
    {
        $this->model = new ReporteProcedenciaAlumnos();
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

        $estado = isset($_GET['estado']) && in_array($_GET['estado'], ['matriculados', 'retirados'], true)
            ? $_GET['estado']
            : null;

        $cursos = $anioId ? $this->model->getCursos($anioId) : [];

        $reporte = [];
        $resumenProcedencias = [];
        $resumenGlobal = ['total' => 0, 'total_matriculados' => 0, 'total_retirados' => 0, 'porcentaje' => 0.0];

        if ($anioId) {
            $reporte = $this->model->getReporteGeneral($anioId, $cursoId, $estado);
            $resumenProcedencias = $this->model->getResumenPorProcedencia($anioId, $cursoId);
            $resumenGlobal = $this->model->getResumenGlobal($anioId, $cursoId);
        }

        require __DIR__ . '/../../../views/reportes/procedencia_alum/reportesProcedencia.php';
    }

    public function pdfProcedencia()
    {
        $anioId = (int) ($_GET['anio_id'] ?? 0);
        $cursoId = isset($_GET['curso_id']) && $_GET['curso_id'] !== ''
            ? (int) $_GET['curso_id']
            : null;
        $estado = isset($_GET['estado']) && in_array($_GET['estado'], ['matriculados', 'retirados'], true)
            ? $_GET['estado']
            : null;
        $inline = ($_GET['preview'] ?? '') === '1';

        if (!$anioId)
            die("Falta anio_id.");

        $reporte = $this->model->getReporteGeneral($anioId, $cursoId, $estado);
        $resumenProcedencias = $this->model->getResumenPorProcedencia($anioId, $cursoId);
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

        $estadoNombre = match ($estado) {
            'matriculados' => 'Solo matriculados',
            'retirados' => 'Solo retirados',
            default => 'Matriculados y retirados',
        };

        ob_start();
        require __DIR__ . '/../../../views/reportes/procedencia_alum/reportesProcedencia_pdf.php';
        $html = ob_get_clean();

        $slug = $cursoId ? $cursoNombre : 'General';
        PdfHelper::stream($html, "ReporteProcedencia_{$slug}_{$anioNombre}.pdf", $inline, 'letter', 'portrait');
    }
}