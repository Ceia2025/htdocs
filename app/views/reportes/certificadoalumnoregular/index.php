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
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-0.5">Reportes · SAAT
            </p>
            <h1 class="text-2xl font-bold text-strong font-display">Certificado de Alumno Regular</h1>
            <p class="text-sm text-muted mt-0.5">Busca al alumno y selecciona el tipo de certificado a
                generar</p>
        </div>
        <a href="index.php?action=reportes" class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
            ⬅ Reportes
        </a>
    </div>
</header>

<main class="report-indigo mx-auto max-w-4xl px-4 py-8 sm:px-6">

    <!-- BUSCADOR -->
    <div class="panel rounded-2xl overflow-hidden shadow-lg mb-6">
        <div class="px-6 py-4 border-b divider-soft">
            <h2 class="text-strong font-bold text-base flex items-center gap-2 font-display">
                <span class="text-xl">🔍</span> Buscar Alumno
            </h2>
        </div>
        <div class="px-6 py-5">
            <div class="relative">
                <input type="text" id="buscador" placeholder="Escribe nombre o RUN del alumno..."
                    autocomplete="off" class="input-field w-full rounded-xl px-4 py-3 text-sm transition" />
                <!-- Spinner -->
                <div id="spinner" class="absolute right-3 top-3 hidden">
                    <svg class="animate-spin h-5 w-5 text-accent" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                    </svg>
                </div>
            </div>

            <!-- Resultados autocomplete -->
            <ul id="resultados"
                class="dropdown-panel divide-soft mt-1 rounded-xl overflow-hidden hidden divide-y">
            </ul>
        </div>
    </div>

    <!-- CARD ALUMNO SELECCIONADO -->
    <div id="card-alumno"
        class="hidden panel rounded-2xl overflow-hidden shadow-lg">
        <div class="px-6 py-4 border-b divider-soft flex items-center gap-3">
            <div class="report-icon-bg w-10 h-10 rounded-xl flex items-center justify-center text-xl">👼</div>
            <div>
                <h2 id="card-nombre" class="text-strong font-bold text-base"></h2>
                <p id="card-sub" class="text-xs text-muted mt-0.5"></p>
            </div>
            <!-- badge % asistencia -->
            <div class="ml-auto">
                <span id="card-pct" class="report-badge text-sm font-bold px-3 py-1 rounded-full">
                </span>
            </div>
        </div>

        <!-- Detalle rápido -->
        <div class="px-6 py-4 grid grid-cols-2 sm:grid-cols-4 gap-4 border-b divider-soft">
            <div>
                <p class="text-xs text-muted uppercase tracking-wide">RUN</p>
                <p id="info-run" class="text-sm text-strong font-medium mt-0.5"></p>
            </div>
            <div>
                <p class="text-xs text-muted uppercase tracking-wide">Curso</p>
                <p id="info-curso" class="text-sm text-strong font-medium mt-0.5"></p>
            </div>
            <div>
                <p class="text-xs text-muted uppercase tracking-wide">Año</p>
                <p id="info-anio" class="text-sm text-strong font-medium mt-0.5"></p>
            </div>
            <div>
                <p class="text-xs text-muted uppercase tracking-wide">Días asistidos</p>
                <p id="info-dias" class="text-sm text-strong font-medium mt-0.5"></p>
            </div>
        </div>

        <!-- Motivo + Botones de descarga -->
        <div class="px-6 py-5 border-t divider-soft flex flex-col gap-4">

            <div>
                <label for="input-motivo" class="block text-xs text-muted uppercase tracking-wide mb-1.5">
                    Motivo del certificado <span class="text-faint">(opcional)</span>
                </label>
                <input id="input-motivo" type="text" autocomplete="off"
                    placeholder="Ej: ser presentado ante institución bancaria"
                    class="input-field w-full rounded-xl px-4 py-3 text-sm transition" />
                <p class="text-xs text-muted mt-1.5">
                    Si se deja vacío, se usará "los fines que estime conveniente".
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a id="btn-normal" href="#" target="_blank"
                    class="report-btn flex-1 inline-flex items-center justify-center gap-2 px-5 py-3
                           font-semibold rounded-xl shadow transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    Certificado Normal
                </a>

                <a id="btn-asistencia" href="#" target="_blank"
                    class="btn-success flex-1 inline-flex items-center justify-center gap-2 px-5 py-3
                           font-semibold rounded-xl shadow transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6M9 7h6M3 17V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    Certificado con Asistencia
                </a>
            </div>

        </div>
    </div>

