<?php
// Variables: $alumno, $curso, $anio, $matricula, $secciones,
// $notasPorSemestre, $resumenAsistencia, $anotaciones, $resumenAnotaciones,
// $atrasos, $resumenAtrasos
$logoPath = __DIR__ . '/../../public/img/LOGO CEIA.jpg';
$logoSrc = file_exists($logoPath)
    ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath))
    : null;

$fmtFecha = fn($f) => $f ? date('d/m/Y', strtotime($f)) : '—';
$colorPct = fn($p) => $p === null ? '#94a3b8' : ($p >= 85 ? '#16a34a' : ($p >= 75 ? '#d97706' : '#dc2626'));

$nombreCompleto = trim(($alumno['nombre'] ?? '') . ' ' . ($alumno['apepat'] ?? '') . ' ' . ($alumno['apemat'] ?? ''));
$run = trim(($alumno['run'] ?? '') . (!empty($alumno['codver']) ? '-' . $alumno['codver'] : ''));

$coloresTipo = [
    'Positiva' => '#16a34a',
    'Leve' => '#d97706',
    'Grave' => '#ea580c',
    'Gravísima' => '#dc2626',
    'Registro' => '#1d4ed8',
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
            background: #ffd500;
            color: #004b8d;
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
            margin-bottom: 6px;
            font-size: 9px;
            line-height: 1.6;
        }

        .sec-titulo {
            font-size: 11px;
            font-weight: bold;
            color: #004b8d;
            text-transform: uppercase;
            border-bottom: 2px solid #ffd500;
            padding-bottom: 3px;
            margin: 16px 0 8px;
        }

        .sem-titulo {
            font-size: 9px;
            font-weight: bold;
            color: #475569;
            margin: 10px 0 4px;
        }

        table.stats {
            width: 100%;
            border-collapse: separate;
            border-spacing: 4px;
            margin-bottom: 6px;
        }

        table.stats td {
            text-align: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 5px;
        }

        .stat-val {
            font-size: 15px;
            font-weight: bold;
        }

        .stat-lbl {
            font-size: 7px;
            color: #64748b;
        }

        table.datos {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        table.datos thead tr {
            background: #004b8d;
        }

        table.datos thead th {
            color: white;
            padding: 4px 6px;
            font-size: 8px;
            text-transform: uppercase;
            text-align: left;
        }

        table.datos thead th.tc {
            text-align: center;
        }

        table.datos tbody td {
            border-bottom: 1px solid #e2e8f0;
            padding: 3.5px 6px;
            font-size: 8.5px;
            vertical-align: top;
        }

        table.datos tbody tr:nth-child(even) td {
            background: #f8fafc;
        }

        .tc {
            text-align: center;
        }

        .nota {
            font-weight: bold;
            padding-right: 6px;
        }

        .gris {
            color: #94a3b8;
        }

        .vacio {
            color: #94a3b8;
            font-style: italic;
            padding: 4px 0;
        }

        .ausencias {
            font-size: 8px;
            color: #475569;
            line-height: 1.6;
            margin-top: 4px;
        }

        .footer {
            margin-top: 14px;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            font-size: 7.5px;
            color: #94a3b8;
            text-align: right;
        }

        /* ═══════════ CALENDARIO DE ASISTENCIA (Compatible PDF) ═══════════ */
        .cal-month-title {
            font-size: 10px;
            font-weight: bold;
            color: #004b8d;
            border-bottom: 2px solid #ffd500;
            padding-bottom: 3px;
            margin-bottom: 6px;
            margin-top: 12px;
        }

        .cal-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .cal-th {
            background: #f1f5f9;
            color: #475569;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            padding: 4px 2px;
            border: 1px solid #cbd5e1;
        }

        .cal-td {
            width: 20%;
            height: 26px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #e2e8f0;
            font-size: 9px;
            font-weight: bold;
        }

        .cal-presente {
            background-color: #dcfce7;
            border-color: #16a34a;
            color: #16a34a;
        }

        .cal-ausente {
            background-color: #fee2e2;
            border-color: #dc2626;
            color: #dc2626;
        }

        .cal-vacio {
            border: none;
            background: transparent;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="header-left">
            <?php if ($logoSrc): ?>
                <img src="<?= $logoSrc ?>" style="width:80px;">
            <?php else: ?>
                <strong style="color:#004b8d;">CEIA Parral</strong>
            <?php endif ?>
        </div>
        <div class="header-right">
            <div class="inst-nombre">Centro de Educación Integrada de Adultos</div>
            <div class="inst-sub">"Juanita Zúñiga Fuentes" – Parral</div>
            <div class="ficha-titulo">Perfil Académico</div>
        </div>
    </div>

    <div class="info-box">
        <strong>Alumno:</strong> <?= htmlspecialchars($nombreCompleto) ?>
        <?php if ($run !== ''): ?> &nbsp;·&nbsp; <strong>RUN:</strong> <?= htmlspecialchars($run) ?><?php endif ?>
        <br>
        <strong>Curso:</strong> <?= htmlspecialchars($curso['nombre']) ?> &nbsp;·&nbsp;
        <strong>Año:</strong> <?= htmlspecialchars($anio['anio']) ?> &nbsp;·&nbsp;
        <strong>Generado:</strong> <?= date('d/m/Y H:i') ?>
    </div>

    <?php /* ═══════════ NOTAS ═══════════ */ ?>
    <?php if (in_array('notas', $secciones, true)): ?>
        <div class="sec-titulo">Notas</div>

        <?php foreach ([1, 2] as $sem):
            $datosSem = $notasPorSemestre[$sem] ?? ['asignaturas' => [], 'promedio_general' => null];
            ?>
            <div class="sem-titulo"><?= $sem ?>° Semestre</div>

            <?php if (empty($datosSem['asignaturas'])): ?>
                <p class="vacio">Sin notas registradas en este semestre.</p>
            <?php else: ?>
                <table class="datos">
                    <thead>
                        <tr>
                            <th>Asignatura</th>
                            <th>Notas</th>
                            <th class="tc">Promedio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datosSem['asignaturas'] as $fila): ?>
                            <tr>
                                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                <td>
                                    <?php foreach ($fila['notas'] as $n):
                                        $v = (float) $n['nota'];
                                        ?>
                                        <span class="nota" style="color:<?= Nota::color($v) ?>"><?= Nota::fmt($v) ?></span>
                                    <?php endforeach ?>
                                </td>
                                <td class="tc">
                                    <?php if ($fila['promedio'] !== null): ?>
                                        <span class="nota" style="color:<?= Nota::color($fila['promedio']) ?>">
                                            <?= Nota::fmt($fila['promedio']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="gris">Pendiente</span>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        <?php if ($datosSem['promedio_general'] !== null): ?>
                            <tr>
                                <td colspan="2" style="text-align:right; font-weight:bold;">Promedio general del semestre</td>
                                <td class="tc">
                                    <span class="nota" style="color:<?= Nota::color($datosSem['promedio_general']) ?>">
                                        <?= Nota::fmt($datosSem['promedio_general']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            <?php endif ?>
        <?php endforeach ?>
    <?php endif ?>

    <?php /* ═══════════ ASISTENCIA ═══════════ */ ?>
    <?php if (in_array('asistencia', $secciones, true)): ?>
        <div class="sec-titulo">Asistencia</div>

        <?php if (empty($resumenAsistencia) || $resumenAsistencia['total'] === 0): ?>
            <p class="vacio">Sin registros de asistencia - feriados - Vacaciones de Invierno</p>
        <?php else: ?>

            <!-- Resumen rápido -->
            <table class="stats">
                <tr>
                    <td>
                        <div class="stat-val" style="color:#1d4ed8"><?= $resumenAsistencia['total'] ?></div>
                        <div class="stat-lbl">Total clases</div>
                    </td>
                    <td>
                        <div class="stat-val" style="color:#16a34a"><?= $resumenAsistencia['presentes'] ?></div>
                        <div class="stat-lbl">Presentes</div>
                    </td>
                    <td>
                        <div class="stat-val" style="color:#dc2626"><?= $resumenAsistencia['ausentes'] ?></div>
                        <div class="stat-lbl">Ausentes</div>
                    </td>
                    <td>
                        <div class="stat-val" style="color:<?= $colorPct($resumenAsistencia['pct']) ?>">
                            <?= $resumenAsistencia['pct'] ?>%
                        </div>
                        <div class="stat-lbl">% Asistencia</div>
                    </td>
                </tr>
            </table>

            <?php
            $nombresMeses = [
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre',
            ];
            $letrasDias = ['L', 'M', 'M', 'J', 'V'];

            // Meses desde el primer hasta el último registro de asistencia
            $mesesRango = [];
            if (!empty($asistenciaDiaria)) {
                $fechasReg = array_keys($asistenciaDiaria);
                $cursor = new DateTime(substr(min($fechasReg), 0, 7) . '-01');
                $limite = new DateTime(substr(max($fechasReg), 0, 7) . '-01');

                while ($cursor <= $limite) {
                    $mesesRango[] = [(int) $cursor->format('Y'), (int) $cursor->format('n')];
                    $cursor->modify('+1 month');
                }
            }

            $gruposMeses = array_chunk($mesesRango, 3);
            ?>

            <?php foreach ($gruposMeses as $grupo): ?>
                <table
                    style="width:100%; margin-bottom:12px; border-collapse:separate; border-spacing:8px; page-break-inside:avoid;">
                    <tr>
                        <?php foreach ($grupo as [$anioMes, $numMes]):
                            $totalDias = (int) date('t', mktime(0, 0, 0, $numMes, 1, $anioMes));

                            // Armar semanas: cada día hábil va en la columna que le corresponde (L=0 ... V=4)
                            $semanas = [];
                            $semana = array_fill(0, 5, null);
                            $semanaConDatos = false;

                            for ($d = 1; $d <= $totalDias; $d++) {
                                $dow = (int) date('N', mktime(0, 0, 0, $numMes, $d, $anioMes)); // 1=lun ... 7=dom
            
                                if ($dow > 5) {
                                    if ($semanaConDatos) {
                                        $semanas[] = $semana;
                                        $semana = array_fill(0, 5, null);
                                        $semanaConDatos = false;
                                    }
                                    continue;
                                }

                                $semana[$dow - 1] = $d;
                                $semanaConDatos = true;
                            }
                            if ($semanaConDatos) {
                                $semanas[] = $semana;
                            }
                            ?>
                            <td
                                style="width:33%; vertical-align:top; border:1px solid #e2e8f0; border-radius:6px; padding:6px; background:#f8fafc;">
                                <div
                                    style="text-align:center; font-weight:bold; color:#004b8d; font-size:9px; margin-bottom:4px; border-bottom:2px solid #ffd500; padding-bottom:2px;">
                                    <?= strtoupper($nombresMeses[$numMes]) ?>
                                </div>

                                <table style="width:100%; border-collapse:collapse; font-size:7px;">
                                    <thead>
                                        <tr style="background:#f1f5f9;">
                                            <?php foreach ($letrasDias as $letra): ?>
                                                <th
                                                    style="border:1px solid #cbd5e1; padding:2px; text-align:center; color:#475569; width:20%;">
                                                    <?= $letra ?>
                                                </th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($semanas as $sem): ?>
                                            <tr>
                                                <?php foreach ($sem as $dia): ?>
                                                    <?php if ($dia === null): ?>
                                                        <td style="border:none; height:18px;"></td>
                                                    <?php else:
                                                        $fechaStr = sprintf('%04d-%02d-%02d', $anioMes, $numMes, $dia);
                                                        $estado = $asistenciaDiaria[$fechaStr] ?? null;

                                                        if ($estado === 'presente') {
                                                            $bgColor = '#dcfce7';
                                                            $color = '#16a34a';
                                                            $border = '#16a34a';
                                                        } elseif ($estado === 'ausente') {
                                                            $bgColor = '#fee2e2';
                                                            $color = '#dc2626';
                                                            $border = '#dc2626';
                                                        } else {
                                                            $bgColor = '#ffffff';
                                                            $color = '#94a3b8';
                                                            $border = '#e2e8f0';
                                                        }
                                                        ?>
                                                        <td
                                                            style="border:1px solid <?= $border ?>; background:<?= $bgColor ?>; color:<?= $color ?>; text-align:center; height:18px; font-weight:bold; font-size:7px;">
                                                            <?= $dia ?>
                                                        </td>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td>
                        <?php endforeach; ?>

                        <?php for ($i = count($grupo); $i < 3; $i++): ?>
                            <td style="width:33%;"></td>
                        <?php endfor; ?>
                    </tr>
                </table>
            <?php endforeach; ?>

            <div class="ausencias">
                <span style="color:#16a34a; font-weight:bold;">■</span> Presente &nbsp;&nbsp;
                <span style="color:#dc2626; font-weight:bold;">■</span> Ausente &nbsp;&nbsp;
                <span style="color:#cbd5e1; font-weight:bold;">■</span> Sin registro de asistencia
            </div>

        <?php endif ?>
    <?php endif ?>

    <?php /* ═══════════ ANOTACIONES ═══════════ */ ?>
    <?php if (in_array('anotaciones', $secciones, true)): ?>
        <div class="sec-titulo">Anotaciones</div>

        <?php if (empty($anotaciones)): ?>
            <p class="vacio">Sin anotaciones registradas.</p>
        <?php else: ?>
            <table class="stats">
                <tr>
                    <td>
                        <div class="stat-val"><?= $resumenAnotaciones['total'] ?></div>
                        <div class="stat-lbl">Total</div>
                    </td>
                    <td>
                        <div class="stat-val" style="color:#16a34a"><?= $resumenAnotaciones['positivas'] ?></div>
                        <div class="stat-lbl">Positivas</div>
                    </td>
                    <td>
                        <div class="stat-val" style="color:#d97706"><?= $resumenAnotaciones['leves'] ?></div>
                        <div class="stat-lbl">Leves</div>
                    </td>
                    <td>
                        <div class="stat-val" style="color:#ea580c"><?= $resumenAnotaciones['graves'] ?></div>
                        <div class="stat-lbl">Graves</div>
                    </td>
                    <td>
                        <div class="stat-val" style="color:#dc2626"><?= $resumenAnotaciones['gravisimas'] ?></div>
                        <div class="stat-lbl">Gravísimas</div>
                    </td>
                    <td>
                        <div class="stat-val" style="color:#1d4ed8"><?= $resumenAnotaciones['registros'] ?></div>
                        <div class="stat-lbl">Registros</div>
                    </td>
                </tr>
            </table>

            <table class="datos">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Asignatura</th>
                        <th>Detalle</th>
                        <th>Apoderado notificado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($anotaciones as $an): ?>
                        <tr>
                            <td><?= $fmtFecha($an['fecha_anotacion']) ?><br><span class="gris"><?= (int) $an['semestre'] ?>°
                                    Sem.</span></td>
                            <td style="color:<?= $coloresTipo[$an['tipo']] ?? '#475569' ?>; font-weight:bold;">
                                <?= htmlspecialchars($an['tipo']) ?>
                            </td>
                            <td><?= htmlspecialchars($an['asignatura_nombre'] ?? $an['abreviatura'] ?? '—') ?></td>
                            <td>
                                <?= htmlspecialchars($an['contenido']) ?>
                                <?php if (!empty($an['accion_realizada'])): ?>
                                    <br><span class="gris">Acción: <?= htmlspecialchars($an['accion_realizada']) ?></span>
                                <?php endif ?>
                            </td>
                            <td><?= htmlspecialchars($an['notificado_apoderado'] ?? '—') ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php endif ?>
    <?php endif ?>

    <?php /* ═══════════ ATRASOS ═══════════ */ ?>
    <?php if (in_array('atrasos', $secciones, true)): ?>
        <div class="sec-titulo">Atrasos</div>

        <?php if (empty($atrasos)): ?>
            <p class="vacio">Sin atrasos registrados.</p>
        <?php else: ?>
            <table class="stats">
                <tr>
                    <td>
                        <div class="stat-val"><?= $resumenAtrasos['total'] ?></div>
                        <div class="stat-lbl">Total</div>
                    </td>
                    <td>
                        <div class="stat-val" style="color:#16a34a"><?= $resumenAtrasos['justificados'] ?></div>
                        <div class="stat-lbl">Justificados</div>
                    </td>
                    <td>
                        <div class="stat-val" style="color:#dc2626"><?= $resumenAtrasos['injustificados'] ?></div>
                        <div class="stat-lbl">Injustificados</div>
                    </td>
                    <td>
                        <div class="stat-val"><?= $resumenAtrasos['sem1'] ?></div>
                        <div class="stat-lbl">1° Semestre</div>
                    </td>
                    <td>
                        <div class="stat-val"><?= $resumenAtrasos['sem2'] ?></div>
                        <div class="stat-lbl">2° Semestre</div>
                    </td>
                </tr>
            </table>

            <table class="datos">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th class="tc">Hora llegada</th>
                        <th class="tc">Semestre</th>
                        <th>Estado</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($atrasos as $at):
                        $esJust = (int) $at['justificado'] === 1;
                        ?>
                        <tr>
                            <td><?= $fmtFecha($at['fecha']) ?></td>
                            <td class="tc"><?= htmlspecialchars(substr($at['hora_llegada'], 0, 5)) ?></td>
                            <td class="tc"><?= (int) $at['semestre'] ?>°</td>
                            <td style="color:<?= $esJust ? '#16a34a' : '#dc2626' ?>; font-weight:bold;">
                                <?= $esJust ? 'Justificado' : 'Injustificado' ?>
                            </td>
                            <td><?= htmlspecialchars($at['observacion'] ?? '') ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php endif ?>
    <?php endif ?>

    <div class="footer">
        Perfil académico generado automáticamente — Sistema SAAT · C.E.I.A. Parral <?= date('Y') ?>
        <br>Desarrollado por Daniel Scarlazzetta
    </div>
</body>

</html>