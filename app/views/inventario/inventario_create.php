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

<body class="h-full bg-gray-900">
    <header
        class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-white">Nuevo Registro de Inventario</h1>
        </div>
    </header>

    <main>
        <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="bg-gray-700 p-8 rounded-2xl shadow-lg">
                <form method="POST" action="index.php?action=inventario_store">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-200">Nombre del bien</label>
                            <input type="text" name="nombre" id="nombre" required placeholder="Ej: Mesa"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Tipo (autocompletado) -->
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-200">Tipo <span
                                    class="text-gray-400 text-xs">(opcional)</span></label>
                            <input type="text" name="tipo" id="tipo" list="tipos-list" autocomplete="off"
                                placeholder="Ej: Redonda, Trapezoidal..."
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <datalist id="tipos-list">
                                <?php foreach (($tipos ?? []) as $t): ?>
                                    <option value="<?= htmlspecialchars($t) ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <!-- Naturaleza -->
                        <div>
                            <label for="naturaleza" class="block text-sm font-medium text-gray-200">Naturaleza</label>
                            <select name="naturaleza" id="naturaleza"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="inventariable">Inventariable (mesa, silla...)</option>
                                <option value="consumible">Consumible (hojas, plumones...)</option>
                                <option value="transitorio">Transitorio (de un funcionario)</option>
                            </select>
                        </div>

                        <!-- Cantidad a crear (solo inventariable) -->
                        <div id="wrap-fichas">
                            <label for="cantidad_fichas" class="block text-sm font-medium text-gray-200">Cantidad a
                                crear</label>
                            <input type="number" name="cantidad_fichas" id="cantidad_fichas" min="1" value="1"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <p class="text-xs text-gray-400 mt-1">Genera fichas correlativas (MESA-001, 002...). Si es
                                &gt;1, el N y codigo se generan solos.</p>
                        </div>

                        <!-- Cantidad (solo consumible) -->
                        <div id="wrap-cantidad" class="hidden">
                            <label for="cantidad" class="block text-sm font-medium text-gray-200">Cantidad
                                (stock)</label>
                            <input type="number" name="cantidad" id="cantidad" min="1" value="1"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Numero -->
                        <div>
                            <label for="numero" class="block text-sm font-medium text-gray-200">N&deg;</label>
                            <input type="number" name="numero" id="numero" min="1"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none disabled:opacity-40">
                            <p class="text-xs text-gray-400 mt-1">Se sugiere solo; puedes cambiarlo.</p>
                        </div>

                        <!-- Codigo unico -->
                        <div>
                            <label for="codigo_unico" class="block text-sm font-medium text-gray-200">Codigo
                                unico</label>
                            <input type="text" name="codigo_unico" id="codigo_unico" placeholder="Ej: MESA-001"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 font-mono focus:ring-2 focus:ring-indigo-500 focus:outline-none disabled:opacity-40">
                            <p class="text-xs text-gray-400 mt-1">Autogenerado (editable). Vacio = se genera solo.</p>
                        </div>

                        <!-- Nivel Educativo -->
                        <div>
                            <label for="nivel_id" class="block text-sm font-medium text-gray-200">Nivel
                                Educativo</label>
                            <select name="nivel_id" id="nivel_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Seleccione --</option>
                                <?php foreach ($niveles as $nivel): ?>
                                    <option value="<?= $nivel['id'] ?>"><?= htmlspecialchars($nivel['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label for="estado_id" class="block text-sm font-medium text-gray-200">Estado de
                                Conservacion</label>
                            <select name="estado_id" id="estado_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Seleccione --</option>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?= $estado['id'] ?>"><?= htmlspecialchars($estado['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Lugar Fisico -->
                        <div>
                            <label for="lugar_id" class="block text-sm font-medium text-gray-200">Lugar Fisico</label>
                            <select name="lugar_id" id="lugar_id" required
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                <option value="">-- Seleccione --</option>
                                <?php foreach ($lugares as $lugar): ?>
                                    <option value="<?= $lugar['id'] ?>"><?= htmlspecialchars($lugar['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Fecha de registro -->
                        <div>
                            <label for="fecha_registro" class="block text-sm font-medium text-gray-200">Fecha de
                                registro</label>
                            <input type="date" name="fecha_registro" id="fecha_registro" max="<?= date('Y-m-d') ?>"
                                value="<?= date('Y-m-d') ?>"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>

                        <!-- Procedencia (opcional) + boton ventana flotante -->
                        <div class="md:col-span-2">
                            <label for="procedencia_id" class="block text-sm font-medium text-gray-200">Procedencia
                                <span class="text-gray-400 text-xs">(opcional)</span></label>
                            <div class="mt-2 flex gap-2">
                                <select name="procedencia_id" id="procedencia_id"
                                    class="flex-1 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                    <option value="">-- Sin procedencia --</option>
                                    <?php foreach (($procedencias ?? []) as $p): ?>
                                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars(Procedencia::etiqueta($p)) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" onclick="abrirModalProc()"
                                    class="shrink-0 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition">
                                    &#10133; Nueva
                                </button>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="md:col-span-2">
                            <label for="observaciones" class="block text-sm font-medium text-gray-200">Observaciones
                                <span class="text-gray-400 text-xs">(opcional)</span></label>
                            <textarea name="observaciones" id="observaciones" rows="2"
                                class="mt-2 w-full rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                        </div>

                    </div>

                    <!-- Botones -->
                    <div class="flex space-x-4 mt-8">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-200 ease-in-out">
                            Guardar
                        </button>
                        <a href="index.php?action=inventario_index"
                            class="w-full text-center inline-block rounded-lg bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 transition duration-200 ease-in-out">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>


    <!-- ===================== MODAL NUEVA PROCEDENCIA ===================== -->
    <div id="modal-proc" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="cerrarModalProc()"></div>
        <div class="relative bg-gray-800 border border-gray-700 rounded-2xl shadow-2xl p-6 w-full max-w-md z-10">

            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-400 mb-0.5">Catalogo</p>
                    <h3 class="text-lg font-bold text-white">Nueva procedencia</h3>
                </div>
                <button type="button" onclick="cerrarModalProc()"
                    class="text-gray-500 hover:text-white transition p-1 rounded-lg hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-200">Tipo</label>
                    <select id="proc-tipo"
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="Proyecto">Proyecto</option>
                        <option value="Donación">Donación</option>
                        <option value="Inversión">Inversión</option>
                        <option value="Compra">Compra</option>
                        <option value="Sin información">Sin información</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200">Fondo / Donante <span
                            class="text-gray-400 text-xs">(opcional)</span></label>
                    <input type="text" id="proc-fondo" placeholder="Ej: Proyecto FNDR 2024, Apoderados..."
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200">Fecha de adquisicion <span
                            class="text-gray-400 text-xs">(opcional)</span></label>
                    <input type="date" id="proc-fecha" max="<?= date('Y-m-d') ?>"
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="cerrarModalProc()"
                        class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                        Cancelar
                    </button>
                    <button type="button" id="btn-guardar-proc" onclick="guardarProcedencia()"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl transition text-sm">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        const naturaleza = document.getElementById('naturaleza');
        const wrapCantidad = document.getElementById('wrap-cantidad');
        const wrapFichas = document.getElementById('wrap-fichas');
        const inpFichas = document.getElementById('cantidad_fichas');
        const inpCantidad = document.getElementById('cantidad');
        const inpNombre = document.getElementById('nombre');
        const inpNumero = document.getElementById('numero');
        const inpCodigo = document.getElementById('codigo_unico');

        let codigoTocado = false;
        inpCodigo.addEventListener('input', () => codigoTocado = true);

        function toggleNaturaleza() {
            if (naturaleza.value === 'consumible') {
                wrapCantidad.classList.remove('hidden');
                wrapFichas.classList.add('hidden');
                inpFichas.value = 1;
            } else {
                wrapCantidad.classList.add('hidden');
                wrapFichas.classList.remove('hidden');
                inpCantidad.value = 1;
            }
            toggleSerie();
        }

        function toggleSerie() {
            const enSerie = naturaleza.value !== 'consumible' && parseInt(inpFichas.value || '1', 10) > 1;
            inpNumero.disabled = enSerie;
            inpCodigo.disabled = enSerie;
            if (enSerie) { inpNumero.value = ''; inpCodigo.value = ''; }
        }

        naturaleza.addEventListener('change', toggleNaturaleza);
        inpFichas.addEventListener('input', toggleSerie);
        toggleNaturaleza();

        // Autocompletar Tipo + sugerir codigo
        const datalistTipos = document.getElementById('tipos-list');
        async function refrescarTipos(nombre) {
            try {
                const url = 'index.php?action=inventario_tipos' + (nombre ? '&nombre=' + encodeURIComponent(nombre) : '');
                const resp = await fetch(url);
                const tipos = await resp.json();
                datalistTipos.innerHTML = tipos.map(t => '<option value="' + String(t).replace(/"/g, '&quot;') + '"></option>').join('');
            } catch (e) { console.error('No se pudieron cargar los tipos', e); }
        }

        inpNombre.addEventListener('blur', async () => {
            const nombre = inpNombre.value.trim();
            if (nombre === '') return;
            refrescarTipos(nombre);
            if (inpCodigo.disabled) return;
            try {
                const resp = await fetch('index.php?action=inventario_sugerir_codigo&nombre=' + encodeURIComponent(nombre));
                const data = await resp.json();
                if (!inpNumero.value) inpNumero.value = data.numero;
                if (!codigoTocado) inpCodigo.value = data.codigo_unico;
            } catch (e) { console.error('No se pudo sugerir el codigo', e); }
        });

        // ---------- VENTANA FLOTANTE: NUEVA PROCEDENCIA ----------
        const modalProc = document.getElementById('modal-proc');
        const selProc = document.getElementById('procedencia_id');

        function abrirModalProc() { modalProc.classList.remove('hidden'); }
        function cerrarModalProc() { modalProc.classList.add('hidden'); }

        async function guardarProcedencia() {
            const btn = document.getElementById('btn-guardar-proc');
            btn.disabled = true;

            const body = new URLSearchParams({
                tipo: document.getElementById('proc-tipo').value,
                donador_fondo: document.getElementById('proc-fondo').value,
                fecha_adquisicion: document.getElementById('proc-fecha').value,
            });

            try {
                const resp = await fetch('index.php?action=inventario_procedencia_ajax', { method: 'POST', body });
                const data = await resp.json();

                // Agrega la nueva procedencia al select y la deja seleccionada
                const opt = document.createElement('option');
                opt.value = data.id;
                opt.textContent = data.etiqueta;
                opt.selected = true;
                selProc.appendChild(opt);

                // Limpia y cierra
                document.getElementById('proc-fondo').value = '';
                document.getElementById('proc-fecha').value = '';
                cerrarModalProc();
            } catch (e) {
                console.error('No se pudo crear la procedencia', e);
                alert('No se pudo crear la procedencia.');
            } finally {
                btn.disabled = false;
            }
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') cerrarModalProc();
        });
    </script>
</body>

<?php include __DIR__ . "/../layout/footer.php"; ?>