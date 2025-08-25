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
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']); // Eliminar el mensaje de la sesión después de mostrarlo
} else {
    $mensaje = null;
}

include '../../includes/navbar.php';
include_once '../../includes/config.php';
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla Dinámica</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<div class="container mt-5">
<h2 class="text-center mb-4">Rendimiento Académico Cierre Anual</h2>
    <form id="form-curso">
        <label for="curso" class="form-label">Selecciona Curso:</label>
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
        <button type="button" id="cargar-datos" class="btn btn-primary mt-3">Cargar Datos</button>
    </form>

    <div class="mt-5">
        <table class="table table-bordered" id="tabla-alumnos">
            <!-- Aquí se cargará la tabla dinámicamente -->
        </table>
    </div>
</div>

<script>
    document.getElementById("cargar-datos").addEventListener("click", function() {
        const cursoId = document.getElementById("curso").value;

        if (!cursoId) {
            alert("Seleccione un curso");
            return;
        }

        fetch("cargar_datos.php?curso_id=" + cursoId)
            .then(response => response.json())
            .then(data => {
                const tabla = document.getElementById("tabla-alumnos");
                tabla.innerHTML = "";

                // Crear encabezado
                const thead = document.createElement("thead");
                const headerRow = document.createElement("tr");
                headerRow.innerHTML = `
                    <th>N/L</th>
                    <th>RUN</th>
                    <th>Nombre</th>
                `;
                data.asignaturas.forEach(asignatura => {
                    const th = document.createElement("th");
                    th.textContent = asignatura.nombre;
                    headerRow.appendChild(th);
                });
                headerRow.innerHTML += `
                    <th>Promedio General</th>
                    <th>% Asistencia</th>
                    <th>Observaciones</th>
                `;
                thead.appendChild(headerRow);
                tabla.appendChild(thead);

                // Crear cuerpo
                const tbody = document.createElement("tbody");
                data.alumnos.forEach((alumno, index) => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${alumno.run}</td>
                        <td>${alumno.nombre_completo}</td>
                    `;
                    data.asignaturas.forEach(() => {
                        row.innerHTML += `<td></td>`; // Celdas vacías para notas
                    });
                    row.innerHTML += `
                        <td></td> <!-- Promedio General -->
                        <td></td> <!-- % Asistencia -->
                        <td></td> <!-- Observaciones -->
                    `;
                    tbody.appendChild(row);
                });
                tabla.appendChild(tbody);
            });
    });
</script>
</body>
</html>