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
        }

        .page {
            margin: 12px 18px 70px 18px;
        }

        /* ── ENCABEZADO 3 LOGOS ── */
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #004b8d;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .h-left { display: table-cell; width: 28%; vertical-align: middle; }
        .h-center { display: table-cell; width: 44%; vertical-align: middle; text-align: center; }
        .h-right { display: table-cell; width: 28%; vertical-align: middle; text-align: right; }
        .header img { height: 50px; width: auto; }

        /* ── TÍTULO ── */
        .doc-header { text-align: center; margin-bottom: 8px; }
        .badge-titulo {
            display: inline-block; background: #004b8d; color: #fff; font-weight: bold;
            font-size: 11px; text-transform: uppercase; padding: 5px 18px;
            border-radius: 4px; letter-spacing: 0.5px;
        }
        .doc-subtitulo { font-size: 10px; color: #475569; margin-top: 4px; }

        /* ── INFO BAR ── */
        .info-bar {
            display: table; width: 100%; background: #f1f5f9; border: 1px solid #cbd5e1;
            border-radius: 4px; padding: 5px 10px; margin-bottom: 7px;
        }
        .ib-left { display: table-cell; vertical-align: middle; }
        .ib-right { display: table-cell; vertical-align: middle; text-align: right; }
        .ib-titulo { font-size: 12px; font-weight: bold; color: #004b8d; }
        .ib-sub { font-size: 9px; color: #64748b; margin-top: 2px; }
        .ib-prom { font-size: 18px; font-weight: bold; }
        .ib-prom-lb { font-size: 8px; color: #64748b; }

        /* ── STATS ── */
        .stats {
            display: table; width: 100%; margin-bottom: 8px;
            border-collapse: separate; border-spacing: 5px 0;
        }
        .stat {
            display: table-cell; text-align: center; background: #f8fafc;
            border: 1px solid #e2e8f0; border-radius: 4px; padding: 4px 3px; width: 25%;
        }
        .sv { font-size: 17px; font-weight: bold; }
        .sl { font-size: 8px; color: #64748b; margin-top: 2px; }

        /* ── TABLA PRINCIPAL ── */
        table.nt {
            width: 100%; 
            border-collapse: separate;
            border-spacing: 3px 0;
            table-layout: fixed;
            font-size: 7px;
        }

        table.nt thead th,
        table.nt tbody td,
        table.nt tfoot td {
            border: 1px solid #cbd5e1;
            border-radius: 3px;
            padding: 3px 2px;
            text-align: center;
            vertical-align: middle;
        }

        table.nt thead th {
            background: #1e3a5f;
            color: #fff;
            border-color: #1e40af;
            font-size: 7px;
            word-wrap: break-word;
        }

        table.nt thead tr.sub-row th {
            background: #2c5282;
            border-color: #1e40af;
            font-size: 7px;
            padding: 2px 1px;
        }

        table.nt thead th.th-final { 
            background: #0f766e; 
            border-color: #0d9488; 
        }

        table.nt thead th.th-gen { 
            background: #004b8d; 
            border-color: #004b8d; 
        }

        table.nt thead th.th-al { 
            text-align: left; 
            padding-left: 5px; 
        }

        table.nt tbody tr:nth-child(even) td { 
            background: #f8fafc; 
        }

        table.nt tbody td.td-al { 
            text-align: left; 
            padding-left: 5px; 
            font-weight: bold; 
        }

        table.nt tbody td.td-al span { 
            font-weight: normal; 
            color: #64748b; 
            font-size: 7px; 
        }

        table.nt tbody td.td-final {
            background: #f0fdfa !important;
            font-weight: bold;
            border-color: #99f6e4;
            font-size: 8px;
        }

        table.nt tfoot td {
            background: #dbeafe;
            font-weight: bold;
            font-size: 8px;
            color: #1e3a5f;
            border-color: #93c5fd;
        }

        table.nt tfoot td.td-al { 
            text-align: left; 
            padding-left: 5px; 
        }

        .ok { color: #16a34a; font-weight: bold; }
        .mal { color: #dc2626; font-weight: bold; }
        .nd { color: #94a3b8; font-style: italic; font-size: 7px; }

        /* ── LEYENDA / PIE ── */
        .leyenda {
            margin-top: 6px; font-size: 8px; color: #64748b;
            border-top: 1px dashed #e2e8f0; padding-top: 4px;
        }
        .pie-pagina { position: fixed; bottom: 0; left: 0; width: 100%; }
        .pie-pagina img { width: 100%; display: block; }
        .footer {
            margin-top: 6px; border-top: 1px solid #e2e8f0; padding-top: 4px;
            font-size: 8px; color: #94a3b8; text-align: right;
        }
    </style>
</head>

<body>

    <?php
    require_once __DIR__ . '/helperPDF.php';

    $semestres = [1, 2];

    $promMatrix = [1 => [], 2 => []];
    $promAsig = [1 => [], 2 => []];

    foreach ($semestres as $sem) {
        foreach ($asignaturas as $asig) {
            $asigId = $asig['id'];
            $promsCol = [];

            foreach ($alumnos as $alumno) {
                $mid = $alumno['matricula_id'];
                $ns = $notas[$sem][$asigId][$mid] ?? [];
                $p = Nota::promedio($ns);
                $promMatrix[$sem][$mid][$asigId] = $p;
                if ($p !== null) $promsCol[] = $p;
            }
            $promAsig[$sem][$asigId] = Nota::promedio($promsCol);
        }
    }

    $finalMatrix = [];
    $finalAsig = [];

    foreach ($asignaturas as $asig) {
        $asigId = $asig['id'];
        $finalesCol = [];

        foreach ($alumnos as $alumno) {
            $mid = $alumno['matricula_id'];
            $p1 = $promMatrix[1][$mid][$asigId] ?? null;
            $p2 = $promMatrix[2][$mid][$asigId] ?? null;
            
            $f = ($p1 !== null && $p2 !== null) ? Nota::promedio([$p1, $p2]) : null;
            
            $finalMatrix[$mid][$asigId] = $f;
            if ($f !== null) $finalesCol[] = $f;
        }
        $finalAsig[$asigId] = Nota::promedio($finalesCol);
    }

    $promAlumnoSem = [1 => [], 2 => []];
    $promAlumnoFinal = [];

    foreach ($alumnos as $alumno) {
        $mid = $alumno['matricula_id'];
        foreach ($semestres as $sem) {
            $vals = array_filter($promMatrix[$sem][$mid] ?? [], fn($v) => $v !== null);
            $promAlumnoSem[$sem][$mid] = Nota::promedio($vals);
        }
        $valsFinal = array_filter($finalMatrix[$mid] ?? [], fn($v) => $v !== null);
        $promAlumnoFinal[$mid] = Nota::promedio($valsFinal);
    }

    $promCursoSem = [];
    foreach ($semestres as $sem) {
        $promCursoSem[$sem] = Nota::promedio(array_filter($promAlumnoSem[$sem], fn($v) => $v !== null));
    }
    $promCursoFinal = Nota::promedio(array_filter($promAlumnoFinal, fn($v) => $v !== null));

    $totalActivos = 0;
    $aprobados = 0;
    foreach ($alumnos as $alumno) {
        if (!empty($alumno['fecha_retiro'])) continue;
        $totalActivos++;
        if (Nota::aprobada($promAlumnoFinal[$alumno['matricula_id']] ?? null)) $aprobados++;
    }
    $reprobados = $totalActivos - $aprobados;
    $pctAprobados = $totalActivos > 0 ? round($aprobados / $totalActivos * 100) : 0;

    // ── ANCHOS DE COLUMNA (Columna # reducida a 10px) ──
    $numAsig = count($asignaturas);
    $anchoNum = 10;          // REDUCIDO al mínimo
    $anchoAlumno = 85;       
    $anchoAsigFinal = 32;    
    $anchoGen = 28;          
    ?>

    <?php pdfNotasPiePagina(); ?>

    <div class="page">
        <?php pdfNotasHeaderTresLogos(); ?>

        <div class="doc-header">
            <div class="badge-titulo">Consolidado Anual de Notas</div>
            <div class="doc-subtitulo">
                <?= htmlspecialchars($curso['nombre']) ?> &nbsp;·&nbsp; Año <?= htmlspecialchars($anio['anio']) ?>
            </div>
        </div>

        <div class="info-bar">
            <div class="ib-left">
                <div class="ib-titulo">Consolidado Anual · <?= htmlspecialchars($curso['nombre']) ?></div>
                <div class="ib-sub">
                    Año <?= htmlspecialchars($anio['anio']) ?> &nbsp;·&nbsp;
                    <?= count($alumnos) ?> alumnos &nbsp;·&nbsp;
                    <?= $numAsig ?> asignaturas
                </div>
            </div>
            <div class="ib-right">
                <div class="ib-prom" style="color:<?= Nota::color($promCursoFinal) ?>">
                    <?= Nota::fmt($promCursoFinal) ?>
                </div>
                <div class="ib-prom-lb">Promedio final del curso</div>
            </div>
        </div>

        <div class="stats">
            <div class="stat">
                <div class="sv" style="color:#1d4ed8;"><?= count($alumnos) ?></div>
                <div class="sl">Total alumnos</div>
            </div>
            <div class="stat">
                <div class="sv" style="color:#16a34a;"><?= $aprobados ?></div>
                <div class="sl">Aprobados</div>
            </div>
            <div class="stat">
                <div class="sv" style="color:#dc2626;"><?= $reprobados ?></div>
                <div class="sl">Reprobados / Pendientes</div>
            </div>
            <div class="stat">
                <div class="sv" style="color:<?= $pctAprobados >= 70 ? '#16a34a' : '#dc2626' ?>">
                    <?= $pctAprobados ?>%
                </div>
                <div class="sl">% Aprobación</div>
            </div>
        </div>

        <table class="nt">
            <thead>
                <tr>
                    <th rowspan="2" style="width:<?= $anchoNum ?>px;">#</th>
                    <th rowspan="2" class="th-al" style="width:<?= $anchoAlumno ?>px;">Alumno</th>
                    
                    <?php foreach ($asignaturas as $asig): ?>
                        <th style="width:<?= $anchoAsigFinal ?>px;">
                            <?= htmlspecialchars($asig['nombre']) ?>
                        </th>
                    <?php endforeach; ?>
                    
                    <th colspan="3" class="th-gen">Promedio General</th>
                </tr>
                <tr class="sub-row">
                    <?php foreach ($asignaturas as $asig): ?>
                        <th class="th-final">Final</th>
                    <?php endforeach; ?>
                    
                    <th class="th-gen" style="width:<?= $anchoGen ?>px;">1°S</th>
                    <th class="th-gen" style="width:<?= $anchoGen ?>px;">2°S</th>
                    <th class="th-final" style="width:<?= $anchoGen ?>px;">Final</th>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach ($alumnos as $alumno):
                    $mid = $alumno['matricula_id'];
                    $estaRet = !empty($alumno['fecha_retiro']);
                ?>
                    <tr <?= $estaRet ? 'style="opacity:0.5;"' : '' ?>>
                        <td style="font-weight:bold; color:#6366f1; padding: 2px 0;">
                            <?= $alumno['numero_lista'] ?? '—' ?>
                        </td>
                        <td class="td-al">
                            <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat']) ?>
                            <br><span><?= htmlspecialchars($alumno['nombre']) ?></span>
                            <?php if ($estaRet): ?>
                                <br><span style="color:#ef4444;">Retirado</span>
                            <?php endif; ?>
                        </td>

                        <?php foreach ($asignaturas as $asig):
                            $asigId = $asig['id'];
                            $pf = $finalMatrix[$mid][$asigId] ?? null;
                        ?>
                            <td class="td-final">
                                <?php if ($pf !== null): ?>
                                    <span class="<?= Nota::aprobada($pf) ? 'ok' : 'mal' ?>">
                                        <?= number_format($pf, 1) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="nd">—</span>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>

                        <?php
                        $g1 = $promAlumnoSem[1][$mid] ?? null;
                        $g2 = $promAlumnoSem[2][$mid] ?? null;
                        $gf = $promAlumnoFinal[$mid] ?? null;
                        ?>
                        <td style="color:<?= Nota::color($g1) ?>; font-weight:bold;">
                            <?= $g1 !== null ? number_format($g1, 1) : '<span class="nd">S/N</span>' ?>
                        </td>
                        <td style="color:<?= Nota::color($g2) ?>; font-weight:bold;">
                            <?= $g2 !== null ? number_format($g2, 1) : '<span class="nd">S/N</span>' ?>
                        </td>
                        <td class="td-final" style="color:<?= Nota::color($gf) ?>; font-size:9px;">
                            <?= $gf !== null ? number_format($gf, 1) : '<span class="nd">—</span>' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            
            <tfoot>
                <tr>
                    <td></td>
                    <td class="td-al">Promedio por asignatura</td>
                    
                    <?php foreach ($asignaturas as $asig):
                        $asigId = $asig['id'];
                        $af = $finalAsig[$asigId] ?? null;
                    ?>
                        <td style="color:<?= Nota::color($af) ?>; font-weight:bold;">
                            <?= Nota::fmt($af) ?>
                        </td>
                    <?php endforeach; ?>
                    
                    <td style="color:<?= Nota::color($promCursoSem[1]) ?>;">
                        <?= Nota::fmt($promCursoSem[1]) ?>
                    </td>
                    <td style="color:<?= Nota::color($promCursoSem[2]) ?>;">
                        <?= Nota::fmt($promCursoSem[2]) ?>
                    </td>
                    <td style="color:<?= Nota::color($promCursoFinal) ?>;">
                        <?= Nota::fmt($promCursoFinal) ?>
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="leyenda">
            <strong>Leyenda:</strong> &nbsp;
            <span class="ok">■</span> Promedio ≥ 4.0 Aprobado &nbsp;
            <span class="mal">■</span> Promedio &lt; 4.0 Reprobado &nbsp;
            <span class="nd">—</span> Final no calculable (falta un semestre) &nbsp;·&nbsp;
            <em>La nota "Final" de cada asignatura es el promedio entre el 1° y 2° Semestre.</em>
        </div>

        <div class="footer">
            Documento generado el <?= date('d/m/Y H:i') ?> — Desarrollado por Daniel Scarlazzetta — Sistema SAAT · C.E.I.A. Parral
        </div>

    </div>
</body>
</html>