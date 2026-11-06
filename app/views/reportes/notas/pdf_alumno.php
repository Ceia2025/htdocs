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
            font-size: 8px;
        }

        table.nt thead th {
            background: #0f3d7a;
            color: #fff;
            border: 1px solid #ffffff;
            padding: 4px 2px;
            text-align: center;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        table.nt thead tr.nota-row th {
            background: #1d5aa8;
            font-size: 6.5px;
            padding: 3px 1px;
        }

        table.nt thead th.th-asig {
            text-align: left;
            padding-left: 6px;
            width: 105px;
            font-size: 8px;
        }

        table.nt thead th.th-prom {
            width: 40px;
            font-size: 6.5px;
            background: #1d5aa8;
        }

        table.nt thead th.th-fin {
            width: 48px;
            font-size: 6px;
        }

        table.nt thead th.gap,
        table.nt tbody td.gap,
        table.nt tfoot td.gap {
            width: 5px;
            padding: 0;
            border-top: none;
            border-bottom: none;
        }

        table.nt thead th.gap {
            background: #ffffff;
            border: none;
        }

        table.nt tbody td.gap {
            background: #dbeafe;
            border-left: none;
            border-right: none;
        }

        table.nt tbody td {
            border: 1px solid #dbe4f0;
            padding: 6px 2px;
            text-align: center;
            vertical-align: middle;
            background: #ffffff;
            font-size: 8.5px;
        }

        table.nt tbody tr:nth-child(even) td {
            background: #f1f6fc;
        }

        table.nt tbody tr:nth-child(even) td.gap {
            background: #dbeafe;
        }

        table.nt tbody td.td-a {
            text-align: left;
            padding-left: 6px;
            font-weight: bold;
            font-size: 7.5px;
            color: #1e3a5f;
        }

        table.nt tfoot td {
            border: 1px solid #c7d7ee;
            padding: 6px 2px;
            text-align: center;
            background: #e3edf9;
            font-weight: bold;
            font-size: 9px;
            color: #0f3d7a;
        }

        table.nt tfoot td.td-a {
            text-align: left;
            padding-left: 6px;
            font-size: 8px;
        }

        table.nt tfoot td.gap {
            background: #dbeafe;
            border-left: none;
            border-right: none;
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
            font-size: 6.5px;
            font-style: italic;
            font-weight: bold;
        }

        .nd {
            color: #94a3b8;
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
    require_once __DIR__ . '/../../../models/Nota.php';
    require_once __DIR__ . '/helperPDF.php';

    // ── Promedios por asignatura y semestre ──────────────────────────
    // El total esperado de cada asignatura es su cantidad de FECHAS únicas
    // en ese semestre. Si al alumno le falta alguna → promedioCompleto()
    // devuelve null y la asignatura queda "Pend." (mismo criterio que la web).
    $promsSem1 = [];
    $promsSem2 = [];
    $filas = [];

    // Ubica cada nota en su columna según la posición de su fecha entre
    // TODAS las fechas de esa asignatura. Así una nota "salteada" (ej. la
    // 3ª evaluación) cae en su columna real, no en la primera libre.
    $ubicarEnColumnas = function (array $notas, array $fechasColumna): array {
        $posDeFecha = array_flip($fechasColumna); // fecha => índice de columna
        $cols = array_fill(0, max(1, count($fechasColumna)), null);
        foreach ($notas as $n) {
            $f = substr($n['fecha'], 0, 10);
            if (isset($posDeFecha[$f])) {
                $cols[$posDeFecha[$f]] = $n;
            }
        }
        return $cols;
    };

    foreach ($asignaturas as $asig) {
        $id = $asig['id'];
        $raw1 = $notasSem[1][$id] ?? [];
        $raw2 = $notasSem[2][$id] ?? [];

        // Fechas de referencia del curso para ESA asignatura y semestre
        $fechas1 = $fechasRef[1][$id] ?? [];
        $fechas2 = $fechasRef[2][$id] ?? [];

        // Notas ubicadas en su columna real (huecos null donde falta)
        $cols1 = $ubicarEnColumnas($raw1, $fechas1);
        $cols2 = $ubicarEnColumnas($raw2, $fechas2);

        // Total esperado = cantidad de columnas del CURSO en esa asignatura
        $esperado1 = count($fechas1);
        $esperado2 = count($fechas2);

        $p1 = Nota::promedioCompleto($raw1, $esperado1);
        $p2 = Nota::promedioCompleto($raw2, $esperado2);

        if ($p1 !== null)
            $promsSem1[] = $p1;
        if ($p2 !== null)
            $promsSem2[] = $p2;

        $pFinal = ($p1 !== null && $p2 !== null)
            ? Nota::promedio([$p1, $p2])
            : null;

        $filas[] = [
            'nombre' => $asig['nombre'],
            'cols1' => $cols1,
            'cols2' => $cols2,
            'p1' => $p1,
            'p2' => $p2,
            'pFinal' => $pFinal,
            'tiene1' => count($raw1) > 0,
            'tiene2' => count($raw2) > 0,
        ];
    }

    // ── Promedios generales (promedio de promedios) ──────────────────
    $promGenSem1 = Nota::promedio($promsSem1);
    $promGenSem2 = Nota::promedio($promsSem2);

    $cursosPrimerNivel = [2, 5, 8];
    $esPrimerNivel = in_array((int) ($curso['id'] ?? 0), $cursosPrimerNivel);
    ?>

    <?php
    $nombreCursoCorte = mb_strtolower(trim($curso['nombre']), 'UTF-8');
    $esCuartoMedio = (bool) preg_match('/(^|\s)(4|iv|cuarto)\s*[°º]?\s*(año\s+)?medio/u', $nombreCursoCorte);
    $fechaCorte = sprintf('%04d-%s', (int) $anio['anio'], $esCuartoMedio ? '11-06' : '11-24');
    $s2Calculable = date('Y-m-d') >= $fechaCorte;

    $promsS2 = [];
    $finales = [];
    foreach ($filas as &$f) {
        if ($s2Calculable) {
            $notasS2 = array_filter($f['cols2'], fn($n) => $n !== null);
            $f['p2'] = Nota::promedio($notasS2);
        } else {
            $f['p2'] = null;
        }

        $f['pFinal'] = ($f['p1'] !== null && $f['p2'] !== null)
            ? Nota::promedio([$f['p1'], $f['p2']])
            : null;

        if ($f['p2'] !== null)
            $promsS2[] = $f['p2'];
        if ($f['pFinal'] !== null)
            $finales[] = $f['pFinal'];
    }
    unset($f);

    $promGenSem2 = Nota::promedio($promsS2);
    $promFinalGeneral = Nota::promedio($finales);
    ?>



    <!-- PIE FIJO -->
    <?php pdfNotasPiePagina(); ?>

    <div class="page">

        <!-- ENCABEZADO -->
        <?php pdfNotasHeaderTresLogos(); ?>

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
                    <th colspan="<?= $maxCols[1] ?>">1.er Semestre</th>
                    <th class="th-prom" rowspan="2">Promedio<br>1.er Semestre</th>
                    <th class="gap" rowspan="2"></th>
                    <th colspan="<?= $maxCols[2] ?>">2.º Semestre</th>
                    <th class="th-prom" rowspan="2">Promedio<br>2.º Semestre</th>
                    <th class="gap" rowspan="2"></th>
                    <th colspan="2">Promedios Finales</th>
                </tr>
                <tr class="nota-row">
                    <?php for ($i = 1; $i <= $maxCols[1]; $i++): ?>
                        <th>N<?= $i ?></th>
                    <?php endfor; ?>
                    <?php for ($i = 1; $i <= $maxCols[2]; $i++): ?>
                        <th>N<?= $i ?></th>
                    <?php endfor; ?>
                    <th class="th-fin">Promedio final<br>por asignatura</th>
                    <th class="th-fin">Promedio<br>general final</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filas as $fila):
                    $cols1 = $fila['cols1'];
                    $cols2 = $fila['cols2'];
                    $p1 = $fila['p1'];
                    $p2 = $fila['p2'];
                    $pFinal = $fila['pFinal'];
                    ?>
                    <tr>
                        <td class="td-a"><?= htmlspecialchars($fila['nombre']) ?></td>

                        <?php for ($i = 0; $i < $maxCols[1]; $i++):
                            $n = $cols1[$i] ?? null;
                            $v = $n !== null ? Nota::redondear((float) $n['nota']) : null;
                            ?>
                            <td>
                                <?php if ($v !== null): ?>
                                    <span class="<?= Nota::aprobada($v) ? 'ok' : 'mal' ?>"><?= Nota::fmt($v) ?></span>
                                <?php else: ?>
                                    <span class="nd">—</span>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>

                        <td style="color:<?= Nota::color($p1) ?>;font-weight:bold;">
                            <?php if ($p1 !== null): ?>
                                <?= Nota::fmt($p1) ?>
                            <?php elseif ($fila['tiene1']): ?>
                                <span class="pen">Pend.</span>
                            <?php else: ?>
                                <span class="nd">—</span>
                            <?php endif; ?>
                        </td>

                        <td class="gap"></td>

                        <?php for ($i = 0; $i < $maxCols[2]; $i++):
                            $n = $cols2[$i] ?? null;
                            $v = $n !== null ? Nota::redondear((float) $n['nota']) : null;
                            ?>
                            <td>
                                <?php if ($v !== null): ?>
                                    <span class="<?= Nota::aprobada($v) ? 'ok' : 'mal' ?>"><?= Nota::fmt($v) ?></span>
                                <?php else: ?>
                                    <span class="nd">—</span>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>

                        <td style="color:<?= Nota::color($p2) ?>;font-weight:bold;">
                            <?php if ($p2 !== null): ?>
                                <?= Nota::fmt($p2) ?>
                            <?php elseif ($fila['tiene2']): ?>
                                <span class="pen">Pend.</span>
                            <?php else: ?>
                                <span class="nd">—</span>
                            <?php endif; ?>
                        </td>

                        <td class="gap"></td>

                        <td style="color:<?= Nota::color($pFinal) ?>;font-weight:bold;">
                            <?php if ($pFinal !== null): ?>
                                <?= Nota::fmt($pFinal) ?>
                            <?php elseif ($fila['tiene1'] || $fila['tiene2']): ?>
                                <span class="pen">Pend.</span>
                            <?php else: ?>
                                <span class="nd">—</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="nd">—</span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="td-a" colspan="<?= 1 + $maxCols[1] ?>">Promedio General</td>

                    <td style="color:<?= Nota::color($promGenSem1) ?>">
                        <?php if ($promGenSem1 !== null): ?>
                            <?= Nota::fmt($promGenSem1) ?>
                        <?php else: ?>
                            <span class="pen">Pend.</span>
                        <?php endif; ?>
                    </td>

                    <td class="gap"></td>

                    <td colspan="<?= $maxCols[2] ?>"></td>

                    <td style="color:<?= Nota::color($promGenSem2) ?>">
                        <?php if ($promGenSem2 !== null): ?>
                            <?= Nota::fmt($promGenSem2) ?>
                        <?php else: ?>
                            <span class="pen">Pend.</span>
                        <?php endif; ?>
                    </td>

                    <td class="gap"></td>

                    <td></td>
                    <td style="color:<?= Nota::color($promFinalGeneral) ?>;">
                        <?php if ($promFinalGeneral !== null): ?>
                            <?= Nota::fmt($promFinalGeneral) ?>
                        <?php elseif ($promGenSem1 !== null || $promGenSem2 !== null): ?>
                            <span class="pen">Pend.</span>
                        <?php else: ?>
                            <span class="nd">—</span>
                        <?php endif; ?>
                    </td>
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