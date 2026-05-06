<?php
// Variables: $general (array curso => alumnos), $resumen, $anioNombre, $mesNombre
$logoPath   = __DIR__ . '/../../../public/img/LOGO CEIA.jpg';
$logoExists = file_exists($logoPath);
$totalGlobal = array_sum(array_map('count', $general));
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
    .ficha-titulo { display: inline-block; margin-top: 5px; padding: 3px 10px; background: #ffd500; color: #004b8d; font-weight: bold; font-size: 10px; text-transform: uppercase; border-radius: 3px; }
    .info-box { background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 4px; padding: 6px 10px; margin-bottom: 10px; font-size: 9px; }
    /* RESUMEN POR CURSO */
    .resumen-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    .resumen-table thead tr { background: #0f172a; }
    .resumen-table thead th { color: #94a3b8; padding: 4px 6px; font-size: 7.5px; text-transform: uppercase; text-align: left; }
    .resumen-table thead th.tc { text-align: center; }
    .resumen-table tbody td { border-bottom: 1px solid #e2e8f0; padding: 4px 6px; font-size: 8.5px; }
    /* DETALLE POR CURSO */
    .curso-titulo { background: #1e40af; color: white; font-weight: bold; font-size: 10px; padding: 4px 8px; margin-top: 12px; border-radius: 3px 3px 0 0; }
    table.detalle { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
    table.detalle thead tr { background: #1e3a8a; }
    table.detalle thead th { color: white; padding: 3px 5px; font-size: 7.5px; text-transform: uppercase; }
    table.detalle thead th.tc { text-align: center; }
    table.detalle tbody td { border-bottom: 1px solid #e2e8f0; padding: 3px 5px; font-size: 8px; }
    table.detalle tbody tr:nth-child(even) td { background: #f8fafc; }
    .tc { text-align: center; }
    .verde    { color: #16a34a; font-weight: bold; }
    .amarillo { color: #d97706; font-weight: bold; }
    .rojo     { color: #dc2626; font-weight: bold; }
    .gris     { color: #94a3b8; }
    .footer { margin-top: 14px; border-top: 1px solid #e2e8f0; padding-top: 6px; font-size: 7.5px; color: #94a3b8; text-align: right; }
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
        <div class="ficha-titulo">Reporte General de Asistencia — <?= htmlspecialchars($anioNombre) ?></div>
    </div>
</div>

<div class="info-box">
    <strong>Año académico:</strong> <?= htmlspecialchars($anioNombre) ?> &nbsp;·&nbsp;
    <strong>Período:</strong> <?= htmlspecialchars($mesNombre) ?> &nbsp;·&nbsp;
    <strong>Total alumnos:</strong> <?= $totalGlobal ?> &nbsp;·&nbsp;
    <strong>Generado:</strong> <?= date('d/m/Y H:i') ?>
</div>

<!-- RESUMEN POR CURSO -->
<strong style="font-size:10px; color:#004b8d;">Resumen por curso</strong>
<table class="resumen-table" style="margin-top:4px;">
    <thead>
        <tr>
            <th>Curso</th>
            <th class="tc">Alumnos</th>
            <th class="tc">Clases</th>
            <th class="tc">Presentes</th>
            <th class="tc">% Asistencia</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resumen as $r):
            $p = $r['pct'];
            $c = $p === null ? 'gris' : ($p >= 85 ? 'verde' : ($p >= 75 ? 'amarillo' : 'rojo'));
        ?>
        <tr>
            <td style="font-weight:bold"><?= htmlspecialchars($r['curso']) ?></td>
            <td class="tc"><?= $r['total_alumnos'] ?></td>
            <td class="tc"><?= $r['total_clases'] ?></td>
            <td class="tc" style="color:#16a34a; font-weight:bold"><?= $r['total_presentes'] ?></td>
            <td class="tc <?= $c ?>"><?= $p !== null ? $p . '%' : '—' ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<!-- DETALLE POR CURSO -->
<?php $primero = true; foreach ($general as $cursoNombre => $alumnos):
    $conMes = isset($alumnos[0]) && $alumnos[0]['pct_mes'] !== null;
?>
<div class="<?= $primero ? '' : 'page-break' ?>">
    <div class="curso-titulo">🎓 <?= htmlspecialchars($cursoNombre) ?> — <?= count($alumnos) ?> alumnos</div>
    <table class="detalle">
        <thead>
            <tr>
                <th>Alumno</th>
                <th>RUN</th>
                <th class="tc">Clases</th>
                <th class="tc">Pres.</th>
                <th class="tc">% Acum.</th>
                <?php if ($conMes): ?>
                    <th class="tc">Días mes</th>
                    <th class="tc">% Mes</th>
                <?php endif ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $al):
                $pa = $al['pct_acumulado'];
                $pm = $al['pct_mes'];
                $ca = $pa === null ? 'gris' : ($pa >= 85 ? 'verde' : ($pa >= 75 ? 'amarillo' : 'rojo'));
                $cm = $pm === null ? 'gris' : ($pm >= 85 ? 'verde' : ($pm >= 75 ? 'amarillo' : 'rojo'));
            ?>
            <tr>
                <td><?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?></td>
                <td style="font-family:monospace; color:#64748b"><?= htmlspecialchars($al['run']) ?></td>
                <td class="tc"><?= $al['total_clases'] ?></td>
                <td class="tc" style="color:#16a34a; font-weight:bold"><?= $al['presentes_acum'] ?></td>
                <td class="tc <?= $ca ?>"><?= $pa !== null ? $pa . '%' : '—' ?></td>
                <?php if ($conMes): ?>
                    <td class="tc"><?= $al['total_mes'] ?? '—' ?></td>
                    <td class="tc <?= $cm ?>"><?= $pm !== null ? $pm . '%' : '—' ?></td>
                <?php endif ?>
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