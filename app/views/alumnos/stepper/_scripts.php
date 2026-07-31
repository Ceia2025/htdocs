<script>
document.addEventListener('DOMContentLoaded', () => {

    // ─── Estado global ───────────────────────────────
    let currentStep   = 1;
    let runDuplicado  = false;
    let contactoIndex = 0;
    let tieneRun      = true; // true = chileno (RUN), false = extranjero (doc libre)

    const steps       = document.querySelectorAll('.step');
    const progressBar = document.getElementById('progress-bar');
    const stepperForm = document.getElementById('stepperForm');

    // Refs RUN alumno
    const runInput     = document.getElementById('run');
    const codverInput  = document.getElementById('codver');
    const runError     = document.getElementById('run-error');
    const runExistsMsg = document.getElementById('run-exists-msg');

    // Refs modo documento (chileno / extranjero)
    const nacidoChile     = document.getElementById('nacido_chile');
    const runLabelTexto   = document.getElementById('run-label-texto');
    const runHintExt      = document.getElementById('run-hint-extranjero');
    const etniaSelect     = document.getElementById('etnia_select');
    const etniaHintExt    = document.getElementById('etnia-hint-extranjero');
    const nacionalidadInp = document.getElementById('nacionalidad');
    const nacionalidadReq = document.getElementById('nacionalidad-req');

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
            const formatted  = formatRun(e.target.value);
            e.target.value   = formatted;
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

    // ─── Validación de requeridos por paso (doble red) ─────
    // Limpia el estado de error visual de un campo
    function limpiarError(el) {
        el.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
        const msg = el.parentElement.querySelector('.campo-error-msg');
        if (msg) msg.remove();
    }

    // Marca un campo como inválido con aviso inline
    function marcarError(el, texto) {
        el.classList.add('border-red-500', 'ring-1', 'ring-red-500');
        // Evitar duplicar el mensaje
        if (!el.parentElement.querySelector('.campo-error-msg')) {
            const p = document.createElement('p');
            p.className = 'campo-error-msg text-red-500 text-sm mt-1';
            p.textContent = texto;
            el.parentElement.appendChild(p);
        }
    }

    // Valida los campos [required] visibles dentro de un contenedor.
    // Devuelve el primer campo inválido, o null si todo OK.
    function validarRequeridos(contenedor) {
        let primerInvalido = null;

        contenedor.querySelectorAll('[required]').forEach(el => {
            // Ignorar campos ocultos (por CSS o por estar en paso no visible)
            if (el.offsetParent === null) return;

            limpiarError(el);
            const vacio = !el.value || el.value.trim() === '';

            if (vacio) {
                marcarError(el, 'Este campo es obligatorio.');
                if (!primerInvalido) primerInvalido = el;
            }
        });

        return primerInvalido;
    }

    // Enfoca y hace scroll al campo con error
    function irAlCampo(el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => el.focus({ preventScroll: true }), 300);
    }

    // Valida el paso actualmente visible. Devuelve true si es válido.
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

    // ─── Stepper ─────────────────────────────────────
    function showStep(step) {
        steps.forEach((s, idx) => {
            s.classList.toggle('hidden', (idx + 1) !== step);
        });
        if (progressBar) {
            progressBar.style.width = ((step / steps.length) * 100) + '%';
        }
    }

    document.querySelectorAll('.next-step').forEach(btn => {
        btn.addEventListener('click', e => {
            // 1) Validar requeridos del paso actual
            if (!validarPaso(currentStep)) {
                e.preventDefault();
                return;
            }
            // 2) Bloquear si el RUN está duplicado
            if (runDuplicado) {
                alert('⚠️ No puedes continuar. El documento ya está registrado en el sistema.');
                e.preventDefault();
                return;
            }
            if (currentStep < steps.length) {
                currentStep++;
                showStep(currentStep);
            }
        });
    });

    document.querySelectorAll('.prev-step').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });

    // ─── Envío del formulario (segunda red) ───────────
    if (stepperForm) {
        stepperForm.addEventListener('submit', e => {
            // Validar TODOS los pasos antes de enviar
            for (let i = 1; i <= steps.length; i++) {
                const invalido = validarRequeridos(steps[i - 1]);
                if (invalido) {
                    e.preventDefault();
                    // Ir al paso que contiene el error y saltar al campo
                    currentStep = i;
                    showStep(currentStep);
                    setTimeout(() => irAlCampo(invalido), 100);
                    return;
                }
            }

            if (runDuplicado) {
                e.preventDefault();
                alert('⚠️ No se puede enviar. El documento ya existe.');
                return;
            }

            // Habilitar campos deshabilitados para que viajen en el POST
            if (etniaSelect && etniaSelect.disabled) {
                etniaSelect.disabled = false;
            }
            stepperForm.querySelectorAll('input[disabled], select[disabled], textarea[disabled]')
                       .forEach(el => el.disabled = false);
        });
    }

    // ─── Regiones / Ciudades ──────────────────────────
    fetch('../utils/comunas-regiones.json')
        .then(res => {
            if (!res.ok) throw new Error('No se pudo cargar comunas-regiones.json');
            return res.json();
        })
        .then(data => {
            const regionSelect = document.getElementById('region');
            const ciudadSelect = document.getElementById('ciudad');
            if (!regionSelect || !ciudadSelect || !data?.regiones) return;

            data.regiones.forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.region;
                opt.textContent = r.region;
                regionSelect.appendChild(opt);
            });

            regionSelect.addEventListener('change', () => {
                ciudadSelect.innerHTML = '<option value="">Seleccione una ciudad</option>';
                const reg = data.regiones.find(r => r.region === regionSelect.value);
                if (reg && Array.isArray(reg.comunas)) {
                    reg.comunas.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c;
                        opt.textContent = c;
                        ciudadSelect.appendChild(opt);
                    });
                }
            });
        })
        .catch(err => console.error('Error regiones/comunas:', err));

    // ─── Autocompletar Nivel/Ciclo según escolaridad (Paso 3) ─────
    (function () {
        const MAP_NIVEL = {
            'Basica Completa': '8vo Basico',
            'Media Completa':  '4to Medio',
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
    const RELACIONES = [
        'Madre','Padre','Hermano','Hermana',
        'Abuelo','Abuela','Tío','Tía',
        'Amigo','Amiga','Esposo','Esposa',
        'Apoderado','Apoderado Suplente',
        'Tutor','Tutor Legal','Representante','Por poder Simple', 'Psicóloga',
        'Hijo','Hija'
    ];

    function opcionesRelacion(sel = '') {
        return RELACIONES.map(r =>
            `<option value="${r}" ${r === sel ? 'selected' : ''}>${r}</option>`
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

        const tipoSel  = card.querySelector('.contacto-tipo');
        const campoObs = card.querySelector('.campo-obs');
        tipoSel.addEventListener('change', () => {
            campoObs.classList.toggle('hidden', tipoSel.value !== 'emergencia');
        });

        card.querySelector('.btn-eliminar-contacto').addEventListener('click', () => {
            card.remove();
            renumerarContactos();
        });

        const inputRunC = card.querySelector(`#run_contacto_${index}`);
        const inputDVC  = card.querySelector(`#dv_contacto_${index}`);
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

    function renumerarContactos() {
        document.querySelectorAll('.contacto-card').forEach((card, i) => {
            const t = card.querySelector('.contacto-titulo');
            if (t) t.textContent = `Contacto #${i + 1}`;
        });
    }

    function agregarContacto() {
        const container = document.getElementById('contactos-container');
        container.appendChild(crearTarjetaContacto(contactoIndex));
        contactoIndex++;
    }

    document.getElementById('btn-agregar-contacto').addEventListener('click', agregarContacto);
    agregarContacto();

    // ─── Inicialización ───────────────────────────────
    aplicarModoDocumento();
    showStep(currentStep);
});
</script>