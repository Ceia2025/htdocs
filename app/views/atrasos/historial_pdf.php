<?php
// Variables esperadas: $agrupado, $historial, $anioActivo
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
            border-bottom: 3px solid #b45309;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header-left  { display: table-cell; width: 18%; vertical-align: middle; }
        .header-right { display: table-cell; width: 82%; vertical-align: middle; padding-left: 12px; }
        .inst-nombre  { font-size: 13px; font-weight: bold; color: #004b8d; text-transform: uppercase; }
        .inst-sub     { font-size: 9px;  color: #475569; }
        .ficha-titulo {
            display: inline-block; margin-top: 5px; padding: 3px 10px;
            background: #ffd500; color: #004b8d; font-weight: bold;
            font-size: 10px; text-transform: uppercase; border-radius: 3px;
        }

        .subtitulo { font-size: 9px; color: #475569; margin-bottom: 12px; }

        /* Curso separador */
        .curso-header {
            background: #b45309;
            color: white;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            padding: 5px 8px;
            margin-top: 14px;
            margin-bottom: 0;
            border-radius: 4px 4px 0 0;
        }

        /* Alumno separador */
        .alumno-header {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-top: none;
            padding: 5px 8px;
            margin-bottom: 0;
        }
        .alumno-nombre { font-weight: bold; font-size: 9.5px; color: #1e293b; }
        .alumno-sub    { font-size: 8px; color: #64748b; }
        .alumno-stats  { float: right; font-size: 9px; }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        thead tr { background: #78350f; }
        thead th {
            color: white; padding: 4px; text-align: left;
            font-size: 7.5px; text-transform: uppercase;
        }
        thead th.tc { text-align: center; }
        tbody td {
            border-bottom: 1px solid #e2e8f0;
            padding: 3.5px 4px;
            font-size: 8px;
        }
        tbody tr:nth-child(even) { background: #fafafa; }
        .tc { text-align: center; }

        .badge-ij { color: #dc2626; font-weight: bold; }
        .badge-j  { color: #16a34a; font-weight: bold; }
        .hora     { color: #d97706; font-weight: bold; }
        .sancion  { color: #ea580c; font-weight: bold; }

        /* Resumen general */
        .resumen {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 12px;
            font-size: 9px;
        }
        .resumen strong { color: #9a3412; }

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
            <div class="ficha-titulo">
                Historial de Atrasos con Medida Disciplinaria — <?= $anioActivo ?? date('Y') ?>
            </div>
        </div>
    </div>

    <div class="subtitulo">
        Generado el: <?= date('d/m/Y H:i') ?> &nbsp;·&nbsp;
        Total de registros: <?= count($historial) ?>
    </div>

    <!-- RESUMEN GENERAL -->
    <?php
    $totalGeneral = count($historial);
    $totalCursos  = count($agrupado);
    $totalAlumnos = array_sum(array_map('count', $agrupado));
    $totalIJ = count(array_filter($historial, fn($h) => !$h['justificado']));
    $totalJ  = $totalGeneral - $totalIJ;
    ?>
    <div class="resumen">
        <strong>Resumen:</strong>
        <?= $totalGeneral ?> atraso(s) con medida disciplinaria aplicada &nbsp;·&nbsp;
        <?= $totalCursos ?> curso(s) &nbsp;·&nbsp;
        <?= $totalAlumnos ?> alumno(s) afectados &nbsp;·&nbsp;
        <span style="color:#dc2626"><?= $totalIJ ?> injustificados</span> &nbsp;·&nbsp;
        <span style="color:#16a34a"><?= $totalJ ?> justificados</span>
    </div>

    <!-- CONTENIDO AGRUPADO POR CURSO → ALUMNO -->
    <?php foreach ($agrupado as $cursoNombre => $alumnos): ?>

        <div class="curso-header">
            🏫 <?= htmlspecialchars($cursoNombre) ?>
            — <?= array_sum(array_map('count', $alumnos)) ?> atraso(s)
        </div>

        <?php foreach ($alumnos as $alumnoNombre => $atrasos): ?>
            <?php
            $totalA = count($atrasos);
            $injA   = count(array_filter($atrasos, fn($a) => !$a['justificado']));
            ?>

            <div class="alumno-header">
                <div class="alumno-stats">
                    Total: <strong><?= $totalA ?></strong> &nbsp;·&nbsp;
                    IJ: <strong style="color:#dc2626"><?= $injA ?></strong>
                </div>
                <div class="alumno-nombre"><?= htmlspecialchars($alumnoNombre) ?></div>
                <div class="alumno-sub">RUN: <?= htmlspecialchars($atrasos[0]['alumno_run']) ?></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Fecha atraso</th>
                        <th class="tc">Hora</th>
                        <th class="tc">Sem.</th>
                        <th class="tc">Estado</th>
                        <th>Observación</th>
                        <th>Eliminado por</th>
                        <th>Fecha acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($atrasos as $h): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($h['fecha_atraso'])) ?></td>
                            <td class="tc hora"><?= substr($h['hora_llegada'], 0, 5) ?></td>
                            <td class="tc"><?= $h['semestre'] ?>°</td>
                            <td class="tc">
                                <?php if ($h['justificado']): ?>
                                    <span class="badge-j">J</span>
                                <?php else: ?>
                                    <span class="badge-ij">IJ</span>
                                <?php endif ?>
                            </td>
                            <td><?= $h['observacion'] ? htmlspecialchars($h['observacion']) : '—' ?></td>
                            <td><?= htmlspecialchars($h['eliminado_por_nombre'] ?? 'Sistema') ?></td>
                            <td><?= (new DateTime($h['eliminado_at']))->format('d/m/Y H:i') ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

        <?php endforeach ?>

    <?php endforeach ?>

    <div class="footer">
        Reporte generado automáticamente por el sistema — C.E.I.A. Parral <?= date('Y') ?>
        <br>Desarrollado por Daniel Scarlazzetta
    </div>

</body>
</html>