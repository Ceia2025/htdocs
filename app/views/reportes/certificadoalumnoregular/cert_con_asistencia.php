<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Certificado Alumno Regular – Con Asistencia</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

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

        .header-left {
            display: table-cell;
            width: 28%;
            vertical-align: top;
        }

        .header-center {
            display: table-cell;
            width: 30%;
            vertical-align: top;
            text-align: center;
        }

        .header-right {
            display: table-cell;
            width: 28%;
            vertical-align: top;
            text-align: right;
        }

        .header img {
            height: 68px;
            width: auto;
        }

        .header-center img {
            height: 28px;
            width: auto;
            display: block;
            margin: -29px auto 0;
        }

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
            margin-bottom: 12px;
        }

        .cert-body p {
            margin-bottom: 8px;
        }

        .bold {
            font-weight: bold;
        }

        .quot {
            font-weight: bold;
        }

        .upper {
            text-transform: uppercase;
        }

        .italic {
            font-style: italic;
        }

        /* ── BULLETS ── */
        .bullets {
            margin: 8px 0 10px 30px;
        }

        .bullets li {
            list-style: disc;
            font-style: italic;
            font-size: 11px;
            line-height: 1.7;
            margin-bottom: 3px;
        }

        .bullets li strong {
            font-style: normal;
        }

        /* ── TABLA ASISTENCIA ── */
        .tabla-wrap {
            margin: 10px 60px 12px 60px;
        }

        .tabla-asistencia {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            font-style: italic;
        }

        .tabla-asistencia th {
            border-bottom: 1px solid #000;
            font-weight: bold;
            font-style: italic;
            padding: 3px 8px;
            text-align: left;
            font-size: 10.5px;
        }

        .tabla-asistencia td {
            padding: 3px 8px;
            font-style: italic;
        }

        .tabla-asistencia tr:nth-child(even) td {
            background: #f5f5f5;
        }

        .tabla-asistencia tfoot td {
            border-top: 1px solid #000;
            font-weight: bold;
        }

        .tabla-asistencia .num {
            text-align: right;
        }

        /* ── FIRMA ── */
        .firma-wrap {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 8px;
        }

        .firma-wrap img {
            height: 160px;
            width: auto;
            display: block;
            margin: 0 auto;
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
            margin-top: 28px;
            font-size: 11px;
        }

        .fecha-vb p {
            margin-bottom: 4px;
        }

        .vb img {
            height: 28px;
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
    $base = 'http://localhost:8080/app/public/img/';
    $logo1 = $base . 'certificado_1.png';
    $logo2 = $base . 'certificado_3.png';   // orden: 1, 3, 2
    $logo3 = $base . 'certificado_2.png';
    $timbre = $base . 'TIMBRE_JJACH.png';
    $piePag = $base . 'pie_pagina_certificado.png';

    $nombreCompleto = strtoupper(trim("$nombre $apepat " . ($apemat ?? '')));
    $runCompleto = $run . ($codver ? '-' . $codver : '');
    $esF = ($sexo ?? '') === 'F';
    $laEl = $esF ? 'la' : 'el';
    $alumnaO = $esF ? 'alumna' : 'alumno';
    $sraSr = $esF ? 'Srta.' : 'Sr.';
    $tratamiento = 'Don (ña):';

    $mesesES = [
        'enero',
        'febrero',
        'marzo',
        'abril',
        'mayo',
        'junio',
        'julio',
        'agosto',
        'septiembre',
        'octubre',
        'noviembre',
        'diciembre'
    ];

    $nacTexto = '';
    if (!empty($alumno['fechanac'] ?? $fechanac ?? '')) {
        $fn = new DateTime($fechanac ?? '');
        $nacTexto = $fn->format('d') . ' ' . $mesesES[(int) $fn->format('n') - 1] . ' ' . $fn->format('Y');
    }
    $diasES = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    $fechaTexto = $diasES[date('w')] . ', ' . date('j') . ' de ' . $mesesES[date('n') - 1] . ' de ' . date('Y');

    // Fecha de inasistencia = hoy
    $hoyTexto = date('d/m/Y');

    // Fecha matrícula — si viene en los datos
    $fechaMatTexto = '';
    if (!empty($fecha_matricula ?? '')) {
        $fm = new DateTime($fecha_matricula);
        $fechaMatTexto = $fm->format('d/m/Y');
    }

    $asistencia_mensual = $asistencia_mensual ?? [];
    ?>

    <!-- ══ PIE (fixed) ══ -->
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
        <div class="cert-titulo">Certificado Alumno Regular</div>

        <!-- ══ CUERPO ══ -->
        <div class="cert-body">
            <p>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                El director ADP que suscribe, certifica que <?= $tratamiento ?>
                <span class="bold upper"><?= htmlspecialchars($nombreCompleto) ?>.</span>
                Rut. <span class="bold"><?= htmlspecialchars($runCompleto) ?></span>,
                <?php if (!empty($nacTexto)): ?>
                    Nacido (a) el <span class="bold"><?= $nacTexto ?></span>,
                <?php endif ?>
                Es Alumno (a) Regular del
                <span class="quot">"<span class="bold upper"><?= htmlspecialchars($curso) ?></span>"</span>
                Año de Enseñanza, matriculado (a),
                para el Año <span class="bold"><?= htmlspecialchars($anio) ?></span>,
                en el Centro de Educación Integrada de Adultos,
                <span class="bold">"Juanita Zúñiga Fuentes"</span>,
                Establecimiento Dependiente de La Servicio Local de educación pública los Álamos
                &nbsp;<span class="bold">N.º 3290 / 81</span>&nbsp;
                del Ministerio de Educación.
            </p>
        </div>

        <!-- ══ BULLETS ══ -->
        <ul class="bullets">
            <li>
                El alumno (a) durante lo que va corrido de este año presenta la siguiente
                situación de acuerdo a su inasistencia.
            </li>
            <li>La inasistencia es parcial al día <strong><?= $hoyTexto ?></strong></li>
            <?php if (!empty($fechaMatTexto)): ?>
                <li>
                    La Alumna (o) <?= htmlspecialchars($apepat) ?>,
                    fue matriculada con fecha <strong><?= $fechaMatTexto ?></strong>
                </li>
            <?php endif ?>
        </ul>

        <!-- ══ TABLA MENSUAL ══ -->
        <?php
        $mesesNombre = [
            1 => 'MARZO',
            2 => 'MARZO',
            3 => 'MARZO',
            4 => 'ABRIL',
            5 => 'MAYO',
            6 => 'JUNIO',
            7 => 'JULIO',
            8 => 'AGOSTO',
            9 => 'SEPTIEMBRE',
            10 => 'OCTUBRE',
            11 => 'NOVIEMBRE',
            12 => 'DICIEMBRE'
        ];
        $nombresMes = [
            1 => 'ENERO',
            2 => 'FEBRERO',
            3 => 'MARZO',
            4 => 'ABRIL',
            5 => 'MAYO',
            6 => 'JUNIO',
            7 => 'JULIO',
            8 => 'AGOSTO',
            9 => 'SEPTIEMBRE',
            10 => 'OCTUBRE',
            11 => 'NOVIEMBRE',
            12 => 'DICIEMBRE'
        ];
        ?>
        <div class="tabla-wrap">
            <table class="tabla-asistencia">
                <thead>
                    <tr>
                        <th>MES</th>
                        <th style="text-align:center;">Días Trab.<br>SIGE</th>
                        <th style="text-align:center;">Asist.</th>
                        <th style="text-align:center;">Inasist.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($asistencia_mensual as $mes):
                        $ausentes = $mes['total'] - $mes['presentes'];
                        ?>
                        <tr>
                            <td><?= $nombresMes[(int) $mes['mes_num']] ?? strtoupper($mes['mes_nombre']) ?></td>
                            <td class="num"><?= $mes['total'] ?></td>
                            <td class="num"><?= $mes['presentes'] ?></td>
                            <td class="num"><?= $ausentes ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong>TOTAL</strong></td>
                        <td class="num"><?= $total_dias ?></td>
                        <td class="num"><?= $dias_presentes ?></td>
                        <td class="num"><?= $dias_ausentes ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- ══ CIERRE ══ -->
        <ul class="bullets">
            <li>
                Se otorga el presente certificado a petición del interesado para ser presentado en:
                <br><strong><?= htmlspecialchars($motivo ?? 'los fines que estime conveniente') ?></strong>
            </li>
        </ul>

        <!-- ══ FIRMA ══ -->
        <div class="firma-wrap">
            <img src="<?= $timbre ?>" alt="Firma Director">
        </div>

        <!-- ══ FECHA Y Vº Bº ══ -->
        <div class="fecha-vb">
            <p><?= ucfirst($fechaTexto) ?></p>
        </div>

    </div><!-- /page -->

</body>

</html>