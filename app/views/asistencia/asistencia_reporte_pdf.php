<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header {
            border-bottom: 2px solid #004b8d;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .titulo {
            font-size: 18px;
            font-weight: bold;
            color: #004b8d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #004b8d;
            color: white;
            padding: 8px;
            text-align: left;
            text-transform: uppercase;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Colores manuales para el PDF */
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
    <div class="header">
        <div class="titulo">Reporte de Asistencia</div>
        <div>Generado el:
            <?= date('d/m/Y') ?>
        </div>
    </div>

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