<?php
/**
 * Helper compartido para las vistas de PDF de Reportes > Notas.
 * Requiere que Nota.php ya esté cargado (usa Nota::aprobada()).
 */

if (!defined('PDF_NOTAS_IMG_BASE')) {
    define('PDF_NOTAS_IMG_BASE', 'http://localhost:8080/app/public/img/');
    define('PDF_NOTAS_LOGO_IZQ', PDF_NOTAS_IMG_BASE . 'certificado_1.png');
    define('PDF_NOTAS_LOGO_CENTRO', PDF_NOTAS_IMG_BASE . 'certificado_3.png');
    define('PDF_NOTAS_LOGO_DER', PDF_NOTAS_IMG_BASE . 'certificado_2.png');
    define('PDF_NOTAS_PIE_PAGINA', PDF_NOTAS_IMG_BASE . 'pie_pagina_certificado.png');
}

if (!function_exists('pdfNotasHeaderTresLogos')) {
    function pdfNotasHeaderTresLogos(): void
    {
        ?>
        <div class="header">
            <div class="h-left"><img src="<?= PDF_NOTAS_LOGO_IZQ ?>" alt="Educación Pública"></div>
            <div class="h-center"><img src="<?= PDF_NOTAS_LOGO_CENTRO ?>" alt="Los Álamos"></div>
            <div class="h-right"><img src="<?= PDF_NOTAS_LOGO_DER ?>" alt="CEIA"></div>
        </div>
        <?php
    }
}

if (!function_exists('pdfNotasPiePagina')) {
    function pdfNotasPiePagina(): void
    {
        ?>
        <div class="pie-pagina"><img src="<?= PDF_NOTAS_PIE_PAGINA ?>" alt="Pie"></div>
        <?php
    }
}

if (!function_exists('pdfNotasBarraHtml')) {
    /** Barra proporcional (referencia 7.0), verde si aprobado, rojo si no. */
    function pdfNotasBarraHtml(?float $v): string
    {
        if ($v === null) return '';
        $pct   = min(100, round(($v / 7.0) * 100));
        $color = Nota::aprobada($v) ? '#16a34a' : '#dc2626';
        return '<span class="barra-wrap"><span class="barra-fill" style="width:' . $pct . '%;background:' . $color . ';"></span></span>';
    }
}

if (!function_exists('pdfNotasMedallaClase')) {
    function pdfNotasMedallaClase(int $i): string
    {
        if ($i === 0) return 'med-1';
        if ($i === 1) return 'med-2';
        if ($i === 2) return 'med-3';
        return '';
    }
}