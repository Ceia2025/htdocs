<?php

$asistenciaModel = new Asistencia();
$cursoInfo = $asistenciaModel->getCurso($_GET['curso_id'] ?? 0);
$nombreCurso = $cursoInfo['nombre'] ?? 'Curso no encontrado';

// Header helpers
$logoPath = __DIR__ . '/../../public/img/LOGO CEIA.jpg';
$logoExists = file_exists($logoPath);
$anioFicha = date('Y');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 18px 20px;
        }

        /* HEADER INSTITUCIONAL */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 3px solid #004b8d;
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

        /* SUB HEADER REPORTE */
        .sub-header {
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .titulo {
            font-size: 16px;
            font-weight: bold;
            color: #004b8d;
        }

        .parrafo {
            font-size: 12px;
            font-weight: bold;
            color: #CA8A04;
        }

        /* TABLA */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #004b8d;
            color: white;
            padding: 6px;
            text-transform: uppercase;
            font-size: 10px;
        }

        td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .verde {
            color: #15803d;
        }

        .amarillo {
            color: #a16207;
        }

        .rojo {
            color: #b91c1c;
        }
    </style>
</head>

<body>

    <!-- HEADER BONITO -->
    <div class="header">
        <div class="header-left">
            <?php if ($logoExists): ?>
                <img src="http://localhost:8080/app/public/img/LOGO%20CEIA.jpg" style="width:90px;">
            <?php else: ?>
                <strong style="color:#004b8d;">CEIA Parral</strong>
            <?php endif; ?>
        </div>

        <div class="header-right">
            <div class="inst-nombre">Centro de Educación Integrada de Adultos</div>
            <div class="inst-sub">"Juanita Zúñiga Fuentes" – Parral</div>
            <div class="ficha-titulo">
                Reporte de Asistencia <?= $anioFicha ?> / C.E.I.A. Parral
            </div>
        </div>
    </div>

    <!-- SUBTITULO -->
    <div class="sub-header">
        <div class="titulo">Reporte de Asistencia</div>
        <div class="parrafo">
            <?= htmlspecialchars($nombreCurso) ?>
        </div>
        <div>Generado el: <?= date('d/m/Y') ?></div>
    </div>

    <!-- TABLA -->
    <table>
        <thead>
            <tr>
                <th>Alumno</th>
                <th class="text-center">Clases</th>
                <th class="text-center">Presentes</th>
                <th class="text-center">% Asistencia</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalle as $row):
                $total = $row['total_clases'] ?? 0;
                $asistencias = $row['presentes'] ?? 0;
                $porcentaje = ($total > 0) ? round(($asistencias / $total) * 100, 1) : 0;

                $claseColor = ($porcentaje >= 75) ? 'verde' : (($porcentaje >= 50) ? 'amarillo' : 'rojo');
                ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($row['apepat'] . ' ' . $row['apemat'] . ', ' . $row['nombre']) ?>
                    </td>
                    <td class="text-center">
                        <?= $total ?>
                    </td>
                    <td class="text-center">
                        <?= $asistencias ?>
                    </td>
                    <td class="text-center font-bold <?= $claseColor ?>">
                        <?= $porcentaje ?>%
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>