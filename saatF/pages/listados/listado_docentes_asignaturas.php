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
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>C.E.I.A -> S.A.A.T.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <style>
        /* Estilos para la impresión */
        @media print {
            #printButton, 
            .navbar {
                display: none;
            }
            .no-print {
                display: none;
            }
            .asignatura {
                color: white; /* Asegúrate de que el texto sea legible al imprimir */
                font-weight: bold;
                text-align: center;
            }
        }

        /* Asignar colores dinámicos a las asignaturas */
        .asignatura {
            padding: 5px;
            color: white; /* Color de texto para la vista normal */
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">LISTADO DE DOCENTES Y SUS ASIGNATURAS</h2>

    <p></p>
            <div class='text-center no-print'>
                    <button id='printButton' class='btn btn-success' onclick='window.print()'>
                        <i class='bi bi-printer'></i> Imprimir
                    </button>
                    <p></p>
                  </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>DOCENTE</th>
                <th>ASIGNATURAS</th>
            </tr>
        </thead>
        <tbody>
            <?php
        

            $sql = "SELECT d.nombre AS docente_nombre, d.apellido AS docente_apellido, GROUP_CONCAT(a.nombre ORDER BY a.nombre SEPARATOR ', ') AS asignaturas
                    FROM docentes AS d
                    INNER JOIN docente_asignatura AS da ON d.id = da.docente_id
                    INNER JOIN asignaturas AS a ON da.asignatura_id = a.id
                    GROUP BY d.id
                    ORDER BY d.apellido, d.nombre";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["docente_nombre"] . " " . $row["docente_apellido"] . "</td>";
                    echo "<td>" . $row["asignaturas"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No hay docentes asignados a asignaturas.</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
