<?php
// Variables disponibles: $reporte, $resumenCursos, $resumenGlobal,
//                        $anioNombre, $cursoNombre, $cursoId
$logoPath   = __DIR__ . '/../../../public/img/LOGO CEIA.jpg';
$logoExists = file_exists($logoPath);

$tituloFiltro = $cursoId ? $cursoNombre : 'Todos los cursos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1e293b; margin: 18px 20px; }

    .header { display: table; width: 100%; border-bottom: 3px solid #004b8d; padding-bottom: 8px; margin-bottom: 10px; }
    .header-left  { display: table-cell; width: 16%; vertical-align: middle; }
    .header-right { display: table-cell; width: 84%; vertical-align: middle; padding-left: 12px; }
    .inst-nombre  { font-size: 12px; font-weight: bold; color: #004b8d; text-transform: uppercase; }
    .inst-sub     { font-size: 9px; color: #475569; }
    .ficha-titulo {
        display: inline-block; margin-top: 5px; padding: 3px 10px;
        background: #fee2e2; color: #b91c1c; font-weight: bold;
        font-size: 10px; text-transform: uppercase; border-radius: 3px;
    }

    .info-box {
        background: #f1f5f9; border: 1px solid #cbd5e1;
        border-radius: 4px; padding: 6px 10px; margin-bottom: 10px; font-size: 9px;
    }

    .resumen-global { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    .resumen-global thead tr { background: #0f172a; }
    .resumen-global thead th {
        color: #94a3b8; padding: 4px 6px;
        font-size: 7.5px; text-transform: uppercase; text-align: left;
    }
    .resumen-global thead th.tc { text-align: center; }
    .resumen-global tbody td { border-bottom: 1px solid #e2e8f0; padding: 4px 6px; font-size: 8.5px; }
    .resumen-global tbody tr:nth-child(even) td { background: #f8fafc; }

    .curso-titulo {
        background: #b91c1c; color: white; font-weight: bold;
        font-size: 10px; padding: 4px 8px; margin-top: 12px;
        border-radius: 3px 3px 0 0;
    }
    table.detalle { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
    table.detalle thead tr { background: #7f1d1d; }
    table.detalle thead th {
        color: white; padding: 3px 5px;
        font-size: 7.5px; text-transform: uppercase;
    }
    table.detalle thead th.tc { text-align: center; }
    table.detalle tbody td { border-bottom: 1px solid #e2e8f0; padding: 3px 5px; font-size: 8px; }
    table.detalle tbody tr:nth-child(even) td { background: #f8fafc; }

    .retirado { color: #b91c1c; font-weight: bold; }
    .tc { text-align: center; }

    .bar-wrap { background: #e2e8f0; border-radius: 3px; height: 6px; width: 80px; display: inline-block; vertical-align: middle; }
    .bar-fill  { background: #b91c1c; border-radius: 3px; height: 6px; display: block; }

    .footer {
        margin-top: 14px; border-top: 1px solid #e2e8f0;
        padding-top: 6px; font-size: 7.5px; color: #94a3b8; text-align: right;
    }
    .page-break { page-break-before: always; }
</style>
</head>
<body>

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
        <div class="ficha-titulo">Reporte de Alumnos Retirados — <?= htmlspecialchars($anioNombre) ?></div>
    </div>
</div>

<div class="info-box">
    <strong>Año académico:</strong> <?= htmlspecialchars($anioNombre) ?> &nbsp;·&nbsp;
    <strong>Curso:</strong> <?= htmlspecialchars($tituloFiltro) ?> &nbsp;·&nbsp;
    <strong>Total matriculados:</strong> <?= $resumenGlobal['total_matriculados'] ?> &nbsp;·&nbsp;
    <strong>Total retirados:</strong> <?= $resumenGlobal['total_retirados'] ?> &nbsp;·&nbsp;
    <strong>% retiro general:</strong> <?= $resumenGlobal['porcentaje'] ?>% &nbsp;·&nbsp;
    <strong>Generado:</strong> <?= date('d/m/Y H:i') ?>
</div>

<strong style="font-size:10px; color:#b91c1c;">Resumen por curso</strong>
<table class="resumen-global" style="margin-top:4px;">
    <thead>
        <tr>
            <th>Curso</th>
            <th class="tc">Matriculados</th>
            <th class="tc">Retirados</th>
            <th class="tc">% Retiro</th>
            <th class="tc">Distribución</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resumenCursos as $rc):
            $barPx = (int) round($rc['porcentaje'] * 0.8); // escala sobre 80px = 100%
        ?>
        <tr>
            <td><?= htmlspecialchars($rc['curso']) ?></td>
            <td class="tc"><?= $rc['total_matriculados'] ?></td>
            <td class="tc" style="font-weight:bold; color:#b91c1c;"><?= $rc['total_retirados'] ?></td>
            <td class="tc"><?= $rc['porcentaje'] ?>%</td>
            <td class="tc">
                <span class="bar-wrap">
                    <span class="bar-fill" style="width:<?= $barPx ?>px;"></span>
                </span>
            </td>
        </tr>
        <?php endforeach ?>
        <tr style="border-top:2px solid #cbd5e1;">
            <td style="font-weight:bold; font-size:8px; text-transform:uppercase; color:#1e293b;">Total</td>
            <td class="tc" style="font-weight:bold;"><?= $resumenGlobal['total_matriculados'] ?></td>
            <td class="tc" style="font-weight:bold; color:#b91c1c;"><?= $resumenGlobal['total_retirados'] ?></td>
            <td class="tc" style="font-weight:bold;"><?= $resumenGlobal['porcentaje'] ?>%</td>
            <td></td>
        </tr>
    </tbody>
</table>

<?php $primero = true; foreach ($reporte as $cursoNombreLoop => $alumnos): ?>
<div class="<?= $primero ? '' : 'page-break' ?>">
    <div class="curso-titulo">
        🚪 <?= htmlspecialchars($cursoNombreLoop) ?>
        — <?= count($alumnos) ?> retirado<?= count($alumnos) !== 1 ? 's' : '' ?>
    </div>
    <table class="detalle">
        <thead>
            <tr>
                <th>Alumno</th>
                <th>RUN</th>
                <th class="tc">Fecha matrícula</th>
                <th class="tc">Fecha de retiro</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $al): ?>
            <tr>
                <td><?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?></td>
                <td style="font-family:monospace; color:#64748b; font-size:7.5px"><?= htmlspecialchars($al['run']) ?></td>
                <td class="tc" style="color:#475569">
                    <?= $al['fecha_matricula'] ? date('d/m/Y', strtotime($al['fecha_matricula'])) : '—' ?>
                </td>
                <td class="tc retirado">
                    <?= date('d/m/Y', strtotime($al['fecha_retiro'])) ?>
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