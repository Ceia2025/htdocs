<?php
// ===================== HELPERS =====================
function v($arr, $key, $default = 'No registrado')
{
    if (!isset($arr[$key]) || $arr[$key] === '' || $arr[$key] === null) {
        return $default;
    }
    return htmlspecialchars($arr[$key]);
}

function siNo($val)
{
    return $val ? 'Sí' : 'No';
}

// Edad
$edadTexto = 'No registrada';
if (!empty($alumno['fechanac'])) {
    try {
        $fn = new DateTime($alumno['fechanac']);
        $hoy = new DateTime();
        $edadTexto = $hoy->diff($fn)->y . ' años';
    } catch (Exception $e) {
        $edadTexto = 'No registrada';
    }
}

// Antecedente escolar: usa la variable que exista
$escolarData = [];
if (isset($escolar) && is_array($escolar)) {
    $escolarData = $escolar;
} elseif (isset($familia) && is_array($familia)) {   // por compatibilidad con tu versión anterior
    $escolarData = $familia;
}

// Antecedentes familiares (puede venir vacío si aún no lo cargas en el controlador)
$familiarData = isset($antecedentes) && is_array($antecedentes) ? $antecedentes : [];

// Ruta absoluta al logo (archivo local para Dompdf)
$logoPath = __DIR__ . '/../../public/img/LOGO CEIA.jpg';
$logoExists = file_exists($logoPath);

// Año de la ficha (si quieres, puedes sacar de created_at)
$anioFicha = !empty($alumno['created_at'])
    ? (new DateTime($alumno['created_at']))->format('Y')
    : date('Y');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ficha del Alumno</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
            margin: 20px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }

        .header-left {
            display: table-cell;
            width: 25%;
            vertical-align: top;
        }

        .header-right {
            display: table-cell;
            width: 75%;
            text-align: center;
            vertical-align: middle;
        }

        .header-logo {
            max-width: 110px;
            max-height: 110px;
        }

        .inst-nombre {
            font-size: 14px;
            font-weight: bold;
            color: #0056A8;
            text-transform: uppercase;
        }

        .inst-sub {
            font-size: 11px;
            margin-top: 3px;
        }

        .ficha-titulo {
            margin-top: 6px;
            padding: 4px 0;
            background: #ffd500;
            color: #004b8d;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        .info-top {
            margin-bottom: 10px;
            font-size: 10px;
        }

        .info-top span {
            display: inline-block;
            margin-right: 12px;
        }

        .section {
            margin-top: 10px;
            border: 1px solid #d0d5dd;
            border-radius: 4px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #004b8d;
            color: #fff;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 11px;
        }

        .section-body {
            padding: 6px 8px 4px 8px;
        }

        .grid-2 {
            width: 100%;
            border-collapse: collapse;
        }

        .grid-2 td {
            width: 50%;
            vertical-align: top;
            padding: 2px 4px;
        }

        .field-label {
            font-weight: bold;
            color: #004b8d;
        }

        .simple-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            font-size: 10px;
        }

        .simple-table th,
        .simple-table td {
            border: 1px solid #cbd5e1;
            padding: 3px 4px;
        }

        .simple-table th {
            background: #e5efff;
            color: #1e293b;
            font-weight: bold;
        }

        .simple-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        .footer {
            margin-top: 14px;
            font-size: 9px;
            text-align: right;
            color: #64748b;
        }
    </style>
</head>

