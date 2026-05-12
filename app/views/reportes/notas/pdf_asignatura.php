<?php
// views/reportes/notas/pdf_asignatura.php
// Variables esperadas:
//   $curso, $anio, $asignatura, $semestre
//   $alumnos   — array con matricula_id, nombre, apepat, apemat, numero_lista, fecha_retiro
//   $notas     — mapa matricula_id => [notas ordenadas por fecha]
//   $maxCols   — int: máximo de notas que tiene cualquier alumno (columnas dinámicas)
//   $logoPath  — ruta absoluta al logo

$logoExists = !empty($logoPath) && file_exists($logoPath);

// Stats globales
$totalActivos = 0;
$aprobados = 0;
$sumaProms = 0;
$cntProms = 0;
$sumaColsTotal = array_fill(0, $maxCols, 0);
$cntColsTotal = array_fill(0, $maxCols, 0);

foreach ($alumnos as $a) {
    if (!empty($a['fecha_retiro']))
        continue;
    $totalActivos++;
    $ns = array_values($notas[$a['matricula_id']] ?? []);
    if (count($ns) > 0) {
        $suma = 0;
        foreach ($ns as $i => $n) {
            $v = floatval($n['nota']);
            $suma += $v;
            if (isset($sumaColsTotal[$i])) {
                $sumaColsTotal[$i] += $v;
                $cntColsTotal[$i]++;
            }
        }
        $prom = $suma / count($ns);
        $sumaProms += $prom;
        $cntProms++;
        if ($prom >= 4.0)
            $aprobados++;
    }
}

$promCurso = $cntProms > 0 ? round($sumaProms / $cntProms, 1) : null;
$pctAprobados = $totalActivos > 0 ? round($aprobados / $totalActivos * 100) : 0;
$reprobados = $totalActivos - $aprobados;

