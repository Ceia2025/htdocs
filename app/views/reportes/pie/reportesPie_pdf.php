<?php
require_once __DIR__ . '/helperPDF.php';
$tituloFiltro = $cursoId ? $cursoNombre : 'Todos los cursos';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        <?= pdfPieCssComun() ?>

        .resumen-global {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .resumen-global thead tr {
            background: #0f172a;
        }

        .resumen-global thead th {
            color: #94a3b8;
            padding: 4px 6px;
            font-size: 7.5px;
            text-transform: uppercase;
            text-align: left;
        }

        .resumen-global thead th.tc {
            text-align: center;
        }

        .resumen-global tbody td {
            border-bottom: 1px solid #e2e8f0;
            padding: 4px 6px;
            font-size: 8.5px;
        }

        .resumen-global tbody tr:nth-child(even) td {
            background: #f8fafc;
        }

        .pie {
            color: #0e7490;
            font-weight: bold;
        }

        .bar-wrap {
            background: #e2e8f0;
            border-radius: 3px;
            height: 6px;
            width: 80px;
            display: inline-block;
            vertical-align: middle;
        }

        .bar-fill {
            background: #0e7490;
            border-radius: 3px;
            height: 6px;
            display: block;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    <?php pdfPieHeader('Reporte de Alumnos PIE — ' . $anioNombre); ?>

    <div class="info-box">
        <strong>Año académico:</strong> <?= htmlspecialchars($anioNombre) ?> &nbsp;·&nbsp;
        <strong>Curso:</strong> <?= htmlspecialchars($tituloFiltro) ?> &nbsp;·&nbsp;
        <strong>Total matriculados:</strong> <?= $resumenGlobal['total_matriculados'] ?> &nbsp;·&nbsp;
        <strong>Alumnos PIE:</strong> <?= $resumenGlobal['total_pie'] ?> &nbsp;·&nbsp;
        <strong>% PIE:</strong> <?= $resumenGlobal['porcentaje'] ?>% &nbsp;·&nbsp;
        <strong>Generado:</strong> <?= date('d/m/Y H:i') ?>
    </div>

    <strong style="font-size:10px; color:#0e7490;">Resumen por categoría PIE (informativo, todos los estados)</strong>
    <table class="resumen-global" style="margin-top:4px;">
        <thead>
            <tr>
                <th>Categoría</th>
                <th class="tc">Total alumnos</th>
                <th class="tc">%</th>
                <th class="tc">Distribución</th>
            </tr>
        </thead>
        <tbody>
            <?php $totalCat = array_sum(array_column($resumenCategorias, 'total'));
            foreach ($resumenCategorias as $rc):
                $pct = $totalCat > 0 ? round($rc['total'] / $totalCat * 100, 1) : 0;
                $barPx = (int) round($pct * 0.8);
                ?>
                <tr>
                    <td><?= htmlspecialchars($rc['categoria']) ?></td>
                    <td class="tc" style="font-weight:bold; color:#0e7490;"><?= $rc['total'] ?></td>
                    <td class="tc"><?= $pct ?>%</td>
                    <td class="tc">
                        <span class="bar-wrap">
                            <span class="bar-fill" style="width:<?= $barPx ?>px;"></span>
                        </span>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <strong style="font-size:10px; color:#0e7490;">Resumen por curso</strong>
    <table class="resumen-global" style="margin-top:4px;">
        <thead>
            <tr>
                <th>Curso</th>
                <th class="tc">Matriculados</th>
                <th class="tc">PIE</th>
                <th class="tc">% PIE</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resumenCursos as $rc): ?>
                <tr>
                    <td><?= htmlspecialchars($rc['curso']) ?></td>
                    <td class="tc"><?= $rc['total_matriculados'] ?></td>
                    <td class="tc" style="font-weight:bold; color:#0e7490;"><?= $rc['total_pie'] ?></td>
                    <td class="tc"><?= $rc['porcentaje'] ?>%</td>
                </tr>
            <?php endforeach ?>
            <tr style="border-top:2px solid #cbd5e1;">
                <td style="font-weight:bold; font-size:8px; text-transform:uppercase;">Total</td>
                <td class="tc" style="font-weight:bold;"><?= $resumenGlobal['total_matriculados'] ?></td>
                <td class="tc" style="font-weight:bold; color:#0e7490;"><?= $resumenGlobal['total_pie'] ?></td>
                <td class="tc" style="font-weight:bold;"><?= $resumenGlobal['porcentaje'] ?>%</td>
            </tr>
        </tbody>
    </table>

    <?php foreach ($reporte as $cursoNombreLoop => $alumnos): ?>
        <div>
            <div class="curso-titulo">
                🎓 <?= htmlspecialchars($cursoNombreLoop) ?>
                — <?= count($alumnos) ?> alumno<?= count($alumnos) !== 1 ? 's' : '' ?> PIE
            </div>
            <table class="detalle">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>RUN</th>
                        <th class="tc">Sexo</th>
                        <th>Problema de aprendizaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alumnos as $al): ?>
                        <tr>
                            <td><?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?></td>
                            <td style="font-family:monospace; color:#64748b; font-size:7.5px">
                                <?= htmlspecialchars($al['run']) ?></td>
                            <td class="tc" style="color:#475569">
                                <?= $al['sexo'] === 'F' ? 'F' : ($al['sexo'] === 'M' ? 'M' : '—') ?>
                            </td>
                            <td class="pie"><?= !empty($al['prob_apren']) ? htmlspecialchars($al['prob_apren']) : '—' ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endforeach ?>

    <?php pdfPieFooter(); ?>
</body>

</html>