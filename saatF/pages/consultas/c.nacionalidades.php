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

session_start();
include '../../includes/navbar.php';
include_once '../../includes/conexion.php';
include '../../includes/config.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Abrir la conexión a la base de datos
$database = new Connection();
$db = $database->open();

// Parámetros de filtro
$edad_inicial = isset($_GET['edad_inicial']) ? (int)$_GET['edad_inicial'] : 0;
$edad_final = isset($_GET['edad_final']) ? (int)$_GET['edad_final'] : 100;
$curso = isset($_GET['curso']) ? $_GET['curso'] : '';
$nacionalidad = isset($_GET['nacionalidad']) ? (int)$_GET['nacionalidad'] : 0;
$ordenar_por = isset($_GET['ordenar_por']) ? $_GET['ordenar_por'] : '';

// Función para calcular la edad en años, meses y días desde una fecha de nacimiento
function calcularEdad($fechaNacimiento) {
    $fechaNacimiento = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    $diferencia = $hoy->diff($fechaNacimiento);
    return "{$diferencia->y} años, {$diferencia->m} meses, {$diferencia->d} días";
}

// Construir la consulta SQL para obtener alumnos, curso y nacionalidad
$sql = "SELECT a.run, a.nombres, a.apaterno, a.amaterno, a.fnac, c.nombre AS curso, n.pais_nac, n.gentilicio_nac 
        FROM alum a
        INNER JOIN cursos c ON a.codtipoense = c.codtipoense AND a.codgrado = c.codgrado
        INNER JOIN nacionalidad n ON a.nacionalidad = n.idnacionalidad
        WHERE (YEAR(CURDATE()) - YEAR(a.fnac)) BETWEEN :edad_inicial AND :edad_final";

// Aplicar filtro de curso si se selecciona un curso
if (!empty($curso) && $curso !== 'Todos') {
    $sql .= " AND c.nombre = :curso";
}

// Aplicar filtro de nacionalidad si se selecciona
if (!empty($nacionalidad)) {
    $sql .= " AND a.nacionalidad = :nacionalidad";
}

// Aplicar orden si se selecciona
if (!empty($ordenar_por)) {
    $sql .= " ORDER BY ";
    switch ($ordenar_por) {
        case 'run':
            $sql .= "a.run";
            break;
        case 'nombres':
            $sql .= "a.nombres";
            break;
        case 'apaterno':
            $sql .= "a.apaterno";
            break;
        case 'amaterno':
            $sql .= "a.amaterno";
            break;
        case 'edad':
            $sql .= "(YEAR(CURDATE()) - YEAR(a.fnac))";
            break;
        case 'pais_nac':
            $sql .= "n.pais_nac";
            break;
        default:
            $sql .= "a.run"; // Ordenar por run por defecto
            break;
    }
}

// Preparar y ejecutar la consulta
$stmt = $db->prepare($sql);

// Vincular los parámetros
$stmt->bindParam(':edad_inicial', $edad_inicial, PDO::PARAM_INT);
$stmt->bindParam(':edad_final', $edad_final, PDO::PARAM_INT);
if (!empty($curso) && $curso !== 'Todos') {
    $stmt->bindParam(':curso', $curso);
}
if (!empty($nacionalidad)) {
    $stmt->bindParam(':nacionalidad', $nacionalidad, PDO::PARAM_INT);
}

// Ejecutar la consulta
$stmt->execute();
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para llenar el combobox de cursos
$sqlCursos = "SELECT nombre, COUNT(a.run) AS cantidad FROM cursos c
              LEFT JOIN alum a ON c.codtipoense = a.codtipoense AND c.codgrado = a.codgrado
              GROUP BY c.nombre";
$cursosStmt = $db->prepare($sqlCursos);
$cursosStmt->execute();
$cursos = $cursosStmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para llenar el combobox de nacionalidades
$sqlNacionalidades = "SELECT idnacionalidad, pais_nac FROM nacionalidad";
$nacionalidadesStmt = $db->prepare($sqlNacionalidades);
$nacionalidadesStmt->execute();
$nacionalidades = $nacionalidadesStmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para contar alumnos por nacionalidad
$sqlContarNacionalidades = "SELECT n.pais_nac, COUNT(a.run) AS cantidad FROM alum a
                             INNER JOIN nacionalidad n ON a.nacionalidad = n.idnacionalidad
                             GROUP BY n.pais_nac
                             ORDER BY (n.pais_nac = 'Chile') DESC, cantidad DESC"; // Chile primero y luego por cantidad
$contarNacionalidadesStmt = $db->prepare($sqlContarNacionalidades);
$contarNacionalidadesStmt->execute();
$nacionalidadesContadas = $contarNacionalidadesStmt->fetchAll(PDO::FETCH_ASSOC);

