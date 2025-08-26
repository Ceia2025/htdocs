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

// Obtener el run del alumno seleccionado
$alum_run = isset($_GET['run']) ? $_GET['run'] : '';

// Inicializar las variables para almacenar los datos
$alumnoData = [];
$anotacionesN = [];
$anotacionesP = [];
$anotacionesO = [];
$atrasos = [];
$salidas = [];

// Obtener los datos del alumno
try {
    $sqlAlumno = "SELECT run, digv, nombres, apaterno, amaterno, fnac, descgrado, tipoensenanza, especialidad FROM alum WHERE run = :run";
    $stmt = $db->prepare($sqlAlumno);
    $stmt->bindParam(':run', $alum_run);
    $stmt->execute();
    $alumnoData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Obtener las anotaciones
    $sqlAnotaciones = "SELECT tipo, anotacion, fanota, hanota, categoria FROM anotaciones WHERE alum_run = :run ORDER BY FIELD(tipo, 'N', 'P', 'O'), fanota ASC";
    $stmt = $db->prepare($sqlAnotaciones);
    $stmt->bindParam(':run', $alum_run);
    $stmt->execute();
    $anotaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($anotaciones as $anotacion) {
        if ($anotacion['tipo'] == 'N') {
            $anotacionesN[] = $anotacion;
        } elseif ($anotacion['tipo'] == 'P') {
            $anotacionesP[] = $anotacion;
        } else {
            $anotacionesO[] = $anotacion;
        }
    }

    // Obtener los atrasos
    $sqlAtrasos = "SELECT fatraso, hatraso, jatraso FROM atrasos WHERE alum_run = :run";
    $stmt = $db->prepare($sqlAtrasos);
    $stmt->bindParam(':run', $alum_run);
    $stmt->execute();
    $atrasos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener las salidas
    $sqlSalidas = "SELECT fsalida, hsalida, jsalida FROM salidas WHERE alum_run = :run";
    $stmt = $db->prepare($sqlSalidas);
    $stmt->bindParam(':run', $alum_run);
    $stmt->execute();
    $salidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo '<div class="alert alert-danger" role="alert">Error al obtener datos: ' . $e->getMessage() . '</div>';
}

// Función para calcular la edad en años, meses y días
function calcularEdad($fechaNacimiento) {
    $fechaNacimiento = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    $edad = $hoy->diff($fechaNacimiento);
    return [
        'años' => $edad->y,
        'meses' => $edad->m,
        'días' => $edad->d
    ];
}

// Calcular la edad del alumno
$edadAlumno = isset($alumnoData['fnac']) ? calcularEdad($alumnoData['fnac']) : '';

// Contar el número de anotaciones en cada tipo
$numAnotacionesN = count($anotacionesN);
$numAnotacionesP = count($anotacionesP);
$numAnotacionesO = count($anotacionesO);
$numAtrasos = count($atrasos);
$numSalidas = count($salidas);

date_default_timezone_set('America/Santiago');


// Obtener la fecha y hora actuales
$fechaActual = date('d/m/Y');
$horaActual = date('H:i:s');
?>


