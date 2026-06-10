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
            font-size: 10px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }

        .page {
            margin: 22px 30px 90px 30px;
        }

        /* ── ENCABEZADO 3 LOGOS ── */
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #004b8d;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .h-left {
            display: table-cell;
            width: 28%;
            vertical-align: middle;
        }

        .h-center {
            display: table-cell;
            width: 44%;
            vertical-align: middle;
            text-align: center;
        }

        .h-right {
            display: table-cell;
            width: 28%;
            vertical-align: middle;
            text-align: right;
        }

        .header img {
            height: 62px;
            width: auto;
        }

        /* Título del documento (debajo de los logos) */
        .doc-header {
            text-align: center;
            margin-bottom: 8px;
        }

        .doc-titulo {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .doc-subtitulo {
            font-size: 7.5px;
            color: #475569;
            margin-top: 2px;
        }

        /* ── DECRETO ── */
        .dec {
            font-size: 9px;
            color: #64748b;
            margin-bottom: 8px;
            line-height: 1.6;
            border-left: 2px solid #004b8d;
            padding-left: 6px;
        }

        /* ── FICHA ALUMNO ── */
        .ficha {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .ficha td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            font-size: 8px;
        }

        .ficha .lb {
            background: #dbeafe;
            font-weight: bold;
            width: 20%;
            color: #1e3a5f;
        }

        /* ── TABLA NOTAS ── */
        table.nt {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            table-layout: fixed;
        }

        table.nt thead tr.sem-row th {
            background: #1e3a5f;
            color: #fff;
            padding: 3px 1px;
            border: 1px solid #1e40af;
            font-size: 7px;
            text-align: center;
        }

        table.nt thead tr.nota-row th {
            background: #004b8d;
            color: #fff;
            padding: 2.5px 1px;
            border: 1px solid #1e40af;
            font-size: 6.5px;
            text-align: center;
        }

        table.nt thead th.th-asig {
            text-align: left;
            padding-left: 5px;
            width: 120px;
        }

        table.nt tbody td {
            border: 1px solid #e2e8f0;
            padding: 2.5px 1.5px;
            text-align: center;
            vertical-align: middle;
        }

        table.nt tbody tr:nth-child(even) td {
            background: #f8fafc;
        }

        table.nt tbody td.td-a {
            text-align: left;
            padding-left: 5px;
            font-weight: bold;
            font-size: 7px;
            width: 120px;
        }

        table.nt tfoot td {
            border: 1px solid #cbd5e1;
            padding: 3px 1.5px;
            text-align: center;
            background: #dbeafe;
            font-weight: bold;
            font-size: 9.5px;
            color: #1e3a5f;
        }

        table.nt tfoot td.td-a {
            text-align: left;
            padding-left: 5px;
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
            color: #cbd5e1;
        }

        /* ── ASISTENCIA ── */
        .asis {
            display: table;
            width: 100%;
            margin-top: 10px;
            border-collapse: separate;
            border-spacing: 4px;
        }

        .ac {
            display: table-cell;
            width: 33.3%;
            text-align: center;
            border: 1px solid #e2e8f0;
            padding: 6px 4px;
            background: #f8fafc;
            border-radius: 4px;
        }

        .av {
            font-size: 18px;
            font-weight: bold;
        }

        .al {
            font-size: 6.5px;
            color: #64748b;
            margin-top: 2px;
        }

        /* ── OBSERVACIONES ── */
        .obs {
            margin-top: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 5px 8px;
            font-size: 9px;
            background: #f8fafc;
        }

        .obs-t {
            font-weight: bold;
            color: #1e3a5f;
            margin-bottom: 3px;
            font-size: 7.5px;
        }

        /* ── FIRMA ── */
        .firmas {
            display: table;
            width: 100%;
            margin-top: 20px;
        }

        .firma-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 16px;
        }

        .firma-linea {
            border-top: 1px solid #334155;
            padding-top: 5px;
            margin-top: 40px;
        }

        .firma-nombre {
            font-weight: bold;
            font-size: 8px;
        }

        .firma-cargo {
            font-size: 7px;
            color: #64748b;
        }

        /* ── PIE ── */
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

        /* ── FOOTER TEXTO ── */
        .footer {
            margin-top: 8px;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
            font-size: 9px;
            color: #94a3b8;
            text-align: right;
        }
    </style>
</head>

