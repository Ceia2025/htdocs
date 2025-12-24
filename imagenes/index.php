<?php
// === CONFIGURACIÓN BASE ===
$baseDir = __DIR__ . '/images/';
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/images/';


error_reporting(E_ALL & ~E_WARNING);


// Obtener carpeta actual desde GET (por defecto primera)
$subcarpeta = $_GET['folder'] ?? '';
$directorio = $baseDir . ($subcarpeta ? $subcarpeta . '/' : '');
$urlCarpeta = $baseUrl . ($subcarpeta ? $subcarpeta . '/' : '');

// === LISTAR SUBCARPETAS ===
$subcarpetas = array_values(array_filter(scandir($baseDir), function ($item) use ($baseDir) {
    return $item !== '.' && $item !== '..' && is_dir($baseDir . $item);
}));

// === LISTAR IMÁGENES ===
if (is_dir($directorio)) {
    $archivos = scandir($directorio);
} else {
    $archivos = [];
}

$extensiones = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$imagenes = array_values(array_filter($archivos, function ($archivo) use ($directorio, $extensiones) {
    $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
    return in_array($extension, $extensiones) && is_file($directorio . $archivo);
}));

// === PAGINACIÓN ===
$porPagina = 20;
$total = count($imagenes);
$paginaActual = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$inicio = ($paginaActual - 1) * $porPagina;
$imagenesPagina = array_slice($imagenes, $inicio, $porPagina);
$totalPaginas = max(1, ceil($total / $porPagina));
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Galería de Imágenes</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Galería de Imágenes</h1>

    <!-- === SELECTOR DE SUBCARPETAS === -->
    <div class="carpetas">
        <strong>Álbumes:</strong>
        <?php foreach ($subcarpetas as $carp): ?>
            <a href="?folder=<?= urlencode($carp) ?>" class="<?= $carp === $subcarpeta ? 'activo' : '' ?>">
                📁 <?= htmlspecialchars($carp) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- === GALERÍA === -->
    <div class="galeria">
        <?php if (empty($imagenesPagina)): ?>
            <p>No hay imágenes en esta carpeta.</p>
        <?php else: ?>
            <?php foreach ($imagenesPagina as $index => $img): ?>
                <div class="imagen">
                    <img class="lazy" data-src="<?= $urlCarpeta . $img ?>" data-full="<?= $urlCarpeta . $img ?>"
                        alt="<?= htmlspecialchars($img) ?>" onclick="abrirModal(<?= $index ?>)">
                    <p><?= htmlspecialchars($img) ?></p>
                    <?php
                    $nombreBase = pathinfo($img, PATHINFO_FILENAME);
                    $urlOriginal = str_replace('/images/', '/original/', $urlCarpeta) . $img;
                    ?>
                    <a class="descargar" href="<?= $urlOriginal ?>" download>Descargar</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- === PAGINACIÓN === -->
    <div class="paginacion">
        <?php if ($paginaActual > 1): ?>
            <a href="?folder=<?= urlencode($subcarpeta) ?>&page=<?= $paginaActual - 1 ?>" class="boton-pag prev">
                &laquo; Anterior
            </a>
        <?php endif; ?>

        <div class="contador">
            Página <strong><?= $paginaActual ?></strong> de <strong><?= $totalPaginas ?></strong>
        </div>

        <?php if ($paginaActual < $totalPaginas): ?>
            <a href="?folder=<?= urlencode($subcarpeta) ?>&page=<?= $paginaActual + 1 ?>" class="boton-pag next">
                Siguiente &raquo;
            </a>
        <?php endif; ?>
    </div>

    <!-- === MODAL === -->
    <div id="modal" class="modal">
        <span class="cerrar" onclick="cerrarModal()">&times;</span>
        <span class="flecha izq" onclick="cambiarImagen(-1)">&#10094;</span>

        <a id="descargarModal" class="descargar-modal" href="#" download>⬇ Descargar</a>

        <img id="imagenAmpliada" class="imagen-ampliada" src="" alt="">
        <span class="flecha der" onclick="cambiarImagen(1)">&#10095;</span>
    </div>

    <script src="script.js"></script>

    <footer>
        <form action="optimizar_lote.php" method="post" target="procesoOpt" onsubmit="return iniciarOpt();">
            <button id="btnOptimizar" class="boton-pag" type="submit">
            🔄 Optimizar nuevas imágenes
            </button>
            <div id="progresoBox" style="display:none;max-width:360px;margin:20px auto;">
                <div style="background:#eee;border-radius:12px;overflow:hidden;">
                    <div id="barraProgreso"
                        style="width:0%;height:20px;background:linear-gradient(135deg,#00b3ff,#0078ff);">
                    </div>
                </div>

                <div id="textoProgreso" style="margin-top:8px;color:#fff;font-weight:600;">
                    0%
                </div>

                <div id="tiempoRestante" style="margin-top:6px;color:#ddd;font-size:14px;"></div>

                <button onclick="cancelarOpt()" class="boton-pag" style="margin-top:10px;background:#c0392b;">
                    ❌ Cancelar
                </button>
            </div>

            <iframe name="procesoOpt" style="display:none;"></iframe>
        </form>

        <p id="estadoOpt" style="margin-top:10px;color:#fff;display:none;">
            ⏳ Optimizando imágenes, por favor espera...
        </p>
    </footer>
    <script>
        function iniciarOpt() {
            document.getElementById('btnOptimizar').disabled = true;
            document.getElementById('btnOptimizar').innerText = 'Procesando...';
            document.getElementById('progresoBox').style.display = 'block';
            return true;
        }

        function iniciarProgreso(total) {
            actualizarProgreso(0, 0);
        }

        function actualizarProgreso(porcentaje, segundos) {
            document.getElementById('barraProgreso').style.width = porcentaje + '%';
            document.getElementById('textoProgreso').innerText = porcentaje + '%';

            if (segundos > 0) {
                const min = Math.floor(segundos / 60);
                const sec = segundos % 60;
                document.getElementById('tiempoRestante').innerText =
                    `⏱ Tiempo restante aprox: ${min}m ${sec}s`;
            }
        }

        function finalizarProgreso(optimizadas, total) {
            document.getElementById('textoProgreso').innerText = '✅ Completado';
            document.getElementById('tiempoRestante').innerText =
                `📊 Optimizadas: ${optimizadas} / ${total}`;
            document.getElementById('btnOptimizar').innerText = '✔ Optimizado';
        }

        function cancelarOpt() {
            fetch('cancelar.php');
            document.getElementById('tiempoRestante').innerText = '❌ Cancelando...';
        }

        function cancelado() {
            document.getElementById('textoProgreso').innerText = '⛔ Cancelado';
            document.getElementById('tiempoRestante').innerText = 'Proceso detenido';
        }
    </script>

</body>

</html>