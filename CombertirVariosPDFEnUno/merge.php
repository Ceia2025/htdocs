<?php
require_once __DIR__ . '/FPDI-2.6.4/src/autoload.php';
require_once __DIR__ . '/FPDF-master/fpdf.php';

use setasign\Fpdi\Fpdi;

if (!empty($_FILES['pdfs']['name'][0])) {
    $pdf = new FPDI();

    foreach ($_FILES['pdfs']['tmp_name'] as $file) {
        $pageCount = $pdf->setSourceFile($file);
        for ($i = 1; $i <= $pageCount; $i++) {
            $tplId = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tplId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplId);
        }
    }

    // Guardar PDF en el servidor
    $pdf->Output('F', __DIR__ . '/documento_unido.pdf');

    // Redirigir a index.php con un flag para abrir el modal
    header("Location: index.php?merged=1");
    exit;
} else {
    echo "No subiste archivos.";
}