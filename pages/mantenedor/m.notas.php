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
include_once '../../includes/config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.E.I.A -> Actualización de Notas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
            cursor: pointer;
            padding: 10px;
        }
        .table td.alumno {
            white-space: nowrap;
            text-align: left;
        }
        .nota-roja {
            color: red;
            font-weight: bold; /* Negrita */
            font-size: 1em;  /* Tamaño más grande */
        }
        .nota-azul {
            color: blue;
            font-weight: bold; /* Negrita */
            font-size: 1em;  /* Tamaño más grande */
        }
        .promedio-bajo {
            color: red;
            font-weight: bold; /* Negrita */
            font-size: 1em;  /* Tamaño más grande */
        }
        .promedio-alto {
            color: blue;
            font-weight: bold; /* Negrita */
            font-size: 1em;  /* Tamaño más grande */
        }
    </style>
</head>
<body>
<div class="container-fluid mt-5">
    <h2>ACTUALIZACIÓN DE CALIFICACIONES
    </h2>
    <form method="POST" action="">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="curso" class="form-label">Curso:</label>
                <select class="form-select" id="curso" name="curso" required>
                    <option value="">Seleccione un curso</option>
                    <?php
                    // Cargar cursos desde la base de datos
                    $sql_cursos = "SELECT id, nombre, jornada, descgrado, letra FROM cursos";
                    $result_cursos = $conn->query($sql_cursos);
                    if ($result_cursos->num_rows > 0) {
                        while ($row_curso = $result_cursos->fetch_assoc()) {
                            echo "<option value='" . $row_curso['id'] . "'>" . $row_curso['nombre'] . " - " . $row_curso['jornada'] . " - " . $row_curso['descgrado'] . " - " . $row_curso['letra'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="asignatura" class="form-label">Asignatura:</label>
                <select class="form-select" id="asignatura" name="asignatura" required>
                    <option value="">Seleccione una asignatura</option>
 
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Cargar</button>
        <button type="button" class="btn btn-secondary no-print align-items-center" onclick="window.print();">Imprimir</button>
    </form>


    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['curso']) && !empty($_POST['asignatura'])) {
        $curso = $_POST['curso'];
        $asignatura = $_POST['asignatura'];

        // Consultar estudiantes del curso
        $sql_estudiantes = "
            SELECT alum.run, alum.nombres, alum.apaterno, alum.amaterno 
            FROM alum 
            JOIN cursos ON alum.descgrado = cursos.descgrado 
            AND alum.codtipoense = cursos.codtipoense 
            AND alum.codgrado = cursos.codgrado 
            WHERE cursos.id = '$curso' ORDER BY alum.apaterno ASC";
            
        $result_estudiantes = $conn->query($sql_estudiantes);

        // Obtener los nombres del curso y asignatura para mostrar
        $sql_curso_info = "SELECT nombre FROM cursos WHERE id = '$curso'";
        $sql_asignatura_info = "SELECT nombre FROM asignaturas WHERE id = '$asignatura'";
        $nombre_curso = $conn->query($sql_curso_info)->fetch_assoc()['nombre'];
        $nombre_asignatura = $conn->query($sql_asignatura_info)->fetch_assoc()['nombre'];

        echo "<h3>Curso: $nombre_curso | Asignatura: $nombre_asignatura</h3>";

        if ($result_estudiantes->num_rows > 0) {
            echo '<form id="notasForm" method="POST" action="update_notas.php">';
            echo '<input type="hidden" name="curso" value="' . $curso . '">';
            echo '<input type="hidden" name="asignatura" value="' . $asignatura . '">';
        
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered table-striped" style="table-layout: auto; width: 100%;">';
            echo '<thead>';
            echo '<tr><th class="alumno">Alumnos</th>'; // Aplicar la clase "alumno"
            echo '<th colspan="6" class="text-center">Primer Semestre</th>';
            echo '<th>Promedio 1S</th>';
            echo '<th colspan="6" class="text-center">Segundo Semestre</th>';
            echo '<th>Promedio 2S</th>';
            echo '<th>Promedio Final</th></tr>';
            echo '</thead>';
            echo '<tbody>';
        
            while ($row_estudiante = $result_estudiantes->fetch_assoc()) {
                $run_alumno = $row_estudiante['run'];
                $nombre_completo = $row_estudiante['apaterno'] . ' ' . $row_estudiante['amaterno'] . ' ' . $row_estudiante['nombres'];
        
                // Consultar notas guardadas para el alumno, curso y asignatura
                $sql_notas = "SELECT * FROM alum_notas WHERE run_alum_nota = '$run_alumno' AND asignaturas_id = '$asignatura'";
                $result_notas = $conn->query($sql_notas);
        
                // Inicializar notas vacías para cada semestre
                $notas_1s = array_fill(0, 6, ''); // Para el primer semestre
                $notas_2s = array_fill(0, 6, ''); // Para el segundo semestre
        
                // Rellenar arrays con notas obtenidas
                if ($result_notas->num_rows > 0) {
                    while ($row_nota = $result_notas->fetch_assoc()) {
                        if ($row_nota['semestre'] == 1) {
                            $notas_1s[$row_nota['num_nota'] - 1] = htmlspecialchars($row_nota['nota']);
                        } elseif ($row_nota['semestre'] == 2) {
                            $notas_2s[$row_nota['num_nota'] - 1] = htmlspecialchars($row_nota['nota']);
                        }
                    }
                }
        
                // Mostrar el nombre del alumno y sus notas
                echo '<tr>';
                echo "<td>$nombre_completo</td>";
                echo "<input type='hidden' name='alumno[]' value='" . $run_alumno . "'>";
        
                // Notas del primer semestre
                for ($i = 0; $i < 6; $i++) {
                    $readonly = empty($notas_1s[$i]) ? 'readonly' : ''; // Deshabilitar si está vacío
                    echo "<td><input type='text' class='form-control nota-input' name='nota_1s_{$run_alumno}[]' value='" . $notas_1s[$i] . "' $readonly></td>";
                }
        
                // Promedio primer semestre (automático)
                echo "<td><input type='text' class='form-control promedio-1s' readonly></td>";
        
                // Notas del segundo semestre
                for ($i = 0; $i < 6; $i++) {
                    $readonly = empty($notas_2s[$i]) ? 'readonly' : ''; // Deshabilitar si está vacío
                    echo "<td><input type='text' class='form-control nota-input' name='nota_2s_{$run_alumno}[]' value='" . $notas_2s[$i] . "' $readonly></td>";
                }
        
                // Promedio segundo semestre (automático)
                echo "<td><input type='text' class='form-control promedio-2s' readonly></td>";
        
                // Promedio final (automático)
                echo "<td><input type='text' class='form-control promedio-final' readonly></td>";
                echo '</tr>';
            }
        
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '<button type="button" class="btn btn-success" id="openModalButton">Actualizar Notas</button>';
        
            echo '</form>';
        } else {
            echo '<p>No se encontraron estudiantes en este curso.</p>';
        }
        
            }
    ?>
</div>


<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Guardado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas actualizar las notas?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="saveButton">Guardar Notas</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Éxito</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $mensaje; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

  // Calcular promedios automáticamente
  $(document).ready(function() {
    // Función para redondear a un decimal, agregando .0 si es entero
    function redondearADecimal(numero, decimales) {
        var factor = Math.pow(10, decimales);
        var resultado = Math.round(numero * factor) / factor;
        
        // Si el resultado es un número entero, agregarle .0
        return resultado % 1 === 0 ? resultado.toFixed(1) : resultado.toFixed(decimales);
    }

    // Función para aplicar el color de acuerdo al valor de la nota o promedio
    function aplicarColor(input, valor) {
        if (valor && parseFloat(valor) < 4) {
            input.classList.add('promedio-bajo');  // Clase para notas bajas
            input.classList.remove('promedio-alto');  // Eliminar clase de notas altas
        } else {
            input.classList.remove('promedio-bajo');
            input.classList.add('promedio-alto');  // Clase para notas altas
        }
    }

    // Función para calcular los promedios
    function calcularPromedios() {
        $('tbody tr').each(function() {
            var $fila = $(this);
            var sum_1s = 0, count_1s = 0;
            var sum_2s = 0, count_2s = 0;

            // Calcular promedio del primer semestre
            $fila.find('input[name^="nota_1s"]').each(function() {
                var valor = parseFloat($(this).val());
                if (!isNaN(valor)) {
                    sum_1s += valor;
                    count_1s++;
                }
                aplicarColor(this, valor);  // Aplicar color a cada nota individual
            });

            var promedio_1s = count_1s > 0 ? redondearADecimal(sum_1s / count_1s, 1) : '';  // Redondear a un decimal
            var promedio1sInput = $fila.find('.promedio-1s').val(promedio_1s);
            aplicarColor(promedio1sInput[0], promedio_1s);  // Aplicar color al promedio 1S

            // Calcular promedio del segundo semestre
            $fila.find('input[name^="nota_2s"]').each(function() {
                var valor = parseFloat($(this).val());
                if (!isNaN(valor)) {
                    sum_2s += valor;
                    count_2s++;
                }
                aplicarColor(this, valor);  // Aplicar color a cada nota individual
            });

            var promedio_2s = count_2s > 0 ? redondearADecimal(sum_2s / count_2s, 1) : '';  // Redondear a un decimal
            var promedio2sInput = $fila.find('.promedio-2s').val(promedio_2s);
            aplicarColor(promedio2sInput[0], promedio_2s);  // Aplicar color al promedio 2S

            // Calcular promedio final
            if (promedio_1s && promedio_2s) {
                var promedio_final = redondearADecimal((parseFloat(promedio_1s) + parseFloat(promedio_2s)) / 2, 1);  // Redondear a un decimal
                var promedioFinalInput = $fila.find('.promedio-final').val(promedio_final);
                aplicarColor(promedioFinalInput[0], promedio_final);  // Aplicar color al promedio final
            } else {
                $fila.find('.promedio-final').val('');  // Vaciar si no hay suficientes datos
            }
        });
    }

    // Evento para recalcular promedios cuando se cambia una nota
    $(document).on('input', 'input[name^="nota_1s"], input[name^="nota_2s"]', function() {
        calcularPromedios();
    });

    // Calcular y aplicar colores al cargar la página
    calcularPromedios();
});

// Evento para restringir la entrada de notas
document.querySelectorAll('.nota-input').forEach(function(input) {
    input.addEventListener('input', function() {
        // Reemplazar cualquier cosa que no sea un número o un punto
        this.value = this.value.replace(/[^0-9.]/g, '');
        
        // Asegurarse de que solo se permita un punto decimal
        if ((this.value.match(/\./g) || []).length > 1) {
            this.value = this.value.substring(0, this.value.length - 1);
            alert("Solo se permite un punto decimal.");
        }

        // Asegurarse de que el valor esté entre 1.0 y 7.0
        const valor = parseFloat(this.value);
        if (this.value && (valor < 1.0 || valor > 7.0 || this.value.includes('.') && this.value.split('.')[1].length > 1)) {
            this.value = ''; // Limpiar si está fuera de rango o tiene un segundo decimal
            alert("La nota debe estar entre 1.0 y 7.0 y no debe contener letras, ni un segundo decimal.");
        }

        calcularPromedios(); // Actualizar promedios al cambiar el valor
    });

    // Evento para agregar .0 al salir de la caja de texto si es un número entero
    input.addEventListener('blur', function() {
        const valor = this.value;
        if (valor && !valor.includes('.')) {
            this.value = valor + '.0'; // Completar con un decimal al salir
        }
        calcularPromedios();
    });
});

$(document).ready(function() {
    // Abre el modal de confirmación al hacer clic en "Guardar Notas"
    $("#openModalButton").on("click", function() {
        $("#confirmModal").modal("show"); // Mostrar el modal de confirmación
    });

    // Cuando se haga clic en "Guardar Notas" dentro del modal
    $("#saveButton").on("click", function() {
        // Comprobar que el formulario es válido antes de enviar
        if ($("#notasForm")[0].checkValidity()) {
            $("#notasForm").submit(); // Enviar el formulario
        } else {
            alert("Por favor, completa todos los campos requeridos.");
        }
    });
});
$(document).ready(function() {
    // Mostrar el modal de éxito si hay un mensaje
    <?php if (!empty($mensaje)): ?>
        $("#successModal").modal("show");
    <?php endif; ?>
});


$(document).ready(function() {
    $('#curso').on('change', function() {
        var cursoId = $(this).val();


// Limpiar el combo de asignaturas al cambiar el curso
        $('#asignatura').html('<option value="">Seleccione una asignatura</option>');

        if (cursoId) {
            $.ajax({
                url: 'cargar_asignaturas.php', // Ruta al archivo PHP que obtiene las asignaturas y docentes
                type: 'POST',
                data: {curso_id: cursoId},
                dataType: 'json',
                success: function(data) {



// Agregar asignaturas y docentes al combo
                    if (data.length > 0) {
                        $.each(data, function(key, value) {
                            $('#asignatura').append(
                                '<option value="' + value.id + '">' + value.nombre + ' (' + value.docente + ')</option>'
                            );
                        });
                    } else {
                        $('#asignatura').html('<option value="">No hay asignaturas disponibles</option>');
                    }
                },
                error: function() {
                    alert('Hubo un error al cargar las asignaturas');
                }
            });
        }
    });
});








</script>


</body>
</html>
