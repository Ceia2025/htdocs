<?php
require_once __DIR__ . "/../../../controllers/AuthController.php";
$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];
include __DIR__ . "/../../layout/header.php";
include __DIR__ . "/../../layout/navbar.php";
?>

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <!-- HEADER -->
        <header class="bg-gray-800 border-b border-white/10">
            <div class="mx-auto max-w-5xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-400 mb-0.5">
                        Reportes · Notas
                    </p>
                    <h1 class="text-2xl font-bold text-white">Reporte de Notas</h1>
                    <p class="text-sm text-gray-400 mt-0.5">
                        Exporta informes de notas por alumno o por asignatura
                    </p>
                </div>
                <a href="index.php?action=reportes" class="flex items-center gap-2 text-sm text-gray-400 hover:text-white
                      border border-gray-600 hover:border-gray-400 px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Volver a reportes
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6 space-y-6">

            <!-- ── FILTROS GLOBALES ── -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-5">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-4">
                    Seleccionar curso y año
                </h2>
                <div class="flex flex-wrap items-end gap-4">

                    <!-- Año -->
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Año académico</label>
                        <select id="sel-anio" class="bg-gray-900 text-white text-sm border border-gray-600 rounded-lg
                                   px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php foreach ($anios as $a): ?>
                                <option value="<?= $a['id'] ?>" <?= $a['id'] == $anioId ? 'selected' : '' ?>>
                                    <?= $a['anio'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Curso -->
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Curso</label>
                        <select id="sel-curso" class="bg-gray-900 text-white text-sm border border-gray-600 rounded-lg
                                   px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[180px]">
                            <option value="">— Seleccionar curso —</option>
                            <?php foreach ($cursos as $c): ?>
                                <option value="<?= $c['curso_id'] ?>">
                                    <?= htmlspecialchars($c['curso'] ?? $c['curso_nombre'] ?? '—') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Semestre -->
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Semestre</label>
                        <select id="sel-semestre" class="bg-gray-900 text-white text-sm border border-gray-600 rounded-lg
                                   px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1">1° Semestre</option>
                            <option value="2">2° Semestre</option>
                        </select>
                    </div>

                </div>
            </div>

            <!-- ── DOS OPCIONES DE REPORTE ── -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <!-- CARD 1: Informe individual del alumno -->
                <div class="bg-gray-800 border border-blue-700/40 rounded-xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-700/60 flex items-start gap-4">
                        <div
                            class="w-11 h-11 rounded-xl bg-blue-900/30 flex items-center justify-center text-xl flex-shrink-0">
                            👤
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-sm">Informe Individual del Alumno</h3>
                            <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                Genera el informe completo de un alumno con todas sus asignaturas
                                en ambos semestres, promedios y asistencia.
                            </p>
                        </div>
                    </div>
                    <div class="px-5 py-4 space-y-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">Seleccionar alumno</label>
                            <select id="sel-alumno" class="w-full bg-gray-900 text-white text-xs border border-gray-600 rounded-lg
                                       px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">— Primero selecciona un curso —</option>
                            </select>
                        </div>
                        <button id="btn-pdf-alumno" onclick="descargarPdfAlumno()" disabled class="w-full flex items-center justify-center gap-2 px-4 py-2.5
                                   bg-blue-700 hover:bg-blue-600 text-white font-semibold
                                   rounded-xl transition text-sm
                                   disabled:opacity-40 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5
                                     M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Descargar informe
                        </button>
                    </div>
                </div>

                <!-- CARD 2: Notas por asignatura -->
                <div class="bg-gray-800 border border-indigo-700/40 rounded-xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-700/60 flex items-start gap-4">
                        <div
                            class="w-11 h-11 rounded-xl bg-indigo-900/30 flex items-center justify-center text-xl flex-shrink-0">
                            📚
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-sm">Notas por Asignatura</h3>
                            <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                Exporta todas las notas de un ramo específico para el curso completo,
                                con promedios por columna y porcentaje de aprobación.
                            </p>
                        </div>
                    </div>
                    <div class="px-5 py-4 space-y-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">Seleccionar asignatura</label>
                            <select id="sel-asignatura" class="w-full bg-gray-900 text-white text-xs border border-gray-600 rounded-lg
                                       px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">— Primero selecciona un curso —</option>
                            </select>
                        </div>
                        <button id="btn-pdf-asignatura" onclick="descargarPdfAsignatura()" disabled class="w-full flex items-center justify-center gap-2 px-4 py-2.5
                                   bg-indigo-700 hover:bg-indigo-600 text-white font-semibold
                                   rounded-xl transition text-sm
                                   disabled:opacity-40 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5
                                     M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Descargar notas del ramo
                        </button>
                    </div>

                </div>

                <div class="bg-gray-800 border border-teal-700/40 rounded-xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-700/60 flex items-start gap-4">
                        <div
                            class="w-11 h-11 rounded-xl bg-teal-900/30 flex items-center justify-center text-xl flex-shrink-0">
                            📊
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-sm">Consolidado de Notas</h3>
                            <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                                Genera una tabla con todos los alumnos del curso y el promedio
                                de cada asignatura en el semestre seleccionado.
                            </p>
                        </div>
                    </div>
                    <div class="px-5 py-4 space-y-3">
                        <p class="text-xs text-gray-500">
                            Usa el curso, año y semestre seleccionados arriba.
                        </p>
                        <button id="btn-pdf-consolidado" onclick="descargarPdfConsolidado()" disabled class="w-full flex items-center justify-center gap-2 px-4 py-2.5
                   bg-teal-700 hover:bg-teal-600 text-white font-semibold
                   rounded-xl transition text-sm
                   disabled:opacity-40 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5
                       M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Descargar consolidado
                        </button>
                    </div>
                </div>

            </div>

            <!-- Aviso estado -->
            <div id="aviso-curso" class="hidden text-center py-6 text-gray-600 text-sm">
                Selecciona un curso para cargar alumnos y asignaturas
            </div>

        </main>
    </div>
</body>

<script>
    const anioId = document.getElementById('sel-anio').value;

    // Al cambiar curso → cargar alumnos y asignaturas vía fetch
    document.getElementById('sel-curso').addEventListener('change', function () {
        const cursoId = this.value;
        const anioVal = document.getElementById('sel-anio').value;

        const selAlumno = document.getElementById('sel-alumno');
        const selAsig = document.getElementById('sel-asignatura');
        const btnAlumno = document.getElementById('btn-pdf-alumno');
        const btnAsig = document.getElementById('btn-pdf-asignatura');

        // Reset
        selAlumno.innerHTML = '<option value="">— Cargando... —</option>';
        selAsig.innerHTML = '<option value="">— Cargando... —</option>';
        btnAlumno.disabled = true;
        btnAsig.disabled = true;
        document.getElementById('btn-pdf-consolidado').disabled = true;

        if (!cursoId) {
            selAlumno.innerHTML = '<option value="">— Primero selecciona un curso —</option>';
            selAsig.innerHTML = '<option value="">— Primero selecciona un curso —</option>';
            return;
        }

        document.getElementById('btn-pdf-consolidado').disabled = false;

        // Cargar alumnos
        fetch(`index.php?action=api_alumnos_curso&curso_id=${cursoId}&anio_id=${anioVal}`)
            .then(r => r.json())
            .then(data => {
                selAlumno.innerHTML = '<option value="">— Seleccionar alumno —</option>';
                data.forEach(a => {
                    const opt = document.createElement('option');
                    opt.value = a.matricula_id;
                    opt.textContent = a.apepat + ' ' + a.apemat + ', ' + a.nombre;
                    selAlumno.appendChild(opt);
                });
            })
            .catch(() => {
                selAlumno.innerHTML = '<option value="">Error al cargar alumnos</option>';
            });

        // Cargar asignaturas
        fetch(`index.php?action=api_asignaturas_curso&curso_id=${cursoId}`)
            .then(r => r.json())
            .then(data => {
                selAsig.innerHTML = '<option value="">— Seleccionar asignatura —</option>';
                data.forEach(a => {
                    const opt = document.createElement('option');
                    opt.value = a.id;
                    opt.textContent = a.nombre;
                    selAsig.appendChild(opt);
                });
            })
            .catch(() => {
                selAsig.innerHTML = '<option value="">Error al cargar asignaturas</option>';
            });
    });

    // Habilitar botones al seleccionar
    document.getElementById('sel-alumno').addEventListener('change', function () {
        document.getElementById('btn-pdf-alumno').disabled = !this.value;
    });
    document.getElementById('sel-asignatura').addEventListener('change', function () {
        document.getElementById('btn-pdf-asignatura').disabled = !this.value;
    });
    document.getElementById('sel-anio').addEventListener('change', function () {
        // Recargar la página con el nuevo año
        window.location.href = 'index.php?action=reportes_notas&anio_id=' + this.value;
    });

    function descargarPdfAlumno() {
        const matriculaId = document.getElementById('sel-alumno').value;
        if (!matriculaId) return;
        window.open('index.php?action=reportes_notas_pdf_alumno&matricula_id=' + matriculaId, '_blank');
    }

    function descargarPdfAsignatura() {
        const cursoId = document.getElementById('sel-curso').value;
        const anioVal = document.getElementById('sel-anio').value;
        const asignaturaId = document.getElementById('sel-asignatura').value;
        const semestre = document.getElementById('sel-semestre').value;
        if (!cursoId || !asignaturaId) return;
        window.open(
            `index.php?action=reportes_notas_pdf_asignatura&curso_id=${cursoId}&anio_id=${anioVal}&asignatura_id=${asignaturaId}&semestre=${semestre}`,
            '_blank'
        );
    }

    function descargarPdfConsolidado() {
        const cursoId = document.getElementById('sel-curso').value;
        const anioVal = document.getElementById('sel-anio').value;
        const semestre = document.getElementById('sel-semestre').value;
        if (!cursoId) return;
        window.open(
            `index.php?action=reportes_notas_pdf_consolidado&curso_id=${cursoId}&anio_id=${anioVal}&semestre=${semestre}`,
            '_blank'
        );
    }
</script>

<?php include __DIR__ . "/../../layout/footer.php"; ?>