<?php
// Variables esperadas: $tipo, $retiros, $porSemestre, $porMotivo, $porCurso, $topAlumnos, $media, $anioActual, $filtros, $semestre
$titulos = [
    'general' => 'Reporte General de Retiros',
    'curso' => 'Reporte de Retiros por Curso',
    'alumno' => 'Reporte Individual de Retiros',
];
$tituloReporte = $titulos[$tipo] ?? 'Reporte de Retiros';
$totalRetiros = count($retiros);
$totalJust = count(array_filter($retiros, fn($r) => $r['justificado'] === 'Si'));
$totalInjust = count(array_filter($retiros, fn($r) => $r['justificado'] === 'No'));
$totalExtraord = count(array_filter($retiros, fn($r) => $r['extraordinario'] === 'Si'));
$maxCurso = !empty($porCurso) ? $porCurso[0]['total'] : 1;

// Datos del alumno (solo tipo individual)
$alumno = ($tipo === 'alumno' && !empty($retiros)) ? $retiros[0] : null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #1e293b;
            margin: 18px 20px;
        }

        /* HEADER */
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #4338ca;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .header-left {
            display: table-cell;
            width: 18%;
            vertical-align: middle;
        }

        .header-right {
            display: table-cell;
            width: 82%;
            vertical-align: middle;
            padding-left: 12px;
        }

        .inst-nombre {
            font-size: 13px;
            font-weight: bold;
            color: #004b8d;
            text-transform: uppercase;
        }

        .inst-sub {
            font-size: 9px;
            color: #475569;
        }

        .ficha-titulo {
            display: inline-block;
            margin-top: 5px;
            padding: 3px 10px;
            background: #4338ca;
            color: #fff;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            border-radius: 3px;
        }

        /* SUBTÍTULO */
        .sub-header {
            margin: 0 0 12px;
        }

        .titulo {
            font-size: 14px;
            font-weight: bold;
            color: #4338ca;
        }

        .subtitulo {
            font-size: 9px;
            color: #475569;
            margin-top: 2px;
        }

        /* FICHA ALUMNO */
        .ficha-alumno {
            background: #fefce8;
            border: 1px solid #fcd34d;
            border-radius: 5px;
            padding: 10px 12px;
            margin-bottom: 14px;
        }

        .ficha-alumno-nombre {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }

        .ficha-alumno-sub {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
        }

        /* MÉTRICAS */
        .metricas {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }

        .met-cell {
            display: table-cell;
            text-align: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 7px 4px;
            width: 25%;
        }

        .met-valor {
            font-size: 20px;
            font-weight: bold;
        }

        .met-label {
            font-size: 7.5px;
            color: #64748b;
            margin-top: 2px;
        }

        .c-white {
            color: #1e293b;
        }

        .c-red {
            color: #dc2626;
        }

        .c-green {
            color: #16a34a;
        }

        .c-amber {
            color: #d97706;
        }

        .c-ind {
            color: #4338ca;
        }

        /* SECCIÓN */
        .seccion {
            font-size: 9px;
            font-weight: bold;
            color: #004b8d;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
            margin: 12px 0 6px;
        }

        /* SEMESTRES */
        .sem-grid {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }

        .sem-cell {
            display: table-cell;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px 12px;
        }

        .sem-num {
            font-size: 22px;
            font-weight: bold;
            color: #1e293b;
        }

        .sem-sub {
            font-size: 8px;
            color: #64748b;
            margin-bottom: 3px;
        }

        .badge {
            display: inline-block;
            border-radius: 9999px;
            padding: 2px 7px;
            font-size: 8px;
            margin-right: 3px;
        }

        .badge-g {
            background: #dcfce7;
            color: #166534;
        }

        .badge-r {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-a {
            background: #fef3c7;
            color: #92400e;
        }

        /* TABLAS */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        thead tr {
            background: #4338ca;
        }

        thead th {
            color: white;
            padding: 5px 4px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
        }

        thead th.tc {
            text-align: center;
        }

        tbody td {
            border-bottom: 1px solid #e2e8f0;
            padding: 4px;
            font-size: 8.5px;
        }

        tbody tr:nth-child(even) td {
            background: #f8fafc;
        }

        .tc {
            text-align: center;
        }

        .badge-j {
            color: #16a34a;
            font-weight: bold;
        }

        .badge-ij {
            color: #dc2626;
            font-weight: bold;
        }

        .badge-ex {
            color: #d97706;
            font-weight: bold;
        }

        /* BARRAS */
        .barra-bg {
            background: #e2e8f0;
            border-radius: 3px;
            height: 7px;
        }

        .barra-fill {
            background: #4338ca;
            border-radius: 3px;
            height: 7px;
        }

        /* PIE */
        .footer {
            margin-top: 16px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            font-size: 7.5px;
            color: #94a3b8;
            text-align: right;
        }
    </style>
</head>

<body>

    <!-- HEADER INSTITUCIONAL -->
    <div class="header">
        <div class="header-left">
            <?php $logoPath = __DIR__ . '/../../public/img/LOGO CEIA.jpg'; ?>
            <?php if (file_exists($logoPath)): ?>
                <img src="http://localhost:8080/app/public/img/LOGO%20CEIA.jpg" style="width:80px;">
            <?php else: ?>
                <strong style="color:#004b8d;">CEIA Parral</strong>
            <?php endif; ?>
        </div>
        <div class="header-right">
            <div class="inst-nombre">Centro de Educación Integrada de Adultos</div>
            <div class="inst-sub">"Juanita Zúñiga Fuentes" – Parral</div>
            <div class="ficha-titulo"><?= $tituloReporte ?> <?= $anioActual ? $anioActual['anio'] : date('Y') ?></div>
        </div>
    </div>

    <!-- SUBTÍTULO -->
    <div class="sub-header">
        <div class="titulo"><?= $tituloReporte ?></div>
        <div class="subtitulo">
            Generado el: <?= date('d/m/Y H:i') ?>
            <?= $anioActual ? ' · Año ' . $anioActual['anio'] : '' ?>
            <?= !empty($semestre) ? ' · ' . $semestre . '° Semestre' : '' ?>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════
     REPORTE INDIVIDUAL — ficha del alumno
════════════════════════════════════════════════ -->
    <?php if ($tipo === 'alumno'): ?>

        <?php if ($alumno): ?>
            <div class="ficha-alumno">
                <div class="ficha-alumno-nombre">
                    <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat'] . ', ' . $alumno['nombre']) ?>
                </div>
                <div class="ficha-alumno-sub">
                    RUN: <?= htmlspecialchars($alumno['run']) ?>
                    &nbsp;·&nbsp; Curso: <?= htmlspecialchars($alumno['curso']) ?>
                    &nbsp;·&nbsp; Año: <?= $alumno['anio'] ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Métricas individuales -->
        <div class="metricas">
            <div class="met-cell">
                <div class="met-valor c-white"><?= $totalRetiros ?></div>
                <div class="met-label">Total retiros</div>
            </div>
            <div class="met-cell">
                <div class="met-valor c-red"><?= $totalInjust ?></div>
                <div class="met-label">No justificados</div>
            </div>
            <div class="met-cell">
                <div class="met-valor c-green"><?= $totalJust ?></div>
                <div class="met-label">Justificados</div>
            </div>
            <div class="met-cell">
                <div class="met-valor c-amber"><?= $totalExtraord ?></div>
                <div class="met-label">Extraordinarios</div>
            </div>
        </div>

        <!-- Comparativa semestres (individual) -->
        <?php if (!empty($porSemestre)): ?>
            <div class="seccion">Distribución por semestre</div>
            <div class="sem-grid">
                <?php foreach ($porSemestre as $sem): ?>
                    <div class="sem-cell">
                        <div class="sem-sub"><?= $sem['semestre'] ?>° Semestre</div>
                        <div class="sem-num"><?= $sem['total'] ?></div>
                        <div class="sem-sub"><?= $sem['porcentaje'] ?>% del total</div>
                        <span class="badge badge-g"><?= $sem['justificados'] ?> just.</span>
                        <span class="badge badge-r"><?= $sem['injustificados'] ?> no just.</span>
                        <?php if (($sem['extraordinarios'] ?? 0) > 0): ?>
                            <span class="badge badge-a"><?= $sem['extraordinarios'] ?> extraord.</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Detalle individual (sin columna alumno, ya está en la ficha) -->
        <div class="seccion">Detalle de retiros (<?= $totalRetiros ?> registros)</div>
        <table>
            <thead>
                <tr>
                    <th class="tc" style="width:20px">#</th>
                    <th class="tc">Fecha</th>
                    <th class="tc">Hora</th>
                    <th class="tc">Sem.</th>
                    <th>Motivo</th>
                    <th>Quien retira</th>
                    <th class="tc">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($retiros as $i => $r): ?>
                    <tr>
                        <td class="tc" style="color:#94a3b8"><?= $i + 1 ?></td>
                        <td class="tc"><?= date('d/m/Y', strtotime($r['fecha_retiro'])) ?></td>
                        <td class="tc" style="color:#4338ca;font-weight:bold"><?= substr($r['hora_retiro'], 0, 5) ?></td>
                        <td class="tc"><?= $r['semestre'] ?>°</td>
                        <td><?= htmlspecialchars($r['motivo']) ?><?= $r['observacion'] ? '<br><span style="font-size:7.5px;color:#94a3b8;font-style:italic;">' . htmlspecialchars($r['observacion']) . '</span>' : '' ?>
                        </td>
                        <td><?= $r['quien_retira'] ? htmlspecialchars($r['quien_retira']) : '—' ?></td>
                        <td class="tc">
                            <?php if ($r['justificado'] === 'Si'): ?>
                                <span class="badge-j">J</span>
                            <?php else: ?>
                                <span class="badge-ij">IJ</span>
                            <?php endif; ?>
                            <?php if ($r['extraordinario'] === 'Si'): ?>
                                <span class="badge-ex"> EX</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <!-- ══════════════════════════════════════════════
     REPORTE GENERAL / POR CURSO
════════════════════════════════════════════════ -->

        <!-- Métricas generales -->
        <div class="metricas">
            <div class="met-cell">
                <div class="met-valor c-white"><?= $totalRetiros ?></div>
                <div class="met-label">Total retiros</div>
            </div>
            <div class="met-cell">
                <div class="met-valor c-red"><?= $totalInjust ?></div>
                <div class="met-label">No justificados</div>
            </div>
            <div class="met-cell">
                <div class="met-valor c-green"><?= $totalJust ?></div>
                <div class="met-label">Justificados</div>
            </div>
            <div class="met-cell">
                <div class="met-valor c-amber"><?= $totalExtraord ?></div>
                <div class="met-label">Extraordinarios</div>
            </div>
        </div>

        <!-- Comparativa semestres -->
        <?php if (!empty($porSemestre)): ?>
            <div class="seccion">Comparativa por semestre</div>
            <div class="sem-grid">
                <?php foreach ($porSemestre as $sem): ?>
                    <div class="sem-cell">
                        <div class="sem-sub"><?= $sem['semestre'] ?>° Semestre</div>
                        <div class="sem-num"><?= $sem['total'] ?></div>
                        <div class="sem-sub"><?= $sem['porcentaje'] ?>% del total</div>
                        <span class="badge badge-g"><?= $sem['justificados'] ?> just.</span>
                        <span class="badge badge-r"><?= $sem['injustificados'] ?> no just.</span>
                        <?php if (($sem['extraordinarios'] ?? 0) > 0): ?>
                            <span class="badge badge-a"><?= $sem['extraordinarios'] ?> extraord.</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Top alumnos -->
        <?php if (!empty($topAlumnos)): ?>
            <div class="seccion">Top alumnos con más retiros</div>
            <table>
                <thead>
                    <tr>
                        <th class="tc" style="width:25px">#</th>
                        <th>Alumno</th>
                        <th>Curso</th>
                        <th class="tc" style="width:50px">Total</th>
                        <th class="tc" style="width:55px">No just.</th>
                        <th class="tc" style="width:60px">Extraord.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topAlumnos as $i => $al):
                        $col = $i === 0 ? '#d97706' : ($i === 1 ? '#64748b' : ($i === 2 ? '#92400e' : '#94a3b8'));
                        $colT = $al['total'] >= 5 ? '#dc2626' : ($al['total'] >= 3 ? '#d97706' : '#334155');
                        ?>
                        <tr>
                            <td class="tc" style="font-weight:bold;color:<?= $col ?>"><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?></td>
                            <td style="color:#64748b"><?= htmlspecialchars($al['curso']) ?></td>
                            <td class="tc" style="font-weight:bold;font-size:11px;color:<?= $colT ?>"><?= $al['total'] ?></td>
                            <td class="tc badge-ij"><?= $al['injustificados'] ?></td>
                            <td class="tc badge-ex"><?= $al['extraordinarios'] ?? 0 ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Por curso -->
        <?php if (!empty($porCurso)): ?>
            <div class="seccion">Retiros por curso</div>
            <table>
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th class="tc" style="width:60px">Total</th>
                        <th class="tc" style="width:65px">No just.</th>
                        <th class="tc" style="width:65px">Extraord.</th>
                        <th style="width:180px">Barra</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($porCurso as $c):
                        $pct = round($c['total'] / $maxCurso * 100); ?>
                        <tr>
                            <td><?= htmlspecialchars($c['curso']) ?></td>
                            <td class="tc" style="font-weight:bold;color:#4338ca"><?= $c['total'] ?></td>
                            <td class="tc badge-ij"><?= $c['injustificados'] ?></td>
                            <td class="tc badge-ex"><?= $c['extraordinarios'] ?? 0 ?></td>
                            <td>
                                <div class="barra-bg">
                                    <div class="barra-fill" style="width:<?= $pct ?>%"></div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Por motivo -->
        <?php if (!empty($porMotivo)): ?>
            <div class="seccion">Retiros por motivo</div>
            <table>
                <thead>
                    <tr>
                        <th>Motivo</th>
                        <th class="tc" style="width:60px">Total</th>
                        <th class="tc" style="width:65px">Justificados</th>
                        <th class="tc" style="width:70px">No justif.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($porMotivo as $m): ?>
                        <tr>
                            <td><?= htmlspecialchars($m['motivo']) ?></td>
                            <td class="tc" style="font-weight:bold"><?= $m['total'] ?></td>
                            <td class="tc badge-j"><?= $m['justificados'] ?></td>
                            <td class="tc badge-ij"><?= $m['injustificados'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Media -->
        <?php if ($media !== null): ?>
            <div
                style="margin-bottom:12px;padding:8px 12px;background:#f1f5f9;border-radius:4px;border-left:3px solid #4338ca;">
                <strong style="color:#4338ca;">Media de retiros por alumno:</strong>
                <span
                    style="font-size:13px;font-weight:bold;color:#1e293b;margin-left:8px;"><?= number_format($media, 2) ?></span>
            </div>
        <?php endif; ?>

        <!-- Detalle general (con columna alumno) -->
        <div class="seccion">Detalle de retiros (<?= $totalRetiros ?> registros)</div>
        <table>
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Curso</th>
                    <th class="tc">Fecha</th>
                    <th class="tc">Hora</th>
                    <th class="tc">Sem.</th>
                    <th>Motivo</th>
                    <th>Quien retira</th>
                    <th class="tc">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($retiros as $r): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($r['apepat'] . ' ' . $r['apemat'] . ', ' . $r['nombre']) ?><br>
                            <span style="font-size:7.5px;color:#94a3b8;"><?= htmlspecialchars($r['run']) ?></span>
                        </td>
                        <td style="color:#64748b"><?= htmlspecialchars($r['curso']) ?></td>
                        <td class="tc"><?= date('d/m/Y', strtotime($r['fecha_retiro'])) ?></td>
                        <td class="tc" style="color:#4338ca;font-weight:bold"><?= substr($r['hora_retiro'], 0, 5) ?></td>
                        <td class="tc"><?= $r['semestre'] ?>°</td>
                        <td><?= htmlspecialchars($r['motivo']) ?></td>
                        <td><?= $r['quien_retira'] ? htmlspecialchars($r['quien_retira']) : '—' ?></td>
                        <td class="tc">
                            <?php if ($r['justificado'] === 'Si'): ?>
                                <span class="badge-j">J</span>
                            <?php else: ?>
                                <span class="badge-ij">IJ</span>
                            <?php endif; ?>
                            <?php if ($r['extraordinario'] === 'Si'): ?>
                                <span class="badge-ex"> EX</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

    <div class="footer">
        Reporte generado automáticamente por el sistema — C.E.I.A. Parral <?= date('Y') ?>
        <p>Desarrollado por Daniel Scarlazzetta</p>
    </div>

</body>

</html>