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
$ordenar_por = isset($_GET['ordenar_por']) ? $_GET['ordenar_por'] : ''; // Nuevo parámetro para ordenar

// Función para calcular la edad desde una fecha de nacimiento
// Función para calcular la edad en años, meses y días desde una fecha de nacimiento
function calcularEdad($fechaNacimiento) {
    $fechaNacimiento = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    $diferencia = $hoy->diff($fechaNacimiento);

    // Retornar la edad en formato "X años, Y meses, Z días"
    return "{$diferencia->y} años, {$diferencia->m} meses, {$diferencia->d} días";
}
// Construir la consulta SQL para obtener alumnos con hijos y el nombre del curso
$sql = "SELECT a.run, a.nombres, a.apaterno, a.amaterno, a.fnac, c.nombre AS curso, af.nhijos 
        FROM alum a
        INNER JOIN alum_familia af ON a.run = af.run_alum_familia
        INNER JOIN cursos c ON a.codtipoense = c.codtipoense AND a.codgrado = c.codgrado
        WHERE af.nhijos > 0";

// Aplicar filtro de edad
$sql .= " AND (YEAR(CURDATE()) - YEAR(a.fnac)) BETWEEN :edad_inicial AND :edad_final";

// Aplicar filtro de curso si se selecciona un curso
if (!empty($curso)) {
    $sql .= " AND c.nombre = :curso";
}

// Aplicar orden si se selecciona
if (!empty($ordenar_por)) {
    $sql .= " ORDER BY ";

    // Definir qué columna usar para la ordenación
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
        case 'nhijos':
            $sql .= "af.nhijos";
            break;
        case 'curso':
            $sql .= "c.nombre";
            break;
        default:
            $sql .= "a.run"; // Ordenar por run por defecto si no se selecciona
            break;
    }
}

// Preparar y ejecutar la consulta
$stmt = $db->prepare($sql);

// Vincular los parámetros
$stmt->bindParam(':edad_inicial', $edad_inicial, PDO::PARAM_INT);
$stmt->bindParam(':edad_final', $edad_final, PDO::PARAM_INT);
if (!empty($curso)) {
    $stmt->bindParam(':curso', $curso);
}

// Ejecutar la consulta
$stmt->execute();
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para llenar el combobox de cursos
$sqlCursos = "SELECT DISTINCT nombre FROM cursos";
$cursosStmt = $db->prepare($sqlCursos);
$cursosStmt->execute();
$cursos = $cursosStmt->fetchAll(PDO::FETCH_ASSOC);

// Cerrar la conexión a la base de datos
$database->close();
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.A.A.T. -> Alumnos Menores y Mayores de Edad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container">
    <p></p>
    <h4 class="page-header mb-4">REPORTE DE ALUMNOS CON HIJOS</h4>

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
                    <option value="">Todos</option>
                    <?php foreach ($cursos as $cursoOption): ?>
                        <option value="<?php echo $cursoOption['nombre']; ?>" <?php echo $curso === $cursoOption['nombre'] ? 'selected' : ''; ?>>
                            <?php echo $cursoOption['nombre']; ?>
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
                    <option value="nhijos" <?php echo $ordenar_por === 'nhijos' ? 'selected' : ''; ?>>Número de Hijos</option>
                    <option value="curso" <?php echo $ordenar_por === 'curso' ? 'selected' : ''; ?>>Curso</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-secondary no-print align-items-center" onclick="window.print();">Imprimir</button>
            </div>
        </div>
    </form>

    <hr>

    <!-- Tabla con los resultados -->
    <?php if ($alumnos): ?>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Run</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Edad</th>
            <th>Número de Hijos</th>
            <th>Curso</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($alumnos as $alumno): 
    $edad = calcularEdad($alumno['fnac']);
?>
<tr>
    <td><?php echo $alumno['run']; ?></td>
    <td><?php echo $alumno['nombres']; ?></td>
    <td><?php echo $alumno['apaterno']; ?></td>
    <td><?php echo $alumno['amaterno']; ?></td>
    <td><?php echo $edad; ?></td> <!-- Aquí muestra la edad en años, meses y días -->
    <td><?php echo $alumno['nhijos']; ?></td>
    <td><?php echo $alumno['curso']; ?></td>
</tr>
<?php endforeach; ?>
        </tbody>
    </table>
    <p></p>

    <!-- Pie de página -->
    <footer class="bg-primary text-white text-center py-3">
        <div class="container">
            <p>&copy; 2024 Escuela Juanita Zuñiga Fuentes C.E.I.A. Parral</p>
        </div>
    </footer>
    <?php else: ?>
        <div class="alert alert-warning">No se encontraron alumnos con hijos que coincidan con los filtros seleccionados.</div>
    <?php endif; ?>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
