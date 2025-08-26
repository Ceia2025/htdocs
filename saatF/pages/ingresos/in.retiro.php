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
include 'navbar.php';
include_once 'conexion.php'; // Incluye el archivo de conexión una vez

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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.A.A.T. -> ATRASOS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
   <div class="container">
		  <div class="md-4"></div>
    </div>
    
    <!--   FIN MENU   --> 
    <p> </p>
    <div class="col-md-12">
        <h3 class="page-header text-center"> REGISTRO DE RETIROS</h3>
        <p> </p>
        <div class="row">
            <div class="col-md-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <?php if(isset($_GET["option"])): ?>
                            <div class="alert alert-success" role="alert">
                                <strong>Hecho!</strong> El registro ha sido guardado con éxito.
                            </div>
                        <?php endif; ?>
                    </div>
                    <input id="FiltrarContenido" type="text" class="form-control" size="30" placeholder="Ingrese algún parámetro de búsqueda" aria-label="Alumno" aria-describedby="basic-addon1">
                </div>

                <table width="100%" class="table table-bordered table-striped" style="margin-top:20px">
                    <thead>
                        <th>N°</th>
                        <th>Run</th>
                        <th>dv</th>
                        <th>Nombre</th>
                        <th>A.Paterno</th>
                        <th>A.Materno</th>
                        <th>Edad Actual</th>
                        <th>Curso</th>
                        <th>Atraso</th>
                    </thead>
                    <tbody class="BusquedaRapida">
                        <?php foreach ($queryData as $row): ?>
                            <tr>
                                <td align="center" valign="middle"><?php echo $row['idalum']; ?></td>
                                <td align="right" valign="middle"><?php echo $row['run']; ?></td>
                                <td align="center" valign="middle"><?php echo $row['digv']; ?></td>
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
                                    <a href="#retiro_<?php echo $row['run']; ?>" class="btn btn-danger btn-sm" data-toggle="modal">
                                        <span class="glyphicon glyphicon-edit"></span> RETIRO
                                    </a>
                                </td>
                                <?php include('in.retiroregistro.php'); ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>

<!-- Bootstrap core JavaScript
    ================================================== -->   
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>window.jQuery || document.write('<script src="assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
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
    })
  }(jQuery));
});
</script>

</body>
</html>
