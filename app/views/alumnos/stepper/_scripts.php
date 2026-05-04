<script>
document.addEventListener('DOMContentLoaded', () => {

    // ─── Estado global ───────────────────────────────
    let currentStep   = 1;
    let runDuplicado  = false;
    let contactoIndex = 0;

    const steps       = document.querySelectorAll('.step');
    const progressBar = document.getElementById('progress-bar');
    const stepperForm = document.getElementById('stepperForm');

    // Refs RUN alumno
    const runInput     = document.getElementById('run');
    const codverInput  = document.getElementById('codver');
    const runError     = document.getElementById('run-error');
    const runExistsMsg = document.getElementById('run-exists-msg');

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

    // Vincula formato + DV automático a cualquier par de inputs
    function bindRunDV(inputRun, inputDV) {
        inputRun.addEventListener('keypress', e => {
            if (!/[0-9]/.test(e.key)) e.preventDefault();
        });
        inputRun.addEventListener('input', e => {
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

    // ─── RUN del alumno ──────────────────────────────
    if (runInput && codverInput) {
        bindRunDV(runInput, codverInput);

        runInput.addEventListener('input', () => {
            if (runInput.value && !validateRun(runInput.value)) {
                runError.classList.remove('hidden');
            } else {
                runError.classList.add('hidden');
            }
        });

        let checkRunTimeout;
        function checkRun() {
            const val = runInput.value.trim();
            if (!val || !validateRun(val)) {
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
                        runExistsMsg.textContent = '⚠️ Este RUN ya está registrado en el sistema.';
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
        runInput.addEventListener('blur', checkRun);
        runInput.addEventListener('input', () => {
            clearTimeout(checkRunTimeout);
            checkRunTimeout = setTimeout(checkRun, 700);
        });
    }

    // ─── Stepper ─────────────────────────────────────
    function showStep(step) {
        steps.forEach((s, idx) => {
            const active = (idx + 1) === step;
            s.classList.toggle('hidden', !active);
            s.querySelectorAll('[required]').forEach(input => {
                if (!active) {
                    input.dataset.wasRequired = 'true';
                    input.removeAttribute('required');
                } else if (input.dataset.wasRequired === 'true' || !('wasRequired' in input.dataset)) {
                    input.setAttribute('required', 'required');
                }
            });
        });
        if (progressBar) {
            progressBar.style.width = ((step / steps.length) * 100) + '%';
        }
    }

    document.querySelectorAll('.next-step').forEach(btn => {
        btn.addEventListener('click', e => {
            if (runDuplicado) {
                alert('⚠️ No puedes continuar. El RUN ya está registrado en el sistema.');
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

    // ─── Envío del formulario ─────────────────────────
    if (stepperForm) {
        stepperForm.addEventListener('submit', e => {
            if (runDuplicado) {
                e.preventDefault();
                alert('⚠️ No se puede enviar. El RUN ya existe.');
                return;
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

    // ─── Contactos dinámicos (Paso 2) ─────────────────
    const RELACIONES = [
        'Madre','Padre','Hermano','Hermana',
        'Abuelo','Abuela','Tío','Tía',
        'Amigo','Amiga','Esposo','Esposa',
        'Apoderado','Apoderado Suplente',
        'Tutor','Tutor Legal','Representante',
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
                            placeholder="Ej: 12.345.678" maxlength="11"
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

        // Tipo → observación
        const tipoSel  = card.querySelector('.contacto-tipo');
        const campoObs = card.querySelector('.campo-obs');
        tipoSel.addEventListener('change', () => {
            campoObs.classList.toggle('hidden', tipoSel.value !== 'emergencia');
        });

        // Eliminar tarjeta
        card.querySelector('.btn-eliminar-contacto').addEventListener('click', () => {
            card.remove();
            renumerarContactos();
        });

        // RUN contacto
        const inputRunC = card.querySelector(`#run_contacto_${index}`);
        const inputDVC  = card.querySelector(`#dv_contacto_${index}`);
        const inputFull = card.querySelector(`#run_contacto_full_${index}`);
        const errorRunC = card.querySelector(`#run_contacto_error_${index}`);

        bindRunDV(inputRunC, inputDVC);

        function actualizarRunFull() {
            if (inputRunC.value && inputDVC.value) {
                inputFull.value = inputRunC.value + '-' + inputDVC.value;
            } else {
                inputFull.value = '';
            }
        }

        inputRunC.addEventListener('input', () => {
            if (inputRunC.value && !validateRun(inputRunC.value)) {
                errorRunC.classList.remove('hidden');
                inputFull.value = '';
            } else {
                errorRunC.classList.add('hidden');
                setTimeout(actualizarRunFull, 50);
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

    // Contacto inicial automático
    agregarContacto();

    // ─── Mostrar paso inicial ─────────────────────────
    showStep(currentStep);
});
</script>