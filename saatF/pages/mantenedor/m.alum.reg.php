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

// Abrir la conexión a la base de datos
$database = new Connection();
$db = $database->open();

// Inicializar la variable $queryData
$queryData = [];

// Obtener todos los alumnos
try {
    $sqlAlumnos = "SELECT run, nombres, apaterno, amaterno, fnac, descgrado FROM alum";
    $queryData = $db->query($sqlAlumnos)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger" role="alert">Error al obtener datos: ' . $e->getMessage() . '</div>';
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
    <title>S.A.A.T. -> Listado de Alumnos</title>

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
    </style>
</head>
<body>
<div class="container">
    <div class="col-md-12">
        <h3 class="page-header text-center">
            <i class="bi bi-person-lines-fill icon-header"></i>
            <span class="highlighted-text">MODIFICAR ANOTACIONES DE ALUMNOS</span>
        </h3>

        <?php if ($mensajeExito): ?>
            <div id="mensajeExito" class="alert alert-success" role="alert">
                <strong>Hecho!</strong> <?php echo htmlspecialchars($mensajeExito); ?>
            </div>
        <?php endif; ?>

        <div class="input-group mb-3">
            <input id="FiltrarContenido" type="text" class="form-control" placeholder="Ingrese algún parámetro de búsqueda" aria-label="Alumno" aria-describedby="basic-addon1">
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Run</th>
                    <th>Nombres</th>
                    <th>A. Paterno</th>
                    <th>A. Materno</th>
                    <th>Edad Actual</th>
                    <th>Curso</th>
                    <th>Seleccionar</th>
                </tr>
            </thead>
            <tbody class="BusquedaRapida">
                <?php foreach ($queryData as $row): ?>
                    <tr>
                        <td align="right" valign="middle"><?php echo $row['run']; ?></td>
                        <td align="left" valign="middle"><?php echo $row['nombres']; ?></td>
                        <td align="left" valign="middle"><?php echo $row['apaterno']; ?></td>
                        <td align="left" valign="middle"><?php echo $row['amaterno']; ?></td>
                        <td align="left" valign="middle">
                            <?php
                            // Calcular la edad
                            $fechaNacimiento = new DateTime($row['fnac']);
                            $fechaActual = new DateTime();
                            $diferencia = $fechaNacimiento->diff($fechaActual);
                            echo $diferencia->y . " años, " . $diferencia->m . " meses, " . $diferencia->d . " días";
                            ?>
                        </td>
                        <td align="left" valign="middle"><?php echo $row['descgrado']; ?></td>
                        <td align="center" valign="middle">
                            <a href="m.alum.reg.ver.php?run=<?php echo $row['run']; ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-search"></i> Seleccionar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($queryData)): ?>
            <div class="alert alert-warning" role="alert">
                No hay datos disponibles para mostrar.
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Ocultar mensaje de éxito después de 2 segundos
    setTimeout(function() {
        $('#mensajeExito').fadeOut('slow');
    }, 1000);

    (function($) {
        $('#FiltrarContenido').keyup(function () {
            var ValorBusqueda = new RegExp($(this).val(), 'i');
            $('.BusquedaRapida tr').hide();
            $('.BusquedaRapida tr').filter(function () {
                return ValorBusqueda.test($(this).text());
            }).show();
        });
    }(jQuery));
});
</script>
</body>
</html>
