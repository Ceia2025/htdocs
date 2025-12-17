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
                    <a class="descargar" href="<?= $urlCarpeta . $img ?>" download>Descargar</a>
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
    <form action="optimizar_lote.php" method="post" onsubmit="return confirm('¿Optimizar nuevas imágenes?');">
        <button class="boton-pag">
            🔄 Optimizar nuevas imágenes
        </button>
    </form>

</body>

</html>