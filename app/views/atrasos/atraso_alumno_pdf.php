<?php // Variables: $alumno, $atrasos, $resumenAlumno ?>
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

        .ficha {
            background: #fefce8;
            border: 1px solid #fcd34d;
            border-radius: 5px;
            padding: 10px 12px;
            margin-bottom: 14px;
        }

        .ficha-nombre {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }

        .ficha-sub {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
        }

        .metricas {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }

        .met-cell {
            display: table-cell;
            text-align: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 7px 4px;
            width: 20%;
        }

        .met-valor {
            font-size: 20px;
            font-weight: bold;
        }

        .met-label {
            font-size: 7.5px;
            color: #64748b;
            margin-top: 2px;
        }

        .seccion {
            font-size: 9px;
            font-weight: bold;
            color: #004b8d;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
            margin: 12px 0 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

        .hora {
            color: #d97706;
            font-weight: bold;
        }

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
            <div class="ficha-titulo">Reporte Individual de Atrasos
                <?= date('Y') ?>
            </div>
        </div>
    </div>

    <!-- FICHA ALUMNO -->
    <div class="ficha">
        <div class="ficha-nombre">
            <?= htmlspecialchars($alumno['apepat'] . ' ' . $alumno['apemat'] . ', ' . $alumno['nombre']) ?>
        </div>
        <div class="ficha-sub">
            RUN:
            <?= htmlspecialchars($alumno['run']) ?>
            &nbsp;·&nbsp; Curso:
            <?= htmlspecialchars($alumno['curso']) ?>
            &nbsp;·&nbsp; Generado el:
            <?= date('d/m/Y H:i') ?>
        </div>
    </div>

    <!-- MÉTRICAS -->
    <div class="metricas">
        <div class="met-cell">
            <div class="met-valor" style="color:#1e293b">
                <?= $resumenAlumno['total'] ?>
            </div>
            <div class="met-label">Total atrasos</div>
        </div>
        <div class="met-cell">
            <div class="met-valor" style="color:#dc2626">
                <?= $resumenAlumno['injustificados'] ?>
            </div>
            <div class="met-label">Injustificados</div>
        </div>
        <div class="met-cell">
            <div class="met-valor" style="color:#16a34a">
                <?= $resumenAlumno['justificados'] ?>
            </div>
            <div class="met-label">Justificados</div>
        </div>
        <div class="met-cell">
            <div class="met-valor" style="color:#2563eb">
                <?= $resumenAlumno['sem1'] ?>
            </div>
            <div class="met-label">1° Semestre</div>
        </div>
        <div class="met-cell">
            <div class="met-valor" style="color:#7c3aed">
                <?= $resumenAlumno['sem2'] ?>
            </div>
            <div class="met-label">2° Semestre</div>
        </div>
    </div>

    <!-- TABLA -->
    <div class="seccion">Detalle de Atrasos (
        <?= $resumenAlumno['total'] ?> registros)
    </div>
    <table>
        <thead>
            <tr>
                <th class="tc">#</th>
                <th class="tc">Fecha</th>
                <th class="tc">Hora llegada</th>
                <th class="tc">Semestre</th>
                <th class="tc">Estado</th>
                <th>Observación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($atrasos as $i => $a): ?>
                <tr>
                    <td class="tc" style="color:#94a3b8">
                        <?= $i + 1 ?>
                    </td>
                    <td class="tc">
                        <?= date('d/m/Y', strtotime($a['fecha'])) ?>
                    </td>
                    <td class="tc hora">
                        <?= substr($a['hora_llegada'], 0, 5) ?>
                    </td>
                    <td class="tc">
                        <?= $a['semestre'] ?>°
                    </td>
                    <td class="tc">
                        <?php if ($a['justificado']): ?>
                            <span class="badge-j">Justificado</span>
                        <?php else: ?>
                            <span class="badge-ij">Injustificado</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $a['observacion'] ? htmlspecialchars($a['observacion']) : '—' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Reporte generado automáticamente por el sistema — C.E.I.A. Parral
        <?= date('Y') ?>
        <p>Desarrollado por <span> Daniel Scarlazzetta</span></p>
    </div>

</body>

</html>