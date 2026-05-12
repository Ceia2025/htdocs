<?php
// views/reportes/notas/pdf_alumno.php
// Variables esperadas del controlador:
//   $alumno, $curso, $anio, $asignaturas
//   $notasSem[1][asig_id][] = nota   /   $notasSem[2][asig_id][] = nota
//   $maxCols[1], $maxCols[2]  — columnas dinámicas por semestre
//   $asistencia['dias_trabajados','dias_inasistencia','porcentaje']
//   $docenteNombre, $logoPath, $observaciones

$logoExists = !empty($logoPath) && file_exists($logoPath);

// Calcular promedios por fila y promedios generales
$sumaGenSem1 = 0;
$cntGenSem1 = 0;
$sumaGenSem2 = 0;
$cntGenSem2 = 0;

$filas = [];
foreach ($asignaturas as $asig) {
    $id = $asig['id'];
    $ns1 = $notasSem[1][$id] ?? [];
    $ns2 = $notasSem[2][$id] ?? [];

    $p1 = count($ns1) > 0 ? array_sum(array_column($ns1, 'nota')) / count($ns1) : null;
    $p2 = count($ns2) > 0 ? array_sum(array_column($ns2, 'nota')) / count($ns2) : null;

    if ($p1 !== null) {
        $sumaGenSem1 += $p1;
        $cntGenSem1++;
    }
    if ($p2 !== null) {
        $sumaGenSem2 += $p2;
        $cntGenSem2++;
    }

    $filas[] = ['nombre' => $asig['nombre'], 'sem1' => $ns1, 'sem2' => $ns2, 'p1' => $p1, 'p2' => $p2];
}

$promGenSem1 = $cntGenSem1 > 0 ? $sumaGenSem1 / $cntGenSem1 : null;
$promGenSem2 = $cntGenSem2 > 0 ? $sumaGenSem2 / $cntGenSem2 : null;

