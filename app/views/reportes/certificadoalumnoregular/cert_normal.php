<?php require_once __DIR__ . '/../../helper/certificado_alumno_regular_helper.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Certificado Alumno Regular</title>
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
    // ── Recursos e info del alumno (centralizados en el helper) ──
    $img = certImagenes();
    $a = certDatosAlumno([
        'nombre' => $nombre ?? '',
        'apepat' => $apepat ?? '',
        'apemat' => $apemat ?? '',
        'run' => $run ?? '',
        'codver' => $codver ?? '',
        'sexo' => $sexo ?? '',
    ]);

    // ── Fecha larga: manual si viene del index, hoy si viene vacía ──
    $fechaTexto = certFechaLarga($fecha ?? ($_GET['fecha'] ?? null));

    // ── Fecha de nacimiento ──
    $nacTexto = certFechaNacimiento($alumno['fechanac'] ?? $fechanac ?? '');

    // ── Periodo escolar con formato "2.026" (punto de miles sobre el año) ──
    $anioTexto = number_format((int) ($anio ?? 0), 0, '', '.');
    ?>

    <!-- ══ PIE (fixed) ══ -->
    <div class="pie-pagina">
        <img src="<?= $img['pie'] ?>" alt="Pie de página">
    </div>

    <div class="page">

        <!-- ══ ENCABEZADO ══ -->
        <div class="header">
            <div class="header-left"> <img src="<?= $img['logo1'] ?>" alt="Educación Pública"></div>
            <div class="header-center"> <img src="<?= $img['logo2'] ?>" alt="Los Álamos"></div>
            <div class="header-right"> <img src="<?= $img['logo3'] ?>" alt="CEIA"></div>
        </div>

        <!-- ══ TÍTULO ══ -->
        <div class="cert-titulo">Certificado Alumno Regular</div>

        <!-- ══ CUERPO ══ -->
        <div class="cert-body">
            <p>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                El director ADP que suscribe, certifica que <?= $a['tratamiento'] ?>
                <span class="bold upper"><?= htmlspecialchars($a['nombreCompleto']) ?>.</span>
                Rut. <span class="bold"><?= htmlspecialchars($a['runCompleto']) ?></span>,
                <?php if (!empty($nacTexto)): ?>
                    Nacido (a) el <span class="bold"><?= $nacTexto ?></span>,
                <?php endif ?>
                <?php if (!empty($direccion)): ?>
                    Domiciliado (a) en <span class="bold"><?= htmlspecialchars($direccion) ?></span>,
                    <?php if (!empty($ciudad)): ?>
                        de la Ciudad de <span class="bold"><?= htmlspecialchars($ciudad) ?></span>,
                    <?php endif ?>
                <?php endif ?>
                es alumno (a) regular del
                <span class="bold upper"><?= htmlspecialchars($curso) ?></span>
                <?php if (!empty($especialidad)): ?>
                    , en la especialidad de
                    <span class="quot">"<span class="bold"><?= htmlspecialchars($especialidad) ?></span>"</span>
                <?php endif ?>
                del establecimiento a mi cargo, bajo el registro de matrícula
                <span class="bold">N.º <?= htmlspecialchars($numero_matricula ?? '') ?></span>,
                periodo escolar <span class="bold"><?= $anioTexto ?>.</span>
                en el Centro de Educación Integrada de Adultos,
                <span class="bold">"Juanita Zúñiga Fuentes"</span>,
                Establecimiento Dependiente del Servicio Local de Educación Pública Los Álamos y
                Decreto Cooperador <span class="bold">N.º 3290 / 81</span>
                del Ministerio de Educación.
            </p>
        </div>

        <!-- ══ CIERRE ══ -->
        <ul class="bullets">
            <li>
                Se otorga el presente certificado a petición del interesado para ser presentado en:
                <br><strong><?= htmlspecialchars(ucfirst($motivo ?? 'los fines que estime conveniente')) ?></strong>
            </li>
        </ul>

        <!-- ══ FIRMA ══ -->
        <div class="firma-wrap">
            <img src="<?= $img['timbre'] ?>" alt="Firma Director">
        </div>

        <!-- ══ FECHA Y Vº Bº ══ -->
        <div class="fecha-vb">
            <p><?= ucfirst($fechaTexto) ?></p>
        </div>

    </div><!-- /page -->

</body>

</html>