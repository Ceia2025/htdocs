/* Editor de planificaciones EPJA — SAAT
 * Port del Planificador_EPJA_app con persistencia en MySQL (autoguardado por módulo). */
(function () {
    'use strict';

    var D = JSON.parse(document.getElementById('pl-data').textContent);
    var $app = document.getElementById('pl-app');
    var $modal = document.getElementById('pl-modal');
    var $toast = document.getElementById('pl-toast');
    var $sync = document.getElementById('pl-sync');

    var OBLIG = 4;
    var HEAD = ['Temporalidad (fecha / horas)', 'OA (Habilidades y actitudes nucleares)', 'Conocimientos esenciales (el saber)',
        'Habilidad / Siglo XXI (el hacer y ser)', 'Estrategia de actividad', 'Indicadores de evaluación', 'Instrumento', 'Tipo de evaluación (Dec. 67)'];
    var INST_ICON = { 'Pruebas escritas': '✎', 'Trabajos escritos': '▤', 'Presentaciones orales': '◉', 'Portafolios de trabajo': '▣',
        'Cuestionarios': '?', 'Rúbricas de evaluación': '▦', 'Revisión Cuaderno': '▥', 'Resolución problemas': '∑' };

    var mods = D.modulos;           // estado en memoria (fuente de verdad en pantalla)
    var cur = 0;                    // módulo visible
    var dirty = {};                 // módulos con cambios sin guardar
    var timers = {};                // debounce por módulo
    var enVuelo = {};               // guardados en curso
    var toastT = null;

    /* ---------- utilidades ---------- */
    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }
    function attr(v) { return esc(JSON.stringify(v)); }
    function newClase() {
        return { clase: '', fecha_desde: '', fecha_hasta: '', horas: '', oa_id: '', hab21: [], conocimientos: [],
            estrategias: '', indicadores: '', instrumentos: [], tipo: '' };
    }
    function unitName(i) { return i === 0 ? 'Módulo 1 · Nivelación' : i < OBLIG ? 'Módulo ' + (i + 1) : 'Módulo electivo ' + (i === 4 ? 'A' : 'B'); }
    function tabName(i) { return i === 0 ? 'M1 Nivelación' : i < OBLIG ? 'Módulo ' + (i + 1) : 'Electivo ' + (i === 4 ? 'A' : 'B'); }
    function isOpt(i) { return i >= OBLIG; }
    function usado(i) { return i < OBLIG || mods[i].activo; }
    function fuente(i) { return i === 0 ? D.catalogo.previa : D.catalogo.actual; }
    function horasModulo(m) { return (Number(m.horas_semana) || 0) * (Number(m.semanas) || 0); }
    function horasClases(m) { return m.clases.reduce(function (s, c) { return s + (Number(c.horas) || 0); }, 0); }
    function fdate(s) { return s ? s.split('-').reverse().slice(0, 2).join('/') : ''; }
    function keepScroll(fn) { var y = window.scrollY; fn(); window.scrollTo(0, y); }
    function normAmb(x) {
        var k = Object.keys(D.bases.ambitos).filter(function (a) { return a.toLowerCase() === String(x).trim().toLowerCase(); })[0];
        return k || x;
    }
    function ambFor(oa) {
        var k = (oa.ambitos || []).map(normAmb).filter(function (a) { return D.bases.ambitos[a]; });
        return k.length ? k : Object.keys(D.bases.ambitos);
    }
    function oaDe(i, id) {
        return (fuente(i).oas || []).filter(function (o) { return o.id === id; })[0] || null;
    }

    /* ---------- progreso (misma regla que el servidor) ---------- */
    function estadoClase(c) {
        return [c.fecha_desde, String(c.horas).trim(), c.oa_id, c.conocimientos.length, c.estrategias.trim(),
            c.indicadores.trim(), c.instrumentos.length, c.tipo].filter(Boolean).length;
    }
    function estadoModulo(m) {
        var d = (m.nombre.trim() ? 1 : 0) + (m.horas_semana !== '' ? 1 : 0) + (String(m.semanas) !== '' ? 1 : 0), t = 3;
        m.clases.forEach(function (c) { d += estadoClase(c); t += 8; });
        return { d: d, t: t };
    }
    function progreso() {
        var d = 0, t = 0;
        mods.forEach(function (m, i) { if (!usado(i)) return; var s = estadoModulo(m); d += s.d; t += s.t; });
        return { pct: t ? Math.round(d / t * 100) : 0 };
    }
    function hayElectivo() { return mods[4].activo || mods[5].activo; }

    /* ---------- guardado ---------- */
    function setSync(t, cls) { $sync.textContent = t; $sync.className = 'pl-sync' + (cls ? ' ' + cls : ''); }

    function cambio(i) {
        dirty[i] = true;
        setSync('Sin guardar…');
        clearTimeout(timers[i]);
        timers[i] = setTimeout(function () { guardar(i); }, 900);
        pintarProgreso();
    }

    function payload(i) {
        var m = mods[i];
        return {
            csrf: D.csrf, plan_id: D.plan.id, numero: i,
            modulo: { nombre: m.nombre, horas_semana: m.horas_semana, semanas: m.semanas, actitudes: m.actitudes, transversal: m.transversal, activo: m.activo ? 1 : 0 },
            clases: m.clases
        };
    }

    function guardar(i, rapido) {
        if (!dirty[i]) return Promise.resolve(true);
        if (enVuelo[i]) { timers[i] = setTimeout(function () { guardar(i); }, 400); return Promise.resolve(false); }
        clearTimeout(timers[i]);
        dirty[i] = false;
        enVuelo[i] = true;
        setSync('Guardando…');
        return fetch(D.urls.guardar, {
            method: 'POST', credentials: 'same-origin', keepalive: !!rapido,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload(i))
        }).then(function (r) {
            return r.json().catch(function () { return { ok: false, error: 'Respuesta inválida del servidor.' }; });
        }).then(function (j) {
            enVuelo[i] = false;
            if (!j.ok) { dirty[i] = true; setSync('No se pudo guardar', 'err'); aviso(j.error || 'No se pudo guardar.'); return false; }
            if (!Object.keys(dirty).some(function (k) { return dirty[k]; })) setSync('Guardado ✓', 'ok');
            return true;
        }).catch(function () {
            enVuelo[i] = false; dirty[i] = true;
            setSync('Sin conexión', 'err');
            aviso('No se pudo guardar. Revisa tu conexión; se reintentará al seguir editando.');
            return false;
        });
    }

    function guardarTodo(rapido) {
        return Promise.all(Object.keys(dirty).filter(function (k) { return dirty[k]; })
            .map(function (k) { return guardar(Number(k), rapido); }));
    }

    window.addEventListener('beforeunload', function (e) {
        var pendiente = Object.keys(dirty).some(function (k) { return dirty[k]; });
        if (pendiente) { guardarTodo(true); e.preventDefault(); e.returnValue = ''; }
    });
    document.addEventListener('visibilitychange', function () { if (document.hidden) guardarTodo(true); });

    /* ---------- toast / modal ---------- */
    function aviso(t, undo) {
        $toast.textContent = t;
        if (undo) {
            var b = document.createElement('button');
            b.type = 'button'; b.textContent = 'Deshacer';
            b.onclick = function () { undo(); $toast.hidden = true; };
            $toast.appendChild(b);
        }
        $toast.hidden = false;
        clearTimeout(toastT);
        toastT = setTimeout(function () { $toast.hidden = true; }, undo ? 6000 : 3500);
    }
    function cerrarModal() { $modal.innerHTML = ''; }
    function modal(html) {
        $modal.innerHTML = '<div class="modal-backdrop pl-modal" data-act="cerrar-fondo"><div class="pl-sheet" role="dialog" aria-modal="true">' + html + '</div></div>';
    }

    /* ---------- vistas ---------- */
    function pintarProgreso() {
        var p = progreso();
        var el = document.getElementById('pl-prog');
        if (el) {
            el.innerHTML = '<strong>' + p.pct + '%</strong>' +
                '<div class="progress-track pl-bar"><div class="progress-fill" style="width:' + p.pct + '%"></div></div>' +
                '<span class="pl-note">4 módulos obligatorios + al menos 1 electivo · 6 semanas c/u (Bases EPJA 2024)</span>' +
                (hayElectivo() ? '' : '<span class="chip-warn pl-pill">Falta elegir un electivo</span>');
        }
        var tabs = document.getElementById('pl-tabs');
        if (tabs) {
            tabs.innerHTML = mods.map(function (m, i) {
                var s = estadoModulo(m);
                var c = !usado(i) ? '' : s.d === s.t ? 'full' : s.d > 0 ? 'half' : '';
                return '<button type="button" class="tab-btn pl-tab' + (i === cur ? ' active on' : '') + (usado(i) ? '' : ' off') +
                    '" data-act="modulo" data-i="' + i + '"><span class="pl-dot ' + c + '"></span>' + esc(tabName(i)) + '</button>';
            }).join('');
        }
    }

    function hcheck(m) {
        var total = horasModulo(m), asig = horasClases(m);
        if (!total) return '<p class="pl-hint">Completa horas a la semana y semanas: la calculadora saca el total.</p>';
        var c = asig === total ? 'ok' : asig > total ? 'over' : '';
        return '<p class="pl-hint ' + c + '">Horas asignadas a clases: <b>' + asig + ' de ' + total + '</b>' +
            (asig === total ? ' ✓' : asig > total ? ' · te pasaste por ' + (asig - total) + ' h' : ' · faltan ' + (total - asig) + ' h') + '</p>';
    }

    function claseHTML(i, c, k) {
        var m = mods[i], oa = oaDe(i, c.oa_id), src = fuente(i);
        var tog = function (campo, v) { return 'data-act="tog-clase" data-k="' + k + '" data-campo="' + campo + '" data-v="' + attr(v) + '"'; };
        var h = '<article class="pl-card"><div class="pl-card-top"><h3>' + esc(c.clase || 'Clase ' + (k + 1)) + '</h3>' +
            (m.clases.length > 1 ? '<span id="pl-del' + k + '"><button type="button" class="pl-x" data-act="quitar-clase" data-k="' + k + '">Quitar clase</button></span>' : '') + '</div>' +
            '<div class="pl-temp">' +
            '<label class="pl-field"><span class="pl-lbl">Clase</span><input class="input-field" data-c="' + k + '" data-campo="clase" value="' + esc(c.clase) + '" placeholder="Clase ' + (k + 1) + ' (o Clase 3-4)" maxlength="60"></label>' +
            '<label class="pl-field"><span class="pl-lbl">Fecha</span><input class="input-field" type="date" data-c="' + k + '" data-campo="fecha_desde" value="' + esc(c.fecha_desde) + '"' + (D.rango.min ? ' min="' + esc(D.rango.min) + '" max="' + esc(D.rango.max) + '"' : '') + '></label>' +
            '<label class="pl-field"><span class="pl-lbl">Hasta (opcional)</span><input class="input-field" type="date" data-c="' + k + '" data-campo="fecha_hasta" value="' + esc(c.fecha_hasta) + '"' + (D.rango.min ? ' min="' + esc(D.rango.min) + '" max="' + esc(D.rango.max) + '"' : '') + '></label>' +
            '<label class="pl-field"><span class="pl-lbl">Horas</span><input class="input-field" type="number" min="0" max="255" inputmode="numeric" data-c="' + k + '" data-campo="horas" value="' + esc(c.horas) + '" placeholder="2"></label>' +
            '</div>';

        if (oa) {
            h += '<div class="pl-chosen"><div class="pl-chosen-top"><span class="pl-code">' + esc(oa.id) + (i === 0 ? ' <small>(' + esc(src.nivel) + ')</small>' : '') + '</span>' +
                '<span class="pl-actions"><button type="button" class="btn-soft-info pl-btn-sm" data-act="elegir-oa" data-k="' + k + '">Cambiar OA</button>' +
                '<button type="button" class="btn-soft-danger pl-btn-sm" data-act="quitar-oa" data-k="' + k + '">Quitar OA</button></span></div>' +
                '<p>' + esc(oa.texto) + '</p><div class="pl-tags"><span class="pl-tag h">Habilidad: ' + esc(oa.habilidad) + '</span>' +
                (oa.ambitos || []).map(function (x) { return '<span class="pl-tag">' + esc(x) + '</span>'; }).join('') + '</div></div>';

            h += '<div><div class="pl-lbl pl-lbl-b">Habilidades del siglo XXI (Bases EPJA 2024)</div>' +
                ambFor(oa).map(function (a) {
                    return '<div class="pl-amb"><span>' + esc(a) + '</span><div class="pl-chips">' +
                        D.bases.ambitos[a].map(function (x) {
                            return '<button type="button" class="pl-chipb' + (c.hab21.indexOf(x) >= 0 ? ' on' : '') + '" ' + tog('hab21', x) + '>' + esc(x) + '</button>';
                        }).join('') + '</div></div>';
                }).join('') + '</div>';

            h += '<div><div class="pl-lbl pl-lbl-b">Conocimientos esenciales</div><div class="pl-opts">' +
                (src.conocimientos || []).map(function (x) {
                    return '<button type="button" class="pl-opt' + (c.conocimientos.indexOf(x) >= 0 ? ' on' : '') + '" ' + tog('conocimientos', x) + '><span class="pl-ck">✓</span>' + esc(x) + '</button>';
                }).join('') + '</div></div>';

            h += '<div class="pl-rowf">' +
                '<label class="pl-field"><span class="pl-lbl">Estrategias de actividad</span><textarea class="input-field" data-c="' + k + '" data-campo="estrategias" maxlength="5000" placeholder="Breve descripción de la actividad">' + esc(c.estrategias) + '</textarea></label>' +
                '<label class="pl-field"><span class="pl-lbl">Indicadores de evaluación</span><textarea class="input-field" data-c="' + k + '" data-campo="indicadores" maxlength="5000" placeholder="Criterios observables de logro">' + esc(c.indicadores) + '</textarea></label></div>';

            h += '<div><div class="pl-lbl pl-lbl-b">Instrumento de evaluación</div><div class="pl-opts">' +
                D.instrumentos.map(function (x) {
                    var on = c.instrumentos.indexOf(x) >= 0;
                    return '<button type="button" class="pl-opt' + (on ? ' on' : '') + '" ' + tog('instrumentos', x) + '><span class="pl-ck">' + (on ? '✓' : (INST_ICON[x] || '')) + '</span>' + esc(x) + '</button>';
                }).join('') + '</div></div>';

            h += '<div><div class="pl-lbl pl-lbl-b">Tipo de evaluación (Dec. 67)</div><div class="pl-types">' +
                D.tipos.map(function (t) {
                    return '<button type="button" class="pl-type t-' + (t === 'DIAGNÓSTICA' ? 'diag' : t === 'FORMATIVA' ? 'form' : 'sum') + (c.tipo === t ? ' on' : '') +
                        '" data-act="tipo" data-k="' + k + '" data-v="' + esc(t) + '" title="Toca de nuevo para desmarcar">' + esc(t.charAt(0) + t.slice(1).toLowerCase()) + '</button>';
                }).join('') + '</div></div>';
        } else {
            h += '<button type="button" class="pl-pick" data-act="elegir-oa" data-k="' + k + '">Elegir Objetivo de Aprendizaje (OA) ›</button>';
        }
        return h + '</article>';
    }

    function moduloHTML() {
        var i = cur, m = mods[i];
        var nav = '<div class="pl-nav"><button type="button" class="btn-secondary px-4 py-2 rounded-lg" data-act="modulo" data-i="' + (i - 1) + '"' + (i === 0 ? ' disabled' : '') + '>← ' + (i > 0 ? esc(unitName(i - 1)) : '') + '</button>' +
            (i === mods.length - 1
                ? '<a class="btn-primary px-4 py-2 rounded-lg" href="' + esc(D.urls.volver) + '" data-act="terminar">Terminar y volver</a>'
                : '<button type="button" class="btn-primary px-4 py-2 rounded-lg" data-act="modulo" data-i="' + (i + 1) + '">' + esc(unitName(i + 1)) + ' →</button>') + '</div>';

        if (isOpt(i) && !m.activo) {
            return '<section class="panel pl-center"><div class="pl-lbl">Electivo</div><h2>' + esc(unitName(i)) + '</h2>' +
                '<p class="pl-note">Según las Bases Curriculares EPJA 2024 hay 2 módulos electivos y <b>debes cursar al menos uno</b>.</p>' +
                '<button type="button" class="btn-primary px-5 py-2 rounded-lg" data-act="activar">Usar este módulo electivo</button></section>' + nav;
        }
        var total = horasModulo(m);
        var h = '';
        if (i === 0) {
            h += '<div class="banner-info pl-banner"><b>Módulo de nivelación:</b> los OA disponibles son del nivel anterior (' + esc(fuente(0).nivel) + ' · ' + esc(fuente(0).asignatura) + ').</div>';
        }
        h += '<section class="panel pl-sec">' +
            '<label class="pl-field"><span class="pl-lbl">Nombre del ' + esc(unitName(i).toLowerCase()) + '</span><input class="input-field" data-m="nombre" value="' + esc(m.nombre) + '" maxlength="150" placeholder="' + (i === 0 ? 'Ej: Nivelación de contenidos base' : 'Ej: Números racionales y potencias') + '"></label>' +
            '<div class="pl-calc">' +
            '<label class="pl-field"><span class="pl-lbl">Horas a la semana</span><input class="input-field" type="number" min="0" max="255" inputmode="numeric" data-m="horas_semana" value="' + esc(m.horas_semana) + '" placeholder="Ej: 4"></label>' +
            '<span class="pl-op">×</span>' +
            '<label class="pl-field"><span class="pl-lbl">Semanas del módulo <small>(Bases: 6)</small></span><input class="input-field" type="number" min="0" max="255" inputmode="numeric" data-m="semanas" value="' + esc(m.semanas) + '" placeholder="Ej: 6"></label>' +
            '<span class="pl-op">=</span>' +
            '<div class="pl-res"><span class="pl-lbl">Horas del módulo</span><strong id="pl-tot">' + (total || '—') + '</strong></div></div>' +
            '<div id="pl-hcheck">' + hcheck(m) + '</div>' +
            '<div class="pl-sub"><div class="pl-lbl pl-lbl-b">Aprendizaje transversal · actitudes (Bases EPJA 2024)</div>' +
            Object.keys(D.bases.actitudes).map(function (a) {
                return '<div class="pl-amb"><span>' + esc(a) + '</span><div class="pl-chips">' + D.bases.actitudes[a].map(function (x) {
                    return '<button type="button" class="pl-chipb' + (m.actitudes.indexOf(x) >= 0 ? ' on' : '') + '" data-act="tog-actitud" data-v="' + attr(x) + '">' + esc(x) + '</button>';
                }).join('') + '</div></div>';
            }).join('') + '</div>' +
            '<label class="pl-field pl-sub"><span class="pl-lbl">Otro / detalle (opcional)</span><input class="input-field" data-m="transversal" maxlength="255" value="' + esc(m.transversal) + '" placeholder="Ej: Respeto, trabajo colaborativo"></label>' +
            '</section>';
        h += m.clases.map(function (c, k) { return claseHTML(i, c, k); }).join('');
        h += '<button type="button" class="pl-add" data-act="agregar-clase">+ Agregar clase</button>';
        if (isOpt(i)) h += '<div class="pl-sub"><button type="button" class="pl-x" data-act="desactivar">No usar este módulo</button></div>';
        return h + nav;
    }

    function render() {
        $app.innerHTML =
            '<div class="pl-editbar"><button type="button" class="btn-soft-info pl-btn-sm" data-act="exp-doc">⬇ Word</button>' +
            '<button type="button" class="btn-soft-info pl-btn-sm" data-act="exp-xlsx">⬇ Excel</button>' +
            '<button type="button" class="btn-soft-info pl-btn-sm" data-act="exp-pdf">⬇ PDF</button>' +
            '<button type="button" class="btn-soft-info pl-btn-sm" data-act="copiar">⧉ Copiar tabla</button></div>' +
            '<div class="panel pl-progress" id="pl-prog"></div><div class="pl-tabs" id="pl-tabs"></div>' +
            '<div id="pl-body">' + moduloHTML() + '</div>';
        pintarProgreso();
    }
    function renderBody() { keepScroll(function () { document.getElementById('pl-body').innerHTML = moduloHTML(); pintarProgreso(); }); }

    /* ---------- acciones ---------- */
    function irModulo(i) {
        if (i < 0 || i >= mods.length) return;
        guardarTodo();
        cur = i;
        document.getElementById('pl-body').innerHTML = moduloHTML();
        pintarProgreso();
        window.scrollTo(0, 0);
    }
    function toggle(arr, v) { var j = arr.indexOf(v); if (j < 0) arr.push(v); else arr.splice(j, 1); }

    function elegirOA(k) {
        var m = mods[cur], c = m.clases[k], src = fuente(cur);
        var usados = {};
        mods.forEach(function (mm, mi) {
            if ((mi === 0) !== (cur === 0)) return;
            mm.clases.forEach(function (x) { if (x.oa_id) (usados[x.oa_id] = usados[x.oa_id] || []).push(mi + 1); });
        });
        modal('<div class="pl-sheet-top"><h2>Elige el Objetivo de Aprendizaje' + (cur === 0 ? ' <small>· nivel anterior</small>' : '') + '</h2>' +
            '<button type="button" class="btn-secondary px-3 py-1 rounded-lg" data-act="cerrar">Cerrar</button></div>' +
            (src.oas || []).map(function (o) {
                var u = usados[o.id] ? Array.from(new Set(usados[o.id])).join(', ') : '';
                return '<button type="button" class="pl-oacard' + (c.oa_id === o.id ? ' on' : '') + '" data-act="set-oa" data-k="' + k + '" data-v="' + esc(o.id) + '">' +
                    '<span class="pl-code">' + esc(o.id) + '</span><p>' + esc(o.texto) + '</p><div class="pl-tags"><span class="pl-tag h">' + esc(o.habilidad) + '</span>' +
                    (o.ambitos || []).map(function (x) { return '<span class="pl-tag">' + esc(x) + '</span>'; }).join('') + '</div>' +
                    (u ? '<span class="pl-used">Ya usado en módulo ' + esc(u) + '</span>' : '') + '</button>';
            }).join(''));
    }

    function preguntarQuitar(k) {
        document.getElementById('pl-del' + k).innerHTML = '<span class="pl-confirm">¿Quitar esta clase? ' +
            '<button type="button" class="btn-danger pl-btn-sm" data-act="quitar-clase-ok" data-k="' + k + '">Sí, quitar</button> ' +
            '<button type="button" class="btn-secondary pl-btn-sm" data-act="quitar-clase-no">No</button></span>';
    }

    /* ---------- exportes ---------- */
    function temporalidad(c, i) {
        var r = [c.fecha_desde && fdate(c.fecha_desde), c.fecha_hasta && fdate(c.fecha_hasta)].filter(Boolean).join(' – ');
        return (c.clase || 'Clase ' + (i + 1)) + (r ? ' (' + r + ')' : '') + (c.horas ? ' · ' + c.horas + ' h' : '');
    }
    function bloques() {
        return mods.map(function (m, i) {
            if (!usado(i)) return null;
            return {
                m: m, titulo: unitName(i).toUpperCase() + ': ' + (m.nombre || '(sin nombre)'), horas: horasModulo(m),
                rows: m.clases.map(function (c, k) {
                    var o = oaDe(i, c.oa_id) || {};
                    return [temporalidad(c, k), c.oa_id ? c.oa_id + ': ' + (o.texto || '') : '', c.conocimientos.join('; '),
                        c.oa_id ? (o.habilidad || '') + ' / ' + (o.ambitos || []).join(', ') + (c.hab21.length ? ': ' + c.hab21.join(', ') : '') : '',
                        c.estrategias, c.indicadores, c.instrumentos.join(', '), c.tipo ? c.tipo.charAt(0) + c.tipo.slice(1).toLowerCase() : ''];
                })
            };
        }).filter(Boolean);
    }
    function transv(m) { return [m.actitudes.join(', '), m.transversal].filter(Boolean).join('. '); }
    function nombreArchivo(ext) {
        return ('Planificacion_' + D.plan.anio + '_' + D.plan.asignatura + '_' + D.plan.curso).replace(/[^\wÁÉÍÓÚÑáéíóúñ]+/g, '_') + '.' + ext;
    }
    function tablaHTML() {
        var th = 'style="border:1px solid #555;padding:4px;background:#DCE6F7;font-weight:bold;vertical-align:top"';
        var td = 'style="border:1px solid #555;padding:4px;vertical-align:top"';
        return '<h2>PLANIFICACIÓN ' + D.plan.anio + '</h2>' + bloques().map(function (b) {
            return '<table style="border-collapse:collapse;width:100%;font-family:Arial;font-size:10pt;margin-bottom:6pt">' +
                '<tr><td ' + th + '>Nombre Profesor/a</td><td ' + td + ' colspan="7">' + esc(D.plan.docente) + '</td></tr>' +
                '<tr><td ' + th + '>Curso</td><td ' + td + ' colspan="7">' + esc(D.plan.curso) + ' (' + esc(D.plan.nivel) + ')</td></tr>' +
                '<tr><td ' + th + '>Asignatura</td><td ' + td + ' colspan="7">' + esc(D.plan.asignatura) + '</td></tr>' +
                '<tr><td ' + th + '>Módulo</td><td ' + td + ' colspan="7">' + esc(b.titulo) + ' · ' + (b.horas || '-') + ' horas (' + esc(b.m.horas_semana || '-') + ' h/sem × ' + esc(b.m.semanas || '-') + ' sem)</td></tr>' +
                '<tr><td ' + th + '>Aprendizaje Transversal</td><td ' + td + ' colspan="7">' + esc(transv(b.m)) + '</td></tr></table>' +
                '<table style="border-collapse:collapse;width:100%;font-family:Arial;font-size:9pt;margin-bottom:18pt"><tr>' +
                HEAD.map(function (x) { return '<th ' + th + '>' + x + '</th>'; }).join('') + '</tr>' +
                b.rows.map(function (r) { return '<tr>' + r.map(function (cc) { return '<td ' + td + '>' + esc(cc) + '</td>'; }).join('') + '</tr>'; }).join('') + '</table>';
        }).join('');
    }
    function descargar(nombre, blob) {
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob); a.download = nombre;
        document.body.appendChild(a); a.click(); a.remove();
        setTimeout(function () { URL.revokeObjectURL(a.href); }, 2000);
        aviso('Archivo listo');
    }
    function sinLibreria(msg) {
        modal('<h2>No se pudo generar el archivo</h2><p>' + esc(msg) + '</p><p><b>Alternativa:</b> usa <b>Copiar tabla</b> y pégala en Word o Excel (Ctrl+V).</p>' +
            '<div class="pl-actions"><button type="button" class="btn-primary px-4 py-2 rounded-lg" data-act="copiar">⧉ Copiar tabla</button>' +
            '<button type="button" class="btn-secondary px-4 py-2 rounded-lg" data-act="cerrar">Cerrar</button></div>');
    }
    function cargarLib(src, test) {
        return new Promise(function (ok, fail) {
            if (test()) return ok();
            var s = document.createElement('script');
            s.src = src; s.onload = function () { test() ? ok() : fail(); }; s.onerror = fail;
            document.head.appendChild(s);
        });
    }
    function expDoc() {
        var doc = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word"><head><meta charset="utf-8"><style>@page{size:landscape;margin:1.5cm}</style></head><body>' + tablaHTML() + '</body></html>';
        descargar(nombreArchivo('doc'), new Blob(['﻿' + doc], { type: 'application/msword' }));
    }
    function expXlsx() {
        cargarLib('https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js', function () { return window.XLSX; }).then(function () {
            var aoa = [['PLANIFICACIÓN ' + D.plan.anio], ['Profesor/a:', D.plan.docente], ['Curso:', D.plan.curso], ['Asignatura:', D.plan.asignatura], ['Nivel:', D.plan.nivel]];
            bloques().forEach(function (b) {
                aoa.push([]); aoa.push([b.titulo]);
                aoa.push(['Horas:', (b.horas || '-') + ' (' + (b.m.horas_semana || '-') + ' h/sem × ' + (b.m.semanas || '-') + ' sem)']);
                aoa.push(['Aprendizaje transversal:', transv(b.m)]); aoa.push(HEAD);
                b.rows.forEach(function (r) { aoa.push(r); });
            });
            var ws = XLSX.utils.aoa_to_sheet(aoa);
            ws['!cols'] = [24, 50, 36, 34, 40, 40, 24, 16].map(function (w) { return { wch: w }; });
            var wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, 'Planificación');
            descargar(nombreArchivo('xlsx'), new Blob([XLSX.write(wb, { type: 'array', bookType: 'xlsx' })], { type: 'application/octet-stream' }));
        }).catch(function () { sinLibreria('No se cargó el generador de Excel (la red puede estar bloqueando cdnjs.cloudflare.com).'); });
    }
    function expPdf() {
        cargarLib('https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js', function () { return window.jspdf; })
            .then(function () { return cargarLib('https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js', function () { return window.jspdf.jsPDF.API.autoTable; }); })
            .then(function () {
                var doc = new window.jspdf.jsPDF({ orientation: 'landscape', unit: 'pt', format: 'legal' });
                doc.setFontSize(15); doc.text('PLANIFICACIÓN ' + D.plan.anio + ' — ' + D.plan.asignatura, 36, 40);
                doc.setFontSize(10); doc.text('Profesor/a: ' + D.plan.docente + '     Curso: ' + (D.plan.curso || '-') + '     Nivel: ' + D.plan.nivel, 36, 58);
                var y = 72;
                bloques().forEach(function (b) {
                    doc.autoTable({
                        startY: y, head: [[{ content: b.titulo + '   ·   ' + (b.horas || '-') + ' horas   ·   Aprendizaje transversal: ' + (transv(b.m) || '-'), colSpan: 8, styles: { fillColor: [31, 79, 160], halign: 'left' } }], HEAD],
                        body: b.rows, styles: { fontSize: 8, cellPadding: 4, valign: 'top' }, headStyles: { fillColor: [24, 34, 58] },
                        columnStyles: { 0: { cellWidth: 78 }, 7: { cellWidth: 62 } }, margin: { left: 36, right: 36 }
                    });
                    y = doc.lastAutoTable.finalY + 16;
                });
                descargar(nombreArchivo('pdf'), new Blob([doc.output('arraybuffer')], { type: 'application/pdf' }));
            }).catch(function () { sinLibreria('No se cargó el generador de PDF (la red puede estar bloqueando cdnjs.cloudflare.com).'); });
    }
    function copiar() {
        var html = tablaHTML();
        var texto = bloques().map(function (b) { return b.titulo + '\n' + HEAD.join('\t') + '\n' + b.rows.map(function (r) { return r.join('\t'); }).join('\n'); }).join('\n\n');
        var okMsg = function (t) { cerrarModal(); aviso(t); };
        var plano = function () {
            return navigator.clipboard.writeText(texto).then(function () { okMsg('Copiada como texto'); }).catch(function () {
                modal('<h2>Selecciona y copia</h2><p>Selecciona la tabla con el mouse y copia con Ctrl+C.</p><div class="pl-copybox">' + html + '</div>' +
                    '<button type="button" class="btn-secondary px-4 py-2 rounded-lg" data-act="cerrar">Cerrar</button>');
            });
        };
        if (navigator.clipboard && window.ClipboardItem) {
            navigator.clipboard.write([new ClipboardItem({ 'text/html': new Blob([html], { type: 'text/html' }), 'text/plain': new Blob([texto], { type: 'text/plain' }) })])
                .then(function () { okMsg('Tabla copiada: pégala en Word o Excel'); }).catch(plano);
        } else { plano(); }
    }

    /* ---------- eventos (delegación) ---------- */
    $app.addEventListener('input', function (e) {
        var t = e.target, m = mods[cur];
        if (t.dataset.m) {
            m[t.dataset.m] = t.value;
            if (t.dataset.m === 'horas_semana' || t.dataset.m === 'semanas') {
                var tot = horasModulo(m);
                document.getElementById('pl-tot').textContent = tot || '—';
                document.getElementById('pl-hcheck').innerHTML = hcheck(m);
            }
            cambio(cur);
        } else if (t.dataset.c !== undefined) {
            var k = Number(t.dataset.c), campo = t.dataset.campo;
            m.clases[k][campo] = t.value;
            if (campo === 'horas') document.getElementById('pl-hcheck').innerHTML = hcheck(m);
            if (campo === 'clase') { var h3 = t.closest('.pl-card').querySelector('h3'); if (h3) h3.textContent = t.value || 'Clase ' + (k + 1); }
            cambio(cur);
        }
    });

    document.addEventListener('click', function (e) {
        var b = e.target.closest('[data-act]');
        if (!b) return;
        var act = b.dataset.act, m = mods[cur], k = b.dataset.k !== undefined ? Number(b.dataset.k) : null;
        if (act === 'cerrar-fondo') { if (e.target === b) cerrarModal(); return; }
        if (act === 'terminar') { e.preventDefault(); guardarTodo().then(function () { window.location.href = D.urls.volver; }); return; }
        switch (act) {
            case 'cerrar': cerrarModal(); break;
            case 'modulo': irModulo(Number(b.dataset.i)); break;
            case 'activar': m.activo = true; cambio(cur); renderBody(); break;
            case 'desactivar': m.activo = false; cambio(cur); renderBody(); break;
            case 'tog-actitud': toggle(m.actitudes, JSON.parse(b.dataset.v)); cambio(cur); renderBody(); break;
            case 'tog-clase': toggle(m.clases[k][b.dataset.campo], JSON.parse(b.dataset.v)); cambio(cur); renderBody(); break;
            case 'tipo': m.clases[k].tipo = m.clases[k].tipo === b.dataset.v ? '' : b.dataset.v; cambio(cur); renderBody(); break;
            case 'agregar-clase': {
                var prev = m.clases[m.clases.length - 1], n = newClase();
                if (prev && prev.oa_id) n.oa_id = prev.oa_id;
                if (m.clases.length >= 80) { aviso('Máximo 80 clases por módulo.'); break; }
                m.clases.push(n); cambio(cur); renderBody(); break;
            }
            case 'quitar-clase': preguntarQuitar(k); break;
            case 'quitar-clase-no': renderBody(); break;
            case 'quitar-clase-ok': {
                var viejo = m.clases.splice(k, 1)[0], modulo = cur;
                cambio(cur); renderBody();
                aviso('Clase quitada', function () { mods[modulo].clases.splice(k, 0, viejo); cambio(modulo); if (cur === modulo) renderBody(); });
                break;
            }
            case 'elegir-oa': elegirOA(k); break;
            case 'set-oa': {
                var cl = m.clases[k];
                if (cl.oa_id !== b.dataset.v) { cl.oa_id = b.dataset.v; cl.conocimientos = []; cl.hab21 = []; }
                cerrarModal(); cambio(cur); renderBody(); break;
            }
            case 'quitar-oa': {
                var antes = JSON.parse(JSON.stringify(m.clases[k])), mm = cur;
                var hold = { clase: antes.clase, fecha_desde: antes.fecha_desde, fecha_hasta: antes.fecha_hasta, horas: antes.horas };
                m.clases[k] = Object.assign(newClase(), hold);
                cambio(cur); renderBody();
                aviso('OA quitado', function () { mods[mm].clases[k] = antes; cambio(mm); if (cur === mm) renderBody(); });
                break;
            }
            case 'exp-doc': expDoc(); break;
            case 'exp-xlsx': expXlsx(); break;
            case 'exp-pdf': expPdf(); break;
            case 'copiar': copiar(); break;
        }
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') cerrarModal(); });

    mods.forEach(function (m) { if (!m.clases.length) m.clases.push(newClase()); });
    setSync('');
    render();
})();