<?php
require_once __DIR__ . '/../libs/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfHelper
{
    /**
     * Genera un PDF desde HTML y lo envía al navegador.
     *
     * @param string $html        HTML ya renderizado (ob_start + require de la vista)
     * @param string $filename    Nombre del archivo .pdf
     * @param bool   $inline      true = "Ver PDF" (se abre en el navegador), false = "Descargar" (forzado)
     * @param string $paper       Tamaño de papel
     * @param string $orientation 'portrait' | 'landscape'
     */
    public static function stream(
        string $html,
        string $filename,
        bool $inline = false,
        string $paper = 'letter',
        string $orientation = 'portrait'
    ): void {
        try {
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper($paper, $orientation);
            $dompdf->render();

            header('Content-Type: application/pdf');
            $dompdf->stream($filename, ['Attachment' => !$inline]);
            exit;
        } catch (\Exception $e) {
            die("Error al generar PDF: " . $e->getMessage());
        }
    }
}