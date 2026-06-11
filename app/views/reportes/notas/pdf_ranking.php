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
            font-size: 8px;
            color: #1e293b;
        }

        .page {
            margin: 15px 20px 75px 20px;
        }

        /* ── ENCABEZADO ── */
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
            height: 55px;
            width: auto;
        }

        /* ── BADGE TÍTULO ── */
        .doc-header {
            text-align: center;
            margin-bottom: 8px;
        }

        .badge-titulo {
            display: inline-block;
            background: #92400e;
            color: #fff;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            padding: 4px 16px;
            border-radius: 4px;
            letter-spacing: 0.5px;
        }

        .doc-subtitulo {
            font-size: 7.5px;
            color: #475569;
            margin-top: 3px;
        }

        /* ── INFO BAR ── */
        .info-bar {
            display: table;
            width: 100%;
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 4px;
            padding: 5px 10px;
            margin-bottom: 8px;
        }

        .ib-left {
            display: table-cell;
            vertical-align: middle;
        }

        .ib-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }

        .ib-titulo {
            font-size: 9px;
            font-weight: bold;
            color: #92400e;
        }

        .ib-sub {
            font-size: 7px;
            color: #78716c;
            margin-top: 1px;
        }

        .ib-badge {
            font-size: 7px;
            background: #92400e;
            color: #fff;
            padding: 2px 8px;
            border-radius: 3px;
        }

        /* ── BLOQUE CURSO (ranking alumnos) ── */
        .curso-bloque {
            margin-bottom: 10px;
        }

        .curso-label {
            background: #1e3a5f;
            color: #fff;
            font-weight: bold;
            font-size: 7.5px;
            padding: 3px 8px;
            border-radius: 3px 3px 0 0;
            display: inline-block;
            margin-bottom: 0;
        }

        /* ── TABLA RANKING ── */
        table.rk {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5px;
        }

        table.rk thead tr {
            background: #004b8d;
            color: #fff;
        }

        table.rk thead th {
            padding: 3px 4px;
            text-align: center;
            border: 1px solid #1e40af;
            font-size: 7px;
        }

        table.rk thead th.th-al {
            text-align: left;
            padding-left: 6px;
        }

        table.rk tbody tr:nth-child(even) td {
            background: #f8fafc;
        }

        table.rk tbody td {
            border: 1px solid #e2e8f0;
            padding: 2.5px 4px;
            vertical-align: middle;
            text-align: center;
        }

        table.rk tbody td.td-al {
            text-align: left;
        }

        table.rk tbody td.td-al strong {
            font-size: 7.5px;
        }

        table.rk tbody td.td-al span {
            color: #64748b;
            font-size: 7px;
        }

        table.rk tfoot td {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            font-weight: bold;
            font-size: 8px;
            padding: 3px 4px;
            text-align: center;
            color: #92400e;
        }

        table.rk tfoot td.td-al {
            text-align: left;
            padding-left: 6px;
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
            font-style: italic;
        }

        /* ── MEDALLA ── */
        .med-1 {
            color: #d97706;
            font-weight: bold;
        }

        .med-2 {
            color: #94a3b8;
            font-weight: bold;
        }

        .med-3 {
            color: #b45309;
            font-weight: bold;
        }

        /* ── BARRA VISUAL ── */
        .barra-wrap {
            display: inline-block;
            width: 50px;
            height: 5px;
            background: #e2e8f0;
            border-radius: 3px;
            vertical-align: middle;
            margin-left: 3px;
        }

        .barra-fill {
            height: 5px;
            border-radius: 3px;
            display: block;
        }

        /* ── LEYENDA ── */
        .leyenda {
            margin-top: 6px;
            font-size: 7px;
            color: #64748b;
            border-top: 1px dashed #e2e8f0;
            padding-top: 4px;
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

        .footer {
            margin-top: 8px;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
            font-size: 6.5px;
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

    function colorR($v)
    {
        if ($v === null)
            return '#94a3b8';
        return $v >= 4.0 ? '#16a34a' : '#dc2626';
    }
    function claseR($v)
    {
        if ($v === null)
            return 'nd';
        return $v >= 4.0 ? 'ok' : 'mal';
    }
    // Barra proporcional: max referencia = 7.0
    function barraHtml($v)
    {
        if ($v === null)
            return '';
        $pct = min(100, round(($v / 7.0) * 100));
        $color = $v >= 4.0 ? '#16a34a' : '#dc2626';
        return '<span class="barra-wrap"><span class="barra-fill" style="width:' . $pct . '%;background:' . $color . ';"></span></span>';
    }
    function medallaClase($i)
    {
        if ($i === 0)
            return 'med-1';
        if ($i === 1)
            return 'med-2';
        if ($i === 2)
            return 'med-3';
        return '';
    }
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
            <div class="badge-titulo">
                <?= $tipo === 'alumnos' ? '🏆 Ranking de Alumnos por Promedio' : '📊 Ranking de Asignaturas por Promedio' ?>
                · <?= $semestre ?>° Semestre
            </div>
            <div class="doc-subtitulo">
                Año <?= htmlspecialchars($anio['anio']) ?> &nbsp;·&nbsp; Todos los cursos &nbsp;·&nbsp;
                Ordenado de menor a mayor promedio
            </div>
        </div>

        <!-- INFO BAR -->
        <div class="info-bar">
            <div class="ib-left">
                <div class="ib-titulo">
                    <?= $tipo === 'alumnos' ? 'Ranking General de Alumnos' : 'Ranking General de Asignaturas' ?>
                </div>
                <div class="ib-sub">
                    Año <?= htmlspecialchars($anio['anio']) ?> &nbsp;·&nbsp;
                    <?= $semestre ?>° Semestre &nbsp;·&nbsp;
                    <?= count($cursos) ?> cursos incluidos &nbsp;·&nbsp;
                    Los valores más bajos aparecen primero
                </div>
            </div>
            <div class="ib-right">
                <span class="ib-badge">
                    <?= $tipo === 'alumnos' ? 'Por Alumno' : 'Por Asignatura' ?>
                </span>
            </div>
        </div>

        <?php if ($tipo === 'alumnos'): ?>
            <!-- ══ RANKING ALUMNOS (agrupado por curso) ══ -->

            <?php foreach ($rankingAlumnos as $cursoNombre => $filaAlumnos):
                if (empty($filaAlumnos))
                    continue;

                // Stats del curso
                $aprobados = count(array_filter($filaAlumnos, fn($a) => $a['promedio'] !== null && $a['promedio'] >= 4.0));
                $total = count($filaAlumnos);
                $proms = array_filter(array_column($filaAlumnos, 'promedio'), fn($v) => $v !== null);
                $promCurso = count($proms) > 0 ? round(array_sum($proms) / count($proms), 1) : null;
                ?>
                <div class="curso-bloque">
                    <span class="curso-label"><?= htmlspecialchars($cursoNombre) ?></span>
                    <table class="rk">
                        <thead>
                            <tr>
                                <th style="width:16px;">#</th>
                                <th class="th-al">Alumno</th>
                                <th style="width:55px;">Notas</th>
                                <th style="width:55px;">Promedio</th>
                                <th style="width:60px;">Indicador</th>
                                <th style="width:50px;">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filaAlumnos as $i => $a):
                                $p = $a['promedio'];
                                ?>
                                <tr>
                                    <td class="<?= medallaClase($i) ?>">
                                        <?= $i + 1 ?>
                                    </td>
                                    <td class="td-al">
                                        <strong>
                                            <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat']) ?>
                                        </strong>
                                        <span>
                                            <?= htmlspecialchars($a['nombre']) ?>
                                        </span>
                                    </td>
                                    <td style="text-align:center;">
                                        <?php if ($a['pendiente'] ?? false): ?>
                                            <span style="display:inline-block; font-size:6px; font-weight:bold;
                                 background:#fef9c3; color:#854d0e; border:1px solid #ca8a04;
                                 padding:2px 6px; border-radius:8px; white-space:nowrap;">
                                                ⚠ Faltan notas
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="color:<?= colorR($p) ?>;font-weight:bold;font-size:9px;">
                                        <?= $p !== null ? number_format($p, 1) : '<span class="nd">S/N</span>' ?>
                                    </td>
                                    <td>
                                        <?= barraHtml($p) ?>
                                    </td>
                                    <td>
                                        <?php if ($p === null): ?>
                                            <span class="nd">Sin notas</span>
                                        <?php elseif ($p >= 4.0): ?>
                                            <span class="ok">Aprobado</span>
                                        <?php else: ?>
                                            <span class="mal">Reprobado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td class="td-al">Promedio del curso</td>
                                <td></td>
                                <td style="color:<?= colorR($promCurso) ?>">
                                    <?= $promCurso !== null ? number_format($promCurso, 1) : '—' ?>
                                </td>
                                <td></td>
                                <td style="color:<?= colorR($promCurso) ?>">
                                    <?= $aprobados ?>/<?= $total ?> aprobados
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <!-- ══ RANKING ASIGNATURAS ══ -->

            <?php
            // Stats globales
            $promsAsig = array_filter(array_column($rankingAsignaturas, 'promedio'), fn($v) => $v !== null);
            $promGlobal = count($promsAsig) > 0 ? round(array_sum($promsAsig) / count($promsAsig), 1) : null;
            $bajoCuatro = count(array_filter($rankingAsignaturas, fn($a) => $a['promedio'] !== null && $a['promedio'] < 4.0));
            ?>

            <table class="rk">
                <thead>
                    <tr>
                        <th style="width:16px;">#</th>
                        <th class="th-al">Asignatura</th>
                        <th style="width:100px;">Curso</th>
                        <th style="width:55px;">Promedio</th>
                        <th style="width:60px;">Indicador</th>
                        <th style="width:50px;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rankingAsignaturas as $i => $a):
                        $p = $a['promedio'];
                        ?>
                        <tr>
                            <td class="<?= medallaClase($i) ?>"><?= $i + 1 ?></td>
                            <td class="td-al">
                                <strong><?= htmlspecialchars($a['asignatura']) ?></strong>
                                <?php
                                $esPendienteAsig = array_filter(
                                    $pendientesAsignaturas ?? [],
                                    fn($p) => $p['asignatura'] === $a['asignatura'] && $p['curso'] === $a['curso']
                                );
                                if (!empty($esPendienteAsig)):
                                    ?>
                                    <span style="display:inline-block; margin-left:4px; font-size:6px; font-weight:normal;
                     background:#422006; color:#fbbf24; border:1px solid #92400e;
                     padding:1px 5px; border-radius:8px;">
                                        ⚠ Faltan alumnos con nota
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="color:#64748b;"><?= htmlspecialchars($a['curso']) ?></td>
                            <td style="color:<?= colorR($p) ?>;font-weight:bold;font-size:9px;">
                                <?= $p !== null ? number_format($p, 1) : '<span class="nd">S/N</span>' ?>
                            </td>
                            <td><?= barraHtml($p) ?></td>
                            <td>
                                <?php if ($p === null): ?>
                                    <span class="nd">Sin notas</span>
                                <?php elseif ($p >= 4.0): ?>
                                    <span class="ok">Sobre 4.0</span>
                                <?php else: ?>
                                    <span class="mal">Bajo 4.0</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class="td-al">Promedio general (todas las asignaturas)</td>
                        <td></td>
                        <td style="color:<?= colorR($promGlobal) ?>">
                            <?= $promGlobal !== null ? number_format($promGlobal, 1) : '—' ?>
                        </td>
                        <td></td>
                        <td style="color:#dc2626;"><?= $bajoCuatro ?> bajo 4.0</td>
                    </tr>
                </tfoot>
            </table>

        <?php endif; ?>

        <!-- LEYENDA -->
        <div class="leyenda">
            <strong>Leyenda:</strong> &nbsp;
            <span class="ok">■</span> Promedio ≥ 4.0 &nbsp;
            <span class="mal">■</span> Promedio &lt; 4.0 &nbsp;
            <span class="nd">S/N</span> Sin notas registradas &nbsp;·&nbsp;
            Ordenado de menor a mayor · Semestre <?= $semestre ?>° · Año <?= htmlspecialchars($anio['anio']) ?>
        </div>

        <div class="footer">
            Documento generado el <?= date('d/m/Y H:i') ?> — Sistema SAAT · C.E.I.A. Parral
        </div>

    </div>
</body>

</html>