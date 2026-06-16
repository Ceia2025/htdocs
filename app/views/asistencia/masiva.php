<?php
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$auth->checkAuth();

$user   = $_SESSION['user'];
$nombre = $user['nombre'];
$rol    = $user['rol'];

include __DIR__ . "/../layout/header.php";
include __DIR__ . "/../layout/navbar.php";

// Flash messages
$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError   = $_SESSION['flash_error']   ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>

<!-- Flash messages -->
<?php if ($flashSuccess): ?>
    <script>document.addEventListener('DOMContentLoaded', () => showToast(<?= json_encode($flashSuccess) ?>, 'success'));</script>
<?php endif; ?>

<?php if ($flashError): ?>
    <script>document.addEventListener('DOMContentLoaded', () => showToast(<?= json_encode($flashError) ?>, 'error'));</script>
<?php endif; ?>

<?php if (!empty($_GET['guardado'])): ?>
    <script>document.addEventListener('DOMContentLoaded', () => showToast('Asistencia guardada correctamente.', 'success'));</script>
<?php endif ?>

<?php if (!empty($_GET['alerta'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            <?php if ($_GET['alerta'] === 'enviada'): ?>
                showToast('⚠️ Alerta enviada: hay alumnos con 3 días consecutivos de ausencia', 'warning');
            <?php elseif ($_GET['alerta'] === 'sin_ausencias'): ?>
                showToast('✅ Sin alertas de ausencia detectadas', 'success');
            <?php elseif ($_GET['alerta'] === 'error_envio'): ?>
                showToast('❌ Error al enviar la alerta de ausencias', 'error');
            <?php endif; ?>
        });
    </script>
<?php endif; ?>

<header class="page-header">
    <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-accent mb-1">Asistencia</p>
            <h1 class="text-2xl font-bold text-strong font-display">Tomar Asistencia</h1>
            <p class="text-sm text-muted mt-0.5">Curso:
                <?= htmlspecialchars($curso['nombre'] ?? '') ?>
            </p>
        </div>
        <a href="index.php?action=libro_clases&curso_id=<?= $curso['id'] ?>&anio_id=<?= $_GET['anio_id'] ?>"
            class="btn-primary flex items-center gap-2 text-sm px-4 py-2 rounded-lg transition">
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
            <div class="panel rounded-xl overflow-hidden mb-5">

                <?php if ($esEdicion): ?>
                    <div class="flex items-center gap-2 px-5 py-2.5 border-b"
                         style="background: var(--warn-soft); color: var(--warn); border-color: var(--warn);">
                        <span class="text-sm">✏️</span>
                        <p class="text-xs font-medium">
                            Estás <strong>modificando</strong> una asistencia ya registrada para esta fecha.
                        </p>
                    </div>
                <?php endif ?>

                <!-- Fila: fecha y botones de selección -->
                <div class="flex flex-wrap items-center gap-3 px-5 py-4 border-b divider-soft">

                    <div class="flex items-start gap-2 w-full flex-col">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <label class="text-sm font-medium text-soft">Fecha</label>
                            <button type="button" id="btn-fecha"
                                class="btn-secondary text-sm rounded-lg px-3 py-1.5 transition min-w-[130px] text-left">
                                📅 <span id="fecha-display"><?= date('d/m/Y', strtotime($fecha)) ?></span>
                            </button>
                            <input type="hidden" name="fecha" id="campo-fecha" value="<?= $fecha ?>">
                        </div>

                        <div id="aviso-semestre" class="banner-danger hidden px-4 py-2 text-xs rounded-lg flex items-center gap-2">
                            <span>⚠️</span>
                            <span id="aviso-semestre-texto"></span>
                        </div>

                        <!-- CALENDARIO CUSTOM -->
                        <div id="calendario" class="dropdown-panel hidden absolute z-50 mt-1 rounded-2xl p-4 w-80">
                            <div class="flex items-center justify-between mb-3">
                                <button type="button" id="mes-prev" class="icon-btn p-1.5 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <span id="mes-titulo" class="text-sm font-semibold text-strong"></span>
                                <button type="button" id="mes-next" class="icon-btn p-1.5 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-7 mb-1">
                                <?php foreach (['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá', 'Do'] as $d): ?>
                                    <div class="text-center text-xs font-semibold
                                        <?= in_array($d, ['Sá', 'Do']) ? 'text-faint' : 'text-muted' ?> py-1">
                                        <?= $d ?>
                                    </div>
                                <?php endforeach ?>
                            </div>
                            <div id="grid-dias" class="grid grid-cols-7 gap-0.5"></div>
                            <div class="mt-3 pt-3 border-t divider-soft flex items-center gap-4 text-xs text-muted">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 rounded-full legend-dot-accent"></div>Con asistencia
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-azul-vivo"></div>Seleccionado
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hidden sm:block w-px h-5" style="background: var(--border-soft);"></div>

                    <!-- Botones selección masiva -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-muted font-medium">Selección:</span>
                        <button type="button" onclick="marcarTodos(true)"
                            class="btn-soft-success flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            Todos presentes
                        </button>
                        <button type="button" onclick="marcarTodos(false)"
                            class="btn-soft-danger flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Todos ausentes
                        </button>
                    </div>

                    <!-- Contador -->
                    <div class="ml-auto">
                        <span id="contador" class="chip-counter text-xs font-semibold px-3 py-1.5 rounded-full">
                            0 / 0 presentes
                        </span>
                    </div>
                </div>

                <?php if (empty($alumnos)): ?>
                    <div class="px-5 py-8 text-center">
                        <p class="text-muted font-medium">No hay alumnos matriculados en este curso.</p>
                    </div>
                <?php else: ?>
                    <div class="list-head flex items-center px-5 py-2.5 rounded-t-xl gap-6">
                        <span class="text-xs font-semibold uppercase tracking-wider text-muted w-8 text-center">#</span>
                        <span class="text-xs font-semibold uppercase tracking-wider text-muted flex-1">Alumno</span>
                        <span class="text-xs font-semibold uppercase tracking-wider text-muted w-24 text-center">Presente</span>
                    </div>

                    <?php foreach ($alumnos as $i => $alumno):
                        $estaRetirado     = !empty($alumno['fecha_retiro'])    && $fecha >= $alumno['fecha_retiro'];
                        $antesDeMatricula = !empty($alumno['fecha_matricula']) && $fecha <  $alumno['fecha_matricula'];
                        $inactivo         = $estaRetirado || $antesDeMatricula;
                    ?>
                        <label class="list-row flex items-center flex-nowrap px-5 py-3.5 transition group gap-4 <?php echo $inactivo ? 'row-inactive cursor-default' : 'cursor-pointer'; ?>">

                            <div class="w-8 flex justify-center flex-shrink-0">
                                <span class="text-xs font-bold <?= $inactivo ? 'text-danger' : (($alumno['numero_lista'] ?? null) ? 'text-azul-vivo' : 'text-faint') ?>">
                                    <?= $alumno['numero_lista'] ?? '—' ?>
                                </span>
                            </div>

                            <div class="flex-1 min-w-0 flex flex-wrap items-center gap-1.5">
                                <span class="text-sm font-semibold <?= $inactivo ? 'text-danger line-through' : 'text-strong' ?>">
                                    <?= htmlspecialchars(($alumno['apepat'] ?? '') . " " . ($alumno['apemat'] ?? '')) ?>
                                </span>
                                <span class="text-sm <?= $inactivo ? 'text-danger' : 'text-soft' ?>">
                                    , <?= htmlspecialchars($alumno['nombre'] ?? '') ?>
                                </span>

                                <?php if ($estaRetirado): ?>
                                    <span class="chip-danger ml-2 text-xs px-2 py-0.5 rounded-full whitespace-nowrap">
                                        Retirado <?= date('d/m/Y', strtotime($alumno['fecha_retiro'])) ?>
                                    </span>
                                <?php elseif ($antesDeMatricula): ?>
                                    <span class="chip-warn ml-2 text-xs px-2 py-0.5 rounded-full whitespace-nowrap">
                                        Se matricula el <?= date('d/m/Y', strtotime($alumno['fecha_matricula'])) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center flex-shrink-0 ml-auto">
                                <?php if ($inactivo): ?>
                                    <span class="text-danger" title="<?= $estaRetirado ? 'Alumno retirado' : 'Aún no matriculado' ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </span>
                                <?php else: ?>
                                    <?php $estaPresente = $esEdicion ? (($asistenciaExistente[$alumno['matricula_id']] ?? 0) === 1) : true; ?>
                                    <input type="checkbox"
                                           class="presente w-5 h-5 rounded accent-green-500 cursor-pointer"
                                           name="presentes[]"
                                           value="<?= $alumno['matricula_id'] ?>"
                                           <?= $estaPresente ? 'checked' : '' ?>
                                           onchange="actualizarContador()">
                                <?php endif; ?>
                            </div>

                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

            <!-- Botones -->
            <?php if (!empty($alumnos)): ?>
                <div class="flex items-center justify-between gap-4">

                    <!-- Volver -->
                    <a href="index.php?action=asistencia_cursos&anio_id=<?= $_GET['anio_id'] ?>"
                       class="btn-secondary flex items-center gap-2 font-semibold px-6 py-3 rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Volver a cursos
                    </a>

                    <div class="flex items-center gap-3">

                        <!-- ── BOTÓN ELIMINAR ── -->
                        <?php if ($esEdicion): ?>
                            <button type="button" onclick="confirmarEliminar()"
                                    class="btn-soft-danger flex items-center gap-2 font-semibold px-5 py-3 rounded-xl transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Eliminar asistencia
                            </button>
                        <?php endif; ?>

                        <!-- Guardar -->
                        <button type="submit"
                                class="btn-soft-success flex items-center gap-2 font-bold px-8 py-3 rounded-xl transition shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            Guardar Asistencia
                        </button>

                    </div>
                </div>
            <?php endif ?>

        </form>

        <!-- Formulario oculto para eliminar -->
        <form id="form-eliminar" method="POST"
              action="index.php?action=eliminar_asistencia_dia"
              class="hidden">
            <input type="hidden" name="curso_id" value="<?= $_GET['curso_id'] ?>">
            <input type="hidden" name="anio_id"  value="<?= $_GET['anio_id'] ?>">
            <input type="hidden" name="fecha"    id="fecha-eliminar" value="">
        </form>

        <!-- Modal de confirmación -->
        <div id="modal-eliminar"
             class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="modal-danger rounded-2xl p-6 shadow-2xl max-w-sm w-full mx-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="modal-icon-danger w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-strong font-bold text-base">¿Eliminar asistencia?</h3>
                        <p class="text-xs text-muted mt-0.5">Esta acción no se puede deshacer</p>
                    </div>
                </div>

                <p class="text-sm text-soft mb-5">
                    Se eliminarán <strong class="text-strong">todos los registros</strong>
                    de asistencia del día
                    <strong class="text-danger" id="modal-fecha-texto"></strong>
                    para el curso <strong class="text-strong"><?= htmlspecialchars($curso['nombre'] ?? '') ?></strong>.
                </p>

                <div class="flex gap-3">
                    <button onclick="cerrarModal()"
                            class="btn-secondary flex-1 px-4 py-2.5 font-semibold rounded-xl transition text-sm">
                        Cancelar
                    </button>
                    <button onclick="ejecutarEliminar()"
                            class="btn-danger flex-1 px-4 py-2.5 font-bold rounded-xl transition text-sm">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
    const totalAlumnos = <?= $totalActivos ?? 0 ?>;
    const SEM1_INICIO  = '<?= $fechasAnio['sem1_inicio'] ?? '' ?>';
    const SEM1_FIN     = '<?= $fechasAnio['sem1_fin']    ?? '' ?>';
    const SEM2_INICIO  = '<?= $fechasAnio['sem2_inicio'] ?? '' ?>';
    const SEM2_FIN     = '<?= $fechasAnio['sem2_fin']    ?? '' ?>';
    const HOY          = '<?= date('Y-m-d') ?>';
    const FECHAS_CON_ASISTENCIA = new Set(<?= json_encode($fechasConAsistencia ?? []) ?>);
    const MESES = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                   'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

    let calendarioAbierto = false;
    let mesActual, anioActual;
    const FECHA_ACTIVA = '<?= $fecha ?>';

    (function () {
        const f = new Date(FECHA_ACTIVA + 'T00:00:00');
        mesActual  = f.getMonth();
        anioActual = f.getFullYear();
    })();

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

    document.getElementById('mes-prev').addEventListener('click', () => {
        mesActual--; if (mesActual < 0) { mesActual = 11; anioActual--; } renderCalendario();
    });
    document.getElementById('mes-next').addEventListener('click', () => {
        mesActual++; if (mesActual > 11) { mesActual = 0; anioActual++; } renderCalendario();
    });

    function renderCalendario() {
        document.getElementById('mes-titulo').textContent = `${MESES[mesActual]} ${anioActual}`;
        const grid = document.getElementById('grid-dias');
        const fechaSel = document.getElementById('campo-fecha').value;
        grid.innerHTML = '';
        const primerDia = new Date(anioActual, mesActual, 1);
        let offset = primerDia.getDay() - 1;
        if (offset < 0) offset = 6;
        for (let i = 0; i < offset; i++) grid.appendChild(crearCelda(''));
        const diasEnMes = new Date(anioActual, mesActual + 1, 0).getDate();
        for (let d = 1; d <= diasEnMes; d++) {
            const fecha = `${anioActual}-${String(mesActual+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            const diaSemana = new Date(fecha + 'T00:00:00').getDay();
            const esFinSemana = diaSemana === 0 || diaSemana === 6;
            const esFuturo = fecha > HOY;
            const enSemestre = estaEnSemestre(fecha);
            const tieneAsist = FECHAS_CON_ASISTENCIA.has(fecha);
            const esSeleccion = fecha === fechaSel;
            const esHoy = fecha === HOY;
            grid.appendChild(crearCelda(d, fecha, { esFinSemana, esFuturo, enSemestre, tieneAsist, esSeleccion, esHoy }));
        }
    }

    function crearCelda(dia, fecha = null, opts = {}) {
        const el = document.createElement('div');
        el.className = 'cal-day';
        if (!dia) return el;
        const { esFinSemana, esFuturo, enSemestre, tieneAsist, esSeleccion, esHoy } = opts;
        const bloqueado = esFinSemana || esFuturo || !enSemestre;
        if (esSeleccion) el.classList.add('cal-day--selected');
        else if (tieneAsist && !bloqueado) el.classList.add('cal-day--has-attendance');
        else if (bloqueado) el.classList.add('cal-day--blocked');
        else {
            el.classList.add('cal-day--default');
            if (esHoy) el.classList.add('cal-day--today');
        }
        el.textContent = dia;
        if (tieneAsist && !esSeleccion) {
            const punto = document.createElement('div');
            punto.className = 'cal-day-dot';
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

    function seleccionarFecha(fecha) {
        document.getElementById('campo-fecha').value = fecha;
        const [y, m, d] = fecha.split('-');
        document.getElementById('fecha-display').textContent = `${d}/${m}/${y}`;
        const f = new Date(fecha + 'T00:00:00');
        mesActual = f.getMonth(); anioActual = f.getFullYear();
        if (calendarioAbierto) renderCalendario();
        validarFecha();
        const params = new URLSearchParams(window.location.search);
        params.set('fecha', fecha);
        window.location.href = window.location.pathname + '?' + params.toString();
    }

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
        const f = new Date(fecha + 'T00:00:00');
        const esFinSemana = f.getDay() === 0 || f.getDay() === 6;
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
        } else {
            aviso.classList.add('hidden');
            btnGuardar.disabled = false;
            btnGuardar.classList.remove('opacity-40', 'cursor-not-allowed');
        }
    }

    function actualizarContador() {
        const presentes = document.querySelectorAll('.presente:checked').length;
        const el = document.getElementById('contador');
        el.textContent = `${presentes} / ${totalAlumnos} presentes`;
        el.className = 'chip-counter text-xs font-semibold px-3 py-1.5 rounded-full';
        if (totalAlumnos > 0 && presentes === totalAlumnos) el.classList.add('chip-counter--all');
        else if (presentes === 0) el.classList.add('chip-counter--none');
        else el.classList.add('chip-counter--partial');
    }

    function marcarTodos(estado) {
        document.querySelectorAll('.presente').forEach(el => el.checked = estado);
        actualizarContador();
    }

    // ── ELIMINAR ────────────────────────────────────────────────
    function confirmarEliminar() {
        const fecha = document.getElementById('campo-fecha').value;
        const [y, m, d] = fecha.split('-');
        document.getElementById('fecha-eliminar').value    = fecha;
        document.getElementById('modal-fecha-texto').textContent = `${d}/${m}/${y}`;
        document.getElementById('modal-eliminar').classList.remove('hidden');
    }

    function cerrarModal() {
        document.getElementById('modal-eliminar').classList.add('hidden');
    }

    function ejecutarEliminar() {
        document.getElementById('form-eliminar').submit();
    }

    // Cerrar modal con Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') cerrarModal();
    });

    document.addEventListener('DOMContentLoaded', () => {
        actualizarContador();
        validarFecha();
    });

    function getToastStack() {
        let stack = document.getElementById('toast-stack');
        if (!stack) {
            stack = document.createElement('div');
            stack.id = 'toast-stack';
            stack.className = 'fixed top-5 right-5 z-50 flex flex-col gap-3 items-end pointer-events-none';
            document.body.appendChild(stack);
        }
        return stack;
    }

    function showToast(mensaje, tipo = 'info') {
        const clases = {
            success: 'banner-success',
            error:   'banner-danger',
            warning: 'banner-warning',
            info:    'banner-info',
        };
        const toast = document.createElement('div');
        toast.className = [
            'pointer-events-auto flex items-center gap-3 px-4 py-3',
            'rounded-xl shadow-2xl text-sm font-medium max-w-sm w-full',
            'translate-x-full opacity-0 transition-all duration-300',
            clases[tipo] ?? clases.info
        ].join(' ');
        toast.innerHTML = `<span class="flex-1">${mensaje}</span>
            <button onclick="this.parentElement.remove()"
                    class="flex-shrink-0 opacity-50 hover:opacity-100 transition text-lg leading-none">✕</button>`;
        getToastStack().appendChild(toast);
        requestAnimationFrame(() => requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        }));
        setTimeout(() => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 6000);
    }
</script>

<?php include __DIR__ . "/../layout/footer.php"; ?>