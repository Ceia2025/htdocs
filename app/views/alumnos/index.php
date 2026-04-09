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
?>

<main>
    <!-- HEADER -->
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Ficha de Alumnos</h1>
        </div>
    </header>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        <!-- BOTÓN NUEVO ALUMNO -->
        <div class="mb-6 flex justify-between">
            <input id="searchInput" type="text" placeholder="Buscar por RUN o Nombre..."
                class="px-4 py-2 rounded-lg border border-gray-600 bg-gray-800 text-gray-200 focus:ring focus:ring-indigo-500 w-1/2" />

            <a href="index.php?action=alumnos_stepper"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition duration-200">
                Nueva Ficha de Alumno
            </a>
        </div>


        <!-- TABLA -->
        <div class="overflow-x-auto bg-gray-900 rounded-3xl shadow-lg">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-950/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                            Nombre Completo</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                            Contacto</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                            Fecha Nac.</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                            Estado</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-200 uppercase tracking-wider">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    <?php foreach ($alumnos as $alumno):
                        $edad = null;
                        if (!empty($alumno['fechanac'])) {
                            $fechaNac = new DateTime($alumno['fechanac']);
                            $hoy = new DateTime();
                            $edad = $hoy->diff($fechaNac)->y;
                        }

                        $iniciales = strtoupper(
                            substr($alumno['nombre'] ?? '', 0, 1) .
                            substr($alumno['apepat'] ?? '', 0, 1)
                        );
                        ?>
                        <tr class="hover:bg-gray-800/50 transition group cursor-pointer"
                            onclick="window.location='index.php?action=alumno_profile&id=<?= $alumno['id'] ?>';">

                            <!-- 👤 Alumno -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-600/20 border border-indigo-600/30 
                flex items-center justify-center text-indigo-400 text-xs font-bold">
                                        <?= $iniciales ?>
                                    </div>

                                    <div>
                                        <p class="text-sm font-semibold text-white capitalize">
                                            <?= htmlspecialchars($alumno['nombre'] . " " . $alumno['apepat'] . " " . $alumno['apemat']) ?>
                                        </p>
                                        <p class="text-xs text-gray-500 font-mono">
                                            <?= htmlspecialchars($alumno['run'] . '-' . $alumno['codver']) ?>
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            <?= $edad ? $edad . " años" : "Edad no registrada" ?>
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- 📞 Contacto -->
                            <td class="px-5 py-4">
                                <p class="text-sm text-gray-300"><?= htmlspecialchars($alumno['email']) ?></p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    <?= !empty($alumno['telefono']) ? '📞 ' . htmlspecialchars($alumno['telefono']) : '<span class="italic">Sin teléfono</span>' ?>
                                </p>
                            </td>

                            <!-- 📅 Fechas -->
                            <td class="px-5 py-4 text-sm text-gray-300">
                                <p>
                                    🎂
                                    <?= !empty($alumno['fechanac']) ? (new DateTime($alumno['fechanac']))->format('d/m/Y') : 'No registrada' ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    📌 Ingreso:
                                    <?= !empty($alumno['created_at']) ? (new DateTime($alumno['created_at']))->format('d/m/Y') : '—' ?>
                                </p>
                            </td>

                            <!-- Estado -->
                            <td class="px-5 py-4">
                                <?php if (!empty($alumno['deleted_at'])): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg border text-xs font-semibold 
                bg-red-900/40 border-red-500 text-red-300">
                                        ❌ Retirado<br>
                                        <?= (new DateTime($alumno['deleted_at']))->format('d/m/Y') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg border text-xs font-semibold 
                bg-green-900/40 border-green-500 text-green-300">
                                        ✅ Activo
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Acciones -->
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="index.php?action=alumno_edit&id=<?= $alumno['id'] ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg
                bg-indigo-700/30 hover:bg-indigo-700/60 border border-indigo-600/40
                text-indigo-300 text-xs font-semibold transition">
                                        ✏️ Editar
                                    </a>

                                    <a href="index.php?action=alumno_delete&id=<?= $alumno['id'] ?>"
                                        onclick="return confirm('¿Eliminar este alumno?')" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg
                bg-red-900/30 hover:bg-red-900/60 border border-red-700/40
                text-red-400 text-xs font-semibold transition">
                                        🗑️ Eliminar
                                    </a>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <!-- VOLVER -->
        <div class="mt-8 flex items-center justify-center">
            <a href="index.php?action=dashboard"
                class="inline-block rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600">
                ⬅️ Volver al Dashboard
            </a>
        </div>
    </div>
