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

// Consultar los cursos
$sqlCursos = "SELECT DISTINCT nombre FROM cursos";
$stmtCursos = $db->prepare($sqlCursos);
$stmtCursos->execute();
$cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);

// Parámetros de filtro
$edad_menor = isset($_GET['edad_menor']) ? (int)$_GET['edad_menor'] : 17;
$edad_mayor = isset($_GET['edad_mayor']) ? (int)$_GET['edad_mayor'] : 18;
$mostrar = isset($_GET['mostrar']) ? $_GET['mostrar'] : 'todos'; // Combo nuevo
$curso = isset($_GET['curso']) ? $_GET['curso'] : ''; // Filtro opcional para curso

// Función para calcular la edad exacta
function calcularEdad($fechaNacimiento) {
    $fechaNacimiento = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    $edad = $hoy->diff($fechaNacimiento);
    
    // Formatear la edad en años, meses y días
    return "{$edad->y} años, {$edad->m} meses, {$edad->d} días";
}

// Consultar alumnos de acuerdo a la selección del combo
$sqlAlumnos = "SELECT a.run, a.nombres, a.apaterno, a.amaterno, a.fnac, c.nombre AS curso,
              a.fnac AS fecha_nacimiento
              FROM alum a
              INNER JOIN cursos c ON a.codtipoense = c.codtipoense AND a.codgrado = c.codgrado";

// Aplicar filtro de curso si se selecciona
if (!empty($curso)) {
    $sqlAlumnos .= " WHERE c.nombre = :curso";
}

$stmtAlumnos = $db->prepare($sqlAlumnos);

if (!empty($curso)) {
    $stmtAlumnos->bindParam(':curso', $curso);
}

$stmtAlumnos->execute();
$alumnos = $stmtAlumnos->fetchAll(PDO::FETCH_ASSOC);

// Función para clasificar alumnos según edad exacta
function clasificarAlumnos($alumnos, $edad_menor, $edad_mayor) {
    $dentro_rango = [];
    $menores_fuera_rango = [];
    $mayores_fuera_rango = [];

    // Calcular los límites de edad en función de los parámetros
    $fechaLimiteMenor = (new DateTime())->sub(new DateInterval("P{$edad_menor}Y"));
    $fechaLimiteMayor = (new DateTime())->sub(new DateInterval("P{$edad_mayor}Y"));

    foreach ($alumnos as $alumno) {
        $fechaNacimiento = new DateTime($alumno['fecha_nacimiento']);
        
        // Verificar si está dentro del rango detallado
        if ($fechaNacimiento <= $fechaLimiteMenor && $fechaNacimiento > $fechaLimiteMayor) {
            $dentro_rango[] = $alumno;
        } elseif ($fechaNacimiento > $fechaLimiteMenor) {
            $menores_fuera_rango[] = $alumno;
        } else {
            $mayores_fuera_rango[] = $alumno;
        }
    }

    return [$dentro_rango, $menores_fuera_rango, $mayores_fuera_rango];
}

// Clasificar alumnos
list($dentro_rango, $menores_fuera_rango, $mayores_fuera_rango) = clasificarAlumnos($alumnos, $edad_menor, $edad_mayor);

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
    <h4 class="page-header mb-4">REPORTE DE ALUMNOS SEGÚN EDAD</h4>

    <!-- Formulario para ingresar los parámetros de filtro -->
    <form method="GET" action="">
        <div class="row">
            <div class="col-md-2">
                <label for="edad_menor" class="form-label">Edad inicial</label>
                <input type="number" name="edad_menor" id="edad_menor" class="form-control" value="<?php echo $edad_menor; ?>" required>
            </div>
            <div class="col-md-2">
                <label for="edad_mayor" class="form-label">Menor a:</label>
                <input type="number" name="edad_mayor" id="edad_mayor" class="form-control" value="<?php echo $edad_mayor; ?>" required>
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
                <label for="mostrar" class="form-label">Mostrar</label>
                <select name="mostrar" id="mostrar" class="form-control">
                    <option value="todos" <?php echo $mostrar === 'todos' ? 'selected' : ''; ?>>Todos los Alumnos</option>
                    <option value="solo_rango" <?php echo $mostrar === 'solo_rango' ? 'selected' : ''; ?>>Solo del Rango</option>
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

    <!-- Mostrar resultados según la opción seleccionada -->
    <?php if ($mostrar === 'todos' || $mostrar === 'solo_rango'): ?>
        
        <!-- Grupo de alumnos dentro del rango -->
        <?php if ($mostrar === 'todos' || $dentro_rango): ?>
        <h5 class="mt-4">Alumnos dentro del rango (Edad entre <?php echo $edad_menor; ?> y <?php echo $edad_mayor; ?>)</h5>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Run</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Edad</th>
                <th>Curso</th>
            </tr>
            </thead>
            <tbody style="background-color: lightgreen;">
            <?php foreach ($dentro_rango as $alumno): 
                $edad = calcularEdad($alumno['fnac']);
            ?>
            <tr>
                <td><?php echo $alumno['run']; ?></td>
                <td><?php echo $alumno['nombres']; ?></td>
                <td><?php echo $alumno['apaterno']; ?></td>
                <td><?php echo $alumno['amaterno']; ?></td>
                <td><?php echo $edad; ?></td>
                <td><?php echo $alumno['curso']; ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <?php if ($mostrar === 'todos'): ?>
        <!-- Grupo de alumnos menores fuera del rango -->
        <h5 class="mt-4">Alumnos menores fuera del rango (Menores de <?php echo $edad_menor; ?> años)</h5>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Run</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Edad</th>
                <th>Curso</th>
            </tr>
            </thead>
            <tbody style="background-color: lightyellow;">
            <?php foreach ($menores_fuera_rango as $alumno): 
                $edad = calcularEdad($alumno['fnac']);
            ?>
            <tr>
                <td><?php echo $alumno['run']; ?></td>
                <td><?php echo $alumno['nombres']; ?></td>
                <td><?php echo $alumno['apaterno']; ?></td>
                <td><?php echo $alumno['amaterno']; ?></td>
                <td><?php echo $edad; ?></td>
                <td><?php echo $alumno['curso']; ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Grupo de alumnos mayores fuera del rango -->
        <h5 class="mt-4">Alumnos mayores fuera del rango (Mayores de <?php echo $edad_mayor; ?> años)</h5>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Run</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Edad</th>
                <th>Curso</th>
            </tr>
            </thead>
            <tbody style="background-color: lightyellow;">
            <?php foreach ($mayores_fuera_rango as $alumno): 
                $edad = calcularEdad($alumno['fnac']);
            ?>
            <tr>
                <td><?php echo $alumno['run']; ?></td>
                <td><?php echo $alumno['nombres']; ?></td>
                <td><?php echo $alumno['apaterno']; ?></td>
                <td><?php echo $alumno['amaterno']; ?></td>
                <td><?php echo $edad; ?></td>
                <td><?php echo $alumno['curso']; ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
