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

// Obtener el run desde la URL
$run = isset($_GET['run']) ? $_GET['run'] : '';


// Inicializar las variables para almacenar los datos
$alumnoData = [];
$anotacionesN = [];
$anotacionesP = [];
$anotacionesO = [];

// Obtener los datos del alumno
try {
    $sqlAlumno = "SELECT run, digv, nombres, apaterno, amaterno, fnac, descgrado, tipoensenanza, especialidad FROM alum WHERE run = :run";
    $stmt = $db->prepare($sqlAlumno);
    $stmt->bindParam(':run', $run);
    $stmt->execute();
    $alumnoData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Obtener las anotaciones
    $sqlAnotaciones = "SELECT alum_run, idanotaciones, tipo, anotacion, fanota, hanota, categoria FROM anotaciones WHERE alum_run = :run ORDER BY FIELD(tipo, 'N', 'P', 'O'), fanota ASC";
    $stmt = $db->prepare($sqlAnotaciones);
    $stmt->bindParam(':run', $run);
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

date_default_timezone_set('America/Santiago');

// Obtener la fecha y hora actuales
$fechaActual = date('d/m/Y');
$horaActual = date('H:i:s');
?>




<?php if(isset($_GET['message']) && !empty($_GET['message'])): ?>
    <div id="successMessage" class="alert alert-success" role="alert">
        <?php echo htmlspecialchars($_GET['message']); ?>
    </div>
<?php endif; ?>



<?php
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-success" id="message">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
}
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
                <img src="./img/logo_chico.jpg" alt="Logo Colegio" class="header-logo">
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
                                            echo $edadAlumno['años'] . ' años, ' . $edadAlumno['meses'] . ' meses y ' . $edadAlumno['días'];
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
                            <th><strong>Acciones</strong></th>
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
                                    switch($anotacion['categoria']) {
                                        case 'GMA': echo 'Gravisima'; break;
                                        case 'G': echo 'Grave'; break;
                                        case 'L': echo 'Leve'; break;
                                        default: echo $anotacion['categoria']; break;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modificarModal" onclick="loadModalContent('modificar_anotacion.php?id=<?php echo $anotacion['idanotaciones']; ?>&run=<?php echo $run; ?>')">
                                        <i class="bi bi-pencil-square"></i> Modificar
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" onclick="confirmDelete(<?php echo $anotacion['idanotaciones']; ?>, '<?php echo $run; ?>')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p></p>
                
                <!-- Anotaciones Positivas -->
                <div class="text-bg-success p-3 text-left"><strong>ANOTACIONES POSITIVAS (<?php echo $numAnotacionesP; ?>)</strong></div>
                <p></p>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><strong>Fecha</strong></th>
                            <th><strong>Hora</strong></th>
                            <th><strong>Anotación</strong></th>
                            <th><strong>Acciones</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anotacionesP as $anotacion): ?>
                            <tr>
                                <td><?php echo $anotacion['fanota']; ?></td>
                                <td><?php echo $anotacion['hanota']; ?></td>
                                <td><?php echo $anotacion['anotacion']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modificarModal" onclick="loadModalContent('modificar_anotacion.php?id=<?php echo $anotacion['idanotaciones']; ?>&run=<?php echo $run; ?>')">
                                        <i class="bi bi-pencil-square"></i> Modificar
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" onclick="confirmDelete(<?php echo $anotacion['idanotaciones']; ?>, '<?php echo $run; ?>')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p></p>
                
                <!-- Otros Registros -->
                <div class="text-bg-primary p-3 text-left"><strong>OTROS REGISTROS (<?php echo $numAnotacionesO; ?>)</strong></div>
                <p></p>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><strong>Fecha</strong></th>
                            <th><strong>Hora</strong></th>
                            <th><strong>Anotación</strong></th>
                            <th><strong>Acciones</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anotacionesO as $anotacion): ?>
                            <tr>
                                <td><?php echo $anotacion['fanota']; ?></td>
                                <td><?php echo $anotacion['hanota']; ?></td>
                                <td><?php echo $anotacion['anotacion']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modificarModal" onclick="loadModalContent('modificar_anotacion.php?id=<?php echo $anotacion['idanotaciones']; ?>&run=<?php echo $run; ?>')">
                                        <i class="bi bi-pencil-square"></i> Modificar
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" onclick="confirmDelete(<?php echo $anotacion['idanotaciones']; ?>, '<?php echo $run; ?>')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p></p>

               

               
                <p></p>
            <?php else: ?>
                <div class="alert alert-danger" role="alert">No se encontraron datos del alumno.</div>
            <?php endif; ?>
        </div>
    </div>
</div>




<!-- Modal para modificar anotación -->
<div class="modal fade" id="modificarModal" tabindex="-1" aria-labelledby="modificarModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 1650px!important;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modificarModalLabel">Modificar Anotación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalContent"></div>
            </div>
        </div>
    </div>
</div>



<!-- Modal de Confirmación para Eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta anotación?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal de confirmación de guardado -->
<div class="modal fade" id="confirmSaveModal" tabindex="-1" aria-labelledby="confirmSaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmSaveModalLabel">Confirmar Guardado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas guardar los cambios?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmSaveButton">Guardar</button>
            </div>
        </div>
    </div>
</div>



<script>
function loadModalContent(url) {
    $.get(url, function(data) {
        $('#modalContent').html(data);

        // Mostrar/ocultar el campo categoría según el tipo de anotación
        const tipo = $('#tipo').val();
        if (tipo !== 'N') {
            $('#categoria').hide();
        } else {
            $('#categoria').show();
        }

        // Manejar cambio de tipo
        $('#tipo').on('change', function() {
            if ($(this).val() === 'N') {
                $('#categoria').show();
            } else {
                $('#categoria').hide();
            }
        });
    });
}

$(document).ready(function() {
    // Mostrar mensaje de éxito y desvanecerlo después de 30 segundos
    if ($('#successMessage').length) {
        setTimeout(function() {
            $('#successMessage').fadeOut('slow');
        }, 1000);
    }
});


function confirmSave() {
    $('#confirmSaveModal').modal('show');
}

document.addEventListener('DOMContentLoaded', function() {
    $('#confirmSaveButton').click(function() {
        // Aquí se asume que el formulario de guardar tiene el ID 'saveForm'
        var form = $('#saveForm');
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                // Redirige a m.alum.reg.ver.php con el run del alumno y muestra un mensaje de éxito
                var run = '<?php echo $run; ?>';
                window.location.href = 'm.alum.reg.ver.php?run=' + run + '&message=' + encodeURIComponent(response);
            }
        });
    });
});


//ELIMINAR ANOTACIONES

$('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var idanotaciones = button.data('idanotaciones'); 
        var modal = $(this);
        modal.find('#confirmDeleteBtn').data('idanotaciones', idanotaciones);
    });

    $('#confirmDeleteBtn').on('click', function () {
        var idanotaciones = $(this).data('idanotaciones');
        $.ajax({
            url: 'eliminar_anotacion.php',
            type: 'POST',
            data: { idanotaciones: idanotaciones },
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response === 'success') {
                    alert('Anotación eliminada exitosamente');
                    location.reload();
                } else {
                    alert('Error al eliminar la anotación');
                }
            }
        });
    });






</script>
</body>
</html>