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

<header class="page-header">
    <div class="mx-auto max-w-5xl px-4 py-5 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">
                Reportes · Notas
            </p>
            <h1 class="text-2xl font-bold text-strong font-display">Reporte de Notas</h1>
            <p class="text-sm text-muted mt-0.5">
                Exporta informes de notas por alumno o por asignatura
            </p>
        </div>
        <a href="index.php?action=reportes"
            class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Volver a reportes
        </a>
    </div>
</header>

<main class="mx-auto max-w-5xl px-4 py-8 sm:px-6 space-y-6">

    <!-- ── FILTROS GLOBALES ── -->
    <div class="panel rounded-xl p-5">
        <h2 class="text-xs font-semibold uppercase tracking-wider text-muted mb-4">
            Seleccionar curso y año
        </h2>
        <div class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs text-muted mb-1.5">Año académico</label>
                <select id="sel-anio" class="input-field text-sm rounded-lg px-3 py-2">
                    <?php foreach ($anios as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= $a['id'] == $anioId ? 'selected' : '' ?>>
                            <?= $a['anio'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs text-muted mb-1.5">Curso</label>
                <select id="sel-curso" class="input-field text-sm rounded-lg px-3 py-2 min-w-[180px]">
                    <option value="">— Seleccionar curso —</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= $c['curso_id'] ?>">
                            <?= htmlspecialchars($c['curso'] ?? $c['curso_nombre'] ?? '—') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs text-muted mb-1.5">Semestre</label>
                <select id="sel-semestre" class="input-field text-sm rounded-lg px-3 py-2">
                    <option value="1">1° Semestre</option>
                    <option value="2">2° Semestre</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ── CARDS DE REPORTE (2 columnas, todas igual tamaño) ── -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

        <!-- CARD 1: Informe individual del alumno -->
        <div class="report-card report-blue rounded-xl overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b divider-soft flex items-start gap-4">
                <div class="report-icon-bg w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                    👤
                </div>
                <div>
                    <h3 class="text-strong font-bold text-sm font-display">Informe Individual del Alumno</h3>
                    <p class="text-xs text-muted mt-1 leading-relaxed">
                        Genera el informe completo de un alumno con todas sus asignaturas
                        en ambos semestres, promedios y asistencia.
                    </p>
                </div>
            </div>
            <div class="px-5 py-4 space-y-3 flex-1 flex flex-col justify-between">
                <div>
                    <label class="block text-xs text-muted mb-1.5">Seleccionar alumno</label>
                    <select id="sel-alumno" class="input-field w-full text-xs rounded-lg px-3 py-2">
                        <option value="">— Primero selecciona un curso —</option>
                    </select>
                </div>
                <button id="btn-pdf-alumno" onclick="descargarPdfAlumno()" disabled class="report-btn w-full flex items-center justify-center gap-2 px-4 py-2.5
                           font-semibold rounded-xl transition text-sm
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
        <div class="report-card report-indigo rounded-xl overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b divider-soft flex items-start gap-4">
                <div class="report-icon-bg w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                    📚
                </div>
                <div>
                    <h3 class="text-strong font-bold text-sm font-display">Notas por Asignatura</h3>
                    <p class="text-xs text-muted mt-1 leading-relaxed">
                        Exporta todas las notas de un ramo específico para el curso completo,
                        con promedios por columna y porcentaje de aprobación.
                    </p>
                </div>
            </div>
            <div class="px-5 py-4 space-y-3 flex-1 flex flex-col justify-between">
                <div>
                    <label class="block text-xs text-muted mb-1.5">Seleccionar asignatura</label>
                    <select id="sel-asignatura" class="input-field w-full text-xs rounded-lg px-3 py-2">
                        <option value="">— Primero selecciona un curso —</option>
                    </select>
                </div>
                <button id="btn-pdf-asignatura" onclick="descargarPdfAsignatura()" disabled class="report-btn w-full flex items-center justify-center gap-2 px-4 py-2.5
                           font-semibold rounded-xl transition text-sm
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

        <!-- CARD 3: Consolidado de notas -->
        <div class="report-card report-teal rounded-xl overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b divider-soft flex items-start gap-4">
                <div class="report-icon-bg w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                    📊
                </div>
                <div>
                    <h3 class="text-strong font-bold text-sm font-display">Consolidado de Notas</h3>
                    <p class="text-xs text-muted mt-1 leading-relaxed">
                        Genera una tabla con todos los alumnos del curso y el promedio
                        de cada asignatura en el semestre seleccionado.
                    </p>
                </div>
            </div>
            <div class="px-5 py-4 space-y-3 flex-1 flex flex-col justify-between">
                <p class="text-xs text-muted">
                    Usa el curso, año y semestre seleccionados arriba.
                </p>
                <button id="btn-pdf-consolidado" onclick="descargarPdfConsolidado()" disabled class="report-btn w-full flex items-center justify-center gap-2 px-4 py-2.5
                           font-semibold rounded-xl transition text-sm
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

        <!-- CARD 4: Ranking de Notas -->
        <div class="report-card report-amber rounded-xl overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b divider-soft flex items-start gap-4">
                <div class="report-icon-bg w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                    🏆
                </div>
                <div>
                    <h3 class="text-strong font-bold text-sm font-display">Ranking de Notas</h3>
                    <p class="text-xs text-muted mt-1 leading-relaxed">
                        Todos los alumnos y asignaturas ordenados de menor a mayor promedio,
                        agrupados por curso · Año <span id="ranking-anio-label">—</span>
                    </p>
                </div>
            </div>
            <div class="px-5 py-4 space-y-3 flex-1 flex flex-col justify-between">
                <p class="text-xs text-muted">
                    El ranking incluye todos los cursos del año seleccionado automáticamente.
                </p>
                <div class="flex gap-3">
                    <button onclick="abrirModalRanking('alumnos')" id="btn-ranking-alumnos" disabled class="report-btn flex-1 flex items-center justify-center gap-2 px-4 py-2.5
                               font-semibold rounded-xl transition text-sm
                               disabled:opacity-40 disabled:cursor-not-allowed">
                        👤 Alumnos
                    </button>
                    <button onclick="abrirModalRanking('asignaturas')" id="btn-ranking-asignaturas" disabled class="report-btn flex-1 flex items-center justify-center gap-2 px-4 py-2.5
                               font-semibold rounded-xl transition text-sm
                               disabled:opacity-40 disabled:cursor-not-allowed">
                        📚 Asignaturas
                    </button>
                </div>
            </div>
        </div>

        <!-- CARD 5: Ranking de Cursos -->
        <div class="report-card report-amber rounded-xl overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b divider-soft flex items-start gap-4">
                <div class="report-icon-bg w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                    📉
                </div>
                <div>
                    <h3 class="text-strong font-bold text-sm font-display">Cursos por Promedio</h3>
                    <p class="text-xs text-muted mt-1 leading-relaxed">
                        Todos los cursos del año ordenados de menor a mayor promedio general,
                        con porcentaje de aprobación por curso.
                    </p>
                </div>
            </div>
            <div class="px-5 py-4 space-y-3 flex-1 flex flex-col justify-between">
                <p class="text-xs text-muted">
                    Usa el año y semestre seleccionados arriba. No requiere seleccionar curso.
                </p>
                <button onclick="descargarPdfRankingCursos()" class="report-btn w-full flex items-center justify-center gap-2 px-4 py-2.5
                   font-semibold rounded-xl transition text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5
                       M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Descargar ranking de cursos
                </button>
            </div>
        </div>

    </div>

    <div id="aviso-curso" class="hidden text-center py-6 text-faint text-sm">
        Selecciona un curso para cargar alumnos y asignaturas
    </div>

</main>

<!-- ══════════════════════════════════════════════════════════
     MODAL RANKING
     ══════════════════════════════════════════════════════════ -->
<div id="modal-ranking"
    class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="report-amber modal-panel rounded-2xl w-full max-w-4xl max-h-[90vh] flex flex-col shadow-2xl">

        <!-- Header modal -->
        <div class="flex items-center justify-between px-6 py-4 border-b divider-soft">
            <div>
                <h2 class="text-strong font-bold text-lg font-display" id="modal-ranking-titulo">Ranking de Notas</h2>
                <p class="text-xs text-muted mt-0.5" id="modal-ranking-subtitulo">—</p>
            </div>
            <button onclick="cerrarModalRanking()" class="icon-btn transition p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Pestañas + filtro curso -->
        <div class="flex items-center border-b divider-soft px-6">
            <button id="tab-alumnos" onclick="cambiarTab('alumnos')"
                class="tab-btn is-active px-4 py-3 text-sm font-semibold">
                👤 Por Alumno
            </button>
            <button id="tab-asignaturas" onclick="cambiarTab('asignaturas')"
                class="tab-btn px-4 py-3 text-sm font-semibold">
                📚 Por Asignatura
            </button>
            <div id="filtro-curso-wrap" class="ml-auto flex items-center gap-2 py-2">
                <label class="text-xs text-muted whitespace-nowrap">Filtrar curso:</label>
                <select id="sel-ranking-curso" onchange="filtrarCurso(this.value)"
                    class="input-field text-xs rounded-lg px-2 py-1.5">
                    <option value="">— Todos —</option>
                </select>
            </div>
        </div>

        <!-- Contenido scrolleable -->
        <div class="flex-1 overflow-y-auto px-6 py-4" id="modal-ranking-contenido">
            <div class="flex items-center justify-center py-12 text-muted text-sm">
                <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 5.373 0 0 12h4z"></path>
                </svg>
                Cargando ranking...
            </div>
        </div>

        <!-- Footer modal -->
        <div class="px-6 py-4 border-t divider-soft flex justify-between items-center">
            <p class="text-xs text-muted">
                Ordenado de menor a mayor promedio · Semestre <span id="modal-sem-label">—</span>
            </p>
            <button onclick="descargarPdfRanking()" class="report-btn flex items-center gap-2 px-5 py-2.5
                       font-semibold rounded-xl transition text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5
                           M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Descargar PDF
            </button>
        </div>

    </div>
</div>

<script>
    // ── Estado global ─────────────────────────────────────────────
    let rankingTab = 'alumnos';
    let rankingData = null;

    // ── Año → recarga página ──────────────────────────────────────
    document.getElementById('sel-anio').addEventListener('change', function () {
        window.location.href = 'index.php?action=reportes_notas&anio_id=' + this.value;
    });

    // ── Curso → carga alumnos y asignaturas ───────────────────────
    document.getElementById('sel-curso').addEventListener('change', function () {
        const cursoId = this.value;
        const anioVal = document.getElementById('sel-anio').value;
        const selAlumno = document.getElementById('sel-alumno');
        const selAsig = document.getElementById('sel-asignatura');

        selAlumno.innerHTML = '<option value="">— Cargando... —</option>';
        selAsig.innerHTML = '<option value="">— Cargando... —</option>';
        document.getElementById('btn-pdf-alumno').disabled = true;
        document.getElementById('btn-pdf-asignatura').disabled = true;
        document.getElementById('btn-pdf-consolidado').disabled = true;

        if (!cursoId) {
            selAlumno.innerHTML = '<option value="">— Primero selecciona un curso —</option>';
            selAsig.innerHTML = '<option value="">— Primero selecciona un curso —</option>';
            return;
        }

        document.getElementById('btn-pdf-consolidado').disabled = false;

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
            .catch(() => { selAlumno.innerHTML = '<option value="">Error al cargar alumnos</option>'; });

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
            .catch(() => { selAsig.innerHTML = '<option value="">Error al cargar asignaturas</option>'; });
    });

    // ── Habilitar botones al seleccionar ─────────────────────────
    document.getElementById('sel-alumno').addEventListener('change', function () {
        document.getElementById('btn-pdf-alumno').disabled = !this.value;
    });
    document.getElementById('sel-asignatura').addEventListener('change', function () {
        document.getElementById('btn-pdf-asignatura').disabled = !this.value;
    });

    // ── Ranking: habilitar al cargar (año siempre presente) ───────
    (function () {
        const sel = document.getElementById('sel-anio');
        document.getElementById('ranking-anio-label').textContent =
            sel.options[sel.selectedIndex].text;
        document.getElementById('btn-ranking-alumnos').disabled = false;
        document.getElementById('btn-ranking-asignaturas').disabled = false;
    })();

    // ── Descargas ─────────────────────────────────────────────────
    function descargarPdfAlumno() {
        const id = document.getElementById('sel-alumno').value;
        if (!id) return;
        window.open('index.php?action=reportes_notas_pdf_alumno&matricula_id=' + id, '_blank');
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
    function descargarPdfRanking() {
        const anioVal = document.getElementById('sel-anio').value;
        const semestre = document.getElementById('sel-semestre').value;
        window.open(
            `index.php?action=reportes_notas_pdf_ranking&anio_id=${anioVal}&semestre=${semestre}&tipo=${rankingTab}`,
            '_blank'
        );
    }

    // ════════════════════════════════════════════════════════════
    // MODAL RANKING
    // ════════════════════════════════════════════════════════════
    function abrirModalRanking(tab) {
        rankingTab = tab;
        rankingData = null;
        document.getElementById('modal-ranking').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        const anioVal = document.getElementById('sel-anio').value;
        const semestre = document.getElementById('sel-semestre').value;
        const anioTxt = document.getElementById('sel-anio').options[
            document.getElementById('sel-anio').selectedIndex].text;

        document.getElementById('modal-ranking-subtitulo').textContent =
            `Año ${anioTxt} · ${semestre}° Semestre · Todos los cursos`;
        document.getElementById('modal-sem-label').textContent = semestre + '°';
        document.getElementById('sel-ranking-curso').innerHTML = '<option value="">— Todos —</option>';

        activarTab(tab);
        cargarRanking(anioVal, semestre);
    }

    function cerrarModalRanking() {
        document.getElementById('modal-ranking').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModalRanking(); });
    document.getElementById('modal-ranking').addEventListener('click', function (e) {
        if (e.target === this) cerrarModalRanking();
    });

    function cambiarTab(tab) {
        rankingTab = tab;
        activarTab(tab);
        if (rankingData) renderRanking(rankingData);
    }

    function activarTab(tab) {
        ['alumnos', 'asignaturas'].forEach(t => {
            document.getElementById('tab-' + t).classList.toggle('is-active', t === tab);
        });
        document.getElementById('modal-ranking-titulo').textContent =
            tab === 'alumnos' ? '🏆 Ranking de Alumnos por Promedio' : '📊 Ranking de Asignaturas por Promedio';
        document.getElementById('filtro-curso-wrap').style.display =
            tab === 'alumnos' ? 'flex' : 'none';
    }

    function cargarRanking(anioId, semestre) {
        mostrarCargando();
        fetch(`index.php?action=api_ranking&anio_id=${anioId}&semestre=${semestre}`)
            .then(r => r.json())
            .then(data => { rankingData = data; renderRanking(data); })
            .catch(() => {
                document.getElementById('modal-ranking-contenido').innerHTML =
                    '<p class="text-danger text-sm text-center py-8">Error al cargar el ranking.</p>';
            });
    }

    function mostrarCargando() {
        document.getElementById('modal-ranking-contenido').innerHTML = `
        <div class="flex items-center justify-center py-12 text-muted text-sm">
            <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 5.373 0 0 12h4z"></path>
            </svg>
            Cargando ranking...
        </div>`;
    }

    function renderRanking(data) {
        const contenido = document.getElementById('modal-ranking-contenido');

        if (rankingTab === 'alumnos') {
            const cursos = data.alumnos ?? {};
            const cursoKeys = Object.keys(cursos);

            if (cursoKeys.length === 0) {
                contenido.innerHTML = '<p class="text-muted text-sm text-center py-8">Sin datos para este año/semestre.</p>';
                return;
            }

            // Poblar selector de cursos
            const selCurso = document.getElementById('sel-ranking-curso');
            const cursoActual = selCurso.value;
            selCurso.innerHTML = '<option value="">— Todos —</option>';
            cursoKeys.forEach(nombre => {
                const opt = document.createElement('option');
                opt.value = nombre;
                opt.textContent = nombre;
                if (nombre === cursoActual) opt.selected = true;
                selCurso.appendChild(opt);
            });

            let html = '';
            cursoKeys.forEach(cursoNombre => {
                const alumnos = cursos[cursoNombre];
                html += `<div class="mb-6" data-curso="${cursoNombre}">
                <div class="flex items-center gap-2 mb-2">
                    <span class="report-badge text-xs font-bold px-3 py-1 rounded-full">
                        ${cursoNombre}
                    </span>
                    <span class="text-faint text-xs">${alumnos.length} alumno${alumnos.length !== 1 ? 's' : ''}</span>
                </div>
                <table class="data-table w-full text-xs">
                    <thead>
                        <tr>
                            <th class="text-left px-3 py-2 text-muted font-semibold">#</th>
                            <th class="text-left px-3 py-2 text-muted font-semibold">Alumno</th>
                            <th class="text-center px-3 py-2 text-muted font-semibold">Promedio</th>
                        </tr>
                    </thead>
                    <tbody>`;

                alumnos.forEach((a, i) => {
                    const prom = a.promedio;
                    const color = prom === null ? 'text-faint' : (prom >= 4.0 ? 'text-success' : 'text-danger');
                    const promTxt = prom !== null ? parseFloat(prom).toFixed(1) : 'S/N';
                    // Verifica pendiente usando matricula_id
                    const tienePendiente = data.pendientes_alumnos?.[cursoNombre]?.includes(a.matricula_id);
                    const badgePendiente = tienePendiente
                        ? `<span class="chip-warn ml-2 text-[10px] font-normal px-1.5 py-0.5 rounded-full">⚠ Faltan notas</span>`
                        : '';

                    html += `
                    <tr>
                        <td class="px-3 py-2 text-muted font-bold">${i + 1}</td>
                        <td class="px-3 py-2 text-strong font-semibold">
                            ${a.apepat} ${a.apemat}
                            <span class="text-muted font-normal">${a.nombre}</span>
                            ${badgePendiente}
                        </td>
                        <td class="px-3 py-2 text-center font-bold text-base ${color}">${promTxt}</td>
                    </tr>`;
                });

                html += `</tbody></table></div>`;
            });

            contenido.innerHTML = html;
            if (cursoActual) filtrarCurso(cursoActual);

        } else {
            const asigs = data.asignaturas ?? [];

            if (asigs.length === 0) {
                contenido.innerHTML = '<p class="text-muted text-sm text-center py-8">Sin datos para este año/semestre.</p>';
                return;
            }

            let html = `
        <table class="data-table w-full text-xs">
            <thead>
                <tr>
                    <th class="text-left px-3 py-2 text-muted font-semibold">#</th>
                    <th class="text-left px-3 py-2 text-muted font-semibold">Asignatura</th>
                    <th class="text-left px-3 py-2 text-muted font-semibold">Curso</th>
                    <th class="text-center px-3 py-2 text-muted font-semibold">Promedio</th>
                </tr>
            </thead>
            <tbody>`;

            asigs.forEach((a, i) => {
                const prom = a.promedio;
                const color = prom === null ? 'text-faint' : (prom >= 4.0 ? 'text-success' : 'text-danger');
                const promTxt = prom !== null ? parseFloat(prom).toFixed(1) : 'S/N';
                const faltanAlumnos = data.pendientes_asignaturas?.some(
                    p => p.asignatura === a.asignatura && p.curso === a.curso
                );
                const badgeAsig = faltanAlumnos
                    ? `<span class="chip-warn ml-2 text-[10px] font-normal px-1.5 py-0.5 rounded-full">⚠ Faltan alumnos con nota</span>`
                    : '';

                html += `
                <tr>
                    <td class="px-3 py-2 text-muted font-bold">${i + 1}</td>
                    <td class="px-3 py-2 text-strong font-semibold">${a.asignatura}${badgeAsig}</td>
                    <td class="px-3 py-2 text-muted">${a.curso}</td>
                    <td class="px-3 py-2 text-center font-bold text-base ${color}">${promTxt}</td>
                </tr>`;
            });

            html += `</tbody></table>`;
            contenido.innerHTML = html;
        }
    }

    function filtrarCurso(cursoNombre) {
        document.querySelectorAll('#modal-ranking-contenido [data-curso]').forEach(el => {
            el.style.display = (!cursoNombre || el.dataset.curso === cursoNombre) ? '' : 'none';
        });
    }

    function descargarPdfRankingCursos() {
        const anioVal = document.getElementById('sel-anio').value;
        const semestre = document.getElementById('sel-semestre').value;
        window.open(
            `index.php?action=reportes_notas_pdf_ranking_cursos&anio_id=${anioVal}&semestre=${semestre}`,
            '_blank'
        );
    }
</script>

<?php include __DIR__ . "/../../layout/footer.php"; ?>