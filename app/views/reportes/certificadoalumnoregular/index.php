<?php
require_once __DIR__ . "/../../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();
$user = $_SESSION['user'];
$nombre = $user['nombre'];
$rol = $user['rol'];


include __DIR__ . "/../../layout/header.php";
include __DIR__ . "/../../layout/navbar.php";

// Lee un ícono de app/public/svg/{key}.svg y lo devuelve listo para inyectar inline,
// con la clase de tamaño Tailwind ya insertada en el <svg>.
function saat_icon(string $key, string $classes = 'w-4 h-4'): string
{
    $path = __DIR__ . "/../../../public/svg/{$key}.svg";
    if (!file_exists($path)) {
        return '';
    }
    $svg = file_get_contents($path);
    return preg_replace('/<svg /', '<svg class="' . htmlspecialchars($classes) . '" ', $svg, 1);
}
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
        <a href="index.php?action=reportes"
            class="btn-secondary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
            <?= saat_icon('arrow-left', 'w-4 h-4') ?>
            Volver a reportes
        </a>
    </div>
</header>

<main class="report-indigo mx-auto max-w-4xl px-4 py-8 sm:px-6">

    <!-- BUSCADOR -->
    <div class="panel rounded-2xl overflow-hidden shadow-lg mb-6">
        <div class="px-6 py-4 border-b divider-soft">
            <h2 class="text-strong font-bold text-base flex items-center gap-2 font-display">
                <?= saat_icon('search', 'w-5 h-5') ?> Buscar Alumno
            </h2>
        </div>
        <div class="px-6 py-5">
            <div class="relative">
                <input type="text" id="buscador" placeholder="Escribe nombre o RUN del alumno..." autocomplete="off"
                    class="input-field w-full rounded-xl px-4 py-3 text-sm transition" />
                <!-- Spinner -->
                <div id="spinner" class="absolute right-3 top-3 hidden">
                    <svg class="animate-spin h-5 w-5 text-accent" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                    </svg>
                </div>
            </div>

            <!-- Resultados autocomplete -->
            <ul id="resultados" class="dropdown-panel divide-soft mt-1 rounded-xl overflow-hidden hidden divide-y">
            </ul>
        </div>
    </div>

    <!-- CARD ALUMNO SELECCIONADO -->
    <div id="card-alumno" class="hidden panel rounded-2xl overflow-hidden shadow-lg">
        <div class="px-6 py-4 border-b divider-soft flex items-center gap-3">
            <div class="report-icon-bg w-10 h-10 rounded-xl flex items-center justify-center">
                <?= saat_icon('doc', 'w-5 h-5') ?>
            </div>
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

            <!-- Contenedor en Grid para la proporción 2/3 y 1/3 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Motivo (Ocupa 2 de las 3 columnas en pantallas medianas o más grandes) -->
                <div class="md:col-span-2">
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

                <!-- Fecha (Ocupa 1 de las 3 columnas) -->
                <div class="md:col-span-1">
                    <label for="input-fecha" class="block text-xs text-muted uppercase tracking-wide mb-1.5">
                        Indique fecha <span class="text-faint">(opcional)</span>
                    </label>
                    <input id="input-fecha" type="date" autocomplete="off"
                        class="input-field w-full rounded-xl px-4 py-3 text-sm transition" />
                    <p class="text-xs text-muted mt-1.5">
                        Si se deja vacío, se usará "La fecha de hoy".
                    </p>
                </div>

            </div>

            <!-- Botones -->
            <!-- Botones -->
            <div class="flex flex-col sm:flex-row gap-4">

                <!-- Certificado Normal -->
                <div class="flex-1 flex flex-col gap-2">
                    <a id="btn-normal-ver" href="#" target="_blank" class="report-btn inline-flex items-center justify-center gap-2 px-5 py-4
                       font-bold rounded-xl shadow-lg transition text-base">
                        <?= saat_icon('eye', 'w-5 h-5') ?>
                        Ver Certificado Normal
                    </a>
                    <a id="btn-normal" href="#" target="_blank"
                        class="text-center text-xs text-muted hover:text-strong underline transition">
                        Descargar directo sin previsualizar
                    </a>
                </div>

                <!-- Certificado con Asistencia -->
                <div class="flex-1 flex flex-col gap-2">
                    <a id="btn-asistencia-ver" href="#" target="_blank" class="btn-success inline-flex items-center justify-center gap-2 px-5 py-4
                       font-bold rounded-xl shadow-lg transition text-base">
                        <?= saat_icon('eye', 'w-5 h-5') ?>
                        Ver Certificado con Asistencia
                    </a>
                    <a id="btn-asistencia" href="#" target="_blank"
                        class="text-center text-xs text-muted hover:text-strong underline transition">
                        Descargar directo sin previsualizar
                    </a>
                </div>

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
        const inputMotivo = document.getElementById('input-motivo');
        const inputFecha = document.getElementById('input-fecha');
        const btnNormalVer = document.getElementById('btn-normal-ver');

        const btnAsistVer = document.getElementById('btn-asistencia-ver');

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

        function actualizarEnlaces(id) {
            if (!id) return;
            const m = encodeURIComponent(inputMotivo?.value.trim() || 'los fines que estime conveniente');
            const f = encodeURIComponent(inputFecha?.value || '');
            const base = `alumno_id=${id}&motivo=${m}&fecha=${f}`;

            btnNormal.href = `index.php?action=cert_alumno_regular_pdf&${base}`;
            btnNormalVer.href = `index.php?action=cert_alumno_regular_pdf&${base}&preview=1`;

            btnAsist.href = `index.php?action=cert_alumno_regular_pdf_asistencia&${base}`;
            btnAsistVer.href = `index.php?action=cert_alumno_regular_pdf_asistencia&${base}&preview=1`;
        }

        function seleccionar(a) {
            lista.classList.add('hidden');
            input.value = a.nombre_completo.trim();

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

                    actualizarEnlaces(d.id);
                    cardAlumno.classList.remove('hidden');
                });
        }

        // Eventos de entrada en motivo y fecha
        const listenerCampos = () => {
            if (!btnNormal.href.includes('alumno_id')) return;
            const id = new URLSearchParams(btnNormal.href.split('?')[1]).get('alumno_id');
            actualizarEnlaces(id);
        };

        inputMotivo?.addEventListener('input', listenerCampos);
        inputFecha?.addEventListener('change', listenerCampos);

        document.addEventListener('click', e => {
            if (!e.target.closest('#buscador') && !e.target.closest('#resultados')) {
                lista.classList.add('hidden');
            }
        });
    })();
</script>

<?php include __DIR__ . "/../../layout/footer.php"; ?>