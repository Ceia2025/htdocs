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

<?php if (!empty($_GET['guardado'])): ?>
    <div id="toast-ok" class="fixed top-10 right-10 z-50 flex items-center gap-3 bg-gray-900 border border-green-600 
           text-green-300 rounded-2xl px-10 py-10 shadow-2xl shadow-green-900/40 
           transition-all duration-500 max-w-sm">
        <span class="text-2xl">✅</span>
        <p class="text-xl font-medium">Asistencia guardada correctamente.</p>
        <button onclick="cerrarToast()" class="ml-auto text-gray-500 hover:text-white text-lg leading-none">✕</button>
    </div>
    <script>
        setTimeout(() => cerrarToast(), 4000);
        function cerrarToast() {
            const t = document.getElementById('toast-ok');
            if (!t) return;
            t.style.opacity = '0';
            t.style.transform = 'translateY(-10px)';
            setTimeout(() => t.remove(), 400);
        }
    </script>
<?php endif ?>

<body class="h-full bg-gray-900">
    <div class="min-h-full">

        <header class="bg-gray-800 border-b border-white/10">
            <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-400 mb-1">Asistencia</p>
                    <h1 class="text-2xl font-bold text-white">Tomar Asistencia</h1>
                    <p class="text-sm text-gray-400 mt-0.5">Curso:
                        <?= htmlspecialchars($curso['nombre'] ?? '') ?>
                    </p>
                </div>
                <a href="index.php?action=libro_clases&curso_id=<?= $curso['id'] ?>&anio_id=<?= $_GET['anio_id'] ?>"
                    class="flex items-center gap-2 text-sm text-gray-400 hover:text-white border border-gray-600 hover:border-gray-400 px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Ver libro de clases
                </a>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">

                <form method="POST" action="index.php?action=guardar_asistencia_masiva">
                    <input type="hidden" name="curso_id" value="<?= $_GET['curso_id'] ?>">
                    <input type="hidden" name="anio_id" value="<?= $_GET['anio_id'] ?>">

                    <!-- Card fecha + acciones -->
                    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden mb-5">

                        <!-- Fila: fecha y botones de selección -->
                        <div class="flex flex-wrap items-center gap-3 px-5 py-4 border-b border-gray-700">

                            <!-- Fecha -->
                            <!-- Fecha con calendario custom -->
                            <div class="flex items-start gap-2 w-full flex-col">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <label class="text-sm font-medium text-gray-300">Fecha</label>
                                    <button type="button" id="btn-fecha"
                                        class="bg-gray-900 text-white text-sm border border-gray-600 rounded-lg px-3 py-1.5 
                   hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition min-w-[130px] text-left">
                                        📅 <span id="fecha-display"><?= date('d/m/Y') ?></span>
                                    </button>
                                    <input type="hidden" name="fecha" id="campo-fecha" value="<?= date('Y-m-d') ?>">
                                </div>

                                <!-- Aviso de semestre -->
                                <div id="aviso-semestre" class="hidden px-4 py-2 bg-red-900/40 border border-red-700 
         text-red-300 text-xs rounded-lg flex items-center gap-2">
                                    <span>⚠️</span>
                                    <span id="aviso-semestre-texto"></span>
                                </div>

                                <!-- CALENDARIO CUSTOM -->
                                <div id="calendario" class="hidden absolute z-50 mt-1 bg-gray-900 border border-gray-700 
         rounded-2xl shadow-2xl p-4 w-80">

                                    <!-- Nav mes -->
                                    <div class="flex items-center justify-between mb-3">
                                        <button type="button" id="mes-prev"
                                            class="p-1.5 rounded-lg hover:bg-gray-700 text-gray-400 hover:text-white transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <span id="mes-titulo" class="text-sm font-semibold text-white"></span>
                                        <button type="button" id="mes-next"
                                            class="p-1.5 rounded-lg hover:bg-gray-700 text-gray-400 hover:text-white transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Cabecera días -->
                                    <div class="grid grid-cols-7 mb-1">
                                        <?php foreach (['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá', 'Do'] as $d): ?>
                                            <div class="text-center text-xs font-semibold 
                    <?= in_array($d, ['Sá', 'Do']) ? 'text-gray-600' : 'text-gray-400' ?> py-1">
                                                <?= $d ?>
                                            </div>
                                        <?php endforeach ?>
                                    </div>

                                    <!-- Grid días -->
                                    <div id="grid-dias" class="grid grid-cols-7 gap-0.5"></div>

                                    <!-- Leyenda -->
                                    <div
                                        class="mt-3 pt-3 border-t border-gray-800 flex items-center gap-4 text-xs text-gray-500">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-3 h-3 rounded-full bg-indigo-600"></div>
                                            Con asistencia
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                            Seleccionado
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-3 h-3 rounded-sm bg-gray-800 border border-gray-700"></div>
                                            No hábil
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- lalalalalal -->

                            <!-- Aviso de semestre -->
                            <div id="aviso-semestre" class="hidden w-full mt-2 px-4 py-2 bg-red-900/40 border border-red-700 
     text-red-300 text-xs rounded-lg flex items-center gap-2">
                                <span>⚠️</span>
                                <span id="aviso-semestre-texto"></span>
                            </div>


                            <!-- Indicador días con asistencia 
                            <?php if (!empty($fechasConAsistencia)): ?>
                                <div class="w-full mt-2 px-4 py-2 bg-gray-900/60 border border-gray-700 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1.5 font-medium uppercase tracking-wider">
                                        📋 Días con asistencia registrada (<?= count($fechasConAsistencia) ?>)
                                    </p>
                                    <div class="flex flex-wrap gap-1.5">
                                        <?php foreach ($fechasConAsistencia as $fd): ?>
                                            <button type="button"
                                            onclick="document.getElementById('campo-fecha').value='<?= $fd ?>'; validarFecha();"
                                            class="text-xs px-2 py-1 rounded-md bg-indigo-900/40 border border-indigo-700/50 
                                            text-indigo-300 hover:bg-indigo-700/50 transition font-mono">
                                            <?= (new DateTime($fd))->format('d/m') ?>
                                        </button>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                                <?php endif ?>
                                -->

                            <!-- Divisor -->
                            <div class="hidden sm:block w-px h-5 bg-gray-600"></div>

                            <!-- Botones selección masiva -->
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500 font-medium">Selección:</span>
                                <button type="button" onclick="marcarTodos(true)"
                                    class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-green-900/40 text-green-400 border border-green-700/50 hover:bg-green-900/70 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Todos presentes
                                </button>
                                <button type="button" onclick="marcarTodos(false)"
                                    class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-red-900/40 text-red-400 border border-red-700/50 hover:bg-red-900/70 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Todos ausentes
                                </button>
                            </div>

                            <!-- Contador -->
                            <div class="ml-auto">
                                <span id="contador"
                                    class="text-xs font-semibold px-3 py-1.5 rounded-full bg-gray-900 border border-gray-600 text-gray-400">
                                    0 / 0 presentes
                                </span>
                            </div>
                        </div>

                        <!-- Lista de alumnos -->
                        <?php if (empty($alumnos)): ?>
                            <div class="px-5 py-8 text-center">
                                <svg class="w-10 h-10 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                </svg>
                                <p class="text-gray-400 font-medium">No hay alumnos matriculados en este curso.</p>
                            </div>
                        <?php else: ?>
                            <!-- Cabecera tabla -->
                            <div class="grid grid-cols-[1fr_auto] items-center px-5 py-2.5 bg-gray-900/50">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Alumno</span>
                                <span
                                    class="text-xs font-semibold uppercase tracking-wider text-gray-500 w-20 text-center">Presente</span>
                            </div>

                            <!-- Filas -->
                            <?php foreach ($alumnos as $i => $alumno): ?>
                                <label
                                    class="grid grid-cols-[1fr_auto] items-center px-5 py-3.5 border-t border-gray-700/60 hover:bg-gray-700/30 cursor-pointer transition group">
                                    <div>
                                        <span class="text-sm font-semibold text-white">
                                            <?= htmlspecialchars($alumno['apepat'] . " " . $alumno['apemat']) ?>
                                        </span>
                                        <span class="text-sm text-gray-400">,
                                            <?= htmlspecialchars($alumno['nombre']) ?>
                                        </span>
                                    </div>
                                    <div class="w-20 flex justify-center">
                                        <input type="checkbox" class="presente w-5 h-5 rounded accent-green-500 cursor-pointer"
                                            name="presentes[]" value="<?= $alumno['matricula_id'] ?>" checked
                                            onchange="actualizarContador()">
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Botón guardar -->
                    <?php if (!empty($alumnos)): ?>
                        <div class="flex items-center justify-between gap-4">

                            <!-- Botón volver -->
                            <a href="index.php?action=asistencia_cursos&anio_id=<?= $_GET['anio_id'] ?>" class="flex items-center gap-2 bg-gray-700 hover:bg-gray-600 text-white 
                   font-semibold px-6 py-3 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Volver a cursos
                            </a>

                            <!-- Botón guardar -->
                            <button type="submit" class="flex items-center gap-2 bg-green-600 hover:bg-green-500 active:bg-green-700 
                   text-white font-bold px-8 py-3 rounded-xl transition shadow-lg shadow-green-900/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Guardar Asistencia
                            </button>

                        </div>
                    <?php endif ?>

                </form>
            </div>
        </main>
    </div>