<body>

    <?php
    $base = 'http://localhost:8080/app/public/img/';
    $logo1 = $base . 'certificado_1.png';
    $logo2 = $base . 'certificado_3.png';
    $logo3 = $base . 'certificado_2.png';
    $piePag = $base . 'pie_pagina_certificado.png';

    // Calcular promedios
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

    $cursosPrimerNivel = [2, 5, 8];
    $esPrimerNivel = in_array((int) ($curso['id'] ?? 0), $cursosPrimerNivel);
    ?>

    <!-- PIE FIJO -->
    <div class="pie-pagina"><img src="<?= $piePag ?>" alt="Pie"></div>

    <div class="page">

        <!-- ENCABEZADO -->
        <div class="header">
            <div class="h-left"><img src="<?= $logo1 ?>" alt="Educación Pública"></div>
            <div class="h-center"><img src="<?= $logo2 ?>" alt="Los Álamos"></div>
            <div class="h-right"><img src="<?= $logo3 ?>" alt="CEIA"></div>
        </div>

        <!-- TÍTULO -->
        <div class="doc-header">
            <div class="doc-titulo">Informe de Notas</div>
            <div class="doc-subtitulo">Año Escolar <?= htmlspecialchars($anio['anio']) ?></div>
        </div>

        <!-- DECRETO -->
        <div class="dec">
            <?php if ($esPrimerNivel): ?>
                Decreto Supremo de Educación que aprueba Bases Curriculares N°10/2022 ·
            <?php else: ?>
                Decreto Exento de Educación que aprueba Planes y Programas N° 257/2009 ·
            <?php endif; ?>
            Decreto Exento N° 2169/2007 Reglamento de Evaluación y Promoción ·
            Reconocimiento Oficial Ministerio de Educación N°3290/1981
        </div>

        <!-- FICHA ALUMNO -->
        <table class="ficha">
            <tr>
                <td class="lb">Estudiante</td>
                <td style="font-weight:bold;">
                    <?= htmlspecialchars(strtoupper($alumno['apepat'] . ' ' . $alumno['apemat'] . ' ' . $alumno['nombre'])) ?>
                </td>
                <td class="lb" style="width:14%;">Curso</td>
                <td><?= htmlspecialchars($curso['nombre']) ?></td>
            </tr>
            <tr>
                <td class="lb">RUT</td>
                <td><?= htmlspecialchars($alumno['run'] . '-' . ($alumno['codver'] ?? '')) ?></td>
                <td class="lb">Profesor Jefe</td>
                <td><?= htmlspecialchars(strtoupper($docenteNombre ?? '—')) ?></td>
            </tr>

        </table>

        <!-- TABLA NOTAS -->
        <table class="nt">
            <thead>
                <tr class="sem-row">
                    <th class="th-asig" rowspan="2">Asignatura</th>
                    <th colspan="<?= $maxCols[1] ?>">1° Semestre</th>
                    <th colspan="<?= $maxCols[2] ?>" class="sep">2° Semestre</th>
                    <th rowspan="2" style="min-width:28px;">Prom.<br>S1</th>
                    <th rowspan="2" style="min-width:28px;" class="sep">Prom.<br>S2</th>
                    <th rowspan="2" style="min-width:28px;">Prom.<br>Final</th>
                </tr>
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
                        <?php for ($i = 0; $i < $maxCols[1]; $i++):
                            $n = $ns1[$i] ?? null;
                            $v = $n ? floatval($n['nota']) : null;
                            ?>
                            <td><?php if ($v !== null): ?>
                                    <span class="<?= $v >= 4.0 ? 'ok' : 'mal' ?>"><?= number_format($v, 1) ?></span>
                                <?php else: ?><span class="nd">—</span><?php endif; ?>
                            </td>
                        <?php endfor; ?>
                        <?php for ($i = 0; $i < $maxCols[2]; $i++):
                            $n = $ns2[$i] ?? null;
                            $v = $n ? floatval($n['nota']) : null;
                            ?>
                            <td class="<?= $i === 0 ? 'sep' : '' ?>"><?php if ($v !== null): ?>
                                    <span class="<?= $v >= 4.0 ? 'ok' : 'mal' ?>"><?= number_format($v, 1) ?></span>
                                <?php else: ?><span class="nd">—</span><?php endif; ?>
                            </td>
                        <?php endfor; ?>
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
                    <?php for ($i = 0; $i < $maxCols[1]; $i++): ?>
                        <td></td><?php endfor; ?>
                    <?php for ($i = 0; $i < $maxCols[2]; $i++): ?>
                        <td class="<?= $i === 0 ? 'sep' : '' ?>"></td>
                    <?php endfor; ?>
                    <td style="color:<?= colorN($promGenSem1) ?>"><?= fmtN($promGenSem1) ?></td>
                    <td style="color:<?= colorN($promGenSem2) ?>" class="sep"><?= fmtN($promGenSem2) ?></td>
                    <td>—</td>
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
                <div class="al">Días inasistencia</div>
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
            <div class="obs-t">Observaciones:</div>
            <?= htmlspecialchars($observaciones ?? 'DOCUMENTO CEIA') ?><br><br>
            <strong>Pend.:</strong> Falta una o más notas — no se puede calcular promedio de la asignatura ni el
            promedio final.<br>
            <strong>Situación:</strong> Parcial a la fecha<br>
            Asistencia registrada hasta el día <?= date('d/m/Y') ?>
        </div>

        <!-- FECHA -->
        <div style="font-size:7.5px; margin-top:10px;">
            <strong>Fecha del Informe:</strong> <?= date('d/m/Y') ?>
        </div>

        <!-- FIRMAS -->
        <div class="firmas">
            <div class="firma-col">
                <div class="firma-linea">
                    <div class="firma-nombre">
                        <?= htmlspecialchars(strtoupper($docenteNombre ?? '—')) ?>
                    </div>
                    <div class="firma-cargo">Profesor(a) Jefe</div>

                </div>
            </div>
            <div class="firma-col">
                <div class="firma-linea">
                    <div class="firma-nombre">Juan José Araya Chandía</div>
                    <div class="firma-cargo">Director ADP C.E.I.A. Parral</div>
                </div>
            </div>
        </div>

        <div class="footer">
            Documento generado el <?= date('d/m/Y H:i') ?> — Sistema SAAT · C.E.I.A. Parral
        </div>

    </div>
</body>

</html>