<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.A.A.T. -> Detalle Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
            .container {
                width: 100%;
                padding: 0;
            }
            .card-header, .card-body {
                page-break-inside: avoid;
            }
            footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                text-align: right;
                padding-right: 10px;
                font-size: 12px;
            }
        }
        .header-logo {
            max-width: 200px;
            height: auto;
        }
        .page-header {
            margin-bottom: 20px;
        }
        .form-section {
            margin-top: 20px;
        }
        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <p></p>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="page-header mb-0">
                    <i class="bi bi-person icon-header"></i>
                    <span class="highlighted-text">HISTORIAL DE CONDUCTA DEL ALUMNO</span>
                </h4>
                <img src="../../img/logo_chico.jpg" alt="Logo Colegio" class="header-logo">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php if ($alumnoData): ?>
                <div class="card mb-3">
                    <div class="card-header text-bg-primary p-3 text-left">
                        <strong>IDENTIFICACIÓN DEL ALUMNO</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                            <p><strong>Run:</strong> <?php echo $alumnoData['run'] . '-' . $alumnoData['digv']; ?></p>
                            <p><strong>Nombre:</strong> <?php echo $alumnoData['nombres']; ?></p>
                                <p><strong>Apellido Paterno:</strong> <?php echo $alumnoData['apaterno']; ?></p>
                                <p><strong>Apellido Materno:</strong> <?php echo $alumnoData['amaterno']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Fecha de Nacimiento:</strong> <?php echo $alumnoData['fnac']; ?></p>
                                <p><strong>Edad:</strong> 
                                    <?php 
                                        if ($edadAlumno) {
                                            echo $edadAlumno['años'] . ' años, ' . $edadAlumno['meses'] . ' meses y ' . $edadAlumno['días'] . ' días';
                                        }
                                    ?>
                                </p>
                                <p><strong>Curso:</strong> <?php echo $alumnoData['descgrado']; ?></p>
                                <p><strong>Tipo de Enseñanza:</strong> <?php echo $alumnoData['tipoensenanza']; ?></p>
                                <p><strong>Especialidad:</strong> <?php echo $alumnoData['especialidad']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <p></p>
                <!-- Anotaciones Negativas -->
                <div class="text-bg-danger p-3 text-left"><strong>ANOTACIONES NEGATIVAS (<?php echo $numAnotacionesN; ?>)</strong></div>
                <p></p>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><strong>Fecha</strong></th>
                            <th><strong>Hora</strong></th>
                            <th><strong>Anotación</strong></th>
                            <th><strong>Categoría</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anotacionesN as $anotacion): ?>
                            <tr>
                                <td><?php echo $anotacion['fanota']; ?></td>
                                <td><?php echo $anotacion['hanota']; ?></td>
                                <td><?php echo $anotacion['anotacion']; ?></td>
                                <td>
                                    <?php 
                                    // Mostrar categoría en formato legible
                                    switch($anotacion['categoria']) {
                                        case 'GMA': echo 'Gravisima'; break;
                                        case 'G': echo 'Grave'; break;
                                        case 'L': echo 'Leve'; break;
                                        default: echo $anotacion['categoria']; break;
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Anotaciones Positivas -->
                <div class="text-bg-success p-3 text-left"><strong>ANOTACIONES POSITIVAS (<?php echo $numAnotacionesP; ?>)</strong></div>
                <p></p>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><strong>Fecha</strong></th>
                            <th><strong>Hora</strong></th>
                            <th><strong>Anotación</strong></th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anotacionesP as $anotacion): ?>
                            <tr>
                                <td><?php echo $anotacion['fanota']; ?></td>
                                <td><?php echo $anotacion['hanota']; ?></td>
                                <td><?php echo $anotacion['anotacion']; ?></td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Otros Registros -->
                <div class="text-bg-info p-3 text-left"><strong>OTROS REGISTROS (<?php echo $numAnotacionesO; ?>)</strong></div>
                <p></p>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><strong>Fecha</strong></th>
                            <th><strong>Hora</strong></th>
                            <th><strong>Descripción</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anotacionesO as $anotacion): ?>
                            <tr>
                                <td><?php echo $anotacion['fanota']; ?></td>
                                <td><?php echo $anotacion['hanota']; ?></td>
                                <td><?php echo $anotacion['anotacion']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Atrasos -->
                <div class="text-bg-warning p-3 text-left"><i class="bi bi-clock"></i><strong>ATRASOS (<?php echo $numAtrasos; ?>)</strong></div>
                <p></p>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><strong>Fecha</strong></th>
                            <th><strong>Hora</strong></th>
                            <th><strong>Justificación</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($atrasos as $atraso): ?>
                            <tr>
                                <td><?php echo $atraso['fatraso']; ?></td>
                                <td><?php echo $atraso['hatraso']; ?></td>
                                <td><?php echo $atraso['jatraso']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Salidas -->
                <div class="text-bg-secondary p-3 text-left"><strong>SALIDAS (<?php echo $numSalidas; ?>)</strong></div>
                <p></p>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><strong>Fecha</strong></th>
                            <th><strong>Hora</strong></th>
                            <th><strong>Justificación</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salidas as $salida): ?>
                            <tr>
                                <td><?php echo $salida['fsalida']; ?></td>
                                <td><?php echo $salida['hsalida']; ?></td>
                                <td><?php echo $salida['jsalida']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>



                <p></p>
                <p></p>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nombre Funcionario:</strong></p>
                                <p><strong>Run:</strong></p>
                                <p><strong>Cargo:</strong></p>
                                <p></p>
                                <p> <strong>Firma:  _________________________________</strong></p>
                                
                            </div>
                            <div class="col-md-6">
                            <p><strong>Nombre Apoderado y/o Alumno:</strong></p>
                            <p><strong>Run:</strong></p>
                            <p> </p>
                            <p> </p>
                            <p> <strong>Firma:  _________________________________</strong></p>
                            <P></P>
                            <p><strong>Fecha y Hora de Consulta:</strong> <?php echo $fechaActual . ' ' . $horaActual; ?></p>


                            </div>
                        </div>
                    </div>
                </div>



<div class="row">
              <div class="mb-3">

                
                    <button type="button" class="btn btn-primary no-print align-items-center" onclick="window.print();">Imprimir</button>
                
</div>
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    No se encontraron datos del alumno.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-H+K7U5CnXl1h5ywQ4tv9yyzXgEF5rqVkg9Va8IITt/Uz0pzd8tn7HEgf5THR3J5i" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMN0FMYIXrY+Su5E0N9ob7Hnl9N/E0zf2TfVZc5snzVN7iWTXf+elHl4gIxw6U0s" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl7/8wxm5dd3TXWm0KpkrxySK8X3p62eXtAZphzqeE" crossorigin="anonymous"></script>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$database->close();
?>
