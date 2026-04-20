<?php
// app/views/emails/alerta_ausencias.php
// Variables esperadas: $alumnosAusentes (array), $curso (string), $fecha (string), $umbral (int)
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f1f5f9; color: #1e293b; }
    .wrapper { max-width: 600px; margin: 30px auto; background: #fff;
               border-radius: 12px; overflow: hidden;
               box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .header { background: #1e293b; padding: 28px 32px; }
    .header-top { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
    .badge { background: #ef4444; color: #fff; font-size: 11px; font-weight: bold;
             padding: 3px 10px; border-radius: 20px; text-transform: uppercase;
             letter-spacing: 0.5px; }
    .header h1 { color: #fff; font-size: 20px; font-weight: 700; }
    .header p  { color: #94a3b8; font-size: 13px; margin-top: 4px; }

    .body { padding: 28px 32px; }

    .alert-box { background: #fef2f2; border: 1px solid #fecaca;
                 border-left: 4px solid #ef4444;
                 border-radius: 8px; padding: 16px 20px; margin-bottom: 24px; }
    .alert-box p { color: #991b1b; font-size: 14px; line-height: 1.6; }

    .section-title { font-size: 12px; font-weight: 700; text-transform: uppercase;
                     letter-spacing: 0.6px; color: #64748b; margin-bottom: 12px; }

    table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    thead tr { background: #f8fafc; }
    thead th { padding: 10px 14px; text-align: left; font-size: 11px;
               text-transform: uppercase; letter-spacing: 0.4px; color: #64748b;
               border-bottom: 2px solid #e2e8f0; }
    tbody tr { border-bottom: 1px solid #f1f5f9; }
    tbody tr:last-child { border-bottom: none; }
    tbody td { padding: 12px 14px; font-size: 13px; color: #334155; }
    .nombre-alumno { font-weight: 600; color: #1e293b; }
    .ausencias-badge { display: inline-block; background: #fee2e2; color: #991b1b;
                       font-weight: 700; font-size: 12px; padding: 2px 8px;
                       border-radius: 20px; }

    .info-grid { display: grid; grid-template-columns: 1fr 1fr;
                 gap: 12px; margin-bottom: 24px; }
    .info-card { background: #f8fafc; border: 1px solid #e2e8f0;
                 border-radius: 8px; padding: 14px 16px; }
    .info-label { font-size: 11px; color: #94a3b8; text-transform: uppercase;
                  letter-spacing: 0.4px; margin-bottom: 4px; }
    .info-value { font-size: 14px; font-weight: 600; color: #1e293b; }

    .note { background: #fffbeb; border: 1px solid #fde68a;
            border-radius: 8px; padding: 14px 16px; margin-bottom: 24px; }
    .note p { font-size: 13px; color: #92400e; line-height: 1.6; }

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
            <span class="badge">Alerta de Asistencia</span>
        </div>
        <h1>Ausencias Consecutivas Detectadas</h1>
        <p>Sistema SAAT · C.E.I.A. Parral</p>
    </div>

    <!-- BODY -->
    <div class="body">

        <!-- Alerta -->
        <div class="alert-box">
            <p>
                Se han detectado <strong><?= count($alumnosAusentes) ?> alumno(s)</strong>
                con <strong><?= $umbral ?> o más ausencias consecutivas</strong>
                en el curso <strong><?= htmlspecialchars($curso) ?></strong>.
                Se requiere atención inmediata.
            </p>
        </div>

        <!-- Info fecha y curso -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Curso</div>
                <div class="info-value"><?= htmlspecialchars($curso) ?></div>
            </div>
            <div class="info-card">
                <div class="info-label">Fecha de detección</div>
                <div class="info-value"><?= date('d/m/Y', strtotime($fecha)) ?></div>
            </div>
        </div>

        <!-- Tabla de alumnos -->
        <p class="section-title">Alumnos afectados</p>
        <table>
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Curso</th>
                    <th style="text-align:center">Ausencias</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnosAusentes as $al): ?>
                <tr>
                    <td class="nombre-alumno">
                        <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?>
                    </td>
                    <td><?= htmlspecialchars($al['curso']) ?></td>
                    <td style="text-align:center">
                        <span class="ausencias-badge"><?= $al['ausencias'] ?> días</span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Nota -->
        <!--
        <div class="note">
            <p>
                💡 <strong>Acción recomendada:</strong> Contactar al alumno o su apoderado
                para verificar el motivo de las ausencias e informar al equipo de convivencia escolar.
            </p>
        </div>

        -->

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