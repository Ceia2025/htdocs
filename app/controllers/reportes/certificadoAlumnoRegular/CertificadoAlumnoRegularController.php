<?php

require_once __DIR__ . '/../../../models/reportes/certificadoAlumnoRegular/CertificadoAlumnoRegular.php';
require_once __DIR__ . '/../../../helpers/PdfHelper.php';

class CertificadoAlumnoRegularController
{
    private CertificadoAlumnoRegular $model;

    public function __construct()
    {
        $this->model = new CertificadoAlumnoRegular();
    }

    // ─── Vista principal (buscador) ────────────────────────────────────────
    public function index(): void
    {
        include __DIR__ . '/../../../views/reportes/certificadoalumnoregular/index.php';
    }

    // ─── AJAX: búsqueda de alumnos ─────────────────────────────────────────
    public function buscar(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        $term = trim($_GET['term'] ?? '');

        if (strlen($term) < 2) {
            echo json_encode([]);
            exit;
        }

        echo json_encode($this->model->buscarAlumnos($term));
        exit;
    }

    // ─── Genera PDF: Certificado Normal (sin detalle de asistencia) ────────
    public function pdfNormal(): void
    {
        $alumno_id = (int) ($_GET['alumno_id'] ?? 0);
        if (!$alumno_id) {
            http_response_code(400);
            die('ID no válido');
        }

        $data = $this->model->getDatosAlumno($alumno_id);
        if (!$data) {
            http_response_code(404);
            die('Alumno no encontrado');
        }

        $data['motivo'] = trim($_GET['motivo'] ?? '') ?: 'los fines que estime conveniente';
        $data['fecha'] = preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['fecha'] ?? '') ? $_GET['fecha'] : '';
        $inline = ($_GET['preview'] ?? '') === '1';

        $html = $this->renderTemplate('cert_normal', $data);
        PdfHelper::stream($html, "CertAlumnoRegular_{$data['run']}.pdf", $inline, 'letter', 'portrait');
    }

    // ─── Genera PDF: Certificado con Asistencia ────────────────────────────
    public function pdfConAsistencia(): void
    {
        $alumno_id = (int) ($_GET['alumno_id'] ?? 0);
        if (!$alumno_id) {
            http_response_code(400);
            die('ID no válido');
        }

        $data = $this->model->getDatosAlumno($alumno_id);
        if (!$data) {
            http_response_code(404);
            die('Alumno no encontrado');
        }

        $data['motivo'] = trim($_GET['motivo'] ?? '') ?: 'los fines que estime conveniente';
        $data['fecha'] = preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['fecha'] ?? '') ? $_GET['fecha'] : '';
        $inline = ($_GET['preview'] ?? '') === '1';

        $data['asistencia_mensual'] = $this->model->getAsistenciaMensual(
            (int) $data['matricula_id']
        );

        $html = $this->renderTemplate('cert_con_asistencia', $data);
        PdfHelper::stream($html, "CertAsistencia_{$data['run']}.pdf", $inline, 'letter', 'portrait');
    }

    // ─── Helpers ───────────────────────────────────────────────────────────

    private function renderTemplate(string $template, array $data): string
    {
        extract($data);   // variables disponibles en la vista
        ob_start();
        require __DIR__ . "/../../../views/reportes/certificadoalumnoregular/{$template}.php";
        return ob_get_clean();
    }
}