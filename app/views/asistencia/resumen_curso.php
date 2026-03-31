<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth(); // obliga a tener sesión iniciada

$user = $_SESSION['user']; // usuario logueado
$nombre = $user['nombre'];
$rol = $user['rol'];

// Incluir layout
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";


//Incluir el nombre del curso, MODIFICAR EN EL FUTOR, YA QUE ACÁ NO DEBERIA ESTR, SOLO SE PUSO POR ERROR EN EL CONTROLADOR XD
$asistenciaModel = new Asistencia();
$cursoInfo = $asistenciaModel->getCurso($_GET['curso_id'] ?? 0);
$nombreCurso = $cursoInfo['nombre'] ?? 'Curso no encontrado';
?>

<!-- MAIN -->
<header
    class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
    <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-white">Alumnos:
            <span class="text-blue-400"><?= htmlspecialchars($cursoInfo['nombre'] ?? 'Sin Nombre') ?></span>

        </h1>
    </div>
</header>
<main>

    <div class="max-w-7xl mx-auto mt-8 bg-gray-800 p-6 rounded-xl shadow-lg">

        <h2 class="text-2xl font-bold text-white mb-6">
            Resumen de Asistencia del Curso
        </h2>

        <!-- Indicador General -->
        <div class="mb-6 p-4 rounded-lg 
    <?= $porcentaje >= 85 ? 'bg-green-700' :
        ($porcentaje >= 70 ? 'bg-yellow-600' : 'bg-red-700') ?>">

            <h3 class="text-xl font-semibold text-white">
                Asistencia General: <?= $porcentaje ?>%
            </h3>
        </div>

        <!-- Tabla Detallada -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-300">
                <thead class="bg-gray-700 text-gray-200 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-center w-12">#</th>
                        <th class="px-4 py-3">Alumno</th>
                        <th class="px-4 py-3 text-center">Clases</th>
                        <th class="px-4 py-3 text-center">Presentes</th>
                        <th class="px-4 py-3 text-center">Porcentaje hasta la fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php foreach ($detalle as $row): ?>

                        <?php
                        $porcentaje = $row['porcentaje'] ?? 0;  // null coalescing seguro
                        $color = $porcentaje >= 85 ? 'text-green-400' :
                            ($porcentaje >= 70 ? 'text-yellow-400' : 'text-red-400');
                        ?>
                        <?php
                        // 1. Obtener valores (usamos el operador null coalescing para evitar errores si no existen)
                        $total = $row['total_clases'] ?? 0;
                        $asistencias = $row['presentes'] ?? 0;

                        // 2. Calcular porcentaje (evitando división por cero)
                        $porcentaje = ($total > 0) ? round(($asistencias / $total) * 100, 1) : 0;

                        // 3. Determinar el color según el rendimiento
                        if ($porcentaje >= 75) {
                            $color = 'text-green-400';
                        } elseif ($porcentaje >= 50) {
                            $color = 'text-yellow-400';
                        } else {
                            $color = 'text-red-400';
                        }
                        ?>

                        <tr class="hover:bg-gray-700">
                            <td class="px-4 py-2 text-center text-xs font-bold text-indigo-400">
                                <?= $row['numero_lista'] ?? '—' ?>
                            </td>
                            <td class="px-4 py-2">
                                <?= htmlspecialchars($row['apepat'] . ' ' . $row['apemat'] . ', ' . $row['nombre']) ?>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <?= $row['total_clases'] ?? 0 ?>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <?= $row['presentes'] ?? 0 ?>
                            </td>
                            <td class="px-4 py-2 text-center font-bold <?= $color ?>">
                                <?= $porcentaje ?>% <!-- usar $porcentaje, no $row['porcentaje'] -->
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-start gap-4" style="margin-top: 8px">
            <!-- Botón volver -->
            <a href="index.php?action=asistencia_cursos&anio_id=<?= $_GET['anio_id'] ?>" class="flex items-center gap-2 bg-gray-700 hover:bg-gray-600 text-white 
                   font-semibold px-6 py-3 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a cursos
            </a>

            <!--
                        <button id="btnExportarPdf" data-anio="<?= $anioId ?>" data-curso="<?= $cursoId ?>"
                        class="fixed bottom-6 right-6 z-50 flex items-center gap-2 px-5 py-3 bg-red-600 hover:bg-red-500 text-white font-bold rounded-full shadow-2xl transition-all hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span>Exportar Reporte</span>
                    </button>
                    -->
            <button id="btnExportarPdf" data-anio="<?= $anioId ?>" data-curso="<?= $cursoId ?>"
                class="fixed bottom-6 right-6 z-50 flex items-center gap-2 px-5 py-3 bg-red-600 hover:bg-red-500 text-white font-bold rounded-full shadow-2xl transition-all hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span>Exportar Reporte</span>
            </button>

            <div id="container-toast"></div>

        </div>
    </div>

</main>
<script>document.getElementById('btnExportarPdf').addEventListener('click', function () {
        const anioId = this.getAttribute('data-anio');
        const cursoId = this.getAttribute('data-curso');
        const btn = this;
        const span = btn.querySelector('span');

        // Estado de carga
        btn.disabled = true;
        const originalText = span.innerText;
        span.innerText = 'Generando...';

        fetch(`index.php?action=asistencia_pdf&anio_id=${anioId}&curso_id=${cursoId}`)
            .then(response => {
                if (!response.ok) throw new Error('Error al generar el archivo');
                return response.blob();
            })
            .then(blob => {
                // 1. Crear descarga del archivo
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Reporte_Asistencia_${new Date().toLocaleDateString()}.pdf`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);

                // 2. Mostrar tu Toast personalizado
                mostrarToastDescarga();
            })
            .catch(err => alert("Error: " + err.message))
            .finally(() => {
                btn.disabled = false;
                span.innerText = originalText;
            });
    });

    function mostrarToastDescarga() {
        const container = document.getElementById('container-toast');

        // Tu HTML de Toast modificado para descarga
        const toastHtml = `
        <div id="toast-pdf" class="fixed top-10 right-10 z-50 flex items-center gap-3 bg-gray-900 border border-blue-600 
                   text-blue-300 rounded-2xl px-8 py-6 shadow-2xl shadow-blue-900/40 
                   transition-all duration-500 max-w-sm" style="opacity: 0; transform: translateY(-20px);">
            <span class="text-2xl">📄</span>
            <p class="text-lg font-medium">Reporte generado y descargado.</p>
            <button onclick="cerrarToastPdf()" class="ml-auto text-gray-500 hover:text-white text-lg">✕</button>
        </div>
    `;

        container.innerHTML = toastHtml;

        const t = document.getElementById('toast-pdf');

        // Animación de entrada
        setTimeout(() => {
            t.style.opacity = '1';
            t.style.transform = 'translateY(0)';
        }, 100);

        // Auto-cierre
        setTimeout(() => cerrarToastPdf(), 4000);
    }

    function cerrarToastPdf() {
        const t = document.getElementById('toast-pdf');
        if (!t) return;
        t.style.opacity = '0';
        t.style.transform = 'translateY(-10px)';
        setTimeout(() => t.remove(), 500);
    }
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>