// Sumar el total de alumnos de nacionalidades distintas a Chile
$totalDistintosChile = 0;
foreach ($nacionalidadesContadas as $nacionalidadContada) {
    if ($nacionalidadContada['pais_nac'] !== 'Chile') {
        $totalDistintosChile += $nacionalidadContada['cantidad'];
    }
}

// Cerrar la conexión a la base de datos
$database->close();
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Alumnos por Nacionalidades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container">
    <p></p>
    <h4 class="page-header mb-4">REPORTE DE ALUMNOS POR NACIONALIDADES</h4>

    <!-- Formulario para ingresar los parámetros de filtro -->
    <form method="GET" action="">
        <div class="row">
            <div class="col-md-2">
                <label for="edad_inicial" class="form-label">Edad Inicial</label>
                <input type="number" name="edad_inicial" id="edad_inicial" class="form-control" value="<?php echo $edad_inicial; ?>" required>
            </div>
            <div class="col-md-2">
                <label for="edad_final" class="form-label">Edad Final</label>
                <input type="number" name="edad_final" id="edad_final" class="form-control" value="<?php echo $edad_final; ?>" required>
            </div>
            <div class="col-md-2">
                <label for="curso" class="form-label">Curso</label>
                <select name="curso" id="curso" class="form-control">
                    <option value="Todos">Todos</option>
                    <?php foreach ($cursos as $cursoOption): ?>
                        <option value="<?php echo $cursoOption['nombre']; ?>" <?php echo $curso === $cursoOption['nombre'] ? 'selected' : ''; ?>>
                            <?php echo $cursoOption['nombre'] . " (" . $cursoOption['cantidad'] . ")"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="nacionalidad" class="form-label">Nacionalidad</label>
                <select name="nacionalidad" id="nacionalidad" class="form-control">
                    <option value="">Todas</option>
                    <?php foreach ($nacionalidades as $nacOption): ?>
                        <option value="<?php echo $nacOption['idnacionalidad']; ?>" <?php echo $nacionalidad == $nacOption['idnacionalidad'] ? 'selected' : ''; ?>>
                            <?php echo $nacOption['pais_nac']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="ordenar_por" class="form-label">Ordenar por</label>
                <select name="ordenar_por" id="ordenar_por" class="form-control">
                    <option value="">Seleccionar...</option>
                    <option value="run" <?php echo $ordenar_por === 'run' ? 'selected' : ''; ?>>Run</option>
                    <option value="nombres" <?php echo $ordenar_por === 'nombres' ? 'selected' : ''; ?>>Nombre</option>
                    <option value="apaterno" <?php echo $ordenar_por === 'apaterno' ? 'selected' : ''; ?>>Apellido Paterno</option>
                    <option value="amaterno" <?php echo $ordenar_por === 'amaterno' ? 'selected' : ''; ?>>Apellido Materno</option>
                    <option value="edad" <?php echo $ordenar_por === 'edad' ? 'selected' : ''; ?>>Edad</option>
                    <option value="pais_nac" <?php echo $ordenar_por === 'pais_nac' ? 'selected' : ''; ?>>Nacionalidad</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
              
                <button type="button" class="btn btn-secondary no-print align-items-center" onclick="window.print();">Imprimir</button>

            </div>
        </div>
    </form>

    <!-- Tabla de nacionalidades y cantidad de alumnos -->
    <h5 class="mt-4">Cantidad de Alumnos por Nacionalidad:</h5>
    <table class="table table-hover table-striped mt-2">
        <thead>
        <tr>
            <th>Nacionalidad</th>
            <th>Cantidad de Alumnos</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($nacionalidadesContadas as $nacionalidadContada): ?>
            <tr>
                <td><?php echo $nacionalidadContada['pais_nac']; ?></td>
                <td><?php echo $nacionalidadContada['cantidad']; ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td><strong>Total (distinto a Chile):</strong></td>
            <td><strong><?php echo $totalDistintosChile; ?></strong></td>
        </tr>
        </tbody>
    </table>

    <!-- Tabla de resultados -->
    <h5 class="mt-4">Resultados de Alumnos:</h5>
    <table class="table table-hover table-striped mt-4">
        <thead>
        <tr>
            <th>RUN</th>
            <th>Nombres</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Fecha de Nacimiento</th>
            <th>Curso</th>
            <th>Nacionalidad</th>
            <th>Edad</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($alumnos as $alumno): ?>
            <tr>
                <td><?php echo $alumno['run']; ?></td>
                <td><?php echo $alumno['nombres']; ?></td>
                <td><?php echo $alumno['apaterno']; ?></td>
                <td><?php echo $alumno['amaterno']; ?></td>
                <td><?php echo date('d-m-Y', strtotime($alumno['fnac'])); ?></td>
                <td><?php echo $alumno['curso']; ?></td>
                <td><?php echo $alumno['pais_nac']; ?></td>
                <td><?php echo calcularEdad($alumno['fnac']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
