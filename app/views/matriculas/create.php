<?php
require_once __DIR__ . "/../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];
include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";
?>

<header class="page-header">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-strong font-display">Nueva Matrícula</h1>
    </div>
</header>

<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex justify-center">

        <div class="panel p-8 rounded-2xl shadow-lg w-full max-w-lg">
            <h2 class="text-2xl font-bold text-strong font-display mb-6 text-center">Registrar Matrícula</h2>

            <form method="POST" action="index.php?action=matricula_store" class="space-y-5">

                <!-- Buscador de alumno -->
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Alumno</label>
                    <div class="relative">
                        <input type="text" id="alumno_search" autocomplete="off"
                            placeholder="Buscar por nombre o RUN..."
                            class="input-field w-full rounded-lg px-3 py-2.5 text-sm">
                        <input type="hidden" name="alumno_id" id="alumno_id">
                        <ul id="alumno_list"
                            class="dropdown-panel divide-soft hidden absolute z-50 w-full mt-1 rounded-lg max-h-52 overflow-y-auto divide-y">
                        </ul>
                    </div>
                </div>

                <!-- Curso -->
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Curso</label>
                    <select name="curso_id" id="curso_id" required
                        class="input-field w-full rounded-lg px-4 py-2.5 cursor-pointer">
                        <option value="">Seleccione curso</option>
                        <?php foreach ($cursos as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Año -->
                <div>
                    <label class="block text-sm font-medium text-soft mb-2">Año académico</label>
                    <select name="anio_id" id="anio_id" required
                        class="input-field w-full rounded-lg px-4 py-2.5 cursor-pointer">
                        <option value="">Seleccione año</option>
                        <?php foreach ($anios as $an): ?>
                            <option value="<?= $an['id'] ?>"><?= htmlspecialchars($an['anio']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn-soft-success w-full font-semibold py-2.5 rounded-lg transition">
                    Guardar
                </button>
            </form>
        </div>
    </div>

    <div class="mt-6 flex items-center justify-center">
        <a href="index.php?action=matriculas" class="btn-secondary inline-block rounded-md px-4 py-2 text-sm font-semibold shadow">
            Volver
        </a>
    </div>
</main>

<script>
    const searchInput = document.getElementById('alumno_search');
    const hiddenInput = document.getElementById('alumno_id');
    const list        = document.getElementById('alumno_list');

    searchInput.addEventListener('input', function () {
        const term = this.value.trim();
        if (term.length < 2) { list.classList.add('hidden'); return; }

        fetch(`index.php?action=alumno_search_ajax_matricula&term=${encodeURIComponent(term)}`)
            .then(res => res.json())
            .then(data => {
                list.innerHTML = '';
                if (!Array.isArray(data) || data.length === 0) { list.classList.add('hidden'); return; }

                data.forEach(item => {
                    const li = document.createElement('li');
                    const nombreCompleto = `${item.apepat} ${item.apemat}, ${item.nombre}`;
                    const retirado = item.deleted_at ? ' ⚠️ Retirado' : '';

                    li.textContent = `${nombreCompleto} (${item.run})${retirado}`;
                    li.className = [
                        'dropdown-link px-3 py-2.5 cursor-pointer text-sm transition',
                        item.deleted_at ? 'text-warn' : 'text-strong'
                    ].join(' ');

                    li.addEventListener('click', () => {
                        searchInput.value = nombreCompleto;
                        hiddenInput.value = item.id;
                        list.classList.add('hidden');
                    });

                    list.appendChild(li);
                });

                list.classList.remove('hidden');
            })
            .catch(err => {
                console.error('Error buscando alumnos:', err);
                list.classList.add('hidden');
            });
    });

    document.addEventListener('click', (e) => {
        if (!list.contains(e.target) && e.target !== searchInput)
            list.classList.add('hidden');
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>