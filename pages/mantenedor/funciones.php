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
include_once '../../includes/config.php';


function agregarDocente($nombre, $apellido, $email) {
    global $conn;
    $sql = "INSERT INTO docentes (nombre, apellido, email) VALUES ('$nombre', '$apellido', '$email')";
    if ($conn->query($sql) === TRUE) {
        echo "Nuevo docente agregado correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function agregarAsignatura($nombre, $descripcion) {
    global $conn;
    $sql = "INSERT INTO asignaturas (nombre, descripcion) VALUES ('$nombre', '$descripcion')";
    if ($conn->query($sql) === TRUE) {
        echo "Nueva asignatura agregada correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function agregarCurso($nombre, $descripcion) {
    global $conn;
    $sql = "INSERT INTO cursos (nombre, descripcion) VALUES ('$nombre', '$descripcion')";
    if ($conn->query($sql) === TRUE) {
        echo "Nuevo curso agregado correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function agregarHorario($dia, $jornada, $inicio, $fin) {
    global $conn;
    $sql = "INSERT INTO horarios (dia, jornada, inicio, fin) VALUES ('$dia', '$jornada', '$inicio', '$fin')";
    if ($conn->query($sql) === TRUE) {
        echo "Nuevo horario agregado correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function asignarAsignaturaADocente($docente_id, $asignatura_id) {
    global $conn;
    $sql = "INSERT INTO docente_asignatura (docente_id, asignatura_id) VALUES ('$docente_id', '$asignatura_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Asignación de docente a asignatura realizada correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function asignarAsignaturaACurso($curso_id, $asignatura_id, $horario_id) {
    // Realizar la conexión a la base de datos
    include 'conexion.php';

    // Preparar la consulta SQL para insertar la asignatura en el curso con el horario
    $sql = "INSERT INTO asignaturas_cursos (curso_id, asignatura_id, horario_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $curso_id, $asignatura_id, $horario_id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Asignatura asignada correctamente al curso con ID: $curso_id";
                 // Redirigir a index.php después de 2 segundos
                 header("Refresh: 2; url=f.asignar_asignatura_curso.php");
                 echo "Redirigiendo a la página principal en 2 segundos...";
    } else {
        echo "Error al asignar la asignatura al curso: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}

function asignarClase($docente_id, $asignatura_id, $curso_id, $horario_id) {
    global $conn;
    $sql = "INSERT INTO clase (docente_id, asignatura_id, curso_id, horario_id) VALUES ('$docente_id', '$asignatura_id', '$curso_id', '$horario_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Clase asignada correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
