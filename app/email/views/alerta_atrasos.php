<?php
// app/email/views/alerta_atrasos.php
// Variables esperadas: $agrupado, $desde, $hasta, $totalAtrasos, $totalAlumnos, $totalInjustificados
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f1f5f9; color: #1e293b; }
    .wrapper { max-width: 650px; margin: 30px auto; background: #fff;
               border-radius: 12px; overflow: hidden;
               box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .header { background: #1e293b; padding: 28px 32px; }
    .header-top { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
    .badge { background: #f59e0b; color: #1e293b; font-size: 11px; font-weight: bold;
             padding: 3px 10px; border-radius: 20px; text-transform: uppercase;
             letter-spacing: 0.5px; }
    .header h1 { color: #fff; font-size: 20px; font-weight: 700; }
    .header p  { color: #94a3b8; font-size: 13px; margin-top: 4px; }

    .body { padding: 28px 32px; }

    .info-grid { display: grid; grid-template-columns: repeat(3, 1fr);
                 gap: 12px; margin-bottom: 24px; }
    .info-card { background: #f8fafc; border: 1px solid #e2e8f0;
                 border-radius: 8px; padding: 14px 16px; text-align: center; }
    .info-label { font-size: 11px; color: #94a3b8; text-transform: uppercase;
                  letter-spacing: 0.4px; margin-bottom: 4px; }
    .info-value { font-size: 20px; font-weight: 700; color: #1e293b; }

    .curso-title { font-size: 13px; font-weight: 700; text-transform: uppercase;
                   letter-spacing: 0.6px; color: #fff; background: #334155;
                   padding: 8px 14px; border-radius: 6px; margin: 20px 0 10px; }

    table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    thead tr { background: #f8fafc; }
    thead th { padding: 8px 12px; text-align: left; font-size: 11px;
               text-transform: uppercase; letter-spacing: 0.4px; color: #64748b;
               border-bottom: 2px solid #e2e8f0; }
    tbody tr { border-bottom: 1px solid #f1f5f9; }
    tbody tr:last-child { border-bottom: none; }
    tbody td { padding: 8px 12px; font-size: 13px; color: #334155; }
    .nombre-alumno { font-weight: 600; color: #1e293b; }
    .badge-just { display: inline-block; font-weight: 700; font-size: 11px;
                  padding: 2px 8px; border-radius: 20px; }
    .badge-si  { background: #dcfce7; color: #166534; }
    .badge-no  { background: #fee2e2; color: #991b1b; }

    .footer { background: #f8fafc; border-top: 1px solid #e2e8f0;
              padding: 20px 32px; text-align: center; }
    .footer p { font-size: 11px; color: #94a3b8; line-height: 1.8; }
    .footer strong { color: #64748b; }
</style>
</head>
<body>
<div class="wrapper">

    <!-- HEADER -->
    <div class="header">
        <div class="header-top">
            <span class="badge">Reporte Semanal</span>
        </div>
        <h1>Atrasos de la Semana</h1>
        <p>
            Sistema SAAT · C.E.I.A. Parral ·
            <?= date('d/m/Y', strtotime($desde)) ?> al <?= date('d/m/Y', strtotime($hasta)) ?>
        </p>
    </div>

    <!-- BODY -->
    <div class="body">

        <!-- Resumen -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Total atrasos</div>
                <div class="info-value"><?= $totalAtrasos ?></div>
            </div>
            <div class="info-card">
                <div class="info-label">Alumnos afectados</div>
                <div class="info-value"><?= $totalAlumnos ?></div>
            </div>
            <div class="info-card">
                <div class="info-label">Injustificados</div>
                <div class="info-value"><?= $totalInjustificados ?></div>
            </div>
        </div>

        <!-- Detalle por curso -->
        <?php foreach ($agrupado as $cursoNombre => $alumnos): ?>
            <div class="curso-title"><?= htmlspecialchars($cursoNombre) ?></div>
            <table>
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Fecha</th>
                        <th>Hora llegada</th>
                        <th>Justificado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alumnos as $alumnoNombre => $registros): ?>
                        <?php foreach ($registros as $r): ?>
                        <tr>
                            <td class="nombre-alumno"><?= htmlspecialchars($alumnoNombre) ?></td>
                            <td><?= date('d/m/Y', strtotime($r['fecha'])) ?></td>
                            <td><?= htmlspecialchars($r['hora_llegada']) ?></td>
                            <td>
                                <?php if ((int) $r['justificado'] === 1): ?>
                                    <span class="badge-just badge-si">Sí</span>
                                <?php else: ?>
                                    <span class="badge-just badge-no">No</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>

    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>
            Este es un mensaje automático del <strong>Sistema SAAT</strong>.<br>
            C.E.I.A. "Juanita Zúñiga Fuentes" · Parral · <?= date('Y') ?><br>
            Desarrollado por Daniel Scarlazzetta<br>
            No responder a este correo.
        </p>
    </div>

</div>
</body>
</html>