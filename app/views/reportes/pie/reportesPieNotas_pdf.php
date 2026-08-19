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

    table.detalle thead th.tr { text-align: right; }

    .prom-aprobado { color: #15803d; font-weight: bold; }
    .prom-reprobado { color: #b91c1c; font-weight: bold; }
    .prom-sin { color: #94a3b8; }
    .tr { text-align: right; }

    /* Overrides sobre el CSS común */
    .info-box { margin-bottom: 12px; }
    .curso-titulo { margin-top: 10px; }
</style>
</head>
<body>

<?php pdfPieHeader('Notas Alumnos PIE — ' . $anioNombre); ?>

<div class="info-box">
    <strong>Año académico:</strong> <?= htmlspecialchars($anioNombre) ?> &nbsp;·&nbsp;
    <strong>Curso:</strong> <?= htmlspecialchars($tituloFiltro) ?> &nbsp;·&nbsp;
    <strong>Total alumnos PIE:</strong> <?= $resumenGlobal['total_pie'] ?> &nbsp;·&nbsp;
    <strong>Generado:</strong> <?= date('d/m/Y H:i') ?>
</div>

<?php if (empty($reporteNotas)): ?>
    <p style="color:#94a3b8; font-size:9px;">No hay alumnos PIE con notas registradas.</p>
<?php else: ?>
    <?php foreach ($reporteNotas as $cursoNombreLoop => $alumnos): ?>
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
                    <th class="tc">N° notas</th>
                    <th class="tr">Promedio anual</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $al):
                    $prom = $al['promedio'];
                    if ($prom === null) {
                        $cls  = 'prom-sin';
                        $txt  = 'Sin notas';
                    } elseif ($prom >= 4.0) {
                        $cls  = 'prom-aprobado';
                        $txt  = number_format($prom, 1);
                    } else {
                        $cls  = 'prom-reprobado';
                        $txt  = number_format($prom, 1);
                    }
                ?>
                <tr>
                    <td><?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre']) ?></td>
                    <td style="font-family:monospace; color:#64748b; font-size:7.5px"><?= htmlspecialchars($al['run']) ?></td>
                    <td class="tc" style="color:#475569">
                        <?= $al['sexo'] === 'F' ? 'F' : ($al['sexo'] === 'M' ? 'M' : '—') ?>
                    </td>
                    <td class="tc" style="color:#64748b"><?= $al['total_notas'] > 0 ? $al['total_notas'] : '—' ?></td>
                    <td class="tr <?= $cls ?>"><?= $txt ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endforeach ?>
<?php endif ?>

<?php pdfPieFooter(); ?>
</body>
</html>