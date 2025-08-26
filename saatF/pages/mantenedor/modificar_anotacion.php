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
include('../../includes/conexion.php'); // Archivo donde se conecta a la base de datos

// Abrir la conexión a la base de datos
$database = new Connection();
$db = $database->open();

// Obtener el run del alumno desde la URL
$alum_run = isset($_GET['run']) ? $_GET['run'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idanotaciones = $_POST['idanotaciones'];
    $tipo = $_POST['tipo'];
    $anotacion = $_POST['anotacion'];
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : null;
    $alum_run = $_POST['alum_run']; // Asegurarse de obtener el valor de alum_run desde el formulario

    try {
        $sql = "UPDATE anotaciones SET tipo = :tipo, anotacion = :anotacion, categoria = :categoria WHERE idanotaciones = :idanotaciones";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':anotacion', $anotacion);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':idanotaciones', $idanotaciones);
        $stmt->execute();

        // Redirigir al archivo principal con el mensaje de éxito y el run del alumno
        echo '<script>
                alert("Anotación modificada exitosamente");
                window.location.href = "m.alum.reg.ver.php?run=' . htmlspecialchars($alum_run) . '&message=Anotación modificada exitosamente";
              </script>';
        exit();
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger" role="alert">Error al modificar la anotación: ' . $e->getMessage() . '</div>';
    }
} else {
    $idanotaciones = isset($_GET['id']) ? $_GET['id'] : '';
    $anotacion = [];

    try {
        $sql = "SELECT idanotaciones, tipo, anotacion, categoria FROM anotaciones WHERE idanotaciones = :idanotaciones";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':idanotaciones', $idanotaciones);
        $stmt->execute();
        $anotacion = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$anotacion) {
            $anotacion = ['idanotaciones' => '', 'tipo' => '', 'anotacion' => '', 'categoria' => ''];
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger" role="alert">Error al obtener la anotación: ' . $e->getMessage() . '</div>';
    }
}
?>

<form method="post" action="modificar_anotacion.php">
    <input type="hidden" name="idanotaciones" value="<?php echo htmlspecialchars($anotacion['idanotaciones']); ?>">
    <!-- Incluye el run del alumno en un campo oculto -->
    <input type="hidden" name="alum_run" value="<?php echo htmlspecialchars($alum_run); ?>">

    <div class="form-group">
        <label for="tipo">Tipo de Anotación:</label>
        <select name="tipo" id="tipo" class="form-control" onchange="toggleCategoria()">
            <option value="N" <?php if ($anotacion['tipo'] == 'N') echo 'selected'; ?>>Negativa</option>
            <option value="P" <?php if ($anotacion['tipo'] == 'P') echo 'selected'; ?>>Positiva</option>
            <option value="O" <?php if ($anotacion['tipo'] == 'O') echo 'selected'; ?>>Otro</option>
        </select>
    </div>

    <div class="form-group">
        <label for="anotacion">Anotación:</label>
        <textarea name="anotacion" id="anotacion" class="form-control" rows="4"><?php echo htmlspecialchars($anotacion['anotacion']); ?></textarea>
    </div>

    <div class="form-group" id="categoriaGroup" style="display: <?php echo ($anotacion['tipo'] == 'N') ? 'block' : 'none'; ?>;">
        <label for="categoria">Categoría:</label>
        <select name="categoria" id="categoria" class="form-control">
            <option value="GMA" <?php if ($anotacion['categoria'] == 'GMA') echo 'selected'; ?>>Gravisima</option>
            <option value="G" <?php if ($anotacion['categoria'] == 'G') echo 'selected'; ?>>Grave</option>
            <option value="L" <?php if ($anotacion['categoria'] == 'L') echo 'selected'; ?>>Leve</option>
        </select>
    </div>

    <!-- Botón de guardar en el formulario de modificación -->
    <button type="button" class="btn btn-primary" id="showConfirmSaveModal">Guardar</button>

    <!-- Modal de confirmación de guardar -->
    <div class="modal fade" id="confirmSaveModal" tabindex="-1" role="dialog" aria-labelledby="confirmSaveModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmSaveModalLabel">Confirmar Guardar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas guardar los cambios?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    // Al hacer clic en el botón "Guardar", muestra el modal de confirmación
    $('#showConfirmSaveModal').click(function() {
        $('#confirmSaveModal').modal('show');
    });
});

function toggleCategoria() {
    var tipo = document.getElementById('tipo').value;
    var categoriaGroup = document.getElementById('categoriaGroup');
    if (tipo === 'N') {
        categoriaGroup.style.display = 'block';
    } else {
        categoriaGroup.style.display = 'none';
    }
}

// Ejecutar la función al cargar la página para asegurar que el campo de categoría esté correctamente mostrado/oculto
toggleCategoria();
</script>