<body>

    <!-- ENCABEZADO -->
    <div class="header">
        <div class="header-left">
            <?php if ($logoExists): ?>
                <img src="http://localhost:8080/app/public/img/LOGO%20CEIA.jpg"
                    style="width: 120px; height:auto; margin-right:20px;" />
                <!--  
                <img src="" alt="Logo CEIA" class="header-logo">
                < ?= $logoPath ?>
                -->
            <?php else: ?>
                <strong>CEIA Parral</strong>
            <?php endif; ?>
        </div>
        <div class="header-right">
            <div class="inst-nombre">Centro de Educación Integrada de Adultos</div>
            <div class="inst-sub">“Juanita Zúñiga Fuentes” – Parral</div>
            <div class="ficha-titulo">Ficha de Matrícula <?= $anioFicha ?> / C.E.I.A. Parral</div>
        </div>
    </div>

    <div class="info-top">
        <span><strong>RUN Alumno:</strong> <?= v($alumno, 'run') . '-' . v($alumno, 'codver', '') ?></span>
        <span><strong>Nombre:</strong>
            <?= v($alumno, 'nombre') . ' ' . v($alumno, 'apepat') . ' ' . v($alumno, 'apemat') ?>
        </span>
    </div>

    <!-- 1. DATOS DEL ALUMNO -->
    <div class="section">
        <div class="section-title">1. Antecedentes del Alumno</div>
        <div class="section-body">
            <table class="grid-2">
                <tr>
                    <td>
                        <div><span class="field-label">RUN:</span>
                            <?= v($alumno, 'run') . '-' . v($alumno, 'codver', '') ?></div>
                        <div><span class="field-label">Nombre completo:</span>
                            <?= v($alumno, 'nombre') . ' ' . v($alumno, 'apepat') . ' ' . v($alumno, 'apemat') ?></div>
                        <div><span class="field-label">Fecha de nacimiento:</span>
                            <?= !empty($alumno['fechanac']) ? (new DateTime($alumno['fechanac']))->format('d/m/Y') : 'No registrada' ?>
                        </div>
                        <div><span class="field-label">Edad:</span>
                            <?= $edadAl30Junio !== null ? $edadAl30Junio . ' años' : 'No registrada' ?>

                        </div>


                        <div><span class="field-label">Sexo:</span> <?= v($alumno, 'sexo') ?></div>
                    </td>
                    <td>
                        <div><span class="field-label">Fecha creación:</span> <?= v($alumno, 'created_at') ?></div>
                        <div><span class="field-label">Fecha retiro:</span>
                            <?= v($alumno, 'deleted_at', 'No registra') ?></div>
                        <div><span class="field-label">Nacionalidad:</span> <?= v($alumno, 'nacionalidades') ?></div>
                        <div><span class="field-label">Región:</span> <?= v($alumno, 'region') ?></div>
                        <div><span class="field-label">Ciudad / Comuna:</span> <?= v($alumno, 'ciudad') ?></div>
                        <div><span class="field-label">Dirección:</span> <?= v($alumno, 'direccion') ?></div>
                        <div><span class="field-label">Etnia / Pueblo Originario:</span> <?= v($alumno, 'cod_etnia') ?>
                        </div>
                        <div><span class="field-label">Teléfono fijo:</span> <?= v($alumno, 'telefono') ?></div>
                        <div><span class="field-label">Celular:</span> <?= v($alumno, 'celular') ?></div>
                        <div><span class="field-label">Correo electrónico:</span> <?= v($alumno, 'email') ?></div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 2. CONTACTOS DE EMERGENCIA -->
    <div class="section">
        <div class="section-title">2. Contactos de Emergencia</div>
        <div class="section-body">
            <?php if (!empty($contactos)): ?>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Relación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contactos as $c): ?>
                            <tr>
                                <td><?= v($c, 'nombre_contacto') ?></td>
                                <td><?= v($c, 'telefono') ?></td>
                                <td><?= v($c, 'direccion') ?></td>
                                <td><?= v($c, 'relacion') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay contactos de emergencia registrados.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- 3. ANTECEDENTES FAMILIARES -->
    <div class="section">
        <div class="section-title">3. Antecedentes Familiares</div>
        <div class="section-body">
            <?php if (!empty($familiarData)): ?>
                <table class="grid-2">
                    <tr>
                        <td>
                            <div><span class="field-label">Escolaridad Padre:</span> <?= v($familiarData, 'padre') ?></div>
                            <div><span class="field-label">Nivel o ciclo padre:</span>
                                <?= v($familiarData, 'nivel_ciclo_p') ?></div>
                        </td>
                        <td>
                            <div><span class="field-label">Escolaridad Madre:</span> <?= v($familiarData, 'madre') ?></div>
                            <div><span class="field-label">Nivel o ciclo madre:</span>
                                <?= v($familiarData, 'nivel_ciclo_m') ?></div>
                        </td>
                    </tr>
                </table>
            <?php else: ?>
                <p>No hay antecedentes familiares registrados.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- 4. ANTECEDENTE ESCOLAR -->
    <div class="section">
        <div class="section-title">4. Antecedentes Escolares del Alumno</div>
        <div class="section-body">
            <?php if (!empty($escolarData)): ?>
                <table class="grid-2">
                    <tr>
                        <td>
                            <div><span class="field-label">Procedencia del establecimiento:</span>
                                <?= v($escolarData, 'procedencia_colegio') ?></div>
                            <div><span class="field-label">Comuna:</span> <?= v($escolarData, 'comuna') ?></div>
                            <div><span class="field-label">Último curso aprobado:</span>
                                <?= v($escolarData, 'ultimo_curso') ?></div>
                            <div><span class="field-label">Último año cursado:</span>
                                <?= v($escolarData, 'ultimo_anio_cursado') ?></div>
                            <div><span class="field-label">Cursos repetidos:</span>
                                <?= v($escolarData, 'cursos_repetidos', '0') ?></div>
                            <div><span class="field-label">Evaluación psicológica:</span>
                                <?= v($escolarData, 'eva_psico', 'No registrada') ?></div>
                            <div><span class="field-label">Información relevante de salud:</span>
                                <?= v($escolarData, 'info_salud', 'No registrada') ?></div>
                        </td>
                        <td>
                            <div><span class="field-label">Pertenece al 20%:</span>
                                <?= siNo($escolarData['pertenece_20'] ?? 0) ?></div>
                            <div><span class="field-label">Informe 20%:</span>
                                <?= siNo($escolarData['informe_20'] ?? 0) ?></div>
                            <div><span class="field-label">Embarazo:</span>
                                <?php
                                $emb = $escolarData['embarazo'] ?? 0;
                                if ($emb) {
                                    echo 'Sí (' . v($escolarData, 'semanas', '-') . ' semanas)';
                                } else {
                                    echo 'No';
                                }
                                ?>
                            </div>
                            <div><span class="field-label">Problemas de aprendizaje:</span>
                                <?= v($escolarData, 'prob_apren') ?></div>
                            <div><span class="field-label">PIE:</span> <?= v($escolarData, 'pie') ?></div>
                            <div><span class="field-label">Chile Solidario:</span>
                                <?php
                                $cs = $escolarData['chile_solidario'] ?? 0;
                                echo $cs
                                    ? v($escolarData, 'chile_solidario_cual', 'Sí')
                                    : 'No';
                                ?>
                            </div>
                            <div><span class="field-label">Grupo Fonasa:</span>
                                <?= v($escolarData, 'grupo_fonasa', 'No registra') ?></div>
                            <div><span class="field-label">Isapre:</span>
                                <?= v($escolarData, 'isapre', 'No registra') ?></div>
                            <div><span class="field-label">Seguro de salud:</span>
                                <?= v($escolarData, 'seguro_salud', 'No registra') ?></div>
                        </td>
                    </tr>
                </table>
            <?php else: ?>
                <p>No hay antecedente escolar registrado para este alumno.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">
        Ficha generada automáticamente el <?= date('d/m/Y H:i') ?>.
    </div>

</body>

</html>