<?php
// Variables esperadas:
// $curso, $anio, $nombreMes, $fechas, $alumnos, $asistencia, $statsAlumnos, $diasCortos
$logoPath   = __DIR__ . '/../../public/img/LOGO CEIA.jpg';
$logoExists = file_exists($logoPath);

// Totales generales del mes
$totalPresGral = 0;
$totalAusGral  = 0;
$totalDiasGral = 0;
foreach ($statsAlumnos as $st) {
    $totalPresGral += $st['presentes'];
    $totalAusGral  += $st['ausentes'];
    $totalDiasGral += $st['total'];
}
$pctGral = $totalDiasGral > 0 ? round($totalPresGral / $totalDiasGral * 100) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 7.5px;
        color: #1e293b;
        margin: 12px 14px;
        background: #fff;
    }

    /* HEADER */
    .header {
        display: table;
        width: 100%;
        border-bottom: 3px solid #004b8d;
        padding-bottom: 6px;
        margin-bottom: 8px;
    }
    .header-left  { display: table-cell; width: 14%; vertical-align: middle; }
    .header-right { display: table-cell; width: 86%; vertical-align: middle; padding-left: 10px; }
    .inst-nombre  { font-size: 11px; font-weight: bold; color: #004b8d; text-transform: uppercase; }
    .inst-sub     { font-size: 8px; color: #475569; margin-top: 1px; }
    .ficha-titulo {
        display: inline-block; margin-top: 4px; padding: 2px 8px;
        background: #ffd500; color: #004b8d; font-weight: bold;
        font-size: 9px; text-transform: uppercase; border-radius: 3px;
    }

    /* INFO MES */
    .info-mes {
        display: table; width: 100%;
        background: #f1f5f9; border: 1px solid #cbd5e1;
        border-radius: 4px; padding: 5px 10px; margin-bottom: 8px;
    }
    .info-mes-left  { display: table-cell; vertical-align: middle; }
    .info-mes-right { display: table-cell; vertical-align: middle; text-align: right; }
    .info-mes .titulo { font-size: 12px; font-weight: bold; color: #004b8d; }
    .info-mes .sub    { font-size: 8px; color: #64748b; margin-top: 2px; }

    /* RESUMEN STATS */
    .stats-row {
        display: table; width: 100%; margin-bottom: 8px;
        border-collapse: separate; border-spacing: 6px;
    }
    .stat-cell {
        display: table-cell; text-align: center;
        background: #f8fafc; border: 1px solid #e2e8f0;
        border-radius: 4px; padding: 5px 4px; width: 25%;
    }
    .stat-val   { font-size: 16px; font-weight: bold; }
    .stat-label { font-size: 7px; color: #64748b; margin-top: 1px; }
    .c-blue   { color: #1d4ed8; }
    .c-green  { color: #16a34a; }
    .c-red    { color: #dc2626; }
    .c-amber  { color: #d97706; }

    /* TABLA */
    table.asistencia {
        width: 100%;
        border-collapse: collapse;
        font-size: 7px;
    }
    table.asistencia thead tr {
        background: #004b8d;
        color: #fff;
    }
    table.asistencia thead th {
        padding: 3px 2px;
        text-align: center;
        border: 1px solid #1e40af;
        font-size: 7px;
    }
    table.asistencia thead th.th-alumno {
        text-align: left;
        padding-left: 5px;
        min-width: 120px;
    }
    table.asistencia thead th.th-num {
        min-width: 18px;
    }
    table.asistencia tbody tr:nth-child(even) td {
        background: #f8fafc;
    }
    /* Grupos de 5 */
    table.asistencia tbody tr.grupo-impar td {
        background: #e8edf5;
    }
    table.asistencia tbody tr.grupo-impar:nth-child(even) td {
        background: #dde4f0;
    }

    table.asistencia tbody td {
        padding: 2px 2px;
        border: 1px solid #e2e8f0;
        text-align: center;
        vertical-align: middle;
    }
    table.asistencia tbody td.td-alumno {
        text-align: left;
        padding-left: 5px;
        font-weight: bold;
    }
    table.asistencia tbody td.td-alumno span {
        font-weight: normal;
        color: #64748b;
        font-size: 6.5px;
    }

    .presente { color: #16a34a; font-weight: bold; font-size: 9px; }
    .ausente  { color: #dc2626; font-weight: bold; font-size: 9px; }
    .sin-dato { color: #94a3b8; }
    .antes-mat { color: #cbd5e1; }

    /* Columnas de stats al final */
    td.stat-pct {
        font-weight: bold;
        font-size: 7.5px;
        border-left: 2px solid #cbd5e1;
    }
    td.stat-p   { color: #16a34a; font-weight: bold; border-left: 2px solid #cbd5e1; }
    td.stat-a   { color: #dc2626; font-weight: bold; }
    td.stat-d   { color: #64748b; }

    th.th-stat  { border-left: 2px solid #93c5fd !important; font-size: 6.5px; }

    /* FOOTER */
    .footer {
        margin-top: 10px;
        border-top: 1px solid #e2e8f0;
        padding-top: 5px;
        font-size: 7px;
        color: #94a3b8;
        text-align: right;
    }

    /* Leyenda */
    .leyenda {
        margin-top: 6px;
        font-size: 7px;
        color: #64748b;
    }
</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="header-left">
        <?php if ($logoExists): ?>
            <img src="http://localhost:8080/app/public/img/LOGO%20CEIA.jpg" style="width:70px; height:auto;">
        <?php else: ?>
            <strong style="color:#004b8d;">CEIA Parral</strong>
        <?php endif; ?>
    </div>
    <div class="header-right">
        <div class="inst-nombre">Centro de Educación Integrada de Adultos</div>
        <div class="inst-sub">"Juanita Zúñiga Fuentes" – Parral</div>
        <div class="ficha-titulo">
            Libro de Clases — <?= htmlspecialchars($curso['nombre']) ?> · <?= $nombreMes ?>
        </div>
    </div>
</div>

<!-- INFO MES -->
<div class="info-mes">
    <div class="info-mes-left">
        <div class="titulo">
            <?= htmlspecialchars($curso['nombre']) ?> &nbsp;·&nbsp; <?= $nombreMes ?>
        </div>
        <div class="sub">
            Año académico: <?= htmlspecialchars($anio['anio']) ?>
            &nbsp;·&nbsp; Días hábiles registrados: <?= count($fechas) ?>
            &nbsp;·&nbsp; Total alumnos: <?= count($alumnos) ?>
        </div>
    </div>
    <div class="info-mes-right">
        <div style="font-size:18px; font-weight:bold; color:<?= $pctGral >= 85 ? '#16a34a' : ($pctGral >= 75 ? '#d97706' : '#dc2626') ?>">
            <?= $pctGral ?>%
        </div>
        <div style="font-size:7px; color:#64748b;">Asistencia general del mes</div>
    </div>
</div>

<!-- STATS GENERALES -->
<div class="stats-row">
    <div class="stat-cell">
        <div class="stat-val c-blue"><?= count($fechas) ?></div>
        <div class="stat-label">Días hábiles</div>
    </div>
    <div class="stat-cell">
        <div class="stat-val c-green"><?= $totalPresGral ?></div>
        <div class="stat-label">Total asistencias</div>
    </div>
    <div class="stat-cell">
        <div class="stat-val c-red"><?= $totalAusGral ?></div>
        <div class="stat-label">Total inasistencias</div>
    </div>
    <div class="stat-cell">
        <div class="stat-val c-amber"><?= $pctGral ?>%</div>
        <div class="stat-label">% asistencia general</div>
    </div>
</div>

<!-- TABLA ASISTENCIA -->
<table class="asistencia">
    <thead>
        <tr>
            <th class="th-num">#</th>
            <th class="th-alumno">Alumno</th>
            <?php foreach ($fechas as $fecha): ?>
                <th>
                    <?= $fecha->format('d') ?><br>
                    <span style="font-size:6px; font-weight:normal;">
                        <?= $diasCortos[$fecha->format('N') - 1] ?>
                    </span>
                </th>
            <?php endforeach; ?>
            <th class="th-stat">%</th>
            <th class="th-stat">P</th>
            <th class="th-stat">A</th>
            <th class="th-stat">Días</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($alumnos as $idx => $alumno):
            $matriculaId    = $alumno['matricula_id'];
            $fechaMatricula = !empty($alumno['fecha_matricula'])
                ? new DateTime($alumno['fecha_matricula']) : null;
            $st  = $statsAlumnos[$matriculaId];
            $pct = $st['pct'];

            // Color de grupo cada 5 según número de lista
            $numLista   = $alumno['numero_lista'] ?? ($idx + 1);
            $esGrupoPar = (int)(($numLista - 1) / 5) % 2 === 0;
            $claseGrupo = $esGrupoPar ? '' : 'grupo-impar';

            // Color del %
            $colorPct = $pct === null ? '#94a3b8'
                : ($pct >= 85 ? '#16a34a' : ($pct >= 75 ? '#d97706' : '#dc2626'));
        ?>
        <tr class="<?= $claseGrupo ?>">
            <td style="font-weight:bold; color:#6366f1; font-size:8px;">
                <?= $alumno['numero_lista'] ?? '—' ?>
            </td>
            <td class="td-alumno">
                <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat']) ?>
                <br><span><?= htmlspecialchars($alumno['nombre']) ?></span>
            </td>

            <?php foreach ($fechas as $fecha): ?>
                <?php
                $f = $fecha->format('Y-m-d');
                $v = $asistencia[$matriculaId][$f] ?? null;
                $antesDeMatricula = $fechaMatricula && $fecha < $fechaMatricula;
                ?>
                <td>
                    <?php if ($antesDeMatricula): ?>
                        <span class="antes-mat">·</span>
                    <?php elseif ($v == 1): ?>
                        <span class="presente">✓</span>
                    <?php elseif ($v == 0): ?>
                        <span class="ausente">✗</span>
                    <?php else: ?>
                        <span class="sin-dato">—</span>
                    <?php endif; ?>
                </td>
            <?php endforeach; ?>

            <!-- Stats -->
            <td class="stat-pct" style="color:<?= $colorPct ?>">
                <?= $pct !== null ? $pct . '%' : '—' ?>
            </td>
            <td class="stat-p"><?= $st['presentes'] ?></td>
            <td class="stat-a"><?= $st['ausentes'] ?></td>
            <td class="stat-d"><?= $st['total'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- LEYENDA -->
<div class="leyenda">
    <strong>Leyenda:</strong>
    &nbsp; <span style="color:#16a34a; font-weight:bold;">✓</span> Presente
    &nbsp; <span style="color:#dc2626; font-weight:bold;">✗</span> Ausente
    &nbsp; <span style="color:#94a3b8;">—</span> Sin registro
    &nbsp; <span style="color:#cbd5e1;">·</span> Antes de matrícula
    &nbsp;&nbsp; <strong>P</strong> = Presentes · <strong>A</strong> = Ausentes · <strong>Días</strong> = Días registrados desde la matrícula
</div>

<!-- FOOTER -->
<div class="footer">
    Documento generado el <?= date('d/m/Y H:i') ?> — Sistema SAAT · C.E.I.A. Parral
    <br>Desarrollado por Daniel Scarlazzetta
</div>

</body>
</html>