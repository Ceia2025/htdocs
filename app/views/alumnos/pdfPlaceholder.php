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
$edadActualTexto = 'No registrada';
if (!empty($alumno['fechanac'])) {
    try {
        $nac = new DateTime($alumno['fechanac']);
        $hoy = new DateTime();
        $diff = $hoy->diff($nac);
        $edadActualTexto = $diff->y . ' años, ' . $diff->m . ' meses y ' . $diff->d . ' días';

        // Si es menor, agregar cuánto falta para los 18
        if ($diff->y < 18) {
            $cumple18 = clone $nac;
            $cumple18->modify('+18 years');
            $falta = $hoy->diff($cumple18);

        }
    } catch (Exception $e) {
        $edadActualTexto = 'No registrada';
    }
}

$escolarData = isset($escolar) && is_array($escolar) ? $escolar : [];
$familiarData = isset($antecedentes) && is_array($antecedentes) ? $antecedentes : [];

$logoPath = __DIR__ . '/../../public/img/LOGO CEIA.jpg';
$logoExists = file_exists($logoPath);

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
            margin: 0;
            padding: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 18px 20px;
            background: #fff;
        }

        /* ── HEADER ── */
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
            letter-spacing: 0.5px;
        }

        .inst-sub {
            font-size: 10px;
            color: #475569;
            margin-top: 2px;
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

        /* ── INFO RUN/NOMBRE TOP ── */
        .info-top {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 5px 10px;
            margin-bottom: 8px;
            font-size: 10px;
        }

        .info-top span {
            margin-right: 20px;
        }

        /* ── SECCIONES ── */
        .section {
            margin-top: 8px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #004b8d;
            color: #fff;
            padding: 4px 10px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .section-body {
            padding: 6px 10px 8px 10px;
        }

        /* ── GRID DE 3 COLUMNAS para datos del alumno ── */
        .grid-3 {
            width: 100%;
            border-collapse: collapse;
        }

        .grid-3 td {
            width: 33.33%;
            vertical-align: top;
            padding: 2px 6px 2px 0;
        }

        .grid-3 td:not(:last-child) {
            border-right: 1px solid #e2e8f0;
            padding-right: 10px;
            padding-left: 0;
        }

        .grid-3 td:not(:first-child) {
            padding-left: 10px;
        }

        /* ── GRID DE 2 COLUMNAS ── */
        .grid-2 {
            width: 100%;
            border-collapse: collapse;
        }

        .grid-2 td {
            width: 50%;
            vertical-align: top;
            padding: 2px 6px 2px 0;
        }

        .grid-2 td:not(:last-child) {
            border-right: 1px solid #e2e8f0;
            padding-right: 10px;
        }

        .grid-2 td:not(:first-child) {
            padding-left: 10px;
        }

        /* ── FILA DE DATO ── */
        .dato {
            margin-bottom: 3px;
            line-height: 1.5;
        }

        .lbl {
            font-weight: bold;
            color: #004b8d;
            display: inline;
        }

        /* ── TABLA SIMPLE (contactos) ── */
        .simple-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            font-size: 9.5px;
        }

        .simple-table th,
        .simple-table td {
            border: 1px solid #cbd5e1;
            padding: 3px 6px;
        }

        .simple-table th {
            background: #dbeafe;
            color: #1e3a5f;
            font-weight: bold;
        }

        .simple-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        /* ── BADGE estado ── */
        .badge {
            display: inline-block;
            padding: 1px 7px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-activo {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .badge-retirado {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 12px;
            padding-top: 6px;
            border-top: 1px solid #e2e8f0;
            font-size: 8.5px;
            color: #94a3b8;
            text-align: right;
        }

        /* ── DIVISOR DE SUBSECCIÓN ── */
        .sub-titulo {
            font-weight: bold;
            color: #004b8d;
            font-size: 9.5px;
            margin-bottom: 4px;
            margin-top: 2px;
            border-bottom: 1px solid #dbeafe;
            padding-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
    </style>
</head>

<body>

    <!-- ══ ENCABEZADO ══ -->
    <div class="header">
        <div class="header-left">
            <?php if ($logoExists): ?>
                <img src="http://localhost:8080/app/public/img/LOGO%20CEIA.jpg" style="width:90px; height:auto;" />
            <?php else: ?>
                <strong style="color:#004b8d;">CEIA Parral</strong>
            <?php endif; ?>
        </div>
        <div class="header-right">
            <div class="inst-nombre">Centro de Educación Integrada de Adultos</div>
            <div class="inst-sub">"Juanita Zúñiga Fuentes" – Parral</div>
            <div class="ficha-titulo">Ficha de Matrícula <?= $anioFicha ?> / C.E.I.A. Parral</div>
        </div>
    </div>

    <!-- ══ INFO RÁPIDA ══ -->
    <div class="info-top">
        <span><strong>RUN:</strong> <?= v($alumno, 'run') . '-' . v($alumno, 'codver', '') ?></span>
        <span><strong>Alumno:</strong>
            <?= v($alumno, 'nombre') . ' ' . v($alumno, 'apepat') . ' ' . v($alumno, 'apemat') ?>
        </span>
        <span><strong>Estado:</strong>
            <?php if (!empty($alumno['deleted_at'])): ?>
                <span class="badge badge-retirado">Retirado</span>
            <?php else: ?>
                <span class="badge badge-activo">Activo</span>
            <?php endif ?>
        </span>
    </div>

    <!-- ══ 1. DATOS DEL ALUMNO ══ -->
    <div class="section">
        <div class="section-title">1. Antecedentes del Alumno</div>
        <div class="section-body">
            <table class="grid-3">
                <tr>
                    <!-- Col 1: Identificación -->
                    <td>
                        <div class="sub-titulo">Identificación</div>
                        <div class="dato"><span class="lbl">RUN:</span>
                            <?= v($alumno, 'run') . '-' . v($alumno, 'codver', '') ?></div>
                        <div class="dato"><span class="lbl">Nombre:</span> <?= v($alumno, 'nombre') ?></div>
                        <div class="dato"><span class="lbl">Ap. Paterno:</span> <?= v($alumno, 'apepat') ?></div>
                        <div class="dato"><span class="lbl">Ap. Materno:</span> <?= v($alumno, 'apemat') ?></div>
                        <div class="dato"><span class="lbl">Fecha Nac.:</span>
                            <?= !empty($alumno['fechanac']) ? (new DateTime($alumno['fechanac']))->format('d/m/Y') : 'No registrada' ?>
                        </div>
                        <div class="dato">
                            <span class="lbl">Edad (30/06):</span>
                            <?= $edadAl30Junio !== null ? $edadAl30Junio . ' años' : 'No registrada' ?>
                        </div>
                        <div class="dato">
                            <span class="lbl">Edad actual:</span>
                            <?= $edadActualTexto ?>
                        </div>
                        <div class="dato">
                            <span class="lbl">Sexo:</span> <?= v($alumno, 'sexo') ?>
                        </div>
                        <div class="dato">
                            <span class="lbl">Nacionalidad:</span>
                            <?= v($alumno, 'nacionalidades') ?>
                        </div>
                        <div class="dato">
                            <span class="lbl">Etnia:</span>
                            <?= v($alumno, 'cod_etnia') ?>
                        </div>
                    </td>

                    <!-- Col 2: Contacto -->
                    <td>
                        <div class="sub-titulo">Contacto</div>
                        <div class="dato"><span class="lbl">Teléfono:</span> <?= v($alumno, 'telefono') ?></div>
                        <div class="dato"><span class="lbl">Celular:</span> <?= v($alumno, 'celular') ?></div>
                        <div class="dato"><span class="lbl">Email:</span> <?= v($alumno, 'email') ?></div>
                        <div class="dato"><span class="lbl">Región:</span> <?= v($alumno, 'region') ?></div>
                        <div class="dato"><span class="lbl">Ciudad:</span> <?= v($alumno, 'ciudad') ?></div>
                        <div class="dato"><span class="lbl">Dirección:</span> <?= v($alumno, 'direccion') ?></div>
                    </td>

                    <!-- Col 3: Estado -->
                    <td>
                        <div class="sub-titulo">Estado en el Sistema</div>
                        <div class="dato"><span class="lbl">Incorporación:</span> <?= v($alumno, 'created_at') ?></div>
                        <div class="dato"><span class="lbl">Fecha Retiro:</span>
                            <?= v($alumno, 'deleted_at', 'No registra') ?></div>
                        <div class="dato"><span class="lbl">Mayor de Edad:</span> <?= v($alumno, 'mayoredad', 'No') ?>
                        </div>
                        <div class="dato"><span class="lbl">N° Hijos:</span> <?= v($alumno, 'numerohijos', '0') ?></div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ══ 2. CONTACTOS DE EMERGENCIA ══ -->
    <div class="section">
        <div class="section-title">2. Contactos de Emergencia</div>
        <div class="section-body">
            <?php if (!empty($contactos)): ?>
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Relación</th>
                            <th>Dirección</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contactos as $c): ?>
                            <tr>
                                <td><?= v($c, 'nombre_contacto') ?></td>
                                <td><?= v($c, 'telefono') ?></td>
                                <td><?= v($c, 'relacion') ?></td>
                                <td><?= v($c, 'direccion') ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color:#94a3b8; font-style:italic;">No hay contactos de emergencia registrados.</p>
            <?php endif ?>
        </div>
    </div>

    <!-- ══ 3. ANTECEDENTES FAMILIARES ══ -->
    <div class="section">
        <div class="section-title">3. Antecedentes Familiares</div>
        <div class="section-body">
            <?php if (!empty($familiarData)): ?>
                <table class="grid-2">
                    <tr>
                        <td>
                            <div class="sub-titulo">Padre</div>
                            <div class="dato"><span class="lbl">Escolaridad:</span> <?= v($familiarData, 'padre') ?></div>
                            <div class="dato"><span class="lbl">Nivel / Ciclo:</span>
                                <?= v($familiarData, 'nivel_ciclo_p') ?></div>
                        </td>
                        <td>
                            <div class="sub-titulo">Madre</div>
                            <div class="dato"><span class="lbl">Escolaridad:</span> <?= v($familiarData, 'madre') ?></div>
                            <div class="dato"><span class="lbl">Nivel / Ciclo:</span>
                                <?= v($familiarData, 'nivel_ciclo_m') ?></div>
                        </td>
                    </tr>
                </table>
            <?php else: ?>
                <p style="color:#94a3b8; font-style:italic;">No hay antecedentes familiares registrados.</p>
            <?php endif ?>
        </div>
    </div>

    <!-- ══ 4. ANTECEDENTE ESCOLAR ══ -->
    <div class="section">
        <div class="section-title">4. Antecedentes Escolares</div>
        <div class="section-body">
            <?php if (!empty($escolarData)): ?>
                <table class="grid-2">
                    <tr>
                        <!-- Col izq: datos generales -->
                        <td>
                            <div class="sub-titulo">Datos Generales</div>
                            <div class="dato"><span class="lbl">Procedencia:</span>
                                <?= v($escolarData, 'procedencia_colegio') ?></div>
                            <div class="dato"><span class="lbl">Comuna:</span> <?= v($escolarData, 'comuna') ?></div>
                            <div class="dato"><span class="lbl">Último curso:</span> <?= v($escolarData, 'ultimo_curso') ?>
                            </div>
                            <div class="dato"><span class="lbl">Último año:</span>
                                <?= v($escolarData, 'ultimo_anio_cursado') ?></div>
                            <div class="dato"><span class="lbl">Cursos repetidos:</span>
                                <?= v($escolarData, 'cursos_repetidos', '0') ?></div>
                            <div class="dato"><span class="lbl">Eval. psicológica:</span>
                                <?= v($escolarData, 'eva_psico', 'No registrada') ?></div>
                            <div class="dato"><span class="lbl">Info. salud:</span>
                                <?= v($escolarData, 'info_salud', 'No registrada') ?></div>
                        </td>

                        <!-- Col der: condiciones -->
                        <td>
                            <div class="sub-titulo">Condiciones y Programas</div>
                            <div class="dato"><span class="lbl">Pertenece al 20%:</span>
                                <?= siNo($escolarData['pertenece_20'] ?? 0) ?></div>
                            <div class="dato"><span class="lbl">Informe 20%:</span>
                                <?= siNo($escolarData['informe_20'] ?? 0) ?></div>
                            <div class="dato"><span class="lbl">Embarazo:</span>
                                <?= ($escolarData['embarazo'] ?? 0)
                                    ? 'Sí (' . v($escolarData, 'semanas', '-') . ' semanas)'
                                    : 'No' ?>
                            </div>
                            <div class="dato"><span class="lbl">Prob. aprendizaje:</span>
                                <?= v($escolarData, 'prob_apren') ?></div>
                            <div class="dato"><span class="lbl">PIE:</span> <?= v($escolarData, 'pie') ?></div>
                            <div class="dato"><span class="lbl">Chile Solidario:</span>
                                <?= ($escolarData['chile_solidario'] ?? 0)
                                    ? v($escolarData, 'chile_solidario_cual', 'Sí')
                                    : 'No' ?>
                            </div>
                            <div class="dato"><span class="lbl">Grupo Fonasa:</span>
                                <?= v($escolarData, 'grupo_fonasa', 'No registra') ?></div>
                            <div class="dato"><span class="lbl">Isapre:</span>
                                <?= v($escolarData, 'isapre', 'No registra') ?>
                            </div>
                            <div class="dato"><span class="lbl">Seguro salud:</span>
                                <?= v($escolarData, 'seguro_salud', 'No registra') ?></div>
                        </td>
                    </tr>
                </table>
            <?php else: ?>
                <p style="color:#94a3b8; font-style:italic;">No hay antecedente escolar registrado.</p>
            <?php endif ?>
        </div>
    </div>

    <!-- ══ FOOTER ══ -->
    <div class="footer">
        Documento generado automáticamente el <?= date('d/m/Y') ?> — Sistema SAAT · C.E.I.A. Parral</br>
        <p>Desarrollado por <span> Daniel Scarlazzetta</span></p>
    </div>

</body>

</html>