</main>

<script>
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.querySelector('tbody');

    function formatRunForSearch(value) {
        let numeric = value.replace(/\D/g, "");
        if (!numeric) return value;
        return numeric.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    searchInput.addEventListener('blur', () => {
        const value = searchInput.value.trim();
        if (/^\d+$/.test(value)) {
            searchInput.value = formatRunForSearch(value);
        }
    });

    searchInput.addEventListener('input', async () => {
        let term = searchInput.value.trim();

        if (/^\d+$/.test(term)) {
            term = formatRunForSearch(term);
        }

        const response = await fetch(`index.php?action=alumno_search&term=${encodeURIComponent(term)}`);
        if (!response.ok) return;

        const alumnos = await response.json();
        tableBody.innerHTML = '';

        if (alumnos.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-300">
                        No se encontraron resultados
                    </td>
                </tr>`;
            return;
        }

        alumnos.forEach(alumno => {
            const iniciales = (
                (alumno.nombre?.charAt(0) || '') +
                (alumno.apepat?.charAt(0) || '')
            ).toUpperCase();

            const edadTexto = alumno.edad ? `${alumno.edad} años` : 'Edad no registrada';

            const fechaNac = alumno.fechanac
                ? new Date(alumno.fechanac).toLocaleDateString('es-CL', { timeZone: 'UTC' })
                : 'No registrada';

            const fechaIngreso = alumno.created_at
                ? new Date(alumno.created_at).toLocaleDateString('es-CL', { timeZone: 'UTC' })
                : '—';

            const estadoHtml = alumno.deleted_at
                ? `<span class="inline-flex items-center px-2.5 py-1 rounded-lg border text-xs font-semibold
                        bg-red-900/40 border-red-500 text-red-300">
                        ❌ Retirado<br>${new Date(alumno.deleted_at).toLocaleDateString('es-CL', { timeZone: 'UTC' })}
                   </span>`
                : `<span class="inline-flex items-center px-2.5 py-1 rounded-lg border text-xs font-semibold
                        bg-green-900/40 border-green-500 text-green-300">
                        ✅ Activo
                   </span>`;

            const row = document.createElement('tr');
            row.className = "hover:bg-gray-800/50 transition group cursor-pointer";
            row.onclick = () => window.location = `index.php?action=alumno_profile&id=${alumno.id}`;

            row.innerHTML = `
                <!-- Alumno -->
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-600/20 border border-indigo-600/30
                            flex items-center justify-center text-indigo-400 text-xs font-bold">
                            ${iniciales}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white capitalize">
                                ${alumno.nombre} ${alumno.apepat} ${alumno.apemat}
                            </p>
                            <p class="text-xs text-gray-500 font-mono">
                                ${alumno.run}-${alumno.codver}
                            </p>
                            <p class="text-xs text-gray-400">${edadTexto}</p>
                        </div>
                    </div>
                </td>

                <!-- Contacto -->
                <td class="px-5 py-4">
                    <p class="text-sm text-gray-300">${alumno.email ?? ''}</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        ${alumno.telefono ? '📞 ' + alumno.telefono : '<span class="italic">Sin teléfono</span>'}
                    </p>
                </td>

                <!-- Fechas -->
                <td class="px-5 py-4 text-sm text-gray-300">
                    <p>🎂 ${fechaNac}</p>
                    <p class="text-xs text-gray-500 mt-1">📌 Ingreso: ${fechaIngreso}</p>
                </td>

                <!-- Estado -->
                <td class="px-5 py-4">${estadoHtml}</td>

                <!-- Acciones -->
                <td class="px-5 py-4 text-center">
                    <div class="flex items-center justify-center gap-2" onclick="event.stopPropagation()">
                        <a href="index.php?action=alumno_edit&id=${alumno.id}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg
                                  bg-indigo-700/30 hover:bg-indigo-700/60 border border-indigo-600/40
                                  text-indigo-300 text-xs font-semibold transition">
                            ✏️ Editar
                        </a>
                        <a href="index.php?action=alumno_delete&id=${alumno.id}"
                           onclick="return confirm('¿Eliminar este alumno?')"
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg
                                  bg-red-900/30 hover:bg-red-900/60 border border-red-700/40
                                  text-red-400 text-xs font-semibold transition">
                            🗑️ Eliminar
                        </a>
                    </div>
                </td>
            `;

            tableBody.appendChild(row);
        });
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>