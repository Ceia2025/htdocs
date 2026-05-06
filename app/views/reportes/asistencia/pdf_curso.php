<?php
// Variables: $datos, $cursoNombre, $anioNombre, $mesNombre
$logoPath   = __DIR__ . '/../../../public/img/LOGO CEIA.jpg';
$logoExists = file_exists($logoPath);
$totalAlumnos   = count($datos);
$totalClases    = array_sum(array_column($datos, 'total_clases'));
$totalPresentes = array_sum(array_column($datos, 'presentes_acum'));
$pctGeneral     = $totalClases > 0 ? round($totalPresentes / $totalClases * 100, 1) : 0;
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
    .stats { display: table; width: 100%; margin-bottom: 10px; }
    .stat { display: table-cell; text-align: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 5px; width: 25%; }
    .stat-val { font-size: 16px; font-weight: bold; }
    .stat-lbl { font-size: 7px; color: #64748b; }
    table { width: 100%; border-collapse: collapse; margin-top: 6px; }
    thead tr { background: #004b8d; }
    thead th { color: white; padding: 4px 6px; font-size: 8px; text-transform: uppercase; text-align: left; }
    thead th.tc { text-align: center; }
    tbody td { border-bottom: 1px solid #e2e8f0; padding: 3.5px 6px; font-size: 8.5px; }
    tbody tr:nth-child(even) td { background: #f8fafc; }
    .tc { text-align: center; }
    .verde  { color: #16a34a; font-weight: bold; }
    .amarillo { color: #d97706; font-weight: bold; }
    .rojo   { color: #dc2626; font-weight: bold; }
    .gris   { color: #94a3b8; }
    .footer { margin-top: 12px; border-top: 1px solid #e2e8f0; padding-top: 6px; font-size: 7.5px; color: #94a3b8; text-align: right; }
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
        <div class="ficha-titulo">Reporte de Asistencia — <?= htmlspecialchars($cursoNombre) ?></div>
    </div>
</div>

<div class="info-box">
    <strong>Curso:</strong> <?= htmlspecialchars($cursoNombre) ?> &nbsp;·&nbsp;
    <strong>Año:</strong> <?= htmlspecialchars($anioNombre) ?> &nbsp;·&nbsp;
    <strong>Período:</strong> <?= htmlspecialchars($mesNombre) ?> &nbsp;·&nbsp;
    <strong>Generado:</strong> <?= date('d/m/Y H:i') ?>
</div>

<div class="stats">
    <div class="stat">
        <div class="stat-val" style="color:#1d4ed8"><?= $totalAlumnos ?></div>
        <div class="stat-lbl">Alumnos</div>
    </div>
    <div class="stat">
        <div class="stat-val" style="color:#d97706"><?= $totalClases ?></div>
        <div class="stat-lbl">Total clases</div>
    </div>
    <div class="stat">
        <div class="stat-val" style="color:#16a34a"><?= $totalPresentes ?></div>
        <div class="stat-lbl">Total presentes</div>
    </div>
    <div class="stat">
        <div class="stat-val" style="color:<?= $pctGeneral >= 85 ? '#16a34a' : ($pctGeneral >= 75 ? '#d97706' : '#dc2626') ?>">
            <?= $pctGeneral ?>%
        </div>
        <div class="stat-lbl">% Asistencia general</div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Alumno</th>
            <th>RUN</th>
            <th class="tc">Total clases</th>
            <th class="tc">Presentes</th>
            <th class="tc">% Acumulado</th>
            <?php if (isset($datos[0]) && $datos[0]['pct_mes'] !== null): ?>
                <th class="tc">Días mes</th>
                <th class="tc">% Mes</th>
            <?php endif ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($datos as $al):
            $pa = $al['pct_acumulado'];
            $pm = $al['pct_mes'];
            $ca = $pa === null ? 'gris' : ($pa >= 85 ? 'verde' : ($pa >= 75 ? 'amarillo' : 'rojo'));
            $cm = $pm === null ? 'gris' : ($pm >= 85 ? 'verde' : ($pm >= 75 ? 'amarillo' : 'rojo'));
        ?>
        <tr>
            <td><?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?></td>
            <td style="font-family:monospace; font-size:8px; color:#64748b"><?= htmlspecialchars($al['run']) ?></td>
            <td class="tc"><?= $al['total_clases'] ?></td>
            <td class="tc" style="color:#16a34a; font-weight:bold"><?= $al['presentes_acum'] ?></td>
            <td class="tc <?= $ca ?>"><?= $pa !== null ? $pa . '%' : '—' ?></td>
            <?php if (isset($datos[0]) && $datos[0]['pct_mes'] !== null): ?>
                <td class="tc"><?= $al['total_mes'] ?? '—' ?></td>
                <td class="tc <?= $cm ?>"><?= $pm !== null ? $pm . '%' : '—' ?></td>
            <?php endif ?>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<div class="footer">
    Reporte generado automáticamente — Sistema SAAT · C.E.I.A. Parral <?= date('Y') ?>
    <br>Desarrollado por Daniel Scarlazzetta
</div>
</body>
</html>