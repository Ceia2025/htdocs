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


// Abrir la conexión a la base de datos
$database = new Connection();
$db = $database->open();

// Obtener todos los alumnos
$sqlAlumnos = "SELECT * FROM alum";
$queryData = $db->query($sqlAlumnos);
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.A.A.T. -> ANOTACIONES</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="icon" href="saat.ico" type="image/x-icon">
    <style>
        /* Estilos personalizados */
        .input-group {
            margin-bottom: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .auto-width {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
        .red-background {
            background-color: #ffcccc;
        }
        .row-red-background {
            background-color: #ffe6e6;
        }
        .highlighted-text {
            color: #007bff; /* Color azul de Bootstrap */
            font-weight: bold;
        }
        .icon-header {
            font-size: 2rem; /* Tamaño del icono */
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Menu-->
    <div class="row">
        <div class="col-md-12">
            <!-- Aquí puede ir el contenido del menú si es necesario -->
        </div>
    </div>
    <!-- FIN MENU -->

    <div class="col-md-12">
        <h3 class="page-header text-center">
            <i class="bi bi-book icon-header"></i>
            <span class="highlighted-text">REGISTRO DE ANOTACIONES</span>
        </h3>

        <div class="input-group mb-3">
            <?php if(isset($_GET["option"])): ?>
                <div class="alert alert-success" role="alert">
                    <strong>Hecho!</strong> El registro ha sido guardado con éxito.
                </div>
            <?php endif; ?>
            <input id="FiltrarContenido" type="text" class="form-control" placeholder="Ingrese algún parámetro de búsqueda" aria-label="Alumno" aria-describedby="basic-addon1">
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>N°</th> 
                    <th>Run</th>
                    <th>dv</th>
                    <th>Nombre</th>
                    <th>A.Paterno</th>
                    <th>A.Materno</th>
                    <th>Curso</th>
                    <th>Positiva</th>
                    <th>Negativa</th>
                    <th>Otros Registros</th>
                </tr>
            </thead>
            <tbody class="BusquedaRapida">
                <?php foreach ($queryData as $row): ?>
                    <tr>
                        <td><?php echo $row['idalum']; ?></td>
                        <td><?php echo $row['run']; ?></td>
                        <td><?php echo $row['digv']; ?></td>
                        <td><?php echo $row['nombres']; ?></td>
                        <td><?php echo $row['apaterno']; ?></td>
                        <td><?php echo $row['amaterno']; ?></td>
                        <td><?php echo $row['descgrado']; ?></td>
                        <td><a href="#positiva_<?php echo $row['run']; ?>" class="btn btn-success btn-sm" data-bs-toggle="modal">POSITIVA</a></td>
                        <td><a href="#negativa_<?php echo $row['run']; ?>" class="btn btn-danger btn-sm" data-bs-toggle="modal">NEGATIVA</a></td>
                        <td><a href="#otros_registros_<?php echo $row['run']; ?>" class="btn btn-info btn-sm" data-bs-toggle="modal">REGISTROS</a></td>
                    </tr>
                    <?php include('in.anotaregistro.php'); ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('in.anotaregistro.php'); ?>

<!-- Bootstrap core JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

<script>
$(document).ready(function () {
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
