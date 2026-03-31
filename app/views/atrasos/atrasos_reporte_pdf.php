<?php
// Variables esperadas desde el controlador:
// $atrasos, $resumen, $filtrosTexto, $anioActual
$t = $resumen['totales'];
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
            margin-bottom: 10px;
            border-bottom: 3px solid #b45309;
            padding-bottom: 8px;
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
            font-size: 10px;
            color: #475569;
        }

        .ficha-titulo {
            display: inline-block;
            margin-top: 6px;
            padding: 3px 10px;
            background: #ffd500;
            color: #004b8d;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            border-radius: 3px;
        }

        /* SUBTÍTULO */
        .sub-header {
            margin: 10px 0 14px;
        }

        .titulo {
            font-size: 15px;
            font-weight: bold;
            color: #b45309;
        }

        .subtitulo {
            font-size: 10px;
            color: #475569;
            margin-top: 2px;
        }

        /* FILTROS ACTIVOS */
        .filtros {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 4px;
            padding: 5px 8px;
            font-size: 8.5px;
            color: #92400e;
            margin-bottom: 12px;
        }

        /* MÉTRICAS */
        .metricas {
            display: table;
            width: 100%;
            margin-bottom: 14px;
            border-collapse: separate;
            border-spacing: 4px;
        }

        .metrica-cell {
            display: table-cell;
            width: 16.6%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 6px 4px;
            text-align: center;
        }

        .metrica-valor {
            font-size: 18px;
            font-weight: bold;
        }

        .metrica-label {
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

        .c-blue {
            color: #2563eb;
        }

        .c-purple {
            color: #7c3aed;
        }

        /* SECCIÓN TÍTULO */
        .seccion {
            font-size: 10px;
            font-weight: bold;
            color: #004b8d;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
            margin: 12px 0 6px;
        }

        /* TABLA DETALLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        thead tr {
            background: #b45309;
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

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .tc {
            text-align: center;
        }

        .badge-ij {
            color: #dc2626;
            font-weight: bold;
        }

        .badge-j {
            color: #16a34a;
            font-weight: bold;
        }

        /* RANKING + POR CURSO: dos columnas */
        .dos-col {
            display: table;
            width: 100%;
            border-spacing: 6px;
        }

        .col-izq {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }

        .col-der {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }

        .ranking-fila {
            display: table;
            width: 100%;
            border-bottom: 1px solid #f1f5f9;
            padding: 4px 0;
        }

        .ranking-pos {
            display: table-cell;
            width: 20px;
            font-weight: bold;
            font-size: 10px;
            color: #d97706;
            vertical-align: middle;
        }

        .ranking-nom {
            display: table-cell;
            vertical-align: middle;
            font-size: 8.5px;
        }

        .ranking-nom .curso-txt {
            font-size: 7.5px;
            color: #94a3b8;
        }

        .ranking-total {
            display: table-cell;
            width: 30px;
            text-align: right;
            font-size: 13px;
            font-weight: bold;
            vertical-align: middle;
        }

        .barra-wrap {
            margin-bottom: 7px;
        }

        .barra-label {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }

        .barra-nombre {
            display: table-cell;
            font-size: 8px;
            color: #334155;
        }

        .barra-valor {
            display: table-cell;
            text-align: right;
            font-size: 8px;
            font-weight: bold;
            color: #d97706;
            width: 30px;
        }

        .barra-bg {
            background: #e2e8f0;
            border-radius: 3px;
            height: 7px;
        }

        .barra-fill {
            background: #d97706;
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
            <?php
            $logoPath = __DIR__ . '/../../public/img/LOGO CEIA.jpg';
            if (file_exists($logoPath)): ?>
                <img src="http://localhost:8080/app/public/img/LOGO%20CEIA.jpg" style="width:80px;">
            <?php else: ?>
                <strong style="color:#004b8d;">CEIA Parral</strong>
            <?php endif; ?>
        </div>
        <div class="header-right">
            <div class="inst-nombre">Centro de Educación Integrada de Adultos</div>
            <div class="inst-sub">"Juanita Zúñiga Fuentes" – Parral</div>
            <div class="ficha-titulo">Reporte de Atrasos
                <?= date('Y') ?> / C.E.I.A. Parral
            </div>
        </div>
    </div>

    <!-- SUBTÍTULO -->
    <div class="sub-header">
        <div class="titulo">Reporte de Atrasos por Curso</div>
        <div class="subtitulo">Generado el:
            <?= date('d/m/Y H:i') ?>
        </div>
    </div>

    <!-- FILTROS ACTIVOS -->
    <?php if (!empty($filtrosTexto)): ?>
        <div class="filtros">
            <strong>Filtros aplicados:</strong>
            <?= htmlspecialchars($filtrosTexto) ?>
        </div>
    <?php endif; ?>

    <!-- MÉTRICAS -->
    <div class="seccion">Resumen General</div>
    <div class="metricas">
        <div class="metrica-cell">
            <div class="metrica-valor c-white">
                <?= $t['total'] ?? 0 ?>
            </div>
            <div class="metrica-label">Total atrasos</div>
        </div>
        <div class="metrica-cell">
            <div class="metrica-valor c-red">
                <?= $t['injustificados'] ?? 0 ?>
            </div>
            <div class="metrica-label">Injustificados</div>
        </div>
        <div class="metrica-cell">
            <div class="metrica-valor c-green">
                <?= $t['justificados'] ?? 0 ?>
            </div>
            <div class="metrica-label">Justificados</div>
        </div>
        <div class="metrica-cell">
            <div class="metrica-valor c-amber">
                <?= $t['alumnos_afectados'] ?? 0 ?>
            </div>
            <div class="metrica-label">Alumnos afectados</div>
        </div>
        <div class="metrica-cell">
            <div class="metrica-valor c-blue">
                <?= $t['cursos_afectados'] ?? 0 ?>
            </div>
            <div class="metrica-label">Cursos afectados</div>
        </div>
        <div class="metrica-cell">
            <div class="metrica-valor c-purple">
                <?= $t['dias_con_atrasos'] ?? 0 ?>
            </div>
            <div class="metrica-label">Días con atrasos</div>
        </div>
    </div>

    <!-- RANKING + POR CURSO -->
    <div class="dos-col">
        <!-- Ranking top alumnos -->
        <div class="col-izq">
            <div class="seccion">Top Alumnos con más Atrasos</div>
            <?php foreach (array_slice($resumen['topAlumnos'], 0, 10) as $i => $al):
                $colPos = $i === 0 ? '#d97706' : ($i === 1 ? '#64748b' : ($i === 2 ? '#92400e' : '#94a3b8'));
                ?>
                <div class="ranking-fila">
                    <div class="ranking-pos" style="color: <?= $colPos ?>">
                        <?= $i + 1 ?>
                    </div>
                    <div class="ranking-nom">
                        <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                        <div class="curso-txt">
                            <?= htmlspecialchars($al['curso']) ?>
                        </div>
                    </div>
                    <div class="ranking-total"
                        style="color: <?= $al['total'] >= 5 ? '#dc2626' : ($al['total'] >= 3 ? '#d97706' : '#334155') ?>">
                        <?= $al['total'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Atrasos por curso -->
        <div class="col-der">
            <div class="seccion">Atrasos por Curso</div>
            <?php
            $maxCurso = !empty($resumen['porCurso']) ? $resumen['porCurso'][0]['total'] : 1;
            foreach ($resumen['porCurso'] as $pc):
                $pct = round($pc['total'] / $maxCurso * 100);
                ?>
                <div class="barra-wrap">
                    <div class="barra-label">
                        <div class="barra-nombre">
                            <?= htmlspecialchars($pc['curso']) ?> <span style="color:#dc2626">(
                                <?= $pc['injustificados'] ?> IJ)
                            </span>
                        </div>
                        <div class="barra-valor">
                            <?= $pc['total'] ?>
                        </div>
                    </div>
                    <div class="barra-bg">
                        <div class="barra-fill" style="width: <?= $pct ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- TABLA DETALLE -->
    <div class="seccion">Detalle de Atrasos (
        <?= count($atrasos) ?> registros)
    </div>
    <table>
        <thead>
            <tr>
                <th>Alumno</th>
                <th>Curso</th>
                <th class="tc">Fecha</th>
                <th class="tc">Hora</th>
                <th class="tc">Sem.</th>
                <th class="tc">Estado</th>
                <th>Observación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($atrasos as $a): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat'] . ', ' . $a['nombre']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($a['curso'] ?? '—') ?>
                    </td>
                    <td class="tc">
                        <?= date('d/m/Y', strtotime($a['fecha'])) ?>
                    </td>
                    <td class="tc" style="color:#d97706; font-weight:bold">
                        <?= substr($a['hora_llegada'], 0, 5) ?>
                    </td>
                    <td class="tc">
                        <?= $a['semestre'] ?>°
                    </td>
                    <td class="tc">
                        <?php if ($a['justificado']): ?>
                            <span class="badge-j">J</span>
                        <?php else: ?>
                            <span class="badge-ij">IJ</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $a['observacion'] ? htmlspecialchars($a['observacion']) : '—' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- PIE DE PÁGINA -->
    <div class="footer">
        Reporte generado automáticamente por el sistema — C.E.I.A. Parral
        <?= date('Y') ?>
        <p>Desarrollado por <span> Daniel Scarlazzetta</span></p>
    </div>

</body>

</html>