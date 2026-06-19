<?php
$logoPath = __DIR__ . '/../../../public/img/LOGO CEIA.jpg';
$logoExists = file_exists($logoPath);
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

        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #004b8d;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .header-left {
            display: table-cell;
            width: 16%;
            vertical-align: middle;
        }

        .header-right {
            display: table-cell;
            width: 84%;
            vertical-align: middle;
            padding-left: 12px;
        }

        .inst-nombre {
            font-size: 12px;
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
            background: #fef3c7;
            color: #92400e;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            border-radius: 3px;
        }

        .info-box {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 12px;
            font-size: 9px;
        }

        table.ranking {
            width: 100%;
            border-collapse: collapse;
        }

        table.ranking thead tr {
            background: #1e293b;
        }

        table.ranking thead th {
            color: #94a3b8;
            padding: 5px 7px;
            font-size: 7.5px;
            text-transform: uppercase;
            text-align: left;
        }

        table.ranking thead th.tc {
            text-align: center;
        }

        table.ranking tbody td {
            border-bottom: 1px solid #e2e8f0;
            padding: 5px 7px;
            font-size: 8.5px;
        }

        table.ranking tbody tr:nth-child(even) td {
            background: #f8fafc;
        }

        .pos {
            font-weight: bold;
            color: #64748b;
            text-align: center;
        }

        .aprobado {
            color: #15803d;
            font-weight: bold;
        }

        .reprobado {
            color: #b91c1c;
            font-weight: bold;
        }

        .neutro {
            color: #64748b;
        }

        .tc {
            text-align: center;
        }

        .bar-wrap {
            background: #e2e8f0;
            border-radius: 3px;
            height: 5px;
            width: 60px;
            display: inline-block;
            vertical-align: middle;
        }

        .bar-fill-ok {
            background: #15803d;
            border-radius: 3px;
            height: 5px;
            display: block;
        }

        .bar-fill-bad {
            background: #b91c1c;
            border-radius: 3px;
            height: 5px;
            display: block;
        }

        .medal-1 {
            color: #b91c1c;
            font-weight: bold;
        }

        .medal-end {
            color: #15803d;
            font-weight: bold;
        }

        .footer {
            margin-top: 14px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            font-size: 7.5px;
            color: #94a3b8;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="header-left">
            <?php if ($logoExists): ?>
                <img src="http://localhost:8080/app/public/img/LOGO%20CEIA.jpg" style="width:80px;">
            <?php else: ?>
                <strong style="color:#004b8d;">CEIA Parral</strong>
            <?php endif ?>
        </div>
        <div class="header-right">
            <div class="inst-nombre">Centro de Educación Integrada de Adultos</div>
            <div class="inst-sub">"Juanita Zúñiga Fuentes" – Parral</div>
            <div class="ficha-titulo">
                Ranking de Cursos por Promedio — <?= htmlspecialchars($anioNombre) ?> · <?= $semestre ?>° Semestre
            </div>
        </div>
    </div>

    <div class="info-box">
        <strong>Año académico:</strong> <?= htmlspecialchars($anioNombre) ?> &nbsp;·&nbsp;
        <strong>Semestre:</strong> <?= $semestre ?>° &nbsp;·&nbsp;
        <strong>Cursos con notas:</strong> <?= count($cursos) ?> &nbsp;·&nbsp;
        <strong>Ordenado:</strong> de menor a mayor promedio &nbsp;·&nbsp;
        <strong>Generado:</strong> <?= date('d/m/Y H:i') ?>
    </div>

    <?php if (empty($cursos)): ?>
        <p style="color:#94a3b8; font-size:9px; text-align:center; margin-top:20px;">
            No hay cursos con notas registradas para este año y semestre.
        </p>
    <?php else: ?>
        <table class="ranking">
            <thead>
                <tr>
                    <th class="tc" style="width:28px;">#</th>
                    <th>Curso</th>
                    <th class="tc">Alumnos</th>
                    <th class="tc">N° notas</th>
                    <th class="tc">Promedio</th>
                    <th class="tc">% Aprobación</th>
                    <th class="tc">Distribución</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = count($cursos);
                foreach ($cursos as $idx => $c):
                    $pos = $idx + 1;
                    $prom = $c['promedio'];
                    $pct = $c['pct_aprobacion'];
                    $barPx = (int) round($pct * 0.6);

                    $clsProm = $prom === null ? 'neutro'
                        : ($prom >= 4.0 ? 'aprobado' : 'reprobado');

                    $clsPos = '';
                    if ($pos === 1)
                        $clsPos = 'medal-1';
                    if ($pos === $total)
                        $clsPos = 'medal-end';
                    ?>
                    <tr>
                        <td class="pos <?= $clsPos ?>"><?= $pos ?></td>
                        <td style="font-weight:bold; color:#1e293b;">
                            <?= htmlspecialchars($c['curso']) ?>
                        </td>
                        <td class="tc neutro"><?= $c['total_alumnos'] ?></td>
                        <td class="tc neutro"><?= $c['total_notas'] ?></td>
                        <td class="tc <?= $clsProm ?>" style="font-size:10px;">
                            <?= $prom !== null ? number_format($prom, 1) : '—' ?>
                        </td>
                        <td class="tc">
                            <span class="<?= $pct >= 70 ? 'aprobado' : 'reprobado' ?>">
                                <?= $pct ?>%
                            </span>
                        </td>
                        <td class="tc">
                            <span class="bar-wrap">
                                <span class="<?= $pct >= 70 ? 'bar-fill-ok' : 'bar-fill-bad' ?>"
                                    style="width:<?= $barPx ?>px;"></span>
                            </span>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <div style="margin-top:10px; font-size:7.5px; color:#64748b;">
            <span style="color:#b91c1c; font-weight:bold;">■</span> Posición 1 = curso con menor promedio &nbsp;·&nbsp;
            <span style="color:#15803d; font-weight:bold;">■</span> Última posición = curso con mayor promedio &nbsp;·&nbsp;
            Promedio <span style="color:#15803d; font-weight:bold;">verde</span> ≥ 4.0 &nbsp;·&nbsp;
            <span style="color:#b91c1c; font-weight:bold;">rojo</span> &lt; 4.0
        </div>
    <?php endif ?>

    <div class="footer">
        Reporte generado automáticamente — Sistema SAAT · C.E.I.A. Parral <?= date('Y') ?>
        <br>Desarrollado por Daniel Scarlazzetta
    </div>
</body>

</html>