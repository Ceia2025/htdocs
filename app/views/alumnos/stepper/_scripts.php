<script>
    document.addEventListener('DOMContentLoaded', () => {

        // ─── Estado global ───────────────────────────────
        let currentStep = 1;          // posición (1..N) en el orden del DOM
        let runDuplicado = false;
        let contactoIndex = 0;
        let tieneRun = true;          // true = chileno (RUN), false = extranjero (doc libre)
        let fichaImportada = null;    // respuesta del importador, si se usó

        const steps = document.querySelectorAll('.step');
        const progressBar = document.getElementById('progress-bar');
        const stepperForm = document.getElementById('stepperForm');

        // Refs RUN alumno
        const runInput = document.getElementById('run');
        const codverInput = document.getElementById('codver');
        const runError = document.getElementById('run-error');
        const runExistsMsg = document.getElementById('run-exists-msg');

        // Refs modo documento (chileno / extranjero)
        const nacidoChile = document.getElementById('nacido_chile');
        const runLabelTexto = document.getElementById('run-label-texto');
        const runHintExt = document.getElementById('run-hint-extranjero');
        const etniaSelect = document.getElementById('etnia_select');
        const etniaHintExt = document.getElementById('etnia-hint-extranjero');
        const nacionalidadInp = document.getElementById('nacionalidad');
        const nacionalidadReq = document.getElementById('nacionalidad-req');

        // Región / ciudad
        const regionSelect = document.getElementById('region');
        const ciudadSelect = document.getElementById('ciudad');

        // ─── Utilidades de pasos ─────────────────────────
        // Posición (1..N) del paso cuyo data-step es "n"
        function posicionDePaso(n) {
            const i = Array.from(steps).findIndex(s => s.dataset.step === String(n));
            return i === -1 ? null : i + 1;
        }
        function dataStepActual() {
            return steps[currentStep - 1]?.dataset.step;
        }

        // ─── Helpers RUN ─────────────────────────────────
        function formatRun(value) {
            value = value.replace(/\D/g, '');
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function validateRun(value) {
            const n = parseInt(value.replace(/\./g, ''), 10);
            return !isNaN(n) && n >= 1000000 && n <= 109000000;
        }

        function calcularDV(rut) {
            let suma = 0, mult = 2;
            for (let i = rut.length - 1; i >= 0; i--) {
                suma += parseInt(rut.charAt(i), 10) * mult;
                mult = mult < 7 ? mult + 1 : 2;
            }
            const r = 11 - (suma % 11);
            if (r === 11) return '0';
            if (r === 10) return 'K';
            return String(r);
        }

        // Vincula formato + DV automático (solo aplica en modo chileno)
        function bindRunDV(inputRun, inputDV) {
            inputRun.addEventListener('keypress', e => {
                if (inputRun === runInput && !tieneRun) return;
                if (!/[0-9]/.test(e.key)) e.preventDefault();
            });
            inputRun.addEventListener('input', e => {
                if (inputRun === runInput && !tieneRun) {
                    inputDV.value = '';
                    return;
                }
                const formatted = formatRun(e.target.value);
                e.target.value = formatted;
                const soloNumeros = formatted.replace(/\./g, '');
                if (formatted && !validateRun(formatted)) {
                    inputDV.value = '';
                } else if (soloNumeros.length > 0 && validateRun(formatted)) {
                    inputDV.value = calcularDV(soloNumeros);
                } else {
                    inputDV.value = '';
                }
            });
        }

        // ─── Errores y avisos visuales ───────────────────
        function limpiarError(el) {
            el.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
            const msg = el.parentElement.querySelector('.campo-error-msg');
            if (msg) msg.remove();
        }

        function marcarError(el, texto) {
            el.classList.add('border-red-500', 'ring-1', 'ring-red-500');
            if (!el.parentElement.querySelector('.campo-error-msg')) {
                const p = document.createElement('p');
                p.className = 'campo-error-msg text-red-500 text-sm mt-1';
                p.textContent = texto;
                el.parentElement.appendChild(p);
            }
        }

        // Aviso amarillo (dato importado que hay que revisar). Se quita al editar el campo.
        function marcarAviso(el, texto) {
            if (!el) return;
            el.classList.add('campo-aviso');
            const contenedor = el.closest('.flex.rounded-lg, .flex.gap-3, .flex.gap-2')?.parentElement || el.parentElement;
            const p = document.createElement('p');
            p.className = 'campo-aviso-msg';
            p.textContent = '⚠ ' + texto;
            contenedor.appendChild(p);

            const quitar = () => {
                el.classList.remove('campo-aviso');
                p.remove();
            };
            el.addEventListener('input', quitar, { once: true });
            el.addEventListener('change', quitar, { once: true });
        }

        function limpiarAvisos() {
            document.querySelectorAll('.campo-aviso').forEach(el => el.classList.remove('campo-aviso'));
            document.querySelectorAll('.campo-aviso-msg, .campo-error-msg').forEach(el => el.remove());
            document.querySelectorAll('.border-red-500').forEach(el =>
                el.classList.remove('border-red-500', 'ring-1', 'ring-red-500'));
        }

        // ─── Validación de tarjetas de contacto (Paso 2) ─────
        function validarContactos() {
            let primerInvalido = null;
            const cards = document.querySelectorAll('.contacto-card');

            cards.forEach(card => {
                const campos = card.querySelectorAll('input, select, textarea');

                // ¿La tarjeta tiene algún dato? (ignoramos "tipo", que siempre trae un valor por defecto)
                let tieneDatos = false;
                campos.forEach(c => {
                    if (c.name && c.name.includes('[tipo]')) return;
                    if (c.value && c.value.trim() !== '') tieneDatos = true;
                });

                if (!tieneDatos) return;

                const nombre = card.querySelector('input[name*="[nombre_contacto]"]');
                const relacion = card.querySelector('select[name*="[relacion]"]');

                [nombre, relacion].forEach(el => {
                    if (!el) return;
                    limpiarError(el);
                    if (!el.value || el.value.trim() === '') {
                        marcarError(el, 'Si completas datos de este contacto, este campo es obligatorio.');
                        if (!primerInvalido) primerInvalido = el;
                    }
                });
            });

            return primerInvalido;
        }

        // Valida los campos [required] visibles dentro de un contenedor.
        function validarRequeridos(contenedor) {
            let primerInvalido = null;

            contenedor.querySelectorAll('[required]').forEach(el => {
                if (el.offsetParent === null) return; // campo oculto por lógica

                limpiarError(el);
                const vacio = !el.value || el.value.trim() === '';

                if (vacio) {
                    marcarError(el, 'Este campo es obligatorio.');
                    if (!primerInvalido) primerInvalido = el;
                }
            });

            return primerInvalido;
        }

        function irAlCampo(el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => el.focus({ preventScroll: true }), 300);
        }

        function validarPaso(step) {
            const contenedor = steps[step - 1];
            if (!contenedor) return true;

            const invalido = validarRequeridos(contenedor);
            if (invalido) {
                irAlCampo(invalido);
                return false;
            }
            return true;
        }

        // ─── Verificación de RUN/documento duplicado ─────
        let checkRunTimeout;
        function checkRun() {
            const val = runInput.value.trim();
            const debeVerificar = tieneRun ? validateRun(val) : val.length > 0;

            if (!val || !debeVerificar) {
                runExistsMsg.textContent = '';
                runExistsMsg.classList.add('hidden');
                runInput.classList.remove('border-red-500');
                runDuplicado = false;
                return;
            }

            fetch(`index.php?action=check_run_exists&run=${encodeURIComponent(val)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.exists) {
                        runExistsMsg.textContent = tieneRun
                            ? '⚠️ Este RUN ya está registrado en el sistema.'
                            : '⚠️ Este número de documento ya está registrado en el sistema.';
                        runExistsMsg.classList.remove('hidden');
                        runInput.classList.add('border-red-500');
                        runDuplicado = true;
                    } else {
                        runExistsMsg.textContent = '';
                        runExistsMsg.classList.add('hidden');
                        runInput.classList.remove('border-red-500');
                        runDuplicado = false;
                    }
                })
                .catch(err => console.error('Error verificando RUN:', err));
        }

        // ─── Modo documento: chileno (RUN) vs extranjero (libre) ─────
        function aplicarModoDocumento() {
            tieneRun = nacidoChile.checked;

            codverInput.value = '';
            runError.classList.add('hidden');
            runExistsMsg.textContent = '';
            runExistsMsg.classList.add('hidden');
            runInput.classList.remove('border-red-500');
            runDuplicado = false;

            if (tieneRun) {
                runLabelTexto.textContent = 'RUN';
                runInput.placeholder = 'Ej: 12.345.678';
                runHintExt.classList.add('hidden');

                codverInput.setAttribute('required', 'required');
                codverInput.readOnly = true;
                codverInput.classList.add('cursor-not-allowed');

                etniaSelect.disabled = false;
                etniaHintExt.classList.add('hidden');

                nacionalidadInp.removeAttribute('required');
                nacionalidadReq.classList.add('hidden');
            } else {
                runLabelTexto.textContent = 'N° de documento';
                runInput.placeholder = 'Pasaporte / DNI / IPE...';
                runInput.value = '';
                runHintExt.classList.remove('hidden');

                codverInput.value = '';
                codverInput.removeAttribute('required');
                codverInput.readOnly = true;
                codverInput.classList.add('cursor-not-allowed');

                etniaSelect.value = 'Otro';
                etniaSelect.disabled = true;
                etniaHintExt.classList.remove('hidden');

                if (nacionalidadInp.value.trim() === 'Chilena') {
                    nacionalidadInp.value = '';
                }
                nacionalidadInp.setAttribute('required', 'required');
                nacionalidadReq.classList.remove('hidden');
            }
        }

        // ─── RUN del alumno ──────────────────────────────
        if (runInput && codverInput) {
            bindRunDV(runInput, codverInput);

            runInput.addEventListener('input', () => {
                if (tieneRun && runInput.value && !validateRun(runInput.value)) {
                    runError.classList.remove('hidden');
                } else {
                    runError.classList.add('hidden');
                }
            });

            runInput.addEventListener('blur', checkRun);
            runInput.addEventListener('input', () => {
                clearTimeout(checkRunTimeout);
                checkRunTimeout = setTimeout(checkRun, 700);
            });
        }

        if (nacidoChile) {
            nacidoChile.addEventListener('change', aplicarModoDocumento);
        }

        // Celular del alumno: solo dígitos
        const celularAlumno = document.getElementById('celular_alumno');
        if (celularAlumno) {
            celularAlumno.addEventListener('input', () => {
                celularAlumno.value = celularAlumno.value.replace(/\D/g, '').slice(0, 8);
            });
        }

        // ─── Stepper ─────────────────────────────────────
        function showStep(step) {
            steps.forEach((s, idx) => {
                s.classList.toggle('hidden', (idx + 1) !== step);
            });
            if (progressBar) {
                progressBar.style.width = ((step / steps.length) * 100) + '%';
            }
            document.querySelectorAll('.step-label').forEach((lbl, idx) => {
                lbl.classList.toggle('text-accent', idx + 1 === step);
            });
            document.querySelectorAll('.step-dot').forEach((dot, idx) => {
                dot.style.background = idx + 1 <= step ? 'var(--azul-vivo)' : 'var(--border-soft)';
            });
            if (steps[step - 1]?.dataset.step === '5') {
                renderResumenMatricula();
            }
        }

        document.querySelectorAll('.next-step').forEach(btn => {
            btn.addEventListener('click', e => {
                if (!validarPaso(currentStep)) {
                    e.preventDefault();
                    return;
                }
                if (dataStepActual() === '2') {
                    const invalidoContacto = validarContactos();
                    if (invalidoContacto) {
                        irAlCampo(invalidoContacto);
                        e.preventDefault();
                        return;
                    }
                }
                if (runDuplicado) {
                    alert('⚠️ No puedes continuar. El documento ya está registrado en el sistema.');
                    e.preventDefault();
                    return;
                }
                if (currentStep < steps.length) {
                    currentStep++;
                    showStep(currentStep);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });

        document.querySelectorAll('.prev-step').forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });

        // ─── Envío del formulario (segunda red) ───────────
        if (stepperForm) {
            stepperForm.addEventListener('submit', e => {
                const pasoOriginal = currentStep;

                // Validar TODOS los pasos. Se muestra cada paso un instante para que
                // offsetParent distinga "oculto por lógica" de "paso no visible".
                for (let i = 1; i <= steps.length; i++) {
                    showStep(i);
                    const invalido = validarRequeridos(steps[i - 1]);
                    if (invalido) {
                        e.preventDefault();
                        currentStep = i;
                        setTimeout(() => irAlCampo(invalido), 100);
                        return;
                    }
                }
                showStep(pasoOriginal);

                const invalidoContacto = validarContactos();
                if (invalidoContacto) {
                    e.preventDefault();
                    currentStep = posicionDePaso(2);
                    showStep(currentStep);
                    setTimeout(() => irAlCampo(invalidoContacto), 100);
                    return;
                }

                if (runDuplicado) {
                    e.preventDefault();
                    alert('⚠️ No se puede enviar. El documento ya existe.');
                    return;
                }

                // Habilitar campos deshabilitados para que viajen en el POST
                stepperForm.querySelectorAll('input[disabled], select[disabled], textarea[disabled]')
                    .forEach(el => el.disabled = false);

                // Evitar doble envío (doble alumno)
                const btnGuardar = document.getElementById('btnGuardar');
                if (btnGuardar) {
                    btnGuardar.disabled = true;
                    btnGuardar.classList.add('opacity-60', 'cursor-wait');
                }
            });
        }

        // ─── Regiones / Ciudades ──────────────────────────
        function cargarComunas(region) {
            ciudadSelect.innerHTML = '<option value="">Seleccione una ciudad</option>';
            const reg = (window.SAAT_REGIONES || []).find(r => r.region === region);
            if (reg && Array.isArray(reg.comunas)) {
                reg.comunas.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c;
                    opt.textContent = c;
                    ciudadSelect.appendChild(opt);
                });
            }
        }

        // Promesa: el importador espera a que las regiones estén cargadas
        const regionesListas = fetch('../utils/comunas-regiones.json')
            .then(res => {
                if (!res.ok) throw new Error('No se pudo cargar comunas-regiones.json');
                return res.json();
            })
            .then(data => {
                if (!regionSelect || !ciudadSelect || !data?.regiones) return;
                window.SAAT_REGIONES = data.regiones;

                data.regiones.forEach(r => {
                    const opt = document.createElement('option');
                    opt.value = r.region;
                    opt.textContent = r.region;
                    regionSelect.appendChild(opt);
                });

                regionSelect.addEventListener('change', () => cargarComunas(regionSelect.value));
            })
            .catch(err => console.error('Error regiones/comunas:', err));

        // ─── Autocompletar Nivel/Ciclo según escolaridad (Paso 3) ─────
        (function () {
            const MAP_NIVEL = {
                'Basica Completa': '8vo Basico',
                'Media Completa': '4to Medio',
            };
            function vincularNivel(selectId, inputId) {
                const sel = document.getElementById(selectId);
                const inp = document.getElementById(inputId);
                if (!sel || !inp) return;
                sel.addEventListener('change', () => {
                    if (MAP_NIVEL[sel.value] !== undefined) {
                        inp.value = MAP_NIVEL[sel.value];
                    }
                });
            }
            vincularNivel('padre', 'nivel_ciclo_p');
            vincularNivel('madre', 'nivel_ciclo_m');
        })();

        // ─── Contactos dinámicos (Paso 2) ─────────────────
        const RELACIONES = window.SAAT_RELACIONES || [
            'Madre', 'Padre', 'Hermano', 'Hermana',
            'Abuelo', 'Abuela', 'Tío', 'Tía',
            'Amigo', 'Amiga', 'Esposo', 'Esposa',
            'Apoderado', 'Apoderado Suplente',
            'Tutor', 'Tutor Legal', 'Representante', 'Por poder Simple', 'Psicóloga',
            'Hijo', 'Hija'
        ];

        function escapar(s) {
            return String(s ?? '').replace(/[&<>"']/g, ch =>
                ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch]));
        }

        function opcionesRelacion(sel = '') {
            return RELACIONES.map(r =>
                `<option value="${escapar(r)}" ${r === sel ? 'selected' : ''}>${escapar(r)}</option>`
            ).join('');
        }

        function crearTarjetaContacto(index) {
            const card = document.createElement('div');
            card.className = 'contacto-card bg-gray-800/60 border border-gray-600 rounded-2xl p-6 space-y-4 relative';
            card.dataset.index = index;

            card.innerHTML = `
            <button type="button"
                class="btn-eliminar-contacto absolute top-4 right-4 text-red-400 hover:text-red-300 text-xs font-bold transition">
                ✕ Eliminar
            </button>

            <h3 class="text-sm font-bold text-indigo-300 uppercase tracking-wider contacto-titulo">
                Contacto #${index + 1}
            </h3>
            <div class="contacto-avisos space-y-1"></div>

            <div>
                <label class="block text-sm font-medium text-gray-200">Tipo de contacto</label>
                <select name="emergencias[${index}][tipo]"
                    class="contacto-tipo mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2
                           focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="padre_madre_tutor">👨‍👩‍👦 Padre / Madre / Tutor Legal</option>
                    <option value="apoderado">🧑‍💼 Representante / Apoderado</option>
                    <option value="emergencia">🆘 Emergencia / Apoderado Suplente</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-200">Nombre completo</label>
                    <input type="text" name="emergencias[${index}][nombre_contacto]"
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2
                               focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200">R.U.N. del contacto</label>
                    <div class="mt-2 flex gap-2 items-center">
                        <input type="text"
                            id="run_contacto_${index}"
                            name="emergencias[${index}][run_contacto_num]"
                            placeholder="Ej: 12.345.678 o documento" maxlength="20"
                            class="w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2
                                   focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <span class="text-gray-400 font-bold flex-shrink-0">-</span>
                        <input type="text"
                            id="dv_contacto_${index}"
                            name="emergencias[${index}][run_contacto_dv]"
                            readonly placeholder="DV" maxlength="1"
                            class="w-16 rounded-lg bg-gray-700 border border-gray-600 text-white px-3 py-2
                                   text-center cursor-not-allowed">
                        <input type="hidden"
                            name="emergencias[${index}][run_contacto]"
                            id="run_contacto_full_${index}">
                    </div>
                    <p id="run_contacto_error_${index}" class="text-red-400 text-xs mt-1 hidden">RUN inválido</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-200">Dirección</label>
                    <input type="text" name="emergencias[${index}][direccion]"
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2
                               focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200">Comuna o Sector</label>
                    <input type="text" name="emergencias[${index}][comuna]"
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2
                               focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-200">Correo electrónico</label>
                <input type="email" name="emergencias[${index}][email]"
                    class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2
                           focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-200">Teléfono</label>
                    <input type="text" name="emergencias[${index}][telefono]"
                        class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2
                               focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200">Celular (sin +569)</label>
                    <div class="mt-2 flex rounded-lg overflow-hidden border border-gray-700">
                        <span class="bg-gray-600 text-gray-300 px-3 py-2 text-sm flex-shrink-0">+569</span>
                        <input type="text" name="emergencias[${index}][celular]" placeholder="12345678"
                            class="flex-1 bg-gray-900 text-white px-3 py-2
                                   focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-200">Vínculo / Relación</label>
                <select name="emergencias[${index}][relacion]"
                    class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-4 py-2
                           focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">Seleccione vínculo</option>
                    ${opcionesRelacion()}
                </select>
            </div>

            <div class="campo-obs hidden">
                <label class="block text-sm font-medium text-gray-200">Observación</label>
                <textarea name="emergencias[${index}][observacion]" rows="3"
                    class="mt-2 w-full rounded-lg bg-gray-900 border border-gray-700 text-white px-3 py-2
                           focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="Información adicional relevante..."></textarea>
            </div>
        `;

            const tipoSel = card.querySelector('.contacto-tipo');
            const campoObs = card.querySelector('.campo-obs');
            tipoSel.addEventListener('change', () => {
                campoObs.classList.toggle('hidden', tipoSel.value !== 'emergencia');
            });

            card.querySelector('.btn-eliminar-contacto').addEventListener('click', () => {
                card.remove();
                renumerarContactos();
            });

            const inputRunC = card.querySelector(`#run_contacto_${index}`);
            const inputDVC = card.querySelector(`#dv_contacto_${index}`);
            const inputFull = card.querySelector(`#run_contacto_full_${index}`);
            const errorRunC = card.querySelector(`#run_contacto_error_${index}`);

            function esDocumentoLibre(valor) {
                return /[a-jl-zA-JL-Z]/.test(valor);
            }

            inputRunC.addEventListener('keypress', e => {
                if (esDocumentoLibre(inputRunC.value + e.key)) return;
                if (!/[0-9]/.test(e.key)) e.preventDefault();
            });

            inputRunC.addEventListener('input', () => {
                const valor = inputRunC.value;

                if (esDocumentoLibre(valor)) {
                    errorRunC.classList.add('hidden');
                    inputDVC.value = '';
                    inputFull.value = valor.trim();
                    return;
                }

                const formatted = formatRun(valor);
                inputRunC.value = formatted;
                const soloNumeros = formatted.replace(/\./g, '');

                if (formatted && !validateRun(formatted)) {
                    errorRunC.classList.remove('hidden');
                    inputDVC.value = '';
                    inputFull.value = '';
                } else {
                    errorRunC.classList.add('hidden');
                    if (soloNumeros.length > 0 && validateRun(formatted)) {
                        inputDVC.value = calcularDV(soloNumeros);
                        inputFull.value = formatted + '-' + inputDVC.value;
                    } else {
                        inputDVC.value = '';
                        inputFull.value = '';
                    }
                }
            });

            return card;
        }

        // Llena una tarjeta con los datos que vienen de la ficha
        function rellenarTarjeta(card, d) {
            const idx = card.dataset.index;
            const campo = n => card.querySelector(`[name="emergencias[${idx}][${n}]"]`);

            const tipo = card.querySelector('.contacto-tipo');
            tipo.value = d.tipo || 'emergencia';
            tipo.dispatchEvent(new Event('change'));

            ['nombre_contacto', 'direccion', 'comuna', 'email', 'telefono', 'celular', 'observacion'].forEach(n => {
                const el = campo(n);
                if (el && d[n]) el.value = d[n];
            });

            const rel = campo('relacion');
            if (rel && d.relacion) rel.value = d.relacion;

            if (d.run_num) {
                const runC = card.querySelector(`#run_contacto_${idx}`);
                runC.value = d.run_num;
                runC.dispatchEvent(new Event('input'));
            }
        }

        function avisoEnTarjeta(card, texto) {
            const caja = card.querySelector('.contacto-avisos');
            const p = document.createElement('p');
            p.className = 'campo-aviso-msg';
            p.textContent = '⚠ ' + texto;
            caja.appendChild(p);
            card.classList.add('campo-aviso');
        }

        function renumerarContactos() {
            document.querySelectorAll('.contacto-card').forEach((card, i) => {
                const t = card.querySelector('.contacto-titulo');
                if (t) t.textContent = `Contacto #${i + 1}`;
            });
        }

        function agregarContacto(datos = null) {
            const container = document.getElementById('contactos-container');
            const card = crearTarjetaContacto(contactoIndex);
            container.appendChild(card);
            contactoIndex++;
            if (datos) rellenarTarjeta(card, datos);
            return card;
        }

        document.getElementById('btn-agregar-contacto').addEventListener('click', () => agregarContacto());
        agregarContacto();

        // ─── Resumen en el paso de matrícula ──────────────
        function renderResumenMatricula() {
            const v = n => (stepperForm.querySelector(`[name="${n}"]`)?.value || '').trim();
            const nombre = [v('apepat'), v('apemat')].filter(Boolean).join(' ') + (v('nombre') ? ', ' + v('nombre') : '');
            const run = v('run') ? (v('run') + (codverInput.value ? '-' + codverInput.value : '')) : 'Sin documento';
            const nac = v('fechanac') ? new Date(v('fechanac') + 'T00:00:00').toLocaleDateString('es-CL') : 'sin fecha de nacimiento';
            const nContactos = Array.from(document.querySelectorAll('.contacto-card'))
                .filter(c => c.querySelector('input[name*="[nombre_contacto]"]')?.value.trim()).length;

            document.getElementById('resumen-nombre').textContent = nombre.trim() || '—';
            document.getElementById('resumen-detalle').textContent =
                `${run} · ${nac} · ${nContactos} contacto(s)`;

            // Datos importados que quedaron marcados en amarillo en los pasos anteriores
            const pendientes = Array.from(document.querySelectorAll('.campo-aviso'))
                .filter(el => el.closest('.step')?.dataset.step !== '5').length;
            const p = document.getElementById('resumen-pendientes');
            if (pendientes > 0) {
                p.textContent = `⚠ Quedan ${pendientes} dato(s) marcados en amarillo para revisar. Puedes guardar igual y corregirlos después desde el perfil del alumno.`;
                p.classList.remove('hidden');
            } else {
                p.classList.add('hidden');
            }
        }

        // ═════════════════════════════════════════════════
        //  IMPORTAR FICHA EXCEL (Paso 0)
        // ═════════════════════════════════════════════════
        const importFile      = document.getElementById('import-file');
        const importDropzone  = document.getElementById('import-dropzone');
        const importFileName  = document.getElementById('import-file-name');
        const importLoading   = document.getElementById('import-loading');
        const importError     = document.getElementById('import-error');
        const importResultado = document.getElementById('import-resultado');
        const importNext      = document.getElementById('import-next');
        const importSaltar    = document.getElementById('import-saltar');

        // Saltar del paso 0 directo a Matrícula. Al guardar se validan TODOS los pasos:
        // si falta algo obligatorio, el formulario lleva al paso y al campo que corresponde.
        if (importSaltar) {
            importSaltar.addEventListener('click', () => {
                if (runDuplicado) {
                    alert('⚠️ No puedes continuar. El documento ya está registrado en el sistema.');
                    return;
                }
                currentStep = posicionDePaso(5);
                showStep(currentStep);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        function mostrarErrorImport(msg) {
            importError.textContent = msg;
            importError.classList.remove('hidden');
        }

        function elegirArchivo(file) {
            if (!file) return;
            if (!/\.(xls|xlsx)$/i.test(file.name)) {
                mostrarErrorImport('Solo se aceptan archivos .xls o .xlsx.');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                mostrarErrorImport('El archivo no puede pesar más de 5 MB.');
                return;
            }
            importFileName.textContent = file.name;
            importarFicha(file);
        }

        if (importFile) {
            importFile.addEventListener('change', () => elegirArchivo(importFile.files[0]));

            ['dragenter', 'dragover'].forEach(ev => importDropzone.addEventListener(ev, e => {
                e.preventDefault();
                importDropzone.classList.add('drag-over');
            }));
            ['dragleave', 'drop'].forEach(ev => importDropzone.addEventListener(ev, e => {
                e.preventDefault();
                importDropzone.classList.remove('drag-over');
            }));
            importDropzone.addEventListener('drop', e => elegirArchivo(e.dataTransfer.files[0]));

            document.getElementById('import-otra').addEventListener('click', () => importFile.click());
        }

        async function importarFicha(file) {
            importError.classList.add('hidden');
            importResultado.classList.add('hidden');
            importLoading.classList.remove('hidden');

            const fd = new FormData();
            fd.append('ficha', file);

            try {
                const resp = await fetch('index.php?action=alumno_import_ficha', {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin',
                });

                const texto = await resp.text();
                let data;
                try {
                    data = JSON.parse(texto);
                } catch {
                    // Respuesta no-JSON: mostrar pistas para diagnosticar
                    const redirigido = resp.redirected || !resp.url.includes('alumno_import_ficha');
                    const extracto = texto.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim().slice(0, 300);
                    console.error('Respuesta de alumno_import_ficha:', resp.status, resp.url, texto);

                    let msg = `El servidor no devolvió JSON (HTTP ${resp.status}).`;
                    if (redirigido) {
                        msg += ` La petición terminó en "${resp.url}": falta el permiso "alumno_import_ficha" o la ruta en el router.`;
                    }
                    if (extracto) msg += ` Respuesta: ${extracto}`;
                    throw new Error(msg);
                }

                if (!resp.ok || !data.ok) {
                    throw new Error(data.error || 'No se pudo leer la ficha.');
                }

                limpiarFormulario();
                await rellenarFormulario(data);
                mostrarResultado(data);
                fichaImportada = data;
            } catch (err) {
                mostrarErrorImport(err.message);
                if (!fichaImportada) importSaltar.classList.add('hidden');
            } finally {
                importLoading.classList.add('hidden');
                importFile.value = '';
            }
        }

        // Deja el formulario como recién abierto (para importar otra ficha encima)
        function limpiarFormulario() {
            stepperForm.reset();
            limpiarAvisos();
            aplicarModoDocumento();

            ciudadSelect.innerHTML = '<option value="">Seleccione una ciudad</option>';

            document.getElementById('contactos-container').innerHTML = '';

            ['prevision_select', 'chile_solidario_select', 'embarazo_select'].forEach(id =>
                document.getElementById(id)?.dispatchEvent(new Event('change')));

            document.getElementById('curso-sugerido-box').classList.add('hidden');
        }

        function setCampo(name, value) {
            const el = stepperForm.querySelector(`[name="${CSS.escape(name)}"]`);
            if (!el) return;

            el.value = value;
            if (el.tagName === 'SELECT' && el.value !== value) {
                marcarAviso(el, `El valor de la ficha ("${value}") no existe en la lista: elígelo a mano.`);
                return;
            }
            el.dispatchEvent(new Event('change', { bubbles: true }));
        }

        async function rellenarFormulario(data) {
            const c = data.campos || {};

            // 1) Chileno / extranjero (antes del RUN, porque cambia cómo se procesa)
            nacidoChile.checked = c.tiene_run !== '0';
            aplicarModoDocumento();

            // 2) RUN: el evento input formatea y calcula el DV
            if (c.run) {
                runInput.value = c.run;
                runInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
            runDuplicado = !!data.duplicado;

            // 3) Región y comuna (espera a que el JSON de regiones esté cargado)
            await regionesListas;
            if (c.region) {
                regionSelect.value = c.region;
                cargarComunas(c.region);
                if (c.ciudad) ciudadSelect.value = c.ciudad;
            }

            // 4) Resto de campos, en el orden que vienen (los selects dependientes van después de su padre)
            const especiales = new Set(['tiene_run', 'run', 'codver', 'region', 'ciudad']);
            Object.entries(c).forEach(([name, value]) => {
                if (especiales.has(name) || value === '' || value === null) return;
                setCampo(name, String(value));
            });

            // 5) Contactos
            const contactos = data.contactos || [];
            const tarjetas = {};
            if (contactos.length) {
                contactos.forEach(ct => { tarjetas[ct.tipo] = agregarContacto(ct); });
            } else {
                agregarContacto();
            }

            // 6) Avisos sobre los campos
            (data.avisos || []).forEach(a => {
                if (a.campo.startsWith('contacto:')) {
                    const card = tarjetas[a.campo.split(':')[1]];
                    if (card) avisoEnTarjeta(card, a.mensaje.replace(/^[^:]+:\s*/, ''));
                    return;
                }
                if (a.campo === 'ciudad') {
                    marcarAviso(regionSelect, a.mensaje);
                    return;
                }
                // El aviso del DV se muestra en el RUN (que es lo que hay que corregir)
                const nombreCampo = a.campo === 'codver' ? 'run' : a.campo;
                const el = stepperForm.querySelector(`[name="${CSS.escape(nombreCampo)}"]`);
                if (el) marcarAviso(el, a.mensaje);
            });

            // 7) Curso sugerido en el paso de matrícula
            const box = document.getElementById('curso-sugerido-box');
            if (data.resumen?.curso) {
                document.getElementById('curso-sugerido-nivel').textContent = data.resumen.nivel || '';
                document.getElementById('curso-sugerido-nombre').textContent = data.resumen.curso;
                box.classList.remove('hidden');
            } else {
                box.classList.add('hidden');
            }
        }

        function mostrarResultado(data) {
            const r = data.resumen || {};
            const txt = (id, v) => { document.getElementById(id).textContent = v || '—'; };

            txt('res-alumno', r.alumno);
            txt('res-run', r.run);
            txt('res-nivel', r.nivel);
            txt('res-anio', r.anio);
            txt('res-curso', r.curso || 'Sin sugerencia: elígelo en el último paso');
            txt('res-contactos', String((data.contactos || []).length));

            const dup = document.getElementById('import-duplicado');
            if (data.duplicado) {
                dup.textContent = '⛔ Este alumno ya está registrado en el sistema. No se puede crear de nuevo; búscalo en el listado.';
                dup.classList.remove('hidden');
            } else {
                dup.classList.add('hidden');
            }

            const avisos = (data.avisos || []).filter(a => a.campo !== 'run' || !data.duplicado);
            const lista = document.getElementById('import-avisos');
            lista.innerHTML = '';
            avisos.forEach(a => {
                const li = document.createElement('li');
                li.textContent = a.mensaje;
                lista.appendChild(li);
            });
            document.getElementById('import-avisos-wrap').classList.toggle('hidden', avisos.length === 0);

            importNext.textContent = 'Revisar paso a paso →';
            importSaltar.classList.toggle('hidden', !!data.duplicado);
            importResultado.classList.remove('hidden');
        }

        // ─── Inicialización ───────────────────────────────
        aplicarModoDocumento();
        showStep(currentStep);
    });
</script>
