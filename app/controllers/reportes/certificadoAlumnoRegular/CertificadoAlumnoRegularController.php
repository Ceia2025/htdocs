<?php

require_once __DIR__ . '/../../../models/reportes/certificadoAlumnoRegular/CertificadoAlumnoRegular.php';
require_once __DIR__ . '/../../../libs/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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
        if (!$alumno_id) { http_response_code(400); die('ID no válido'); }

        $data = $this->model->getDatosAlumno($alumno_id);
        if (!$data) { http_response_code(404); die('Alumno no encontrado'); }

        $html = $this->renderTemplate('cert_normal', $data);
        $this->streamPdf($html, "CertAlumnoRegular_{$data['run']}.pdf");
    }

    // ─── Genera PDF: Certificado Con Asistencia ────────────────────────────
    public function pdfConAsistencia(): void
    {
        $alumno_id = (int) ($_GET['alumno_id'] ?? 0);
        if (!$alumno_id) { http_response_code(400); die('ID no válido'); }

        $data = $this->model->getDatosAlumno($alumno_id);
        if (!$data) { http_response_code(404); die('Alumno no encontrado'); }

        $data['asistencia_mensual'] = $this->model->getAsistenciaMensual(
            (int) $data['matricula_id']
        );

        $html = $this->renderTemplate('cert_con_asistencia', $data);
        $this->streamPdf($html, "CertAsistencia_{$data['run']}.pdf");
    }

    // ─── Helpers ───────────────────────────────────────────────────────────

    private function renderTemplate(string $template, array $data): string
    {
        extract($data);   // variables disponibles en la vista
        ob_start();
        require __DIR__ . "/../../../views/reportes/certificadoalumnoregular/{$template}.php";
        return ob_get_clean();
    }

    private function streamPdf(string $html, string $filename): void
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }
}