</body>

<script>
    const totalAlumnos = <?= count($alumnos ?? []) ?>;
    const SEM1_INICIO = '<?= $fechasAnio['sem1_inicio'] ?? '' ?>';
    const SEM1_FIN = '<?= $fechasAnio['sem1_fin'] ?? '' ?>';
    const SEM2_INICIO = '<?= $fechasAnio['sem2_inicio'] ?? '' ?>';
    const SEM2_FIN = '<?= $fechasAnio['sem2_fin'] ?? '' ?>';
    const HOY = '<?= date('Y-m-d') ?>';
    const FECHAS_CON_ASISTENCIA = new Set(<?= json_encode($fechasConAsistencia ?? []) ?>);

    const MESES = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    let calendarioAbierto = false;
    let mesActual, anioActual;

    // ── Inicializar con fecha de hoy ──
    (function () {
        const hoy = new Date(HOY + 'T00:00:00');
        mesActual = hoy.getMonth();
        anioActual = hoy.getFullYear();
    })();

    // ── Abrir/cerrar calendario ──
    document.getElementById('btn-fecha').addEventListener('click', (e) => {
        e.stopPropagation();
        const cal = document.getElementById('calendario');
        calendarioAbierto = !calendarioAbierto;
        cal.classList.toggle('hidden', !calendarioAbierto);
        if (calendarioAbierto) renderCalendario();
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('#calendario') && !e.target.closest('#btn-fecha')) {
            document.getElementById('calendario').classList.add('hidden');
            calendarioAbierto = false;
        }
    });

    // ── Navegación de meses ──
    document.getElementById('mes-prev').addEventListener('click', () => {
        mesActual--;
        if (mesActual < 0) { mesActual = 11; anioActual--; }
        renderCalendario();
    });
    document.getElementById('mes-next').addEventListener('click', () => {
        mesActual++;
        if (mesActual > 11) { mesActual = 0; anioActual++; }
        renderCalendario();
    });

    // ── Renderizar calendario ──
    function renderCalendario() {
        document.getElementById('mes-titulo').textContent = `${MESES[mesActual]} ${anioActual}`;

        const grid = document.getElementById('grid-dias');
        const fechaSel = document.getElementById('campo-fecha').value;
        grid.innerHTML = '';

        // Primer día del mes (ajustado a lunes=0)
        const primerDia = new Date(anioActual, mesActual, 1);
        let offset = primerDia.getDay() - 1;
        if (offset < 0) offset = 6;

        // Espacios vacíos
        for (let i = 0; i < offset; i++) {
            grid.appendChild(crearCelda(''));
        }

        // Días del mes
        const diasEnMes = new Date(anioActual, mesActual + 1, 0).getDate();
        for (let d = 1; d <= diasEnMes; d++) {
            const fecha = `${anioActual}-${String(mesActual + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            const diaSemana = new Date(fecha + 'T00:00:00').getDay(); // 0=dom, 6=sab
            const esFinSemana = diaSemana === 0 || diaSemana === 6;
            const esFuturo = fecha > HOY;
            const enSemestre = estaEnSemestre(fecha);
            const tieneAsist = FECHAS_CON_ASISTENCIA.has(fecha);
            const esSeleccion = fecha === fechaSel;
            const esHoy = fecha === HOY;

            grid.appendChild(crearCelda(d, fecha, {
                esFinSemana, esFuturo, enSemestre, tieneAsist, esSeleccion, esHoy
            }));
        }
    }

    function crearCelda(dia, fecha = null, opts = {}) {
        const el = document.createElement('div');
        el.className = 'relative flex items-center justify-center h-8 w-full rounded-lg text-xs font-medium transition select-none';

        if (!dia) {
            return el; // celda vacía
        }

        const { esFinSemana, esFuturo, enSemestre, tieneAsist, esSeleccion, esHoy } = opts;
        const bloqueado = esFinSemana || esFuturo || !enSemestre;

        if (esSeleccion) {
            el.className += ' bg-blue-600 text-white ring-2 ring-blue-400 ring-offset-1 ring-offset-gray-900';
        } else if (tieneAsist && !bloqueado) {
            el.className += ' bg-indigo-700/70 text-indigo-200 hover:bg-indigo-600 cursor-pointer';
        } else if (bloqueado) {
            el.className += ' text-gray-700 cursor-not-allowed';
        } else {
            el.className += ' text-gray-300 hover:bg-gray-700 cursor-pointer';
            if (esHoy) el.className += ' ring-1 ring-blue-500';
        }

        el.textContent = dia;

        // Punto indicador de asistencia registrada
        if (tieneAsist && !esSeleccion) {
            const punto = document.createElement('div');
            punto.className = 'absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-indigo-400';
            el.appendChild(punto);
        }

        if (!bloqueado) {
            el.addEventListener('click', () => {
                seleccionarFecha(fecha);
                document.getElementById('calendario').classList.add('hidden');
                calendarioAbierto = false;
            });
        }

        return el;
    }

    // ── Seleccionar fecha ──
    function seleccionarFecha(fecha) {
        document.getElementById('campo-fecha').value = fecha;
        const [y, m, d] = fecha.split('-');
        document.getElementById('fecha-display').textContent = `${d}/${m}/${y}`;

        // Re-render para actualizar selección visual
        const fechaObj = new Date(fecha + 'T00:00:00');
        mesActual = fechaObj.getMonth();
        anioActual = fechaObj.getFullYear();
        if (calendarioAbierto) renderCalendario();

        validarFecha();
    }

    // ── Validar fecha ──
    function estaEnSemestre(fecha) {
        if (!fecha) return false;
        const enSem1 = SEM1_INICIO && SEM1_FIN && fecha >= SEM1_INICIO && fecha <= SEM1_FIN;
        const enSem2 = SEM2_INICIO && SEM2_FIN && fecha >= SEM2_INICIO && fecha <= SEM2_FIN;
        return enSem1 || enSem2;
    }

    function validarFecha() {
        const fecha = document.getElementById('campo-fecha').value;
        const aviso = document.getElementById('aviso-semestre');
        const texto = document.getElementById('aviso-semestre-texto');
        const btnGuardar = document.querySelector('button[type="submit"]');

        const fechaObj = new Date(fecha + 'T00:00:00');
        const diaSemana = fechaObj.getDay();
        const esFinSemana = diaSemana === 0 || diaSemana === 6;
        const esFuturo = fecha > HOY;
        const valida = !esFinSemana && !esFuturo && estaEnSemestre(fecha);

        if (!valida) {
            let msg = 'Fecha fuera del período escolar.';
            if (esFuturo) msg = 'No puedes registrar asistencia en una fecha futura.';
            if (esFinSemana) msg = 'Los fines de semana no son días hábiles.';

            texto.textContent = msg;
            aviso.classList.remove('hidden');
            btnGuardar.disabled = true;
            btnGuardar.classList.add('opacity-40', 'cursor-not-allowed');
            btnGuardar.classList.remove('hover:bg-green-500');
        } else {
            aviso.classList.add('hidden');
            btnGuardar.disabled = false;
            btnGuardar.classList.remove('opacity-40', 'cursor-not-allowed');
            btnGuardar.classList.add('hover:bg-green-500');
        }
    }

    // ── Contador presentes ──
    function actualizarContador() {
        const presentes = document.querySelectorAll('.presente:checked').length;
        const el = document.getElementById('contador');
        el.textContent = `${presentes} / ${totalAlumnos} presentes`;
        if (presentes === totalAlumnos) {
            el.className = 'text-xs font-semibold px-3 py-1.5 rounded-full bg-green-900/40 border border-green-700/50 text-green-400';
        } else if (presentes === 0) {
            el.className = 'text-xs font-semibold px-3 py-1.5 rounded-full bg-red-900/40 border border-red-700/50 text-red-400';
        } else {
            el.className = 'text-xs font-semibold px-3 py-1.5 rounded-full bg-yellow-900/40 border border-yellow-700/50 text-yellow-400';
        }
    }

    function marcarTodos(estado) {
        document.querySelectorAll('.presente').forEach(el => el.checked = estado);
        actualizarContador();
    }

    document.addEventListener('DOMContentLoaded', () => {
        actualizarContador();
        validarFecha();
    });
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>