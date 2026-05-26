<?php
// Variables disponibles: $reporte, $resumenCursos, $resumenGlobal,
//                        $anioNombre, $cursoNombre, $etniaFiltro, $etnias
$logoPath   = __DIR__ . '/../../../public/img/LOGO CEIA.jpg';
$logoExists = file_exists($logoPath);

$totalGlobal = array_sum(array_column($resumenGlobal, 'total'));
$tituloFiltro = $cursoId ? $cursoNombre : 'Todos los cursos';
$etniaTitulo  = $etniaFiltro ?: 'Todos los pueblos originarios';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1e293b; margin: 18px 20px; }

    /* HEADER */
    .header { display: table; width: 100%; border-bottom: 3px solid #004b8d; padding-bottom: 8px; margin-bottom: 10px; }
    .header-left  { display: table-cell; width: 16%; vertical-align: middle; }
    .header-right { display: table-cell; width: 84%; vertical-align: middle; padding-left: 12px; }
    .inst-nombre  { font-size: 12px; font-weight: bold; color: #004b8d; text-transform: uppercase; }
    .inst-sub     { font-size: 9px; color: #475569; }
    .ficha-titulo {
        display: inline-block; margin-top: 5px; padding: 3px 10px;
        background: #ffd500; color: #004b8d; font-weight: bold;
        font-size: 10px; text-transform: uppercase; border-radius: 3px;
    }

    /* INFO BOX */
    .info-box {
        background: #f1f5f9; border: 1px solid #cbd5e1;
        border-radius: 4px; padding: 6px 10px; margin-bottom: 10px; font-size: 9px;
    }

    /* RESUMEN GLOBAL */
    .resumen-global { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    .resumen-global thead tr { background: #0f172a; }
    .resumen-global thead th {
        color: #94a3b8; padding: 4px 6px;
        font-size: 7.5px; text-transform: uppercase; text-align: left;
    }
    .resumen-global thead th.tc { text-align: center; }
    .resumen-global tbody td { border-bottom: 1px solid #e2e8f0; padding: 4px 6px; font-size: 8.5px; }
    .resumen-global tbody tr:nth-child(even) td { background: #f8fafc; }

    /* DETALLE POR CURSO */
    .curso-titulo {
        background: #1e40af; color: white; font-weight: bold;
        font-size: 10px; padding: 4px 8px; margin-top: 12px;
        border-radius: 3px 3px 0 0;
    }
    table.detalle { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
    table.detalle thead tr { background: #1e3a8a; }
    table.detalle thead th {
        color: white; padding: 3px 5px;
        font-size: 7.5px; text-transform: uppercase;
    }
    table.detalle thead th.tc { text-align: center; }
    table.detalle tbody td { border-bottom: 1px solid #e2e8f0; padding: 3px 5px; font-size: 8px; }
    table.detalle tbody tr:nth-child(even) td { background: #f8fafc; }

    /* COLORES */
    .pueblo   { color: #16a34a; font-weight: bold; }
    .no-pueblo { color: #94a3b8; }
    .tc { text-align: center; }

    /* BARRA VISUAL */
    .bar-wrap { background: #e2e8f0; border-radius: 3px; height: 6px; width: 80px; display: inline-block; vertical-align: middle; }
    .bar-fill  { background: #16a34a; border-radius: 3px; height: 6px; display: block; }

    /* FOOTER */
    .footer {
        margin-top: 14px; border-top: 1px solid #e2e8f0;
        padding-top: 6px; font-size: 7.5px; color: #94a3b8; text-align: right;
    }
    .page-break { page-break-before: always; }
</style>
</head>
<body>

<!-- CABECERA -->
<div class="header">
    <div class="header-left">
        <?php if ($logoExists): ?>
            <img src="http://localhost:8080/app/public/img/LOGO%20CEIA.jpg" style="width:80px;">
        <?php else: ?>
            <strong style="color:#004b8d;">CEIA Parral</strong>
        <?php endif ?>
    </div>
    <div class="header-right">
        <div class="inst-nombre">Centro de Educación Integrada de Adultos</div>
        <div class="inst-sub">"Juanita Zúñiga Fuentes" – Parral</div>
        <div class="ficha-titulo">Reporte de Pueblos Originarios — <?= htmlspecialchars($anioNombre) ?></div>
    </div>
</div>

<!-- INFO -->
<div class="info-box">
    <strong>Año académico:</strong> <?= htmlspecialchars($anioNombre) ?> &nbsp;·&nbsp;
    <strong>Curso:</strong> <?= htmlspecialchars($tituloFiltro) ?> &nbsp;·&nbsp;
    <strong>Etnia:</strong> <?= htmlspecialchars($etniaTitulo) ?> &nbsp;·&nbsp;
    <strong>Total alumnos en reporte:</strong> <?= $totalGlobal ?> &nbsp;·&nbsp;
    <strong>Generado:</strong> <?= date('d/m/Y H:i') ?>
</div>

<!-- RESUMEN GLOBAL POR ETNIA -->
<strong style="font-size:10px; color:#004b8d;">Resumen global por pueblo originario</strong>
<table class="resumen-global" style="margin-top:4px;">
    <thead>
        <tr>
            <th>Pueblo Originario / Etnia</th>
            <th class="tc">Total alumnos</th>
            <th class="tc">%</th>
            <th class="tc">Distribución</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resumenGlobal as $rg):
            $pct = $totalGlobal > 0 ? round($rg['total'] / $totalGlobal * 100, 1) : 0;
            $esPueblo = $rg['cod_etnia'] !== 'No pertenece a ningún Pueblo Originario'
                        && $rg['cod_etnia'] !== 'No Registra';
            $barWidth = max(1, (int) $pct) . 'px'; // escala sobre 80px = 100%
            $barPx = (int) round($pct * 0.8);
        ?>
        <tr>
            <td class="<?= $esPueblo ? 'pueblo' : 'no-pueblo' ?>">
                <?= $esPueblo ? '● ' : '' ?><?= htmlspecialchars($rg['cod_etnia']) ?>
            </td>
            <td class="tc" style="font-weight:bold; color:<?= $esPueblo ? '#16a34a' : '#94a3b8' ?>">
                <?= $rg['total'] ?>
            </td>
            <td class="tc"><?= $pct ?>%</td>
            <td class="tc">
                <span class="bar-wrap">
                    <span class="bar-fill" style="width:<?= $barPx ?>px; background:<?= $esPueblo ? '#16a34a' : '#94a3b8' ?>;"></span>
                </span>
            </td>
        </tr>
        <?php endforeach ?>
        <tr style="border-top:2px solid #cbd5e1;">
            <td style="font-weight:bold; font-size:8px; text-transform:uppercase; color:#1e293b;">Total</td>
            <td class="tc" style="font-weight:bold;"><?= $totalGlobal ?></td>
            <td class="tc">100%</td>
            <td></td>
        </tr>
    </tbody>
</table>

<!-- DETALLE POR CURSO -->
<?php $primero = true; foreach ($reporte as $cursoNombreLoop => $alumnos):
    $pueblosEnCurso = array_filter($alumnos, fn($a) =>
        $a['cod_etnia'] !== 'No pertenece a ningún Pueblo Originario'
        && $a['cod_etnia'] !== 'No Registra'
    );
?>
<div class="<?= $primero ? '' : 'page-break' ?>">
    <div class="curso-titulo">
        🎓 <?= htmlspecialchars($cursoNombreLoop) ?>
        — <?= count($alumnos) ?> alumno<?= count($alumnos) !== 1 ? 's' : '' ?>
        <?php if (count($pueblosEnCurso) > 0): ?>
            &nbsp;·&nbsp; <?= count($pueblosEnCurso) ?> de pueblo originario
        <?php endif ?>
    </div>
    <table class="detalle">
        <thead>
            <tr>
                <th>Alumno</th>
                <th>RUN</th>
                <th class="tc">Sexo</th>
                <th>Pueblo Originario / Etnia</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $al):
                $esPueblo = $al['cod_etnia'] !== 'No pertenece a ningún Pueblo Originario'
                            && $al['cod_etnia'] !== 'No Registra';
            ?>
            <tr>
                <td><?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?></td>
                <td style="font-family:monospace; color:#64748b; font-size:7.5px"><?= htmlspecialchars($al['run']) ?></td>
                <td class="tc" style="color:#475569">
                    <?= $al['sexo'] === 'F' ? 'F' : ($al['sexo'] === 'M' ? 'M' : '—') ?>
                </td>
                <td class="<?= $esPueblo ? 'pueblo' : 'no-pueblo' ?>">
                    <?= $esPueblo ? '● ' : '' ?><?= htmlspecialchars($al['cod_etnia']) ?>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php $primero = false; endforeach ?>

<div class="footer">
    Reporte generado automáticamente — Sistema SAAT · C.E.I.A. Parral <?= date('Y') ?>
    <br>Desarrollado por Daniel Scarlazzetta
</div>
</body>
</html>