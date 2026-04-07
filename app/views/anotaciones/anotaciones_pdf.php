<?php
// Variables: $alumnos, $anotacionesPorAlumno, $cursoNombre, $semestreTexto
$coloresTipo = [
    'Leve' => ['bg' => '#fef3c7', 'border' => '#f59e0b', 'text' => '#92400e', 'label' => 'LEVE'],
    'Grave' => ['bg' => '#ffedd5', 'border' => '#f97316', 'text' => '#7c2d12', 'label' => 'GRAVE'],
    'Gravísima' => ['bg' => '#fee2e2', 'border' => '#ef4444', 'text' => '#7f1d1d', 'label' => 'GRAVÍSIMA'],
    'Positiva' => ['bg' => '#dcfce7', 'border' => '#22c55e', 'text' => '#14532d', 'label' => 'POSITIVA'],
    'Registro' => ['bg' => '#dbeafe', 'border' => '#3b82f6', 'text' => '#1e3a8a', 'label' => 'REGISTRO'],
];
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
            margin: 16px 20px;
        }

        /* HEADER */
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 8px;
            margin-bottom: 12px;
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
            font-size: 9px;
            color: #475569;
        }

        .ficha-titulo {
            display: inline-block;
            margin-top: 5px;
            padding: 3px 10px;
            background: #ffd500;
            color: #004b8d;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            border-radius: 3px;
        }

        /* SUBTÍTULO */
        .sub-header {
            margin-bottom: 10px;
        }

        .titulo {
            font-size: 15px;
            font-weight: bold;
            color: #4f46e5;
        }

        .subtitulo {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
        }

        /* RESUMEN GENERAL */
        .resumen {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }

        .res-cell {
            display: table-cell;
            text-align: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 6px 4px;
        }

        .res-valor {
            font-size: 16px;
            font-weight: bold;
        }

        .res-label {
            font-size: 7px;
            color: #64748b;
            margin-top: 1px;
        }

        /* ALUMNO BLOQUE */
        .alumno-bloque {
            margin-bottom: 14px;
            page-break-inside: avoid;
        }

        .alumno-header {
            background: #1e293b;
            color: white;
            padding: 5px 8px;
            border-radius: 4px 4px 0 0;
            font-size: 9.5px;
            font-weight: bold;
            display: table;
            width: 100%;
        }

        .alumno-nombre {
            display: table-cell;
        }

        .alumno-badges {
            display: table-cell;
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 10px;
            font-size: 7.5px;
            font-weight: bold;
            margin-left: 3px;
        }

        /* TABLA ANOTACIONES */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
        }

        thead tr {
            background: #f1f5f9;
        }

        thead th {
            padding: 4px 5px;
            text-align: left;
            font-size: 7.5px;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }

        thead th.tc {
            text-align: center;
        }

        tbody td {
            padding: 4px 5px;
            font-size: 8px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .tc {
            text-align: center;
        }

        .tipo-pill {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
            border-width: 1px;
            border-style: solid;
        }

        /* Sin anotaciones */
        .sin-anot {
            padding: 6px;
            text-align: center;
            color: #94a3b8;
            font-size: 8px;
            font-style: italic;
        }

        /* PIE */
        .footer {
            margin-top: 14px;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
            font-size: 7.5px;
            color: #94a3b8;
            text-align: right;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
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
            <div class="ficha-titulo">Reporte de Anotaciones
                <?= date('Y') ?> / C.E.I.A. Parral
            </div>
        </div>
    </div>

    <!-- SUBTÍTULO -->
    <div class="sub-header">
        <div class="titulo">Reporte de Anotaciones</div>
        <div class="subtitulo">
            Curso: <strong>
                <?= htmlspecialchars($cursoNombre) ?>
            </strong>
            &nbsp;·&nbsp;
            <?= htmlspecialchars($semestreTexto) ?>
            &nbsp;·&nbsp; Generado el:
            <?= date('d/m/Y H:i') ?>
        </div>
    </div>

    <!-- RESUMEN GENERAL DEL CURSO -->
    <?php
    $totales = ['total' => 0, 'Leve' => 0, 'Grave' => 0, 'Gravísima' => 0, 'Positiva' => 0, 'Registro' => 0];
    foreach ($alumnos as $al) {
        $totales['total'] += $al['total_anotaciones'];
        $totales['Leve'] += $al['leves'];
        $totales['Grave'] += $al['graves'];
        $totales['Gravísima'] += $al['gravisimas'];
        $totales['Positiva'] += $al['positivas'];
        $totales['Registro'] += $al['registros'];
    }
    ?>
    <div class="resumen">
        <div class="res-cell" style="width:16%">
            <div class="res-valor" style="color:#1e293b">
                <?= $totales['total'] ?>
            </div>
            <div class="res-label">Total</div>
        </div>
        <div class="res-cell" style="width:16%">
            <div class="res-valor" style="color:#f59e0b">
                <?= $totales['Leve'] ?>
            </div>
            <div class="res-label">Leves</div>
        </div>
        <div class="res-cell" style="width:16%">
            <div class="res-valor" style="color:#f97316">
                <?= $totales['Grave'] ?>
            </div>
            <div class="res-label">Graves</div>
        </div>
        <div class="res-cell" style="width:16%">
            <div class="res-valor" style="color:#ef4444">
                <?= $totales['Gravísima'] ?>
            </div>
            <div class="res-label">Gravísimas</div>
        </div>
        <div class="res-cell" style="width:16%">
            <div class="res-valor" style="color:#22c55e">
                <?= $totales['Positiva'] ?>
            </div>
            <div class="res-label">Positivas</div>
        </div>
        <div class="res-cell" style="width:16%">
            <div class="res-valor" style="color:#3b82f6">
                <?= $totales['Registro'] ?>
            </div>
            <div class="res-label">Registros</div>
        </div>
    </div>

    <!-- BLOQUE POR ALUMNO -->
    <?php foreach ($alumnos as $al):
        $anotaciones = $anotacionesPorAlumno[$al['matricula_id']] ?? [];
        ?>
        <div class="alumno-bloque">

            <div class="alumno-header">
                <div class="alumno-nombre">
                    <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                    <span style="color:#94a3b8; font-weight:normal; font-size:8px">
                        ·
                        <?= htmlspecialchars($al['run']) ?>
                    </span>
                </div>
                <div class="alumno-badges">
                    <?php if ($al['leves'] > 0): ?>
                        <span class="badge" style="background:#fef3c7;color:#92400e;border:1px solid #f59e0b">
                            <?= $al['leves'] ?> L
                        </span>
                    <?php endif; ?>
                    <?php if ($al['graves'] > 0): ?>
                        <span class="badge" style="background:#ffedd5;color:#7c2d12;border:1px solid #f97316">
                            <?= $al['graves'] ?> G
                        </span>
                    <?php endif; ?>
                    <?php if ($al['gravisimas'] > 0): ?>
                        <span class="badge" style="background:#fee2e2;color:#7f1d1d;border:1px solid #ef4444">
                            <?= $al['gravisimas'] ?> GG
                        </span>
                    <?php endif; ?>
                    <?php if ($al['positivas'] > 0): ?>
                        <span class="badge" style="background:#dcfce7;color:#14532d;border:1px solid #22c55e">
                            <?= $al['positivas'] ?> P
                        </span>
                    <?php endif; ?>
                    <?php if ($al['registros'] > 0): ?>
                        <span class="badge" style="background:#dbeafe;color:#1e3a8a;border:1px solid #3b82f6">
                            <?= $al['registros'] ?> R
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (empty($anotaciones)): ?>
                <div class="sin-anot">Sin anotaciones en este período</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th class="tc" style="width:55px">Fecha</th>
                            <th class="tc" style="width:65px">Tipo</th>
                            <th class="tc" style="width:70px">Asignatura</th>
                            <th>Contenido</th>
                            <th class="tc" style="width:50px">Sem.</th>
                            <th class="tc" style="width:55px">Apoderado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anotaciones as $an):
                            $c = $coloresTipo[$an['tipo']] ?? ['bg' => '#f8fafc', 'border' => '#e2e8f0', 'text' => '#334155', 'label' => $an['tipo']];
                            ?>
                            <tr>
                                <td class="tc">
                                    <?= date('d/m/Y', strtotime($an['fecha_anotacion'])) ?>
                                </td>
                                <td class="tc">
                                    <span class="tipo-pill"
                                        style="background:<?= $c['bg'] ?>;border-color:<?= $c['border'] ?>;color:<?= $c['text'] ?>">
                                        <?= $c['label'] ?>
                                    </span>
                                </td>
                                <td class="tc">
                                    <?= htmlspecialchars($an['abreviatura'] ?? $an['asignatura_nombre']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($an['contenido']) ?>
                                </td>
                                <td class="tc">
                                    <?= $an['semestre'] ?>°
                                </td>
                                <td class="tc">
                                    <?= htmlspecialchars($an['notificado_apoderado'] ?? '—') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

    <div class="footer">
        Documento generado automáticamente el
        <?= date('d/m/Y H:i') ?> — Sistema SAAT · C.E.I.A. Parral<br>
        Desarrollado por Daniel Scarlazzetta
    </div>

</body>

</html>