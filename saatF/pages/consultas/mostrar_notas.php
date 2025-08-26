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
// Email: felipe.gutierrez.alfaro@gmail.comS

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
    <title>C.E.I.A -> Consulta de Notas</title>
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
            font-size: 1.2em;  /* Tamaño más grande */
        }
        .nota-azul {
            color: blue;
            font-weight: bold; /* Negrita */
            font-size: 1.2em;  /* Tamaño más grande */
        }
        .promedio-bajo {
            color: red;
            font-weight: bold; /* Negrita */
            font-size: 1.2em;  /* Tamaño más grande */
        }
        .promedio-alto {
            color: blue;
            font-weight: bold; /* Negrita */
            font-size: 1.2em;  /* Tamaño más grande */
        }
    </style>
</head>
<body>
<div class="container-fluid mt-5">
    <h2>CONSULTA DE NOTAS</h2>
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
                    <?php
                    // Cargar asignaturas desde la base de datos
                    $sql_asignaturas = "SELECT id, nombre FROM asignaturas";
                    $result_asignaturas = $conn->query($sql_asignaturas);
                    if ($result_asignaturas->num_rows > 0) {
                        while ($row_asignatura = $result_asignaturas->fetch_assoc()) {
                            echo "<option value='" . $row_asignatura['id'] . "'>" . $row_asignatura['nombre'] . "</option>";
                        }
                    }
                    ?>
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
            echo '<form method="POST" action="guardar_notas.php">';
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
                    echo "<td><input type='text' class='form-control nota-input' value='" . $notas_1s[$i] . "' readonly></td>";
                }

                // Promedio primer semestre (automático)
                echo "<td><input type='text' class='form-control promedio-1s' readonly></td>";

                // Notas del segundo semestre
                for ($i = 0; $i < 6; $i++) {
                    echo "<td><input type='text' class='form-control nota-input' value='" . $notas_2s[$i] . "' readonly></td>";
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
            echo '<button type="submit" class="btn btn-success mt-3">Guardar Notas</button>';
            echo '</form>';
        } else {
            echo '<p>No se encontraron estudiantes en este curso.</p>';
        }
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  // Calcular promedios automáticamente
  function calcularPromedios() {
    const filas = document.querySelectorAll('tbody tr');

    filas.forEach(fila => {
        // Obtener las notas del primer semestre (las primeras 6 columnas después del nombre del alumno)
        const notas1s = Array.from(fila.querySelectorAll('td:nth-child(n+2):nth-child(-n+7) input'))
                             .map(input => parseFloat(input.value) || 0);
        const notas1sFiltradas = notas1s.filter(nota => nota > 0);
        const promedio1s = notas1sFiltradas.length > 0 ? 
                           (notas1sFiltradas.reduce((a, b) => a + b, 0) / notas1sFiltradas.length).toFixed(1) : '';

        // Obtener las notas del segundo semestre (las últimas 6 columnas antes del promedio)
        const notas2s = Array.from(fila.querySelectorAll('td:nth-child(n+9):nth-child(-n+14) input'))
                             .map(input => parseFloat(input.value) || 0);
        const notas2sFiltradas = notas2s.filter(nota => nota > 0);
        const promedio2s = notas2sFiltradas.length > 0 ? 
                           (notas2sFiltradas.reduce((a, b) => a + b, 0) / notas2sFiltradas.length).toFixed(1) : '';

        // Calcular promedio final
        const promedioFinal = (promedio1s && promedio2s) ? 
                              ((parseFloat(promedio1s) + parseFloat(promedio2s)) / 2).toFixed(1) : 
                              (promedio1s || promedio2s || '');

        // Mostrar los promedios en los inputs correspondientes
        fila.querySelector('.promedio-1s').value = promedio1s;
        fila.querySelector('.promedio-2s').value = promedio2s;
        fila.querySelector('.promedio-final').value = promedioFinal;

        // Cambiar el color de las notas y promedios si son menores a 4
        const promedio1sInput = fila.querySelector('.promedio-1s');
        const promedio2sInput = fila.querySelector('.promedio-2s');
        const promedioFinalInput = fila.querySelector('.promedio-final');

        aplicarColor(promedio1sInput, promedio1s);
        aplicarColor(promedio2sInput, promedio2s);
        aplicarColor(promedioFinalInput, promedioFinal);

        // Cambiar el color también de cada nota individual
        fila.querySelectorAll('td input.nota-input').forEach(input => {
            aplicarColor(input, input.value);
        });
    });
}

// Función para aplicar el color de acuerdo al valor de la nota o promedio
function aplicarColor(input, valor) {
    if (valor && parseFloat(valor) < 4) {
        input.classList.add('promedio-bajo');
        input.classList.remove('promedio-alto');
    } else {
        input.classList.remove('promedio-bajo');
        input.classList.add('promedio-alto');
    }
}

// Añadir evento de cambio para cada input de notas
document.querySelectorAll('.nota-input').forEach(input => {
    input.addEventListener('input', calcularPromedios);
});

// Calcular promedios al cargar la página
document.addEventListener('DOMContentLoaded', calcularPromedios);


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
