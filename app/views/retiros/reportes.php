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
    <div class="mx-auto max-w-6xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Convivencia Escolar</p>
            <h1 class="text-2xl font-bold text-strong font-display">Generar reporte de retiros</h1>
        </div>
        <a href="index.php?action=retiros_create"
            class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <circle cx="9" cy="8" r="4"/><path d="M2 20c0-4 4-6 7-6s7 2 7 6"/>
                <line x1="19" y1="8" x2="19" y2="14"/><line x1="16" y1="11" x2="22" y2="11"/>
            </svg>
            Registrar
        </a>
    </div>
</header>

<main class="mx-auto max-w-4xl px-4 py-6 sm:px-6">

    <form method="GET" action="index.php" id="formReporte" class="space-y-5">
        <input type="hidden" name="action" value="retiros_reporte">

        <!-- Tipo de reporte -->
        <div class="panel rounded-xl p-5">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-muted mb-4">Tipo de reporte</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                <label class="cursor-pointer rounded-xl border-2 p-4 transition
                              has-[:checked]:border-azul-vivo has-[:checked]:bg-azul-vivo/10"
                       style="border-color: var(--border-soft);">
                    <input type="radio" name="tipo" value="general" class="sr-only" checked>
                    <div class="flex flex-col gap-2">
                        <div class="card-icon-badge h-8 w-8 rounded-lg flex items-center justify-center">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6M4 20h16"/>
                            </svg>
                        </div>
                        <p class="text-strong text-sm font-semibold">Reporte general</p>
                        <p class="text-muted text-xs">Todos los retiros del período seleccionado</p>
                    </div>
                </label>

                <label class="cursor-pointer rounded-xl border-2 p-4 transition
                              has-[:checked]:border-azul-vivo has-[:checked]:bg-azul-vivo/10"
                       style="border-color: var(--border-soft);">
                    <input type="radio" name="tipo" value="curso" class="sr-only">
                    <div class="flex flex-col gap-2">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                             style="background: var(--warn-soft); color: var(--warn);">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                            </svg>
                        </div>
                        <p class="text-strong text-sm font-semibold">Por curso</p>
                        <p class="text-muted text-xs">Retiros de un curso específico</p>
                    </div>
                </label>

                <label class="cursor-pointer rounded-xl border-2 p-4 transition
                              has-[:checked]:border-azul-vivo has-[:checked]:bg-azul-vivo/10"
                       style="border-color: var(--border-soft);">
                    <input type="radio" name="tipo" value="alumno" class="sr-only">
                    <div class="flex flex-col gap-2">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                             style="background: var(--success-soft); color: var(--success);">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <p class="text-strong text-sm font-semibold">Individual</p>
                        <p class="text-muted text-xs">Historial de retiros de un alumno</p>
                    </div>
                </label>

            </div>
        </div>

        <!-- Parámetros -->
        <div class="panel rounded-xl p-5">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-muted mb-4">Parámetros</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-xs font-medium text-muted mb-1.5">Año académico</label>
                    <select name="anio_id" class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                        <option value="">Todos los años</option>
                        <?php foreach ($anios as $a): ?>
                            <option value="<?= $a['id'] ?>">
                                <?= $a['anio'] ?><?= $a['descripcion'] ? ' — ' . $a['descripcion'] : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-muted mb-1.5">Semestre</label>
                    <select name="semestre" class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                        <option value="">Ambos semestres</option>
                        <option value="1">1° Semestre</option>
                        <option value="2">2° Semestre</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-muted mb-1.5">Fecha desde</label>
                    <input type="date" name="fecha_desde"
                        value="<?= htmlspecialchars($_GET['fecha_desde'] ?? '') ?>"
                        class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                </div>

                <div>
                    <label class="block text-xs font-medium text-muted mb-1.5">Fecha hasta</label>
                    <input type="date" name="fecha_hasta"
                        value="<?= htmlspecialchars($_GET['fecha_hasta'] ?? '') ?>"
                        class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                </div>

                <div id="bloqueCurso">
                    <label class="block text-xs font-medium text-muted mb-1.5">Curso</label>
                    <select name="curso_id" class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                        <option value="">Todos los cursos</option>
                        <?php foreach ($cursos as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="bloqueAlumno" class="hidden">
                    <label class="block text-xs font-medium text-muted mb-1.5">Alumno</label>
                    <select name="alumno_id" id="selectAlumno"
                        class="input-field w-full text-sm rounded-lg px-3 py-2 cursor-pointer">
                        <option value="">— Selecciona primero un curso —</option>
                        <?php foreach ($alumnos as $al): ?>
                            <option value="<?= $al['id'] ?>" data-curso="<?= $al['curso_id'] ?>">
                                <?= htmlspecialchars($al['apepat'] . ' ' . $al['apemat'] . ', ' . $al['nombre'] . ' — ' . $al['run']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
        </div>

        <!-- Acciones -->
        <div class="flex items-center justify-end gap-3">
            <button type="submit" name="formato" value="html"
                class="btn-primary px-5 py-2.5 text-sm font-semibold rounded-lg transition">
                Ver reporte
            </button>
            <button type="submit" name="formato" value="pdf"
                class="btn-danger flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Exportar PDF
            </button>
        </div>

    </form>

</main>

<script>
    const radios      = document.querySelectorAll('input[name="tipo"]');
    const bloqueCurso = document.getElementById('bloqueCurso');
    const bloqueAlumno= document.getElementById('bloqueAlumno');
    const selectCurso = document.querySelector('select[name="curso_id"]');
    const selectAlumno= document.getElementById('selectAlumno');
    const todosAlumnos= Array.from(selectAlumno.options);

    function actualizarBloques() {
        const tipo = document.querySelector('input[name="tipo"]:checked').value;
        bloqueCurso.classList.toggle('hidden', tipo === 'general');
        bloqueAlumno.classList.toggle('hidden', tipo !== 'alumno');
        if (tipo === 'alumno') filtrarAlumnos();
    }

    function filtrarAlumnos() {
        const cursoId = selectCurso.value;
        selectAlumno.innerHTML = '';
        selectAlumno.appendChild(new Option(
            cursoId ? '— Seleccionar alumno —' : '— Selecciona primero un curso —', ''
        ));
        todosAlumnos.forEach(opt => {
            if (!opt.value) return;
            if (!cursoId || opt.dataset.curso === cursoId)
                selectAlumno.appendChild(opt.cloneNode(true));
        });
    }

    selectCurso.addEventListener('change', () => {
        if (document.querySelector('input[name="tipo"]:checked').value === 'alumno')
            filtrarAlumnos();
    });

    radios.forEach(r => r.addEventListener('change', actualizarBloques));
    actualizarBloques();
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>