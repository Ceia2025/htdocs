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
    
    <!-- Estilos personalizados -->
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
    <h2>CONSULTA DE NOTAS</h2>
    <form id="form-consulta-notas" method="POST" action="">
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="curso" class="form-label">Curso:</label>
            <select class="form-select" id="curso" name="curso" required>
                <option value="">Seleccione un curso</option>
                <?php
                // Cargar cursos desde la base de datos
                $sql_cursos = "SELECT id, nombre, jornada, descgrado, codtipoense, codgrado FROM cursos";
                $result_cursos = $conn->query($sql_cursos);
                if ($result_cursos->num_rows > 0) {
                    while ($row_curso = $result_cursos->fetch_assoc()) {
                        echo "<option value='" . $row_curso['id'] . "'>" . $row_curso['nombre'] . " - " . $row_curso['jornada'] . " - " . $row_curso['descgrado'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="alumno" class="form-label">Alumno:</label>
            <select class="form-select" id="alumno" name="alumno" required>
                <option value="">Seleccione un alumno</option>
                <!-- Aquí se cargarán los alumnos con AJAX -->
            </select>
        </div>
    </div>
    <button id="cargar" type="submit" class="btn btn-primary">Cargar</button>
    <button type="button" class="btn btn-secondary no-print align-items-center" onclick="window.print();">Imprimir</button>

</form>
</div>

    <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['curso']) && !empty($_POST['alumno'])) {
    $curso_id = $_POST['curso'];
    $alumno_id = $_POST['alumno'];

// Consultar asignaturas del curso
$sql_asignaturas = "
    SELECT DISTINCT asignaturas.id, asignaturas.nombre 
    FROM asignaturas 
    JOIN asignaturas_cursos ON asignaturas.id = asignaturas_cursos.asignatura_id 
    WHERE asignaturas_cursos.curso_id = '$curso_id'";
    
    $result_asignaturas = $conn->query($sql_asignaturas);

    if ($result_asignaturas->num_rows > 0) {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped" style="table-layout: auto; width: 100%;">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Asignatura</th>';
        echo '<th colspan="6" class="text-center">Primer Semestre</th>';
        echo '<th>Promedio 1S</th>';
        echo '<th colspan="6" class="text-center">Segundo Semestre</th>';
        echo '<th>Promedio 2S</th>';
        echo '<th>Promedio Final</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
    
        // Iterar sobre las asignaturas y obtener las notas del alumno
        while ($row_asignatura = $result_asignaturas->fetch_assoc()) {
            $asignatura_id = $row_asignatura['id'];
            $asignatura_nombre = $row_asignatura['nombre'];
    
            // Obtener notas del alumno para esta asignatura
            $sql_notas = "SELECT * FROM alum_notas WHERE run_alum_nota = '$alumno_id' AND asignaturas_id = '$asignatura_id'";
            $result_notas = $conn->query($sql_notas);
    
            // Inicializar arrays para notas del primer y segundo semestre
            $notas_1s = array_fill(0, 6, '');
            $notas_2s = array_fill(0, 6, '');
    
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
    
            // Mostrar asignatura y notas en la tabla
            echo '<tr>';
            echo "<td>$asignatura_nombre</td>";
    
            // Notas del primer semestre
            for ($i = 0; $i < 6; $i++) {
                echo "<td><input type='text' class='form-control nota-input semestre-1' value='" . $notas_1s[$i] . "' readonly></td>";
            }
    
            // Promedio primer semestre (automático)
            echo "<td><input type='text' class='form-control promedio-1s' readonly></td>";
    
            // Notas del segundo semestre
            for ($i = 0; $i < 6; $i++) {
                echo "<td><input type='text' class='form-control nota-input semestre-2' value='" . $notas_2s[$i] . "' readonly></td>";
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
    } else {
        echo "<p>No se encontraron asignaturas para el curso seleccionado.</p>";
    }
}
    ?>
     
 
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<script>
$(document).ready(function() {
    // Capturar el evento de cambio del curso
    $('#curso').on('change', function() {
        var cursoId = $(this).val(); // Obtener el ID del curso seleccionado

        if (cursoId) {
            $.ajax({
                url: 'cargar_alumnos.php', // Archivo PHP para cargar los alumnos
                type: 'POST',
                data: { curso_id: cursoId },
                success: function(response) {
                    // Poner el HTML de los alumnos en el select
                    $('#alumno').html(response);
                },
                error: function() {
                    alert('Error al cargar los alumnos.');
                }
            });
        } else {
            $('#alumno').html('<option value="">Seleccione un alumno</option>');
        }
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

            actualizarPromedios(); // Actualizar promedios al cambiar el valor
        });

        // Evento para agregar .0 al salir de la caja de texto
        input.addEventListener('blur', function() {
            const valor = this.value;
            if (valor && !valor.includes('.')) {
                this.value = valor + '.0'; // Completar con un decimal al salir
            }
            actualizarPromedios();
        });
    });

    // Inicializar promedios al cargar la página
    actualizarPromedios();
});

// Función para calcular el promedio
function calcularPromedio(notas) {
    let total = 0;
    let count = 0;

    notas.forEach(function(nota) {
        const valor = parseFloat(nota.value);
        if (!isNaN(valor)) {
            total += valor;
            count++;
        }
    });

    if (count > 0) {
        let promedio = total / count;
        return Math.round(promedio * 10) / 10; // Redondear a un decimal
    }
    return '';
}

// Función para aplicar estilo a los promedios
function aplicarEstiloPromedio(promedioElement) {
    promedioElement.classList.remove('promedio-bajo', 'promedio-alto'); // Limpiar clases antes de aplicar
    if (promedioElement.value && parseFloat(promedioElement.value) < 4.0) {
        promedioElement.classList.add('promedio-bajo'); // Aplica rojo si es menor a 4
    } else if (promedioElement.value && parseFloat(promedioElement.value) >= 4.0) {
        promedioElement.classList.add('promedio-alto'); // Aplica azul si es mayor o igual a 4
    }
}

// Función para aplicar estilo a las notas
function aplicarEstiloNotas(notas) {
    notas.forEach(function(nota) {
        const valor = parseFloat(nota.value);
        nota.classList.remove('nota-roja', 'nota-azul'); // Limpiar clases antes de aplicar

        if (!isNaN(valor)) {
            if (valor < 4.0) {
                nota.classList.add('nota-roja'); // Aplica rojo si es menor a 4
            } else if (valor >= 4.0 && valor <= 7.0) {
                nota.classList.add('nota-azul'); // Aplica azul si está entre 4 y 7
            }
        }
    });
}

// Función para actualizar los promedios
function actualizarPromedios() {
    let promedioGeneralTotal = 0;
    let asignaturasCount = 0;

    document.querySelectorAll('tr').forEach(function(row) {
        const notas1S = row.querySelectorAll('.semestre-1'); // Notas del primer semestre
        const notas2S = row.querySelectorAll('.semestre-2'); // Notas del segundo semestre
        const promedio1S = row.querySelector('.promedio-1s');
        const promedio2S = row.querySelector('.promedio-2s');
        const promedioFinal = row.querySelector('.promedio-final');

        // Calcular promedios
        if (promedio1S) {
            promedio1S.value = calcularPromedio(notas1S);
            aplicarEstiloPromedio(promedio1S);
        }

        if (promedio2S) {
            promedio2S.value = calcularPromedio(notas2S);
            aplicarEstiloPromedio(promedio2S);
        }

        // Calcular promedio final si ambos semestres tienen promedios
        if (promedio1S && promedio2S && promedioFinal) {
            const p1 = parseFloat(promedio1S.value);
            const p2 = parseFloat(promedio2S.value);
            if (!isNaN(p1) && !isNaN(p2)) {
                promedioFinal.value = Math.round((p1 + p2) / 2 * 10) / 10; // Redondear a un decimal
                aplicarEstiloPromedio(promedioFinal);
                promedioGeneralTotal += parseFloat(promedioFinal.value);
                asignaturasCount++;
            } else {
                promedioFinal.value = '';
                promedioFinal.classList.remove('promedio-bajo', 'promedio-alto');
            }
        }

        // Aplicar colores a las notas
        aplicarEstiloNotas(notas1S);
        aplicarEstiloNotas(notas2S);
    });

    // Calcular y mostrar el promedio general
    const promedioGeneral = document.querySelector('.promedio-general');
    if (promedioGeneral && asignaturasCount > 0) {
        const promedioFinalGeneral = Math.round((promedioGeneralTotal / asignaturasCount) * 10) / 10;
        promedioGeneral.value = promedioFinalGeneral;
        aplicarEstiloPromedio(promedioGeneral);
    }
}

// Llamar a la función para actualizar los promedios después de cargar las notas
document.addEventListener('DOMContentLoaded', function() {
    actualizarPromedios();
});

</script>
</body>
</html>

