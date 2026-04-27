<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();

$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

$asignaturaSeleccionada = (int) ($_GET['asignatura_id'] ?? 0);
?>

<body class="bg-gray-900 text-white min-h-screen">
    <div class="max-w-6xl mx-auto px-4 py-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-600/20 p-3 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Ingreso de Notas</h1>
                    <p class="text-xs text-gray-500 mt-0.5">
                        <?= $semestreActual ?>° Semestre ·
                        <span class="text-indigo-400"><?= htmlspecialchars($_GET['curso_id'] ?? '') ?></span>
                    </p>
                </div>
            </div>
            <a href="index.php?action=notas_panel_asignaturas&curso_id=<?= $_GET['curso_id'] ?>&anio_id=<?= $_GET['anio_id'] ?>"
                class="text-sm text-gray-400 hover:text-white border border-gray-700 hover:border-gray-500 
                  px-4 py-2 rounded-lg transition">
                ← Volver
            </a>
        </div>

        <form method="POST"
            action="index.php?action=notas_storeGroup&curso_id=<?= $_GET['curso_id'] ?>&anio_id=<?= $_GET['anio_id'] ?>"
            class="space-y-5">

            <input type="hidden" name="semestre" value="<?= $semestreActual ?>">

            <!-- Selector asignatura y fecha -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">
                            Asignatura
                        </label>
                        <select name="asignatura_id" id="asignatura_id" required onchange="filtrarNotas(this.value)"
                            class="w-full bg-gray-900 border border-gray-600 rounded-lg px-3 py-2.5 
                                   text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="">— Seleccionar asignatura —</option>
                            <?php foreach ($asignaturas as $asig): ?>
                                <option value="<?= $asig['id'] ?>" <?= $asignaturaSeleccionada == $asig['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($asig['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">
                            Fecha de evaluación
                        </label>
                        <input type="date" name="fecha" required value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>"
                            class="w-full bg-gray-900 border border-gray-600 rounded-lg px-3 py-2.5 
                                  text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">

                <!-- Sin asignatura -->
                <div id="aviso-seleccionar"
                    class="<?= $asignaturaSeleccionada ? 'hidden' : '' ?> px-6 py-16 text-center">
                    <svg class="w-10 h-10 text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-500 text-sm">Selecciona una asignatura para ver las notas</p>
                </div>

                <!-- Tabla real -->
                <div id="tabla-notas" class="<?= $asignaturaSeleccionada ? '' : 'hidden' ?> overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-700 bg-gray-900/50">
                                <th
                                    class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 w-10">
                                    #</th>
                                <th
                                    class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 w-48">
                                    Alumno</th>
                                <th
                                    class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 w-20">
                                    Estado</th>
                                <th
                                    class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Notas anteriores</th>
                                <th
                                    class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 w-20">
                                    Prom.</th>
                                <?php if ($puedeEditar): ?>
                                    <th
                                        class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-indigo-400 w-28 bg-indigo-900/20 border-l border-indigo-800/40">
                                        Nueva nota
                                    </th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/60">
                            <?php foreach ($alumnos as $a):
                                $estaRetirado = !empty($a['fecha_retiro']);
                                $bgFila = $estaRetirado ? 'bg-red-900/10' : 'hover:bg-gray-700/20';
                                ?>
                                <tr class="transition <?= $bgFila ?>" data-matricula="<?= $a['matricula_id'] ?>"
                                    data-alumno-id="<?= $a['alumno_id'] ?>">

                                    <!-- N° Lista -->
                                    <td
                                        class="px-3 py-3 text-center text-xs font-bold <?= $a['numero_lista'] ? 'text-indigo-400' : 'text-gray-600' ?>">
                                        <?= $a['numero_lista'] ?? '—' ?>
                                    </td>

                                    <!-- Nombre — ancho fijo para que no ocupe tanto -->
                                    <td class="px-3 py-3 max-w-[180px]">
                                        <p class="font-semibold text-white text-xs leading-tight truncate">
                                            <?= htmlspecialchars($a['apepat'] . ' ' . $a['apemat']) ?>
                                        </p>
                                        <p class="text-gray-500 text-xs truncate">
                                            <?= htmlspecialchars($a['nombre']) ?>
                                        </p>
                                        <?php if ($estaRetirado): ?>
                                            <span class="text-red-400 text-[10px]">
                                                Ret. <?= date('d/m/Y', strtotime($a['fecha_retiro'])) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-3 py-3 text-center">
                                        <?php if ($estaRetirado): ?>
                                            <span
                                                class="px-2 py-0.5 bg-red-900/40 text-red-300 rounded-full text-xs font-semibold">
                                                Retirado
                                            </span>
                                        <?php else: ?>
                                            <span
                                                class="px-2 py-0.5 bg-green-900/40 text-green-300 rounded-full text-xs font-semibold">
                                                Activo
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Notas anteriores -->
                                    <td class="px-3 py-3 text-center" id="notas-<?= $a['matricula_id'] ?>">
                                        <span class="text-gray-600 text-xs italic">—</span>
                                    </td>

                                    <!-- Promedio -->
                                    <td class="px-3 py-3 text-center font-bold text-sm text-gray-600"
                                        id="prom-<?= $a['matricula_id'] ?>">
                                        —
                                    </td>

                                    <!-- Nueva nota -->
                                    <?php if ($puedeEditar): ?>
                                        <td class="px-3 py-3 text-center bg-indigo-900/10 border-l border-indigo-800/30">
                                            <?php if (!$estaRetirado): ?>
                                                <input type="number" name="notas[<?= $a['alumno_id'] ?>][nota]" step="0.1" min="1"
                                                    max="7" placeholder="—" class="w-20 bg-gray-900 border border-gray-600 rounded-lg 
                                                          text-center text-white text-sm py-1.5
                                                          focus:ring-2 focus:ring-indigo-500 focus:outline-none
                                                          placeholder-gray-600">
                                            <?php else: ?>
                                                <span class="text-gray-700 text-xs">—</span>
                                            <?php endif; ?>
                                            <input type="hidden" name="notas[<?= $a['alumno_id'] ?>][matricula_id]"
                                                value="<?= $a['matricula_id'] ?>">
                                            <input type="hidden" name="notas[<?= $a['alumno_id'] ?>][deleted_at]"
                                                value="<?= $a['fecha_retiro'] ?? '' ?>">
                                        </td>
                                    <?php endif; ?>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Botón guardar -->
            <?php if ($puedeEditar): ?>
                <div class="flex justify-end">
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 
                               text-white font-semibold rounded-xl transition shadow-lg shadow-indigo-900/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Guardar notas
                    </button>
                </div>
            <?php endif; ?>

        </form>
    </div>
</body>

<script>
    const notasExistentes = <?= json_encode($notasExistentes) ?>;
    const puedeEditar = <?= $puedeEditar ? 'true' : 'false' ?>;

    function filtrarNotas(asignaturaId) {
        const tabla = document.getElementById('tabla-notas');
        const aviso = document.getElementById('aviso-seleccionar');

        if (!asignaturaId) {
            tabla.classList.add('hidden');
            aviso.classList.remove('hidden');
            return;
        }

        tabla.classList.remove('hidden');
        aviso.classList.add('hidden');

        const notasAsig = notasExistentes[asignaturaId] ?? {};

        document.querySelectorAll('#tbody-notas tr, tbody tr[data-matricula]').forEach(fila => {
            const matriculaId = fila.dataset.matricula;
            const notasAlumno = notasAsig[matriculaId] ?? [];

            const celdaNotas = document.getElementById('notas-' + matriculaId);
            const celdaProm = document.getElementById('prom-' + matriculaId);

            if (!celdaNotas) return;

            if (notasAlumno.length === 0) {
                celdaNotas.innerHTML = '<span class="text-gray-600 text-xs italic">Sin notas</span>';
                celdaProm.textContent = '—';
                celdaProm.className = 'px-3 py-3 text-center font-bold text-sm text-gray-600';
                return;
            }

            // Ordenar por fecha
            notasAlumno.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));

            let html = '<div class="flex flex-wrap justify-center gap-1.5">';
            let suma = 0;

            notasAlumno.forEach(n => {
                const nota = parseFloat(n.nota).toFixed(1);
                const aprobado = parseFloat(n.nota) >= 4.0;
                suma += parseFloat(n.nota);

                const [anio, mes, dia] = n.fecha.split('-');
                const fechaCorta = `${dia}/${mes}`;

                const colorNota = aprobado ? 'text-green-400' : 'text-red-400';
                const bgTarjeta = aprobado ? 'bg-gray-700/80' : 'bg-red-900/20 border border-red-800/30';

                html += `
        <div class="inline-flex items-center gap-2 ${bgTarjeta} rounded-lg px-3 py-2 min-w-[80px]">
            
            <!-- Nota grande a la izquierda -->
            <span class="font-bold text-xl ${colorNota} leading-none">${nota}</span>
            
            <!-- Fecha y editar a la derecha -->
            <div class="flex flex-col items-start">
                <span class="text-gray-400 text-[10px] leading-tight">${fechaCorta}</span>
                ${puedeEditar
                        ? `<a href="index.php?action=notas_edit&id=${n.id}"
                          class="text-yellow-500 hover:text-yellow-300 text-[10px] leading-tight mt-0.5"
                          title="Editar">✏ editar</a>`
                        : ''
                    }
            </div>
        </div>`;
            });

            html += '</div>';
            celdaNotas.innerHTML = html;

            const prom = (suma / notasAlumno.length).toFixed(1);
            const aprobadoProm = parseFloat(prom) >= 4.0;
            celdaProm.textContent = prom;
            celdaProm.className = 'px-3 py-3 text-center font-bold text-sm ' +
                (aprobadoProm ? 'text-green-400' : 'text-red-400');
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const sel = document.getElementById('asignatura_id');
        if (sel.value) filtrarNotas(sel.value);
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>