function colorV($v)
{
    if ($v === null)
        return '#94a3b8';
    return $v >= 4.0 ? '#16a34a' : '#dc2626';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 7.5px;
            color: #1e293b;
            margin: 10px 12px;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #004b8d;
            padding-bottom: 5px;
            margin-bottom: 7px;
        }

        .h-left {
            display: table-cell;
            width: 13%;
            vertical-align: middle;
        }

        .h-right {
            display: table-cell;
            width: 87%;
            vertical-align: middle;
            padding-left: 10px;
        }

        .inst-nm {
            font-size: 10px;
            font-weight: bold;
            color: #004b8d;
            text-transform: uppercase;
        }

        .inst-sb {
            font-size: 7.5px;
            color: #475569;
        }

        .badge {
            display: inline-block;
            margin-top: 4px;
            padding: 2px 7px;
            background: #ffd500;
            color: #004b8d;
            font-weight: bold;
            font-size: 8.5px;
            text-transform: uppercase;
            border-radius: 3px;
        }

        .info {
            display: table;
            width: 100%;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 4px 8px;
            margin-bottom: 7px;
        }

        .i-left {
            display: table-cell;
            vertical-align: middle;
        }

        .i-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }

        .i-t {
            font-size: 10px;
            font-weight: bold;
            color: #004b8d;
        }

        .i-sb {
            font-size: 7px;
            color: #64748b;
            margin-top: 1px;
        }

        .stats {
            display: table;
            width: 100%;
            margin-bottom: 7px;
            border-spacing: 4px;
            border-collapse: separate;
        }

        .stat {
            display: table-cell;
            text-align: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 4px 3px;
            width: 25%;
        }

        .sv {
            font-size: 13px;
            font-weight: bold;
        }

        .sl {
            font-size: 6px;
            color: #64748b;
            margin-top: 1px;
        }

        table.nt {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px;
        }

        table.nt thead tr {
            background: #004b8d;
            color: #fff;
        }

        table.nt thead th {
            padding: 2.5px 2px;
            text-align: center;
            border: 1px solid #1e40af;
            font-size: 6.5px;
        }

        table.nt thead th.th-al {
            text-align: left;
            padding-left: 4px;
            min-width: 120px;
        }

        table.nt thead th.th-n {
            min-width: 16px;
        }

        table.nt tbody tr:nth-child(even) td {
            background: #f8fafc;
        }

        table.nt tbody td {
            border: 1px solid #e2e8f0;
            padding: 2px 1.5px;
            text-align: center;
            vertical-align: middle;
        }

        table.nt tbody td.td-al {
            text-align: left;
            padding-left: 4px;
            font-weight: bold;
        }

        table.nt tbody td.td-al span {
            font-weight: normal;
            color: #64748b;
            font-size: 6px;
        }

        table.nt tfoot td {
            border: 1px solid #cbd5e1;
            padding: 2.5px 1.5px;
            text-align: center;
            background: #e8edf5;
            font-weight: bold;
            font-size: 7.5px;
        }

        table.nt tfoot td.td-al {
            text-align: left;
            padding-left: 4px;
        }

        .ok {
            color: #16a34a;
            font-weight: bold;
        }

        .mal {
            color: #dc2626;
            font-weight: bold;
        }

        .nd {
            color: #94a3b8;
        }

        .sep {
            border-left: 2px solid #93c5fd !important;
        }

        .ley {
            margin-top: 6px;
            font-size: 6.5px;
            color: #64748b;
        }

        .footer {
            margin-top: 7px;
            border-top: 1px solid #e2e8f0;
            padding-top: 3px;
            font-size: 6px;
            color: #94a3b8;
            text-align: right;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <div class="h-left">
            <?php if ($logoExists): ?>
                <img src="<?= $logoPath ?>" style="width:60px;height:auto;">
            <?php else: ?>
                <strong style="color:#004b8d;">CEIA</strong>
            <?php endif; ?>
        </div>
        <div class="h-right">
            <div class="inst-nm">Centro de Educación Integrada de Adultos</div>
            <div class="inst-sb">"Juanita Zúñiga Fuentes" – Parral</div>
            <div class="badge">
                Notas ·
                <?= htmlspecialchars($asignatura['nombre']) ?> ·
                <?= $semestre ?>° Semestre
            </div>
        </div>
    </div>

    <!-- INFO -->
    <div class="info">
        <div class="i-left">
            <div class="i-t">
                <?= htmlspecialchars($asignatura['nombre']) ?>
            </div>
            <div class="i-sb">
                Curso:
                <?= htmlspecialchars($curso['nombre']) ?>
                &nbsp;·&nbsp;
                <?= $semestre ?>° Semestre
                &nbsp;·&nbsp; Año
                <?= htmlspecialchars($anio['anio']) ?>
                &nbsp;·&nbsp;
                <?= count($alumnos) ?> alumnos &nbsp;·&nbsp;
                Máx. notas por alumno:
                <?= $maxCols ?>
            </div>
        </div>
        <div class="i-right">
            <div style="font-size:15px;font-weight:bold;color:<?= colorV($promCurso) ?>">
                <?= $promCurso !== null ? $promCurso : '—' ?>
            </div>
            <div style="font-size:6.5px;color:#64748b;">Promedio del curso</div>
        </div>
    </div>

    <!-- STATS -->
    <div class="stats">
        <div class="stat">
            <div class="sv" style="color:#1d4ed8;">
                <?= count($alumnos) ?>
            </div>
            <div class="sl">Total alumnos</div>
        </div>
        <div class="stat">
            <div class="sv" style="color:#16a34a;">
                <?= $aprobados ?>
            </div>
            <div class="sl">Aprobados</div>
        </div>
        <div class="stat">
            <div class="sv" style="color:#dc2626;">
                <?= $reprobados ?>
            </div>
            <div class="sl">Reprobados</div>
        </div>
        <div class="stat">
            <div class="sv" style="color:<?= $pctAprobados >= 70 ? '#16a34a' : '#dc2626' ?>">
                <?= $pctAprobados ?>%
            </div>
            <div class="sl">% Aprobación</div>
        </div>
    </div>

    <!-- TABLA -->
    <table class="nt">
        <thead>
            <tr>
                <th class="th-n">#</th>
                <th class="th-al">Alumno</th>
                <?php for ($i = 1; $i <= $maxCols; $i++): ?>
                    <th>Nota
                        <?= $i ?>
                    </th>
                <?php endfor; ?>
                <th class="sep">Promedio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $alumno):
                $mid = $alumno['matricula_id'];
                $ns = array_values($notas[$mid] ?? []);
                $estaRet = !empty($alumno['fecha_retiro']);
                $suma = 0;
                $cnt = 0;
                foreach ($ns as $i => $n) {
                    $v = floatval($n['nota']);
                    $suma += $v;
                    $cnt++;
                }
                $promFila = $cnt > 0 ? $suma / $cnt : null;
                $cP = colorV($promFila);
                ?>
                <tr <?= $estaRet ? 'style="opacity:0.55;"' : '' ?>>
                    <td style="font-weight:bold;color:#6366f1;font-size:7.5px;">
                        <?= $alumno['numero_lista'] ?? '—' ?>
                    </td>
                    <td class="td-al">
                        <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat']) ?>
                        <br><span>
                            <?= htmlspecialchars($alumno['nombre']) ?>
                        </span>
                        <?php if ($estaRet): ?><br><span style="color:#ef4444;font-size:5.5px;">Retirado</span>
                        <?php endif; ?>
                    </td>
                    <?php for ($i = 0; $i < $maxCols; $i++):
                        $n = $ns[$i] ?? null;
                        $v = $n ? floatval($n['nota']) : null;
                        ?>
                        <td>
                            <?php if ($v !== null): ?>
                                <span class="<?= $v >= 4.0 ? 'ok' : 'mal' ?>">
                                    <?= number_format($v, 1) ?>
                                </span>
                            <?php else: ?>
                                <span class="nd">—</span>
                            <?php endif; ?>
                        </td>
                    <?php endfor; ?>
                    <td class="sep" style="color:<?= $cP ?>;font-weight:bold;font-size:8px;">
                        <?= $promFila !== null ? number_format($promFila, 1) : '—' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td class="td-al">Promedio columna</td>
                <?php for ($i = 0; $i < $maxCols; $i++):
                    $pCol = $cntColsTotal[$i] > 0 ? $sumaColsTotal[$i] / $cntColsTotal[$i] : null;
                    ?>
                    <td style="color:<?= colorV($pCol) ?>">
                        <?= $pCol !== null ? number_format($pCol, 1) : '—' ?>
                    </td>
                <?php endfor; ?>
                <td class="sep" style="color:<?= colorV($promCurso) ?>;font-size:8.5px;">
                    <?= $promCurso !== null ? $promCurso : '—' ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="ley">
        <strong>Leyenda:</strong>
        &nbsp;<span class="ok">Nota ≥ 4.0</span> Aprobado &nbsp;
        <span class="mal">Nota &lt; 4.0</span> Reprobado &nbsp;
        <span class="nd">—</span> Sin nota
    </div>

    <div class="footer">
        Documento generado el
        <?= date('d/m/Y H:i') ?> — Sistema SAAT · C.E.I.A. Parral
    </div>

</body>

</html>