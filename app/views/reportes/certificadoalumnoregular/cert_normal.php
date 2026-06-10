<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Certificado Alumno Regular</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: Arial, sans-serif;
    font-size: 11.5px;
    color: #000;
    background: #fff;
    margin: 0;
    padding: 0;
}

.page {
    margin: 28px 45px 0 45px;
}

/* ── ENCABEZADO 3 LOGOS ── */
.header {
    display: table;
    width: 100%;
    margin-bottom: 24px;
}
.header-left   { display: table-cell; width: 28%; vertical-align: middle; }
.header-center { display: table-cell; width: 44%; vertical-align: middle; text-align: center; }
.header-right  { display: table-cell; width: 28%; vertical-align: middle; text-align: right; }
.header img    { height: 68px; width: auto; }

/* ── TÍTULO ── */
.cert-titulo {
    text-align: center;
    font-size: 13px;
    font-weight: bold;
    text-transform: uppercase;
    margin-bottom: 22px;
    letter-spacing: 0.5px;
}

/* ── CUERPO ── */
.cert-body {
    text-align: justify;
    line-height: 1.85;
    font-size: 11.5px;
    margin-bottom: 18px;
}
.cert-body p { margin-bottom: 10px; }

.bold  { font-weight: bold; }
.upper { text-transform: uppercase; }
.quot  { font-weight: bold; }

/* ── PRESENTADO EN ── */
.presentado {
    margin: 16px 0 0 30px;
    font-size: 11.5px;
    font-weight: bold;
}

/* ── FIRMA ── */
.firma-wrap {
    text-align: right;
    margin-top: 60px;
    margin-bottom: 10px;
}
.firma-wrap img {
    height: 180px;
    width: auto;
    display: block;
    margin-left: auto;
}
.firma-nombre {
    font-weight: bold;
    font-size: 11.5px;
    text-transform: uppercase;
    margin-top: 4px;
}
.firma-cargo {
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;
}

/* ── FECHA Y VB ── */
.fecha-vb {
    margin-top: 30px;
    font-size: 11px;
}
.fecha-vb p { margin-bottom: 4px; }
.vb img {
    height: 32px;
    width: auto;
    vertical-align: middle;
}

/* ── PIE DE PÁGINA ── */
.pie-pagina {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
}
.pie-pagina img {
    width: 100%;
    display: block;
}
</style>
</head>
<body>

<?php
$base      = 'http://localhost:8080/app/public/img/';
$logo1     = $base . 'certificado_1.png';
$logo2     = $base . 'certificado_3.png';   // orden: 1, 3, 2
$logo3     = $base . 'certificado_2.png';
$timbre    = $base . 'TIMBRE_JJACH.png';
$piePag    = $base . 'pie_pagina_certificado.png';

$nombreCompleto = strtoupper(trim("$nombre $apepat " . ($apemat ?? '')));
$runCompleto    = $run . ($codver ? '-' . $codver : '');
$esF            = ($sexo ?? '') === 'F';
$donDona        = $esF ? 'Srta.' : 'Sr.';
$tratamiento    = $esF ? 'Don (ña):' : 'Don (ña):';
$alumnaO        = $esF ? 'alumna' : 'alumno';
$laEl           = $esF ? 'la' : 'el';
$matriculadaO   = $esF ? 'matriculada' : 'matriculado';

// Fecha en español
$diasES   = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
$mesesES  = ['enero','febrero','marzo','abril','mayo','junio',
             'julio','agosto','septiembre','octubre','noviembre','diciembre'];
$fechaTexto = $diasES[date('w')] . ', ' . date('j') . ' de ' . $mesesES[date('n')-1] . ' de ' . date('Y');

// Fecha de nacimiento
$nacTexto = '';
if (!empty($alumno['fechanac'] ?? $fechanac ?? '')) {
    $fn = new DateTime($fechanac ?? '');
    $nacTexto = $fn->format('d') . ' ' . $mesesES[(int)$fn->format('n')-1] . ' ' . $fn->format('Y');
}
?>

<!-- ══ PIE (fixed, primero en el DOM) ══ -->
<div class="pie-pagina">
    <img src="<?= $piePag ?>" alt="Pie de página">
</div>

<div class="page">

    <!-- ══ ENCABEZADO ══ -->
    <div class="header">
        <div class="header-left">
            <img src="<?= $logo1 ?>" alt="Educación Pública">
        </div>
        <div class="header-center">
            <img src="<?= $logo2 ?>" alt="Los Álamos">
        </div>
        <div class="header-right">
            <img src="<?= $logo3 ?>" alt="CEIA">
        </div>
    </div>

    <!-- ══ TÍTULO ══ -->
    <div class="cert-titulo">Certificado Alumno Regular.</div>

    <!-- ══ CUERPO ══ -->
    <div class="cert-body">
        <p>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Certifico Que <?= $tratamiento ?>
            <span class="bold upper"><?= htmlspecialchars($nombreCompleto) ?>.</span>
            Rut. <span class="bold"><?= htmlspecialchars($runCompleto) ?></span>,
            <?php if (!empty($nacTexto)): ?>
                Nacido (a) el <span class="bold"><?= $nacTexto ?></span>,
            <?php endif ?>
            Es Alumno (a) Regular del
            <span class="quot">"<span class="bold upper"><?= htmlspecialchars($curso) ?></span>"</span>
            Año de Enseñanza<?php /* Puedes ajustar el nivel aquí */ ?>,
            matriculado (a) bajo el número de registro Escolar
            <span class="bold">N°<?= str_pad($matricula_id ?? '0', 3, '0', STR_PAD_LEFT) ?></span>,
            para el Año <span class="bold"><?= htmlspecialchars($anio) ?></span>,
            en el Centro de Educación Integrada de Adultos,
            <span class="bold">"Juanita Zúñiga Fuentes"</span>,
            Establecimiento Dependiente de La Ilustre Municipalidad de Parral,
            Decreto Cooperador &nbsp;<span class="bold">N.º 3290 / 81</span>&nbsp;
            del Ministerio de Educación.
        </p>
        <p style="margin-left:30px;">
            Se extiende el presente Certificado, para ser presentado por el interesado (a) en:
        </p>
        <p class="presentado"><?= htmlspecialchars($motivo ?? 'los fines que estime conveniente') ?></p>
    </div>

    <!-- ══ FIRMA ══ -->
    <div class="firma-wrap">
        <img src="<?= $timbre ?>" alt="Firma Director">
    </div>

    <!-- ══ FECHA Y Vº Bº ══ -->
    <div class="fecha-vb">
        <p><?= ucfirst($fechaTexto) ?></p>
        <p class="vb">
            Vº Bº: <img src="<?= $timbre ?>" alt="VB"> JJACH/MACA/PRPC/rlrd
        </p>
    </div>

</div><!-- /page -->

</body>
</html>
