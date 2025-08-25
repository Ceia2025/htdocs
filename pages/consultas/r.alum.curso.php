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


//include '../../includes/config.php';

// Abrir la conexión a la base de datos
$database = new Connection();
$db = $database->open();

// Inicializar la variable $cursoSeleccionado y $queryData
$cursoSeleccionado = null;
$queryData = [];

// Obtener todos los cursos
try {
    $sqlCursos = "SELECT id, nombre FROM cursos";
    $cursos = $db->query($sqlCursos)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger" role="alert">Error al obtener cursos: ' . $e->getMessage() . '</div>';
}

// Si se selecciona un curso, obtener los alumnos de ese curso
$cursoNombre = '';
if (isset($_POST['curso'])) {
    $cursoSeleccionado = $_POST['curso'];

    try {
        // Obtener nombre del curso seleccionado
        $stmtCurso = $db->prepare("SELECT nombre FROM cursos WHERE id = :cursoSeleccionado");
        $stmtCurso->execute(['cursoSeleccionado' => $cursoSeleccionado]);
        $cursoNombre = $stmtCurso->fetchColumn();

        // Obtener los alumnos del curso seleccionado y sus anotaciones, atrasos, y salidas
        $sqlAlumnos = "
        SELECT 
            a.run, a.nombres, a.apaterno, a.amaterno, a.fretiro, -- Se añade fretiro aquí
            
            -- Subconsulta para contar las anotaciones positivas
            (SELECT COUNT(*)
             FROM anotaciones an
             WHERE an.alum_run = a.run
             AND an.tipo = 'P') AS positivas,
             
            -- Subconsulta para contar las anotaciones negativas
            (SELECT COUNT(*)
             FROM anotaciones an
             WHERE an.alum_run = a.run
             AND an.tipo = 'N') AS negativas,
             
            -- Subconsulta para contar otras anotaciones
            (SELECT COUNT(*)
             FROM anotaciones an
             WHERE an.alum_run = a.run
             AND an.tipo = 'O') AS otras,
             
            -- Subconsulta para contar los atrasos
            (SELECT COUNT(*)
             FROM atrasos at
             WHERE at.alum_run = a.run) AS total_atrasos,
             
            -- Subconsulta para contar las salidas
            (SELECT COUNT(*)
             FROM salidas s
             WHERE s.alum_run = a.run) AS total_salidas
    
        FROM alum a
        WHERE a.codgrado = (SELECT codgrado FROM cursos WHERE id = :cursoSeleccionado)
        AND a.codtipoense = (SELECT codtipoense FROM cursos WHERE id = :cursoSeleccionado)
        GROUP BY a.run, a.nombres, a.apaterno, a.amaterno, a.fretiro -- Añadir fretiro aquí
        ORDER BY negativas DESC";
    
    $stmtAlumnos = $db->prepare($sqlAlumnos);
    $stmtAlumnos->execute(['cursoSeleccionado' => $cursoSeleccionado]);
    $queryData = $stmtAlumnos->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger" role="alert">Error al obtener datos: ' . $e->getMessage() . '</div>';
    }
}

// Mostrar mensaje de éxito si existe
$mensajeExito = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.A.A.T. -> Listado de Alumnos por Curso</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .input-group {
            margin-bottom: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .highlighted-text {
            color: #007bff;
            font-weight: bold;
        }
        .icon-header {
            font-size: 2rem;
            vertical-align: middle;
        }
        .table-warning {
            background-color: #ffeeba; /* Color de advertencia para alumnos retirados */
        }
    </style>
</head>
<body>
<div class="container">
    <div class="col-md-12">
        <p></p>
        <h3 class="page-header text-center">
            <i class="bi bi-person-lines-fill icon-header"></i>
            <span class="highlighted-text">REPORTE CONDUCTUAL CURSO</span>
        </h3>
<p></p>
        <?php if ($mensajeExito): ?>
            <div id="mensajeExito" class="alert alert-success" role="alert">
                <strong>Hecho!</strong> <?php echo htmlspecialchars($mensajeExito); ?>
            </div>
        <?php endif; ?>


        <form method="POST" action="r.alum.curso.php">
            <div class="input-group mb-3">
                <label class="input-group-text" for="curso">Seleccionar Curso</label>
                <select class="form-select" id="curso" name="curso" onchange="this.form.submit()">
                    <option value="" disabled selected>Elija un curso...</option>
                    <?php foreach ($cursos as $curso): ?>
                        <option value="<?php echo $curso['id']; ?>" <?php if ($curso['id'] == $cursoSeleccionado) echo 'selected'; ?>>
                            <?php echo $curso['nombre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>



<!-- LINEA HORIZONTAL -->       <div class="row justify-content-md-center">
                                        <div class="col-md-12">
                                                              
                                        </div>
                                </div>


<p></p>
        <?php if ($cursoNombre): ?>
            <h4 class="text-center mb-4">Curso Seleccionado: <?php echo htmlspecialchars($cursoNombre); ?></h4>
        <?php endif; ?>
        <p></p>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Run</th>
                    <th>Nombres</th>
                    <th>A. Paterno</th>
                    <th>A. Materno</th>
                    <th>Anotaciones Positivas</th>
                    <th>Anotaciones Negativas</th>
                    <th>Otras Anotaciones</th>
                    <th>Atrasos</th>
                    <th>Salidas</th>
                    <th>Seleccionar</th>
                </tr>
            </thead>

<tbody>
<?php if ($queryData): ?>
                    <?php foreach ($queryData as $row): ?>
                        <?php
                        // Verificar si el alumno está retirado
                        $esRetirado = ($row['fretiro'] != '1900-01-01');
                        ?>
            <tr class="<?php echo $esRetirado ? 'table-warning' : ''; ?>"> <!-- Clase CSS si el alumno está retirado -->
                <td align="right" valign="middle"><?php echo $row['run']; ?></td>
                <td align="left" valign="middle"><?php echo $row['nombres']; ?></td>
                <td align="left" valign="middle"><?php echo $row['apaterno']; ?></td>
                <td align="left" valign="middle"><?php echo $row['amaterno']; ?></td>
                <td align="center" valign="middle"><?php echo $row['positivas']; ?></td>
                <td align="center" valign="middle"><?php echo $row['negativas']; ?></td>
                <td align="center" valign="middle"><?php echo $row['otras']; ?></td>
                <td align="center" valign="middle"><?php echo $row['total_atrasos']; ?></td> <!-- Corregido -->
                <td align="center" valign="middle"><?php echo $row['total_salidas']; ?></td> <!-- Corregido -->
                <td align="center" valign="middle">
                    <a href="l.alum.reg.ver.php?run=<?php echo $row['run']; ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i> Seleccionar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="10" align="center">No hay alumnos disponibles para mostrar.</td>
        </tr>
    <?php endif; ?>
</tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Ocultar mensaje de éxito después de 5 segundos
    setTimeout(function() {
        $('#mensajeExito').fadeOut('slow');
    }, 5000);
</script>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$database->close();
?>
