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
include '../../includes/config.php';
include '../../includes/conexion.php';

// Consulta para obtener todos los alumnos
$sqlAlumnos = "SELECT * FROM alum ORDER BY idalum ASC";
$queryData = $conn->query($sqlAlumnos);


// Obtener cantidad de alumnos
$sql = "SELECT COUNT(*) as total_alumnos FROM alum";
$result = $conn->query($sql);
$total_alumnos = $result->fetch_assoc()['total_alumnos'];

// Calcular el porcentaje
$porcentaje = ($total_alumnos / 270) * 100;

// Cerrar la conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.E.I.A -> S.A.A.T.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css">
    <link rel="icon" href="img/saat.ico" type="image/x-icon">
</head>
<body>

<div class="container">
    <div class="md-4"></div>
</div>

<!--   FIN MENU   -->

<div class="container fluid">
    <h3 class="page-header text-center">CONSULTA ESTUDIANTE</h3>
    <div class="row">
        <div class="col-md-12">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="col-md-10">
                        <h6>N° DE ALUMNOS Max: 270</h7>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:<?php echo $porcentaje; ?>%" aria-valuenow="<?php echo $total_alumnos; ?>" aria-valuemin="0" aria-valuemax="300"><?php echo $total_alumnos; ?> Alumnos (<?php echo round($porcentaje, 2); ?>%)</div>
                            </div>
                    </div>
                </div>
                <div class="col-md-5 text-right mb-2">
                    <input id="FiltrarContenido" type="text" class="form-control" size="15" placeholder="Ingrese algún parametro de busqueda" aria-label="Alumno" aria-describedby="basic-addon1">
                </div>
                <div class="col-md-3">
                    <a href="../reportes/Reporte_Alumnos.php" target="_blank" class="btn btn-success"><i class="bi bi-filetype-pdf"></i> GENERAR REPORTE</a>
                </div>
            </div>

            <table width="100%" class="table table-striped table-bordered">
                <thead>
                    <th>N°</th>
                    <th>Run</th>
                    <th>dv</th>
                    <th>Nombre</th>
                    <th>A.Paterno</th>
                    <th>A.Materno</th>
                    <th>Edad_Actual</th>
                    <th>Curso</th>
                    <th>Telefono</th>
                    <th>Ficha Alumno</th>
                </thead>
                <tbody class="BusquedaRapida">
                    <?php
                    foreach ($queryData as $row) {
                        // Calcular la edad
                        $fecha_nacimiento = new DateTime($row['fnac']);
                        $fecha_actual = new DateTime();
                        $diferencia = $fecha_nacimiento->diff($fecha_actual);
                        $edad = $diferencia->y . " años, " . $diferencia->m . " meses, " . $diferencia->d . " días";
                        ?>
                        <tr>
                            <td align="center" valign="middle"><?php echo $row['idalum']; ?></td>
                            <td align="right" valign="middle"><?php echo $row['run']; ?></td>
                            <td align="center" valign="middle"><?php echo $row['digv']; ?></td>
                            <td align="left" valign="middle"><?php echo $row['nombres']; ?></td>
                            <td align="left" valign="middle"><?php echo $row['apaterno']; ?></td>
                            <td align="left" valign="middle"><?php echo $row['amaterno']; ?></td>
                            <td align="center" valign="middle"><?php echo $edad; ?></td>
                            <td align="center" valign="middle"><?php echo $row['descgrado']; ?></td>
                            <td align="center" valign="middle"><?php echo $row['celular']; ?></td>
                            <td align="center" valign="middle">
                                <a href="c.alum.ficha.php?id=<?php echo $row['run']; ?>" data-toggle="tooltip"class="btn btn-warning mb-2"><i class="bi bi-tv"></i> Ficha</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- Bootstrap core JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {
        $('#FiltrarContenido').keyup(function () {
            var ValorBusqueda = new RegExp($(this).val(), 'i');
            $('.BusquedaRapida tr').hide().filter(function () {
                return ValorBusqueda.test($(this).text());
            }).show();
        });
    });
</script>

</body>
</html>