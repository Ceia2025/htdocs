<?php
// ------------------------------------------------------------

// Autor: Felipe Gutierrez Alfaro
// Fecha de creación: 2024-11-01
// Versión: 1.0
// 
// Copyright (c) 2024 Felipe Gutierrez Alfaro. Todos los derechos reservados.
// 
// Este código fuente está sujeto a derechos de autor y ha sido facilitado 
// de manera gratuita y de por vida a la Escuela Juanita Zuñiga Fuentes CEIA PARRAL.
// 
// Restricciones y condiciones:
// - Este código NO puede ser vendido, ni redistribuido.
// - Solo la Escuela Juanita Zuñiga Fuentes tiene derecho a usar este código sin costo y modificarlo a su gusto.
// - No se otorga ninguna garantía implícita o explícita. Uso bajo su propia responsabilidad.
// - El establecimiento puede utilizar imágenes a su gusto y asignar el nombre que estime conveniente al sistema.
// 
// Contacto: 
// Email: felipe.gutierrez.alfaro@gmail.com
?>
<?php
include_once '../../includes/conexion.php';


// Abrir la conexión a la base de datos
$database = new Connection();
$db = $database->open();

if (!$db) {
    echo "<p class='text-danger text-center'>Error: No se pudo establecer la conexión a la base de datos.</p>";
    exit;
}

// Parámetros para el informe
$run = isset($_GET['run']) ? $_GET['run'] : ''; // Reemplazar '22111684' con el valor predeterminado si GET está vacío

// Consulta para obtener la información del alumno
$consulta_alumno = "SELECT run, nombres, apaterno, amaterno, descgrado, codtipoense FROM alum WHERE run = :run";
$stmt_alumno = $db->prepare($consulta_alumno);
$stmt_alumno->bindParam(':run', $run, PDO::PARAM_STR);
$stmt_alumno->execute();
$alumno = $stmt_alumno->fetch(PDO::FETCH_ASSOC);

// Verificar si el alumno existe
if (!$alumno) {
    echo "<p class='text-center text-danger'>No se encontró información para el RUN especificado.</p>";
    $database->close();
    exit;
}

// Consulta para obtener las notas del alumno
$consulta_notas = "SELECT asignaturas.nombre AS asignatura, num_nota, nota, semestre 
                   FROM alum_notas 
                   JOIN asignaturas ON alum_notas.asignaturas_id = asignaturas.id 
                   WHERE alum_notas.run_alum_nota = :run 
                   ORDER BY asignatura, semestre, num_nota";
$stmt_notas = $db->prepare($consulta_notas);
$stmt_notas->bindParam(':run', $run, PDO::PARAM_STR);
$stmt_notas->execute();

// Organizar las notas por asignatura y semestre
$notas = [];
while ($fila = $stmt_notas->fetch(PDO::FETCH_ASSOC)) {
    $notas[$fila['asignatura']][$fila['semestre']][] = $fila['nota'];
}

// Fecha actual y otros datos fijos
$fecha_informe = date("d/m/Y");
$nombre_profesora_jefe = "Betsabe Gonzalez Bustos";
$dias_trabajados = 95;
$dias_inasistencia = 53;
$porcentaje_asistencia = round((($dias_trabajados - $dias_inasistencia) / $dias_trabajados) * 100, 1);

// Mostrar el informe
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Notas</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table thead th { vertical-align: middle; }
        .table td, .table th { text-align: center; }
        @media print {
            .btn, .navbar, .alert { display: none !important; }
            #printable, #printable * { visibility: visible; }
            #printable { position: absolute; top: 0; left: 0; }
        }
    </style>
</head>
<body>

<div class="container mt-5" id="printable">
    <h1 class="text-center">Informe de Notas</h1>
    <p class="text-center">Primer Semestre Año Escolar 2024</p>
    <p class="text-center">CEIA Juanita Zúñiga Fuentes</p>

    <div class="mb-4">
        <p><strong>Estudiante:</strong> <?= $alumno['nombres'] . ' ' . $alumno['apaterno'] . ' ' . $alumno['amaterno'] ?></p>
        <p><strong>Curso:</strong> <?= $alumno['descgrado'] ?></p>
        <p><strong>Profesora Jefe:</strong> <?= $nombre_profesora_jefe ?></p>
    </div>

    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th rowspan="2">Asignatura</th>
                <th colspan="6">Primer Semestre</th>
                <th>Prom. 1° Sem.</th>
                <th colspan="6">Segundo Semestre</th>
                <th>Prom. 2° Sem.</th>
                <th rowspan="2">Promedio Final</th>
            </tr>
            <tr>
                <?php for ($i = 1; $i <= 6; $i++) { echo "<th>Nota $i</th>"; } ?>
                <th></th>
                <?php for ($i = 1; $i <= 6; $i++) { echo "<th>Nota $i</th>"; } ?>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($notas as $asignatura => $semestres): ?>
                <tr>
                    <td><?= $asignatura ?></td>
                    
                    <?php
                    // Mostrar notas del primer semestre
                    $notas_1er = isset($semestres[1]) ? $semestres[1] : [];
                    $prom_1er = count($notas_1er) > 0 ? array_sum($notas_1er) / count($notas_1er) : "Pendiente";
                    for ($i = 0; $i < 6; $i++) {
                        echo "<td>" . ($notas_1er[$i] ?? '') . "</td>";
                    }
                    echo "<td>" . (is_numeric($prom_1er) ? number_format($prom_1er, 1) : $prom_1er) . "</td>";

                    // Mostrar notas del segundo semestre
                    $notas_2do = isset($semestres[2]) ? $semestres[2] : [];
                    $prom_2do = count($notas_2do) > 0 ? array_sum($notas_2do) / count($notas_2do) : "Pendiente";
                    for ($i = 0; $i < 6; $i++) {
                        echo "<td>" . ($notas_2do[$i] ?? '') . "</td>";
                    }
                    echo "<td>" . (is_numeric($prom_2do) ? number_format($prom_2do, 1) : $prom_2do) . "</td>";

                    // Promedio final
                    $promedio_final = is_numeric($prom_1er) && is_numeric($prom_2do) 
                                      ? number_format(($prom_1er + $prom_2do) / 2, 1)
                                      : "Pendiente";
                    ?>
                    <td><?= $promedio_final ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mt-4">
        <p><strong>Días trabajados:</strong> <?= $dias_trabajados ?></p>
        <p><strong>Días de Inasistencia:</strong> <?= $dias_inasistencia ?></p>
        <p><strong>% Asistencia:</strong> <?= $porcentaje_asistencia ?>%</p>
    </div>

    <p class="text-center"><strong>Fecha del Informe:</strong> <?= $fecha_informe ?></p>
    <p class="text-center"><strong>Betsabe Gonzalez Bustos</strong><br>Profesora Jefe</p>
</div>

<div class="text-center mb-4">
    <button onclick="window.print()" class="btn btn-primary">Imprimir Informe</button>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Cerrar conexión
$database->close();
?>
