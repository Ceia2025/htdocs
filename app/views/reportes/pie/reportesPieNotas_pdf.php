<?php
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
        background: #cffafe; color: #0e7490; font-weight: bold;
        font-size: 10px; text-transform: uppercase; border-radius: 3px;
    }
    .info-box {
        background: #f1f5f9; border: 1px solid #cbd5e1;
        border-radius: 4px; padding: 6px 10px; margin-bottom: 12px; font-size: 9px;
    }
    .curso-titulo {
        background: #0e7490; color: white; font-weight: bold;
        font-size: 10px; padding: 4px 8px; margin-top: 10px;
        border-radius: 3px 3px 0 0;
    }
    table.detalle { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
    table.detalle thead tr { background: #155e75; }
    table.detalle thead th {
        color: white; padding: 3px 5px;
        font-size: 7.5px; text-transform: uppercase;
    }
    table.detalle thead th.tc { text-align: center; }
    table.detalle thead th.tr { text-align: right; }
    table.detalle tbody td { border-bottom: 1px solid #e2e8f0; padding: 3px 5px; font-size: 8px; }
    table.detalle tbody tr:nth-child(even) td { background: #f8fafc; }
    .prom-aprobado { color: #15803d; font-weight: bold; }
    .prom-reprobado { color: #b91c1c; font-weight: bold; }
    .prom-sin { color: #94a3b8; }
    .tc { text-align: center; }
    .tr { text-align: right; }
    .footer {
        margin-top: 14px; border-top: 1px solid #e2e8f0;
        padding-top: 6px; font-size: 7.5px; color: #94a3b8; text-align: right;
    }
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
        <div class="ficha-titulo">Notas Alumnos PIE — <?= htmlspecialchars($anioNombre) ?></div>
    </div>
</div>

<div class="info-box">
    <strong>Año académico:</strong> <?= htmlspecialchars($anioNombre) ?> &nbsp;·&nbsp;
    <strong>Curso:</strong> <?= htmlspecialchars($tituloFiltro) ?> &nbsp;·&nbsp;
    <strong>Total alumnos PIE:</strong> <?= $resumenGlobal['total_pie'] ?> &nbsp;·&nbsp;
    <strong>Generado:</strong> <?= date('d/m/Y H:i') ?>
</div>

<?php if (empty($reporteNotas)): ?>
    <p style="color:#94a3b8; font-size:9px;">No hay alumnos PIE con notas registradas.</p>
<?php else: ?>
    <?php foreach ($reporteNotas as $cursoNombreLoop => $alumnos): ?>
        <div class="curso-titulo">
            🎓 <?= htmlspecialchars($cursoNombreLoop) ?>
            — <?= count($alumnos) ?> alumno<?= count($alumnos) !== 1 ? 's' : '' ?> PIE
        </div>
        <table class="detalle">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>RUN</th>
                    <th class="tc">Sexo</th>
                    <th class="tc">N° notas</th>
                    <th class="tr">Promedio anual</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $al):
                    $prom = $al['promedio'];
                    if ($prom === null) {
                        $cls  = 'prom-sin';
                        $txt  = 'Sin notas';
                    } elseif ($prom >= 4.0) {
                        $cls  = 'prom-aprobado';
                        $txt  = number_format($prom, 1);
                    } else {
                        $cls  = 'prom-reprobado';
                        $txt  = number_format($prom, 1);
                    }
                ?>
                <tr>
                    <td><?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?></td>
                    <td style="font-family:monospace; color:#64748b; font-size:7.5px"><?= htmlspecialchars($al['run']) ?></td>
                    <td class="tc" style="color:#475569">
                        <?= $al['sexo'] === 'F' ? 'F' : ($al['sexo'] === 'M' ? 'M' : '—') ?>
                    </td>
                    <td class="tc" style="color:#64748b"><?= $al['total_notas'] > 0 ? $al['total_notas'] : '—' ?></td>
                    <td class="tr <?= $cls ?>"><?= $txt ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endforeach ?>
<?php endif ?>

<div class="footer">
    Reporte generado automáticamente — Sistema SAAT · C.E.I.A. Parral <?= date('Y') ?>
    <br>Desarrollado por Daniel Scarlazzetta
</div>
</body>
</html>