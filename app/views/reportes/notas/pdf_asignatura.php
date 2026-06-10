<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 8px;
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
    font-size: 9px;
    text-transform: uppercase;
    padding: 5px 18px;
    border-radius: 4px;
    letter-spacing: 0.5px;
}
.doc-subtitulo { font-size: 7.5px; color: #475569; margin-top: 4px; }

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
.ib-titulo  { font-size: 10px; font-weight: bold; color: #004b8d; }
.ib-sub     { font-size: 7px; color: #64748b; margin-top: 2px; }
.ib-prom    { font-size: 16px; font-weight: bold; }
.ib-prom-lb { font-size: 6.5px; color: #64748b; }

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
.sv { font-size: 16px; font-weight: bold; }
.sl { font-size: 6.5px; color: #64748b; margin-top: 2px; }

/* ── TABLA NOTAS ── */
table.nt {
    width: 100%;
    border-collapse: collapse;
    font-size: 7.5px;
}
table.nt thead tr { background: #004b8d; color: #fff; }
table.nt thead th {
    padding: 3px 2px;
    text-align: center;
    border: 1px solid #1e40af;
    font-size: 7px;
}
table.nt thead th.th-al {
    text-align: left;
    padding-left: 5px;
    min-width: 130px;
}
table.nt thead th.th-n { min-width: 18px; }
table.nt tbody tr:nth-child(even) td { background: #f8fafc; }
table.nt tbody td {
    border: 1px solid #e2e8f0;
    padding: 2.5px 2px;
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
    font-size: 6.5px;
}
table.nt tfoot td {
    border: 1px solid #cbd5e1;
    padding: 3px 2px;
    text-align: center;
    background: #dbeafe;
    font-weight: bold;
    font-size: 8px;
    color: #1e3a5f;
}
table.nt tfoot td.td-al { text-align: left; padding-left: 5px; }

.ok  { color: #16a34a; font-weight: bold; }
.mal { color: #dc2626; font-weight: bold; }
.nd  { color: #94a3b8; }
.sep { border-left: 2px solid #93c5fd !important; }

/* ── LEYENDA ── */
.leyenda {
    margin-top: 7px;
    font-size: 7px;
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
    font-size: 6.5px;
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

// Stats
$totalActivos  = 0;
$aprobados     = 0;
$sumaProms     = 0;
$cntProms      = 0;
$sumaColsTotal = array_fill(0, $maxCols, 0);
$cntColsTotal  = array_fill(0, $maxCols, 0);

foreach ($alumnos as $a) {
    if (!empty($a['fecha_retiro'])) continue;
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
        if ($prom >= 4.0) $aprobados++;
    }
}

$promCurso    = $cntProms > 0 ? round($sumaProms / $cntProms, 1) : null;
$pctAprobados = $totalActivos > 0 ? round($aprobados / $totalActivos * 100) : 0;
$reprobados   = $totalActivos - $aprobados;

function colorV($v) {
    if ($v === null) return '#94a3b8';
    return $v >= 4.0 ? '#16a34a' : '#dc2626';
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
            Notas · <?= htmlspecialchars($asignatura['nombre']) ?> · <?= $semestre ?>° Semestre
        </div>
        <div class="doc-subtitulo">
            <?= htmlspecialchars($curso['nombre']) ?> &nbsp;·&nbsp; Año <?= htmlspecialchars($anio['anio']) ?>
        </div>
    </div>

    <!-- INFO BAR -->
    <div class="info-bar">
        <div class="ib-left">
            <div class="ib-titulo"><?= htmlspecialchars($asignatura['nombre']) ?></div>
            <div class="ib-sub">
                Curso: <?= htmlspecialchars($curso['nombre']) ?> &nbsp;·&nbsp;
                <?= $semestre ?>° Semestre &nbsp;·&nbsp;
                Año <?= htmlspecialchars($anio['anio']) ?> &nbsp;·&nbsp;
                <?= count($alumnos) ?> alumnos &nbsp;·&nbsp;
                Máx. <?= $maxCols ?> notas por alumno
            </div>
        </div>
        <div class="ib-right">
            <div class="ib-prom" style="color:<?= colorV($promCurso) ?>">
                <?= $promCurso !== null ? $promCurso : '—' ?>
            </div>
            <div class="ib-prom-lb">Promedio del curso</div>
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

    <!-- TABLA -->
    <table class="nt">
        <thead>
            <tr>
                <th class="th-n">#</th>
                <th class="th-al">Alumno</th>
                <?php for ($i = 1; $i <= $maxCols; $i++): ?>
                    <th>Nota <?= $i ?></th>
                <?php endfor; ?>
                <th class="sep">Promedio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $alumno):
                $mid     = $alumno['matricula_id'];
                $ns      = array_values($notas[$mid] ?? []);
                $estaRet = !empty($alumno['fecha_retiro']);
                $suma    = 0; $cnt = 0;
                foreach ($ns as $i => $n) {
                    $v = floatval($n['nota']);
                    $suma += $v; $cnt++;
                }
                $promFila = $cnt > 0 ? $suma / $cnt : null;
            ?>
            <tr <?= $estaRet ? 'style="opacity:0.5;"' : '' ?>>
                <td style="font-weight:bold;color:#6366f1;font-size:8px;">
                    <?= $alumno['numero_lista'] ?? '—' ?>
                </td>
                <td class="td-al">
                    <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat']) ?>
                    <br><span><?= htmlspecialchars($alumno['nombre']) ?></span>
                    <?php if ($estaRet): ?>
                        <br><span style="color:#ef4444;font-size:6px;">Retirado</span>
                    <?php endif; ?>
                </td>
                <?php for ($i = 0; $i < $maxCols; $i++):
                    $n = $ns[$i] ?? null;
                    $v = $n ? floatval($n['nota']) : null;
                ?>
                    <td><?php if ($v !== null): ?>
                        <span class="<?= $v >= 4.0 ? 'ok' : 'mal' ?>"><?= number_format($v, 1) ?></span>
                    <?php else: ?><span class="nd">—</span><?php endif; ?></td>
                <?php endfor; ?>
                <td class="sep" style="color:<?= colorV($promFila) ?>;font-weight:bold;font-size:8.5px;">
                    <?= $promFila !== null ? number_format($promFila, 1) : '—' ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td class="td-al">Promedio por nota</td>
                <?php for ($i = 0; $i < $maxCols; $i++):
                    $pCol = $cntColsTotal[$i] > 0 ? $sumaColsTotal[$i] / $cntColsTotal[$i] : null;
                ?>
                    <td style="color:<?= colorV($pCol) ?>">
                        <?= $pCol !== null ? number_format($pCol, 1) : '—' ?>
                    </td>
                <?php endfor; ?>
                <td class="sep" style="color:<?= colorV($promCurso) ?>;font-size:9px;">
                    <?= $promCurso !== null ? $promCurso : '—' ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- LEYENDA -->
    <div class="leyenda">
        <strong>Leyenda:</strong> &nbsp;
        <span class="ok">■</span> Nota ≥ 4.0 Aprobado &nbsp;
        <span class="mal">■</span> Nota &lt; 4.0 Reprobado &nbsp;
        <span class="nd">—</span> Sin nota registrada
    </div>

    <div class="footer">
        Documento generado el <?= date('d/m/Y H:i') ?> — Sistema SAAT · C.E.I.A. Parral
    </div>

</div>
</body>
</html>
