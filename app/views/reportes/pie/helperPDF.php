<?php
if (!defined('PDF_PIE_LOGO_PATH')) {
    define('PDF_PIE_LOGO_PATH', __DIR__ . '/../../../public/img/LOGO CEIA.jpg');
    define('PDF_PIE_LOGO_URL', 'http://localhost:8080/app/public/img/LOGO%20CEIA.jpg');
}

if (!function_exists('pdfPieCssComun')) {
    function pdfPieCssComun(): string
    {
        static $css = null;
        if ($css === null) {
            $css = file_get_contents(__DIR__ . '/pdf-common.css');
        }
        return $css;
    }
}

if (!function_exists('pdfPieHeader')) {
    /** Encabezado institucional (logo único) + ficha de título. */
    function pdfPieHeader(string $tituloFicha): void
    {
        $logoExists = file_exists(PDF_PIE_LOGO_PATH);
        ?>
        <div class="header">
            <div class="header-left">
                <?php if ($logoExists): ?>
                    <img src="<?= PDF_PIE_LOGO_URL ?>" style="width:80px;">
                <?php else: ?>
                    <strong style="color:#004b8d;">CEIA Parral</strong>
                <?php endif ?>
            </div>
            <div class="header-right">
                <div class="inst-nombre">Centro de Educación Integrada de Adultos</div>
                <div class="inst-sub">"Juanita Zúñiga Fuentes" – Parral</div>
                <div class="ficha-titulo"><?= htmlspecialchars($tituloFicha) ?></div>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('pdfPieFooter')) {
    function pdfPieFooter(): void
    {
        ?>
        <div class="footer">
            Reporte generado automáticamente — Sistema SAAT · C.E.I.A. Parral <?= date('Y') ?>
            <br>Desarrollado por Daniel Scarlazzetta
        </div>
        <?php
    }
}