function colorN($v)
{
    if ($v === null)
        return '#94a3b8';
    return $v >= 4.0 ? '#16a34a' : '#dc2626';
}
function fmtN($v, $dec = 1)
{
    return $v !== null ? number_format($v, $dec) : '—';
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
            font-size: 7px;
            color: #1e293b;
            margin: 10px 12px;
        }

        /* HEADER */
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

        .h-mid {
            display: table-cell;
            width: 74%;
            vertical-align: middle;
            text-align: center;
            padding: 0 6px;
        }

        .h-right {
            display: table-cell;
            width: 13%;
            vertical-align: middle;
            text-align: right;
        }

        .inst-nm {
            font-size: 10px;
            font-weight: bold;
            color: #004b8d;
            text-transform: uppercase;
        }

        .inst-sb {
            font-size: 7px;
            color: #475569;
        }

        .doc-t {
            font-size: 12px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 2px;
        }

        .doc-sb {
            font-size: 8px;
            color: #475569;
        }

        /* DECRETOS */
        .dec {
            font-size: 6px;
            color: #64748b;
            margin-bottom: 5px;
            line-height: 1.5;
        }

        /* FICHA */
        .ficha {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 7px;
        }

        .ficha td {
            border: 1px solid #cbd5e1;
            padding: 3px 5px;
            font-size: 7.5px;
        }

        .ficha .lb {
            background: #e2e8f0;
            font-weight: bold;
            width: 22%;
            color: #334155;
        }

        /* TABLA NOTAS */
        table.nt {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.5px;
        }

        table.nt thead tr.sem-row th {
            background: #1e3a5f;
            color: #fff;
            padding: 2.5px 1px;
            border: 1px solid #1e40af;
            font-size: 6.5px;
            text-align: center;
        }

        table.nt thead tr.nota-row th {
            background: #004b8d;
            color: #fff;
            padding: 2px 1px;
            border: 1px solid #1e40af;
            font-size: 6px;
            text-align: center;
        }

        table.nt thead th.th-asig {
            text-align: left;
            padding-left: 4px;
            min-width: 100px;
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

        table.nt tbody td.td-a {
            text-align: left;
            padding-left: 4px;
            font-weight: bold;
            font-size: 6.5px;
        }

        table.nt tfoot td {
            border: 1px solid #cbd5e1;
            padding: 2.5px 1px;
            text-align: center;
            background: #f1f5f9;
            font-weight: bold;
            font-size: 7px;
        }

        table.nt tfoot td.td-a {
            text-align: left;
            padding-left: 4px;
        }

        .sep {
            border-left: 2px solid #3b82f6 !important;
        }

        .ok {
            color: #16a34a;
            font-weight: bold;
        }

        .mal {
            color: #dc2626;
            font-weight: bold;
        }

        .pen {
            color: #f59e0b;
            font-size: 6px;
            font-style: italic;
        }

        .nd {
            color: #e2e8f0;
        }

        /* ASISTENCIA */
        .asis {
            display: table;
            width: 100%;
            margin-top: 7px;
        }

        .ac {
            display: table-cell;
            width: 33.3%;
            text-align: center;
            border: 1px solid #e2e8f0;
            padding: 4px;
            background: #f8fafc;
        }

        .av {
            font-size: 13px;
            font-weight: bold;
        }

        .al {
            font-size: 6px;
            color: #64748b;
            margin-top: 1px;
        }

        /* OBS */
        .obs {
            margin-top: 7px;
            border: 1px solid #e2e8f0;
            padding: 4px 7px;
            font-size: 6.5px;
        }

        .obs-t {
            font-weight: bold;
            color: #334155;
            margin-bottom: 2px;
        }

        /* FIRMA */
        .firma {
            margin-top: 18px;
            text-align: center;
            font-size: 7.5px;
        }

        .fl {
            border-top: 1px solid #334155;
            width: 150px;
            margin: 0 auto 2px;
        }

        /* FOOTER */
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
                <strong style="color:#004b8d;font-size:8px;">CEIA</strong>
            <?php endif; ?>
        </div>
        <div class="h-mid">
            <div class="inst-nm">Centro de Educación Integrada de Adultos</div>
            <div class="inst-sb">"Juanita Zúñiga Fuentes" – Parral</div>
            <div class="doc-t">Informe de Notas</div>
            <div class="doc-sb">Año Escolar <?= htmlspecialchars($anio['anio']) ?></div>
        </div>
        <div class="h-right">
            <?php if ($logoExists): ?>
                <img src="<?= $logoPath ?>" style="width:52px;height:auto;">
            <?php endif; ?>
        </div>
    </div>

    <!-- DECRETOS -->
    <div class="dec">
        Decreto Exento de educación que aprueba el Reglamento de Evaluación y Promoción Escolar N° 2169 de 2007<br>
        Decreto Exento o Resolución Exenta de Educación que aprueba Plan y Programas de Estudio N° 257 de 2009<br>
        Decreto Supremo, Resolución Exenta de Educación N° 3290 de 1981
    </div>

    <!-- FICHA ALUMNO -->
    <table class="ficha">
        <tr>
            <td class="lb">Estudiante</td>
            <td style="font-weight:bold;">
                <?= htmlspecialchars(strtoupper(
                    $alumno['apepat'] . ' ' . $alumno['apemat'] . ' ' . $alumno['nombre']
                )) ?>
            </td>
        </tr>
        <tr>
            <td class="lb">Curso</td>
            <td><?= htmlspecialchars($curso['nombre']) ?></td>
        </tr>
        <tr>
            <td class="lb">Profesor Jefe</td>
            <td><?= htmlspecialchars($docenteNombre ?? '—') ?></td>
        </tr>
    </table>

    <!-- TABLA NOTAS -->
    <table class="nt">
        <thead>
            <!-- Fila 1: agrupación semestres -->
            <tr class="sem-row">
                <th class="th-asig" rowspan="2">Asignatura</th>
                <th colspan="<?= $maxCols[1] ?>">Primer Semestre</th>
                <th colspan="<?= $maxCols[2] ?>" class="sep">Segundo Semestre</th>
                <th rowspan="2" style="min-width:30px;">Prom.<br>1° Sem</th>
                <th rowspan="2" style="min-width:30px;" class="sep">Prom.<br>2° Sem</th>
                <th rowspan="2" style="min-width:30px;">Prom.<br>Final</th>
            </tr>
            <!-- Fila 2: numeración de notas -->
            <tr class="nota-row">
                <?php for ($i = 1; $i <= $maxCols[1]; $i++): ?>
                    <th>N<?= $i ?></th>
                <?php endfor; ?>
                <?php for ($i = 1; $i <= $maxCols[2]; $i++): ?>
                    <th class="<?= $i === 1 ? 'sep' : '' ?>">N<?= $i ?></th>
                <?php endfor; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filas as $fila):
                $ns1 = array_values($fila['sem1']);
                $ns2 = array_values($fila['sem2']);
                $p1 = $fila['p1'];
                $p2 = $fila['p2'];
                $pFinal = ($p1 !== null && $p2 !== null) ? ($p1 + $p2) / 2 : null;
                ?>
                <tr>
                    <td class="td-a"><?= htmlspecialchars($fila['nombre']) ?></td>

                    <!-- Notas semestre 1 -->
                    <?php for ($i = 0; $i < $maxCols[1]; $i++):
                        $n = $ns1[$i] ?? null;
                        $v = $n ? floatval($n['nota']) : null;
                        ?>
                        <td>
                            <?php if ($v !== null): ?>
                                <span class="<?= $v >= 4.0 ? 'ok' : 'mal' ?>"><?= number_format($v, 1) ?></span>
                            <?php else: ?>
                                <span class="nd">—</span>
                            <?php endif; ?>
                        </td>
                    <?php endfor; ?>

                    <!-- Notas semestre 2 -->
                    <?php for ($i = 0; $i < $maxCols[2]; $i++):
                        $n = $ns2[$i] ?? null;
                        $v = $n ? floatval($n['nota']) : null;
                        ?>
                        <td class="<?= $i === 0 ? 'sep' : '' ?>">
                            <?php if ($v !== null): ?>
                                <span class="<?= $v >= 4.0 ? 'ok' : 'mal' ?>"><?= number_format($v, 1) ?></span>
                            <?php else: ?>
                                <span class="nd">—</span>
                            <?php endif; ?>
                        </td>
                    <?php endfor; ?>

                    <!-- Promedios -->
                    <td style="color:<?= colorN($p1) ?>;font-weight:bold;">
                        <?= $p1 !== null ? number_format($p1, 1) : '<span class="pen">Pend.</span>' ?>
                    </td>
                    <td style="color:<?= colorN($p2) ?>;font-weight:bold;" class="sep">
                        <?= $p2 !== null ? number_format($p2, 1) : '<span class="pen">Pend.</span>' ?>
                    </td>
                    <td style="color:<?= colorN($pFinal) ?>;font-weight:bold;">
                        <?= $pFinal !== null ? number_format($pFinal, 1) : '<span class="pen">Pend.</span>' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="td-a">Promedio General</td>
                <?php
                // Celdas vacías para columnas de notas sem1
                for ($i = 0; $i < $maxCols[1]; $i++)
                    echo '<td></td>';
                // Celdas vacías para columnas de notas sem2
                for ($i = 0; $i < $maxCols[2]; $i++)
                    echo '<td class="' . ($i === 0 ? 'sep' : '') . '"></td>';
                ?>
                <td style="color:<?= colorN($promGenSem1) ?>;font-size:8px;">
                    <?= $promGenSem1 !== null ? number_format($promGenSem1, 1) : '<span class="pen">Pend.</span>' ?>
                </td>
                <td style="color:<?= colorN($promGenSem2) ?>;font-size:8px;" class="sep">
                    <?= $promGenSem2 !== null ? number_format($promGenSem2, 1) : '<span class="pen">Pend.</span>' ?>
                </td>
                <td style="font-size:8px;">—</td>
            </tr>
        </tfoot>
    </table>

    <!-- ASISTENCIA -->
    <div class="asis">
        <div class="ac">
            <div class="av"><?= $asistencia['dias_trabajados'] ?? '—' ?></div>
            <div class="al">Días trabajados</div>
        </div>
        <div class="ac">
            <div class="av" style="color:#dc2626;"><?= $asistencia['dias_inasistencia'] ?? '—' ?></div>
            <div class="al">Días Inasistencia</div>
        </div>
        <div class="ac">
            <?php
            $pct = $asistencia['porcentaje'] ?? null;
            $cA = $pct !== null ? ($pct >= 85 ? '#16a34a' : ($pct >= 75 ? '#d97706' : '#dc2626')) : '#64748b';
            ?>
            <div class="av" style="color:<?= $cA ?>"><?= $pct !== null ? $pct . '%' : '—' ?></div>
            <div class="al">% Asistencia</div>
        </div>
    </div>

    <!-- OBSERVACIONES -->
    <div class="obs">
        <div class="obs-t">Observaciones del Estudiante:</div>
        <?= htmlspecialchars($observaciones ?? 'DOCUMENTO CEIA') ?><br><br>
        <strong>Pendiente:</strong> Falta una o más notas, no se puede obtener promedio de la asignatura ni el promedio
        final.<br>
        Asistencia registrada hasta el día <?= date('j') ?> de <?= strftime('%B', mktime(0, 0, 0, date('n'), 1)) ?>
    </div>

    <!-- FECHA + FIRMA -->
    <table style="width:100%;margin-top:10px;">
        <tr>
            <td style="font-size:7px;">
                <strong>Fecha del Informe:</strong> <?= date('d/m/Y') ?>
            </td>
            <td style="text-align:right;">
                <div class="firma">
                    <div class="fl"></div>
                    <div style="font-weight:bold;font-size:7.5px;"><?= htmlspecialchars($docenteNombre ?? '') ?></div>
                    <div style="font-size:6.5px;color:#64748b;">Profesor Jefe</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Documento generado el <?= date('d/m/Y H:i') ?> — Sistema SAAT · C.E.I.A. Parral
    </div>

</body>

</html>