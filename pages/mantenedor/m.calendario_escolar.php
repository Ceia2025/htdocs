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
<?php include 'navbar.php'; ?>
<?php include 'config.php'; ?>

<?php
// Inicializar variables
$anio = "";
$meses_dias = array_fill(1, 12, '');

// Obtener el año actual
$current_year = date('Y');

// Guardar datos
if (isset($_POST['save'])) {
    $anio = $_POST['anio'];
    $meses_dias = $_POST['meses_dias'];

    foreach ($meses_dias as $mes => $dias) {
        if (!empty($dias)) {
            $result = $con->query("SELECT * FROM m_escolar WHERE anio='$anio' AND mes='$mes'");
            if ($result->num_rows > 0) {
                $con->query("UPDATE m_escolar SET dias='$dias' WHERE anio='$anio' AND mes='$mes'") or die($con->error);
                $message = "Calendario Actualizado";
            } else {
                $con->query("INSERT INTO m_escolar (anio, mes, dias) VALUES ('$anio', '$mes', '$dias')") or die($con->error);
                $message = "Calendario Agregado";
            }
        }
    }

    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var modal = new bootstrap.Modal(document.getElementById("successModal"));
            document.getElementById("modalMessage").textContent = "' . $message . '";
            modal.show();
        });
    </script>';
}

// Cargar datos existentes al seleccionar un año
if (isset($_POST['load_year'])) {
    $anio = $_POST['anio'];
    $result = $con->query("SELECT * FROM m_escolar WHERE anio='$anio'");
    while ($row = $result->fetch_assoc()) {
        $meses_dias[$row['mes']] = $row['dias'];
    }
}
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
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Mantenedor Calendario Escolar</h2>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-8">
                <form action="" method="post" class="mt-4">
                    <div class="mb-3">
                        <label for="anio" class="form-label">Año Escolar</label>
                        <select class="form-control" id="anio" name="anio" onchange="this.form.submit()" required>
                            <option value="" disabled selected>Seleccione el año</option>
                            <?php
                                $start_year = $current_year - 5;
                                $end_year = $current_year + 5;
                                for ($i = $start_year; $i <= $end_year; $i++) {
                                    $selected = ($i == $anio) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                            ?>
                        </select>
                        <input type="hidden" name="load_year" value="1">
                    </div>
                </form>
                
                <form action="" method="post" class="mt-4">
                    <input type="hidden" name="anio" value="<?php echo $anio; ?>">
                    <div class="row">
                        <?php
                            $months = [
                                "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                            ];
                            foreach ($months as $index => $month) {
                                $month_value = $index + 1;
                                $checked = !empty($meses_dias[$month_value]) ? 'checked' : '';
                                $dias_value = !empty($meses_dias[$month_value]) ? $meses_dias[$month_value] : '';
                                echo '
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="' . $month_value . '" id="mes_' . $month_value . '" name="meses[' . $month_value . ']" ' . $checked . '>
                                            <label class="form-check-label" for="mes_' . $month_value . '">' . $month . '</label>
                                            <input type="number" class="form-control mt-2" name="meses_dias[' . $month_value . ']" value="' . $dias_value . '" placeholder="Cantidad de Días">
                                        </div>
                                    </div>
                                ';
                            }
                        ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-4" name="save">Guardar</button>
                </form>
                <a href="mostrar_calendario.php?anio=<?php echo $anio; ?>" class="btn btn-secondary w-100 mt-3">Ver Calendario</a>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Éxito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalMessage">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>

<?php $con->close(); ?>
