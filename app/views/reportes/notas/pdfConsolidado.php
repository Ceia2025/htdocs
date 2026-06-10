<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
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
.h-left   { display: table-cell; width: 28%; vertical-align: middle; }
.h-center { display: table-cell; width: 44%; vertical-align: middle; text-align: center; }
.h-right  { display: table-cell; width: 28%; vertical-align: middle; text-align: right; }
.header img { height: 62px; width: auto; }

/* ── BADGE TÍTULO ── */
.doc-header { text-align: center; margin-bottom: 10px; }
.badge-titulo {
    display: inline-block;
    background: #004b8d;
    color: #fff;
    font-weight: bold;
    font-size: 11px;
    text-transform: uppercase;
    padding: 5px 18px;
    border-radius: 4px;
    letter-spacing: 0.5px;
}
.doc-subtitulo { font-size: 10px; color: #475569; margin-top: 4px; }

/* ── INFO BAR ── */
.info-bar {
    display: table;
    width: 100%;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 6px 10px;
    margin-bottom: 8px;
}
.ib-left  { display: table-cell; vertical-align: middle; }
.ib-right { display: table-cell; vertical-align: middle; text-align: right; }
.ib-titulo  { font-size: 13px; font-weight: bold; color: #004b8d; }
.ib-sub     { font-size: 9px; color: #64748b; margin-top: 2px; }
.ib-prom    { font-size: 19px; font-weight: bold; }
.ib-prom-lb { font-size: 8px; color: #64748b; }

/* ── STATS ── */
.stats { display: table; width: 100%; margin-bottom: 9px; border-collapse: separate; border-spacing: 5px 0; }
.stat {
    display: table-cell;
    text-align: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: 5px 3px;
    width: 25%;
}
.sv { font-size: 19px; font-weight: bold; }
.sl { font-size: 9px; color: #64748b; margin-top: 2px; }

/* ── TABLA CONSOLIDADO ── */
table.nt {
    width: 100%;
    border-collapse: collapse;
    font-size: 9px;
    table-layout: fixed;
}

/* Fila de grupos (nombre asignatura) */
table.nt thead tr.asig-row th {
    background: #1e3a5f;
    color: #fff;
    padding: 3px 1px;
    border: 1px solid #1e40af;
    font-size: 9px;
    text-align: center;
}
table.nt thead tr.asig-row th.th-al {
    text-align: left;
    padding-left: 5px;
}

table.nt tbody tr:nth-child(even) td { background: #f8fafc; }
table.nt tbody td {
    border: 1px solid #e2e8f0;
    padding: 3px 2px;
    text-align: center;
    vertical-align: middle;
}
table.nt tbody td.td-al {
    text-align: left;
    padding-left: 5px;
    font-weight: bold;
}
table.nt tbody td.td-al span {
    font-weight: normal;
    color: #64748b;
    font-size: 9px;
}
table.nt tfoot td {
    border: 1px solid #cbd5e1;
    padding: 3px 2px;
    text-align: center;
    background: #dbeafe;
    font-weight: bold;
    font-size: 10px;
    color: #1e3a5f;
}
table.nt tfoot td.td-al { text-align: left; padding-left: 5px; }

.ok  { color: #16a34a; font-weight: bold; }
.mal { color: #dc2626; font-weight: bold; }
.nd  { color: #94a3b8; font-style: italic; font-size: 9px; }
.sep { border-left: 2px solid #93c5fd !important; }
.sep-dark { border-left: 2px solid #3b82f6 !important; }

/* ── LEYENDA ── */
.leyenda {
    margin-top: 7px;
    font-size: 12px;
    color: #64748b;
    border-top: 1px dashed #e2e8f0;
    padding-top: 5px;
}

/* ── PIE ── */
.pie-pagina { position: fixed; bottom: 0; left: 0; width: 100%; }
.pie-pagina img { width: 100%; display: block; }

/* ── FOOTER ── */
.footer {
    margin-top: 8px;
    border-top: 1px solid #e2e8f0;
    padding-top: 4px;
    font-size: 12px;
    color: #94a3b8;
    text-align: right;
}
</style>
</head>
<body>

<?php
$base   = 'http://localhost:8080/app/public/img/';
$logo1  = $base . 'certificado_1.png';
$logo2  = $base . 'certificado_3.png';
$logo3  = $base . 'certificado_2.png';
$piePag = $base . 'pie_pagina_certificado.png';

// ── Helpers ──────────────────────────────────────────────────────────────────
function colorV($v) {
    if ($v === null) return '#94a3b8';
    return $v >= 4.0 ? '#16a34a' : '#dc2626';
}

// ── Calcular promedio por semestre de cada alumno × asignatura ───────────────
// $notas[$asignaturaId][$matriculaId] = promedio float|null
$promMatrix = [];        // [matricula_id][asignatura_id] = float|null
$promAsig   = [];        // [asignatura_id] = promedio del curso en esa asig

foreach ($asignaturas as $asig) {
    $asigId = $asig['id'];
    $sumaAsig = 0; $cntAsig = 0;

    foreach ($alumnos as $alumno) {
        $mid = $alumno['matricula_id'];
        $ns  = $notas[$asigId][$mid] ?? [];
        if (count($ns) > 0) {
            $prom = array_sum(array_column($ns, 'nota')) / count($ns);
            $promMatrix[$mid][$asigId] = round($prom, 1);
            $sumaAsig += $prom;
            $cntAsig++;
        } else {
            $promMatrix[$mid][$asigId] = null;
        }
    }

    $promAsig[$asigId] = $cntAsig > 0 ? round($sumaAsig / $cntAsig, 1) : null;
}

// ── Promedio general del alumno (promedio de sus promedios por asig) ─────────
$promAlumno = [];
foreach ($alumnos as $alumno) {
    $mid = $alumno['matricula_id'];
    $vals = array_filter($promMatrix[$mid] ?? [], fn($v) => $v !== null);
    $promAlumno[$mid] = count($vals) > 0 ? round(array_sum($vals) / count($vals), 1) : null;
}

// ── Stats generales ──────────────────────────────────────────────────────────
$totalActivos = 0;
$aprobados    = 0;
foreach ($alumnos as $alumno) {
    if (!empty($alumno['fecha_retiro'])) continue;
    $totalActivos++;
    $p = $promAlumno[$alumno['matricula_id']] ?? null;
    if ($p !== null && $p >= 4.0) $aprobados++;
}
$reprobados   = $totalActivos - $aprobados;
$pctAprobados = $totalActivos > 0 ? round($aprobados / $totalActivos * 100) : 0;

// Promedio general del curso (promedio de los promedios generales de alumnos)
$vals = array_filter($promAlumno, fn($v) => $v !== null);
$promCursoGeneral = count($vals) > 0 ? round(array_sum($vals) / count($vals), 1) : null;

// Ancho de columna por asignatura (distribución equitativa)
$numAsig    = count($asignaturas);
$anchoAlumno = 130; // px fijo para columna nombre
$anchoProm   = 28;  // px fijo columna prom. alumno
// Las asignaturas reparten el resto — dompdf ignora % con table-layout:fixed,
// así que lo ponemos inline en cada <th>
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
            Consolidado de Notas · <?= $semestre ?>° Semestre
        </div>
        <div class="doc-subtitulo">
            <?= htmlspecialchars($curso['nombre']) ?> &nbsp;·&nbsp; Año <?= htmlspecialchars($anio['anio']) ?>
        </div>
    </div>

    <!-- INFO BAR -->
    <div class="info-bar">
        <div class="ib-left">
            <div class="ib-titulo">Consolidado General · <?= htmlspecialchars($curso['nombre']) ?></div>
            <div class="ib-sub">
                <?= $semestre ?>° Semestre &nbsp;·&nbsp;
                Año <?= htmlspecialchars($anio['anio']) ?> &nbsp;·&nbsp;
                <?= count($alumnos) ?> alumnos &nbsp;·&nbsp;
                <?= $numAsig ?> asignaturas
            </div>
        </div>
        <div class="ib-right">
            <div class="ib-prom" style="color:<?= colorV($promCursoGeneral) ?>">
                <?= $promCursoGeneral !== null ? $promCursoGeneral : '—' ?>
            </div>
            <div class="ib-prom-lb">Promedio general del curso</div>
        </div>
    </div>

    <!-- STATS -->
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
            <div class="sl">Reprobados</div>
        </div>
        <div class="stat">
            <div class="sv" style="color:<?= $pctAprobados >= 70 ? '#16a34a' : '#dc2626' ?>">
                <?= $pctAprobados ?>%
            </div>
            <div class="sl">% Aprobación</div>
        </div>
    </div>

    <!-- TABLA CONSOLIDADO -->
    <table class="nt">
        <thead>
            <tr class="asig-row">
                <!-- N° lista -->
                <th style="width:14px;">#</th>
                <!-- Nombre alumno -->
                <th class="th-al" style="width:<?= $anchoAlumno ?>px; text-align:left; padding-left:5px;">
                    Alumno
                </th>
                <!-- Una columna por asignatura -->
                <?php foreach ($asignaturas as $idx => $asig): ?>
                    <th class="<?= $idx === 0 ? 'sep-dark' : '' ?>"
                        style="font-size:9px; padding:2px 1px;">
                        <?= htmlspecialchars($asig['nombre']) ?>
                    </th>
                <?php endforeach; ?>
                <!-- Promedio general alumno -->
                <th class="sep" style="width:<?= $anchoProm ?>px; min-width:<?= $anchoProm ?>px;">
                    Prom.<br>General
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $alumno):
                $mid     = $alumno['matricula_id'];
                $estaRet = !empty($alumno['fecha_retiro']);
                $pGeneral = $promAlumno[$mid] ?? null;
            ?>
            <tr <?= $estaRet ? 'style="opacity:0.5;"' : '' ?>>
                <!-- N° lista -->
                <td style="font-weight:bold; color:#6366f1; font-size:10px;">
                    <?= $alumno['numero_lista'] ?? '—' ?>
                </td>
                <!-- Nombre -->
                <td class="td-al">
                    <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat']) ?>
                    <br><span><?= htmlspecialchars($alumno['nombre']) ?></span>
                    <?php if ($estaRet): ?>
                        <br><span style="color:#ef4444; font-size:9px;">Retirado</span>
                    <?php endif; ?>
                </td>
                <!-- Promedio por asignatura -->
                <?php foreach ($asignaturas as $idx => $asig):
                    $asigId = $asig['id'];
                    $prom   = $promMatrix[$mid][$asigId] ?? null;
                ?>
                    <td class="<?= $idx === 0 ? 'sep-dark' : '' ?>">
                        <?php if ($prom !== null): ?>
                            <span class="<?= $prom >= 4.0 ? 'ok' : 'mal' ?>">
                                <?= number_format($prom, 1) ?>
                            </span>
                        <?php else: ?>
                            <span class="nd">S/N</span>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
                <!-- Promedio general alumno -->
                <td class="sep" style="color:<?= colorV($pGeneral) ?>; font-weight:bold; font-size:11px;">
                    <?= $pGeneral !== null ? number_format($pGeneral, 1) : '<span class="nd">S/N</span>' ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td class="td-al">Promedio por asignatura</td>
                <?php foreach ($asignaturas as $idx => $asig):
                    $pa = $promAsig[$asig['id']] ?? null;
                ?>
                    <td class="<?= $idx === 0 ? 'sep-dark' : '' ?>"
                        style="color:<?= colorV($pa) ?>;">
                        <?= $pa !== null ? number_format($pa, 1) : '—' ?>
                    </td>
                <?php endforeach; ?>
                <td class="sep" style="color:<?= colorV($promCursoGeneral) ?>; font-size:12px;">
                    <?= $promCursoGeneral !== null ? number_format($promCursoGeneral, 1) : '—' ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- LEYENDA -->
    <div class="leyenda">
        <strong>Leyenda:</strong> &nbsp;
        <span class="ok">■</span> Promedio ≥ 4.0 Aprobado &nbsp;
        <span class="mal">■</span> Promedio &lt; 4.0 Reprobado &nbsp;
        <span class="nd">S/N</span> Sin notas registradas en el semestre
        &nbsp;·&nbsp; Valor de cada celda: promedio del semestre seleccionado
    </div>

    <div class="footer">
        Documento generado el <?= date('d/m/Y H:i') ?> — Desarrollado por Daniel Scarlazzetta — Sistema SAAT · C.E.I.A. Parral
    </div>

</div>
</body>
</html>