</main>

<script>
    (function () {
        const input = document.getElementById('buscador');
        const lista = document.getElementById('resultados');
        const spinner = document.getElementById('spinner');
        const cardAlumno = document.getElementById('card-alumno');
        const cardNombre = document.getElementById('card-nombre');
        const cardSub = document.getElementById('card-sub');
        const cardPct = document.getElementById('card-pct');
        const infoRun = document.getElementById('info-run');
        const infoCurso = document.getElementById('info-curso');
        const infoAnio = document.getElementById('info-anio');
        const infoDias = document.getElementById('info-dias');
        const btnNormal = document.getElementById('btn-normal');
        const btnAsist = document.getElementById('btn-asistencia');

        let timer = null;

        input.addEventListener('input', function () {
            clearTimeout(timer);
            const val = this.value.trim();
            if (val.length < 2) { lista.classList.add('hidden'); lista.innerHTML = ''; return; }
            spinner.classList.remove('hidden');
            timer = setTimeout(() => buscar(val), 350);
        });

        function buscar(term) {
            fetch(`index.php?action=cert_alumno_regular_buscar&term=${encodeURIComponent(term)}`)
                .then(r => r.json())
                .then(data => {
                    spinner.classList.add('hidden');
                    lista.innerHTML = '';
                    if (!data.length) {
                        lista.innerHTML = `<li class="px-4 py-3 text-sm text-muted italic">Sin resultados</li>`;
                        lista.classList.remove('hidden');
                        return;
                    }
                    data.forEach(a => {
                        const li = document.createElement('li');
                        li.className = 'dropdown-link px-4 py-3 text-sm cursor-pointer flex justify-between items-center transition';
                        li.innerHTML = `
                    <span><strong class="text-strong">${a.nombre_completo.trim()}</strong> <span class="text-muted ml-2">${a.run}-${a.codver ?? ''}</span></span>
                    <span class="report-badge text-xs px-2 py-0.5 rounded-full">${a.curso} · ${a.anio}</span>
                `;
                        li.addEventListener('click', () => seleccionar(a));
                        lista.appendChild(li);
                    });
                    lista.classList.remove('hidden');
                })
                .catch(() => spinner.classList.add('hidden'));
        }

        function seleccionar(a) {
            const motivo = () => encodeURIComponent(
                document.getElementById('input-motivo')?.value.trim()
                || 'los fines que estime conveniente'
            );
            lista.classList.add('hidden');
            input.value = a.nombre_completo.trim();

            // Obtener datos completos (asistencia) via AJAX
            fetch(`index.php?action=cert_alumno_regular_datos&alumno_id=${a.id}`)
                .then(r => r.json())
                .then(d => {
                    cardNombre.textContent = d.nombre_completo;
                    cardSub.textContent = `Matrícula ${d.anio} · ${d.curso}`;
                    cardPct.textContent = `Asistencia: ${d.porcentaje}%`;
                    infoRun.textContent = `${d.run}-${d.codver ?? ''}`;
                    infoCurso.textContent = d.curso;
                    infoAnio.textContent = d.anio;
                    infoDias.textContent = `${d.dias_presentes} / ${d.total_dias}`;

                    btnNormal.href = `index.php?action=cert_alumno_regular_pdf&alumno_id=${d.id}&motivo=${motivo()}`;
                    btnAsist.href = `index.php?action=cert_alumno_regular_pdf_asistencia&alumno_id=${d.id}&motivo=${motivo()}`;

                    cardAlumno.classList.remove('hidden');
                });
        }

        document.getElementById('input-motivo')?.addEventListener('input', function () {
            if (!btnNormal.href.includes('alumno_id')) return; // si no hay alumno seleccionado, no hacer nada
            const id = new URLSearchParams(btnNormal.href.split('?')[1]).get('alumno_id');
            const m = encodeURIComponent(this.value.trim() || 'los fines que estime conveniente');
            btnNormal.href = `index.php?action=cert_alumno_regular_pdf&alumno_id=${id}&motivo=${m}`;
            btnAsist.href = `index.php?action=cert_alumno_regular_pdf_asistencia&alumno_id=${id}&motivo=${m}`;
        });

        // Cierra lista al hacer clic fuera
        document.addEventListener('click', e => {
            if (!e.target.closest('#buscador') && !e.target.closest('#resultados')) {
                lista.classList.add('hidden');
            }
        });
    })();
</script>

<?php include __DIR__ . "/../../layout/footer.php"; ?>