<?php
/**
 * FichaMatriculaImporter
 * ------------------------------------------------------------------
 * Lee la "FICHA DE MATRÍCULA" (.xls / .xlsx, una hoja, un alumno) del
 * C.E.I.A. y la convierte en un arreglo listo para rellenar el stepper.
 *
 * - NO escribe en la base de datos: solo lee y normaliza.
 * - Las llaves de "campos" son exactamente los name="" del formulario.
 * - Todo lo que no se puede traducir con seguridad queda en "avisos"
 *   para que el usuario lo revise antes de guardar.
 */

require_once __DIR__ . '/../libs/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FichaMatriculaImporter
{
    /** Valores válidos en el sistema (mismos que usan el stepper y la BD) */
    private const ETNIAS = [
        'No pertenece a ningún Pueblo Originario', 'Aymara', 'Likanantai( Atacameño )',
        'Colla', 'Diaguita', 'Quechua', 'Rapa Nui', 'Mapuche', 'Kawésqar', 'Yagán',
        'Otro', 'No Registra',
    ];

    private const NIVELES_PADRES = [
        'Basica Incompleta', 'Basica Completa', 'Media Incompleta', 'Media Completa',
        'Técnico Incompleta', 'Técnico Completa', 'Superior Incompleta', 'Superior Completa',
        'Desconocido',
    ];

    private const ULTIMO_CURSO = [
        'basico' => [1 => '1ro basico', 2 => '2do basico', 3 => '3ro basico', 4 => '4to basico',
                     5 => '5to basico', 6 => '6to basico', 7 => '7mo basico', 8 => '8vo basico'],
        'medio'  => [1 => '1ro medio', 2 => '2do medio', 3 => '3ro Medio', 4 => '4to Medio'],
    ];

    private const ISAPRES = [
        'BANMEDICA' => 'BANCA MEDICA', 'BANCA MEDICA' => 'BANCA MEDICA', 'CRUZ BLANCA' => 'CRUZ BLANCA',
        'COLMENA' => 'COLMENA', 'MAS VIDA' => 'MAS VIDA', 'CON SALUD' => 'CON SALUD',
        'CONSALUD' => 'CON SALUD', 'VIDA TRES' => 'VIDA TRES', 'DIPRECA' => 'DIPRECA',
    ];

    private const CHILE_SOLIDARIO = ['Ninguno', 'Prioritario', 'Preferente', 'Incremento', 'Pro-Retención'];

    /** Bloques de contacto: celda de cada dato dentro de la ficha */
    private const BLOQUES_CONTACTO = [
        'padre_madre_tutor' => [
            'nombre' => 'G64', 'run' => 'U64', 'direccion' => 'G66', 'email' => 'G68',
            'comuna' => 'S68', 'relacion' => 'G70', 'celular' => 'V70',
        ],
        'apoderado' => [
            'nombre' => 'G74', 'run' => 'U74', 'direccion' => 'G76', 'email' => 'G78',
            'comuna' => 'S78', 'relacion' => 'S80', 'celular' => 'G80',
        ],
        'emergencia' => [
            'nombre' => 'G84', 'run' => 'G90', 'direccion' => 'G86', 'relacion' => 'G88',
            'celular' => 'U84', 'observacion' => ['G92', 'G93', 'G94', 'G95'],
        ],
    ];

    private Worksheet $hoja;
    private array $avisos = [];

    /** @var array<int,array{id:int|string,nombre:string}> */
    private array $cursos;
    /** @var array<int,array{id:int|string,anio:int|string}> */
    private array $anios;
    /** @var string[] */
    private array $relaciones;
    /** @var array<int,array{region:string,comunas:string[]}> */
    private array $regiones;

    /**
     * @param array $cursos      filas de cursos2 (id, nombre)
     * @param array $anios       filas de anios2 (id, anio)
     * @param array $relaciones  vínculos válidos (AlumEmergencia::relacionesDisponibles())
     * @param array $regiones    contenido "regiones" de utils/comunas-regiones.json
     */
    public function __construct(array $cursos = [], array $anios = [], array $relaciones = [], array $regiones = [])
    {
        $this->cursos     = $cursos;
        $this->anios      = $anios;
        $this->relaciones = $relaciones;
        $this->regiones   = $regiones;
    }

    /* ================================================================
       API PÚBLICA
    ================================================================ */

    public function importar(string $rutaArchivo): array
    {
        $this->avisos = [];

        $tipo   = IOFactory::identify($rutaArchivo);
        if (!in_array($tipo, ['Xls', 'Xlsx'], true)) {
            throw new RuntimeException('El archivo debe ser una planilla Excel (.xls o .xlsx).');
        }
        $reader = IOFactory::createReader($tipo);
        $reader->setReadDataOnly(true);
        $libro      = $reader->load($rutaArchivo);
        $this->hoja = $libro->getSheet(0);

        $this->validarFormato();

        $campos    = [];
        $alumno    = $this->leerAlumno($campos);
        $this->leerEscolar($campos);
        $this->leerPadres($campos);
        $contactos = $this->leerContactos($alumno['sexo'] ?? null);
        $this->leerMatricula($campos, $alumno);

        $libro->disconnectWorksheets();

        return [
            'ok'        => true,
            'campos'    => $campos,
            'contactos' => $contactos,
            'avisos'    => $this->avisos,
            'resumen'   => [
                'alumno' => trim(($campos['apepat'] ?? '') . ' ' . ($campos['apemat'] ?? '') . ', ' . ($campos['nombre'] ?? '')),
                'run'    => ($campos['run'] ?? '') . (!empty($campos['codver']) ? '-' . $campos['codver'] : ''),
                'nivel'  => $alumno['nivel'] ?? null,
                'curso'  => $alumno['curso_nombre'] ?? null,
                'anio'   => $alumno['anio'] ?? null,
            ],
        ];
    }

    /* ================================================================
       SECCIONES DE LA FICHA
    ================================================================ */

    private function validarFormato(): void
    {
        $a8  = $this->norm($this->txt('A8'));
        $a12 = $this->norm($this->txt('A12'));
        if (!str_contains($a8, 'antecedentes del alumno') || !str_contains($a12, 'paterno')) {
            throw new RuntimeException('El archivo no tiene el formato de la Ficha de Matrícula del C.E.I.A.');
        }
    }

    private function leerAlumno(array &$c): array
    {
        $extra = [];

        $c['apepat'] = $this->txt('G12');
        $c['apemat'] = $this->txt('R12');
        $c['nombre'] = $this->txt('G14');
        foreach (['nombre' => 'Nombres', 'apepat' => 'Apellido paterno'] as $k => $lbl) {
            if ($c[$k] === '') $this->aviso($k, "$lbl vacío en la ficha.");
        }

        // ── RUN / documento ──
        // Acepta: 22776574 (número), "22.776.574", "22776574-7", "22.776.574-7", "22,776,574",
        // con el DV en la misma celda o aparte (I20). Se guarda como siempre: "22.776.574" + DV aparte.
        $run = $this->parsearRun($this->txt('G20'), $this->txt('I20'));

        if ($run['tipo'] === 'run') {
            $c['tiene_run'] = '1';
            $c['run']       = $run['num'];
            $c['codver']    = $run['dv'];
            if ($run['dvFicha'] !== '' && $run['dvFicha'] !== $run['dv']) {
                $this->aviso('codver', "El dígito verificador de la ficha ({$run['dvFicha']}) no coincide con el calculado ({$run['dv']}) para el RUN {$run['num']}. Revisa el RUN.");
            }
        } elseif ($run['tipo'] === 'documento') {
            $c['tiene_run'] = '0';
            $c['run']       = $run['num'];
            $c['codver']    = '';
            $this->aviso('run', "\"{$run['num']}\" no es un RUN chileno válido: se cargó como alumno extranjero (documento libre). Si es chileno, revisa el número.");
        } else {
            $c['tiene_run'] = '1';
            $c['run']       = '';
            $c['codver']    = '';
            $this->aviso('run', 'La ficha no trae RUN.');
        }

        // ── Datos personales ──
        $c['fechanac'] = $this->fecha('R20') ?? '';
        if ($c['fechanac'] === '') $this->aviso('fechanac', 'Fecha de nacimiento vacía o no reconocida.');

        $sexo = $this->norm($this->txt('T26'));
        $c['sexo'] = match (true) {
            str_starts_with($sexo, 'masc') || $sexo === 'm' || $sexo === 'hombre' => 'M',
            str_starts_with($sexo, 'fem')  || $sexo === 'f' || $sexo === 'mujer'  => 'F',
            $sexo === ''                                                           => '',
            default                                                                => 'X',
        };
        $extra['sexo'] = $c['sexo'];

        $c['nacionalidades'] = $this->txt('G26') ?: ($c['tiene_run'] === '1' ? 'Chilena' : '');
        $c['numerohijos']    = (string) ($this->entero('G28') ?? '');
        $c['celular']        = $this->celular($this->txt('O22'));
        $c['email']          = strtolower($this->txt('G32'));
        if ($c['email'] !== '' && !filter_var($c['email'], FILTER_VALIDATE_EMAIL)) {
            $this->aviso('email', "El correo \"{$c['email']}\" no parece válido.");
        }
        $c['direccion'] = $this->txt('G30');

        // ── Región / comuna (nombres exactos del JSON del sistema) ──
        [$c['region'], $c['ciudad']] = $this->buscarRegionComuna($this->txt('T30'));

        // ── Etnia ──
        if ($c['tiene_run'] === '0') {
            $c['cod_etnia'] = 'Otro';
        } else {
            $pertenece = $this->siNo('G42');
            $pueblo    = $this->txt('M42');
            if ($pertenece === '1') {
                $c['cod_etnia'] = $this->elegir($pueblo, self::ETNIAS) ?? 'Otro';
                if ($this->elegir($pueblo, self::ETNIAS) === null) {
                    $this->aviso('cod_etnia', "Pueblo originario \"{$pueblo}\" no reconocido: quedó como \"Otro\".");
                }
            } else {
                $c['cod_etnia'] = 'No pertenece a ningún Pueblo Originario';
            }
        }

        $extra['nivel']        = $this->txt('R14');
        $extra['especialidad'] = $this->txt('R16');
        $extra['tipo']         = $this->txt('G16');
        return $extra;
    }

    private function leerEscolar(array &$c): void
    {
        $p = fn(string $k) => "antecedente_escolar[$k]";

        $c[$p('procedencia_colegio')] = $this->txt('G36');
        $c[$p('comuna')]              = $this->titulo($this->txt('U36'));

        $ucCrudo = $this->txt('G38');
        $uc      = $this->normalizarUltimoCurso($ucCrudo);
        $c[$p('ultimo_curso')] = $uc ?? '';
        if ($ucCrudo !== '' && $uc === null) {
            $this->aviso($p('ultimo_curso'), "Último curso \"{$ucCrudo}\" no reconocido: selecciónalo a mano.");
        }

        $c[$p('ultimo_anio_cursado')] = (string) ($this->entero('N38') ?? '');
        $c[$p('cursos_repetidos')]    = (string) ($this->entero('S38') ?? 0);
        $c[$p('pertenece_20')]        = $this->siNo('G40') ?? '0';
        $c[$p('informe_20')]          = $this->siNo('Q40') ?? '0';

        // Embarazo / semanas ("8 Semanas" → 8)
        $c[$p('embarazo')] = $this->siNo('P42') ?? '0';
        $sem = preg_replace('/\D/', '', $this->txt('W42'));
        $c[$p('semanas')]  = ($c[$p('embarazo')] === '1' && $sem !== '') ? $sem : '';

        $c[$p('info_salud')] = mb_substr(trim($this->txt('G44') . ' ' . $this->txt('G46')), 0, 200);
        $c[$p('eva_psico')]  = $this->txt('I48');

        $c[$p('prob_apren')] = $this->elegir($this->txt('S48'), ['Sin', 'Con', 'Desconocido']) ?? 'Desconocido';
        $pie = $this->norm($this->txt('W48'));
        $c[$p('pie')] = $pie === 'si' ? 'Si' : ($pie === 'no' ? 'No' : 'No se sabe');

        $c[$p('chile_solidario')]      = $this->siNo('G50') ?? '0';
        $c[$p('chile_solidario_cual')] = $c[$p('chile_solidario')] === '1'
            ? ($this->elegir($this->txt('L50'), self::CHILE_SOLIDARIO) ?? 'Ninguno')
            : 'Ninguno';

        // ── Previsión ──
        $isapreCrudo = strtoupper($this->norm($this->txt('U52')));
        $grupo       = strtoupper($this->txt('N52'));
        $fonasaSi    = $this->norm($this->txt('I52')) === 'si' || str_contains($this->norm($this->txt('G52')), 'fonasa');

        if ($isapreCrudo !== '' && !in_array($isapreCrudo, ['NO SABE', 'NINGUNO', 'NO'], true)) {
            $c[$p('fonasa')]       = 'Isapre';
            $c[$p('isapre')]       = self::ISAPRES[$isapreCrudo] ?? 'Ninguno';
            $c[$p('grupo_fonasa')] = 'Ninguno';
            if (!isset(self::ISAPRES[$isapreCrudo])) {
                $this->aviso($p('isapre'), "Isapre \"{$this->txt('U52')}\" no está en la lista del sistema.");
            }
        } elseif ($fonasaSi) {
            $c[$p('fonasa')]       = 'Fonasa';
            $c[$p('grupo_fonasa')] = in_array($grupo, ['A', 'B', 'C', 'D'], true) ? $grupo : 'No Sabe';
            $c[$p('isapre')]       = 'Ninguno';
        } else {
            $c[$p('fonasa')]       = 'Ninguno';
            $c[$p('grupo_fonasa')] = 'Ninguno';
            $c[$p('isapre')]       = 'Ninguno';
        }

        $c[$p('seguro_salud')] = mb_substr($this->txt('H54'), 0, 30);
    }

    private function leerPadres(array &$c): void
    {
        foreach (['padre' => ['G58', 'S58', 'nivel_ciclo_p'], 'madre' => ['G60', 'S60', 'nivel_ciclo_m']] as $campo => [$celEsc, $celNiv, $campoNiv]) {
            $crudo     = $this->txt($celEsc);
            $c[$campo] = $this->elegir($crudo, self::NIVELES_PADRES) ?? 'Desconocido';
            if ($crudo !== '' && $this->elegir($crudo, self::NIVELES_PADRES) === null) {
                $this->aviso($campo, "Escolaridad \"{$crudo}\" no reconocida: quedó como \"Desconocido\".");
            }
            $c[$campoNiv] = $this->txt($celNiv);
        }
    }

    private function leerContactos(?string $sexoAlumno): array
    {
        $contactos = [];

        foreach (self::BLOQUES_CONTACTO as $tipo => $map) {
            $nombre = $this->txt($map['nombre']);
            // La plantilla trae valores por defecto (vínculo "Madre", comuna "PARRAL"):
            // si no hay nombre, el bloque se considera vacío.
            if ($nombre === '') continue;

            $etiqueta = ['padre_madre_tutor' => 'Padre/Madre/Tutor', 'apoderado' => 'Apoderado', 'emergencia' => 'Emergencia'][$tipo];
            $celular  = $this->celular($this->txt($map['celular']));

            // RUN del contacto
            $run = $this->parsearRunContacto($this->txt($map['run']));
            if ($run['num'] !== '' && preg_replace('/\D/', '', $run['num']) === $celular) {
                $this->aviso("contacto:$tipo", "$etiqueta: el RUN ingresado es igual al celular ({$celular}); se dejó vacío.");
                $run = ['num' => '', 'dv' => '', 'aviso' => null];
            }
            if ($run['aviso']) $this->aviso("contacto:$tipo", "$etiqueta: {$run['aviso']}");

            // Vínculo
            $relCruda = $this->txt($map['relacion']);
            $relacion = $this->normalizarRelacion($relCruda);
            if ($relCruda !== '' && $relacion === '') {
                $this->aviso("contacto:$tipo", "$etiqueta: el vínculo \"{$relCruda}\" no existe en el sistema; elígelo a mano.");
            }

            $obs = '';
            if (isset($map['observacion'])) {
                $obs = trim(implode(' ', array_map(fn($cel) => $this->txt($cel), $map['observacion'])));
            }

            $contactos[] = [
                'tipo'            => $tipo,
                'nombre_contacto' => $nombre,
                'run_num'         => $run['num'],
                'run_dv'          => $run['dv'],
                'direccion'       => $this->txt($map['direccion']),
                'comuna'          => isset($map['comuna']) ? $this->titulo($this->txt($map['comuna'])) : '',
                'email'           => isset($map['email']) ? strtolower($this->txt($map['email'])) : '',
                'telefono'        => '',
                'celular'         => $celular,
                'relacion'        => $relacion,
                'observacion'     => $obs,
            ];
        }

        if (!$contactos) {
            $this->aviso('contactos', 'La ficha no trae contactos con nombre.');
        }
        return $contactos;
    }

    private function leerMatricula(array &$c, array &$alumno): void
    {
        // Año académico
        $anio = $this->entero('R6');
        $alumno['anio'] = $anio;
        $c['anio_id'] = '';
        foreach ($this->anios as $a) {
            if ((int) $a['anio'] === (int) $anio) { $c['anio_id'] = (string) $a['id']; break; }
        }
        if ($anio && $c['anio_id'] === '') {
            $this->aviso('anio_id', "El año {$anio} no existe en el sistema: créalo o elige otro año.");
        }

        // Curso sugerido
        [$cursoId, $cursoNombre] = $this->sugerirCurso($alumno['nivel'] ?? '', $alumno['especialidad'] ?? '', $alumno['tipo'] ?? '');
        $c['curso_id'] = $cursoId;
        $alumno['curso_nombre'] = $cursoNombre;
        if ($cursoId === '') {
            $this->aviso('curso_id', 'No se pudo sugerir un curso para el nivel "' . ($alumno['nivel'] ?: 'sin nivel') . '": selecciónalo a mano.');
        }
    }

    /* ================================================================
       REGLAS DE NEGOCIO
    ================================================================ */

    /**
     * "1ro y 2do Medio HC" → "1° y 2° HC"; "3ro Medio TP" + "Electricidad" → "3° Medio TP Eléctrico".
     * Compara números de nivel + modalidad (HC / TP / Básico) + especialidad.
     */
    private function sugerirCurso(string $nivel, string $especialidad, string $tipo): array
    {
        $n = $this->norm($nivel);
        if ($n === '' || str_contains($n, 'practica')) return ['', null];

        $nums = $this->numerosNivel($n);
        if (preg_match('/(\d)\s*nivel\s*basico/', $n, $m)) {
            // 1er Nivel = 1° a 4°, 2do Nivel = 5° y 6°, 3er Nivel = 7° y 8° básico
            $nums = ['1' => ['1', '4'], '2' => ['5', '6'], '3' => ['7', '8']][$m[1]] ?? $nums;
        }

        $todo = $n . ' ' . $this->norm($tipo);
        $modalidad = str_contains($todo, ' hc') || str_contains($todo, 'humanista') ? 'hc'
            : (str_contains($todo, ' tp') ? 'tp'
            : (str_contains($todo, 'basic') ? 'basic' : null));

        $esp = $this->norm($especialidad);
        $espToken = str_contains($esp, 'electric') ? 'electric' : (str_contains($esp, 'parvul') ? 'parvul' : null);

        $candidatos = [];
        foreach ($this->cursos as $curso) {
            $cn = $this->norm($curso['nombre']);
            if ($this->numerosNivel($cn) !== $nums) continue;
            if ($modalidad === 'hc' && !preg_match('/\bhc\b|humanista/', $cn)) continue;
            if ($modalidad === 'tp' && !preg_match('/\btp\b/', $cn)) continue;
            if ($modalidad === 'basic' && !str_contains($cn, 'basic')) continue;
            if ($modalidad === 'tp' && $espToken && !str_contains($cn, $espToken)) continue;
            $candidatos[] = $curso;
        }

        return count($candidatos) === 1
            ? [(string) $candidatos[0]['id'], $candidatos[0]['nombre']]
            : ['', null];
    }

    /** Extrae los números de nivel ordenados: "1ro y 2do medio" → ['1','2'] */
    private function numerosNivel(string $normalizado): array
    {
        preg_match_all('/\b(\d)\b/', $normalizado, $m);
        $nums = array_values(array_unique($m[1]));
        sort($nums);
        return $nums;
    }

    /** "8° BASICO", "8VO BASICO", "5to y 6to Básico", "3er Nivel Básico" → valor del ENUM */
    private function normalizarUltimoCurso(string $crudo): ?string
    {
        $n = $this->norm($crudo);
        if ($n === '') return null;

        $tipo = str_contains($n, 'medio') ? 'medio' : (str_contains($n, 'basic') ? 'basico' : null);
        if (!$tipo) return null;

        if (preg_match('/(\d)\s*nivel/', $n, $m)) {
            $map = $tipo === 'basico' ? ['1' => 4, '2' => 6, '3' => 8] : ['1' => 2, '2' => 4];
            $num = $map[$m[1]] ?? null;
        } else {
            $nums = $this->numerosNivel($n);
            $num  = $nums ? (int) end($nums) : null;
        }

        return $num ? (self::ULTIMO_CURSO[$tipo][$num] ?? null) : null;
    }

    /** "MADRE" → "Madre", "Tío (a)" → "" (ambiguo), "PAREJA" → "" (no existe) */
    private function normalizarRelacion(string $crudo): string
    {
        if ($crudo === '' || str_contains($crudo, '(')) return '';
        return $this->elegir($crudo, $this->relaciones) ?? '';
    }

    /** RUN de contacto: mismo lector que el del alumno */
    private function parsearRunContacto(string $crudo): array
    {
        $run = $this->parsearRun($crudo);

        if ($run['tipo'] === 'vacio') {
            return ['num' => '', 'dv' => '', 'aviso' => null];
        }
        if ($run['tipo'] === 'documento') {
            // Con letras = pasaporte/DNI (válido). Solo números fuera de rango = probable error.
            $soloNumeros = ctype_digit(preg_replace('/[\s.,\-]/', '', $run['num']));
            return $soloNumeros
                ? ['num' => '', 'dv' => '', 'aviso' => "RUN \"{$crudo}\" fuera de rango; se dejó vacío."]
                : ['num' => $run['num'], 'dv' => '', 'aviso' => null];
        }

        $aviso = ($run['dvFicha'] !== '' && $run['dvFicha'] !== $run['dv'])
            ? "el DV de la ficha ({$run['dvFicha']}) no coincide con el calculado ({$run['dv']}) para el RUN {$run['num']}."
            : null;

        return ['num' => $run['num'], 'dv' => $run['dv'], 'aviso' => $aviso];
    }

    /**
     * Lector único de RUN, sin importar cómo venga escrito en la ficha.
     *
     *   22776574 · 22776574.0 · "22.776.574" · "22,776,574" · "22 776 574"
     *   "22776574-7" · "22.776.574-7" · "22.776.574 - k" · "227765747" (9 dígitos, DV pegado)
     *
     * Devuelve:
     *   tipo    'run' | 'documento' | 'vacio'
     *   num     RUN formateado "22.776.574" (o el documento tal cual si no es RUN)
     *   dv      DV calculado (el que se guarda)
     *   dvFicha DV escrito en la ficha (para comparar), '' si no venía
     */
    private function parsearRun(string $crudo, string $dvAparte = ''): array
    {
        $crudo = trim($crudo);
        if ($crudo === '') {
            return ['tipo' => 'vacio', 'num' => '', 'dv' => '', 'dvFicha' => ''];
        }

        $texto = strtoupper(str_replace(['‐', '‑', '–', '—'], '-', $crudo));

        // Letras que no sean una K final → pasaporte / DNI / IPE con letras
        if (preg_match('/[A-JL-Z]/', $texto) || preg_match('/K(?!\s*$)/', $texto)) {
            return ['tipo' => 'documento', 'num' => $crudo, 'dv' => '', 'dvFicha' => ''];
        }

        $dvFicha = '';
        if (preg_match('/^(.*\d)\s*-\s*([0-9K])$/', $texto, $m)) {
            // DV separado por guion
            $cuerpo  = $m[1];
            $dvFicha = $m[2];
        } elseif (preg_match('/^(.*\d)\s*K$/', $texto, $m)) {
            // DV "K" pegado al final
            $cuerpo  = $m[1];
            $dvFicha = 'K';
        } else {
            $cuerpo = $texto;
        }

        // Quitar ".0" de número de Excel y separadores de miles (punto, coma, espacio o guion: "9-706836")
        if (preg_match('/^\d+[.,]0+$/', $cuerpo)) {
            $cuerpo = preg_replace('/[.,]0+$/', '', $cuerpo);
        }
        $digitos = preg_replace('/[\s.,\-]/', '', $cuerpo);

        if (!ctype_digit($digitos)) {
            return ['tipo' => 'documento', 'num' => $crudo, 'dv' => '', 'dvFicha' => ''];
        }

        // 9 dígitos sin guion: el último es el DV pegado (si cuadra con el cálculo)
        if ($dvFicha === '' && strlen($digitos) === 9) {
            $posibleNum = substr($digitos, 0, 8);
            if ($this->runEnRango($posibleNum) && $this->calcularDV($posibleNum) === substr($digitos, -1)) {
                $dvFicha = substr($digitos, -1);
                $digitos = $posibleNum;
            }
        }

        $digitos = ltrim($digitos, '0');
        if ($digitos === '' || !$this->runEnRango($digitos)) {
            return ['tipo' => 'documento', 'num' => $crudo, 'dv' => '', 'dvFicha' => ''];
        }

        if ($dvFicha === '') {
            $dvFicha = strtoupper(trim($dvAparte));
            $dvFicha = preg_match('/^[0-9K]$/', $dvFicha) ? $dvFicha : '';
        }

        return [
            'tipo'    => 'run',
            'num'     => $this->formatearRun($digitos),
            'dv'      => $this->calcularDV($digitos),
            'dvFicha' => $dvFicha,
        ];
    }

    private function buscarRegionComuna(string $comunaCruda): array
    {
        if ($comunaCruda === '') return ['', ''];
        $buscada = $this->norm($comunaCruda);
        foreach ($this->regiones as $r) {
            foreach ($r['comunas'] ?? [] as $comuna) {
                if ($this->norm($comuna) === $buscada) return [$r['region'], $comuna];
            }
        }
        $this->aviso('ciudad', "La comuna \"{$comunaCruda}\" no se encontró en el listado: elige región y comuna a mano.");
        return ['', ''];
    }

    /* ================================================================
       RUN
    ================================================================ */

    private function runEnRango(string $num): bool
    {
        $n = (int) $num;
        return $n >= 1000000 && $n <= 109000000;
    }

    private function formatearRun(string $num): string
    {
        return number_format((int) $num, 0, ',', '.');
    }

    private function calcularDV(string $num): string
    {
        $suma = 0; $mult = 2;
        for ($i = strlen($num) - 1; $i >= 0; $i--) {
            $suma += (int) $num[$i] * $mult;
            $mult = $mult < 7 ? $mult + 1 : 2;
        }
        $r = 11 - ($suma % 11);
        return $r === 11 ? '0' : ($r === 10 ? 'K' : (string) $r);
    }

    /* ================================================================
       LECTURA DE CELDAS
    ================================================================ */

    /** Texto limpio de una celda (números enteros sin ".0") */
    private function txt(string $celda): string
    {
        $cell = $this->hoja->getCell($celda);
        $v = $cell->isFormula() ? $cell->getOldCalculatedValue() : $cell->getValue();
        if ($v === null) return '';
        if (is_float($v) && floor($v) === $v) $v = (string) (int) $v;
        return trim(preg_replace('/\s+/u', ' ', (string) $v));
    }

    private function entero(string $celda): ?int
    {
        $d = preg_replace('/\D/', '', $this->txt($celda));
        return $d === '' ? null : (int) $d;
    }

    /** "Si"/"No" → "1"/"0"; vacío → null */
    private function siNo(string $celda): ?string
    {
        $n = $this->norm($this->txt($celda));
        if ($n === '') return null;
        return in_array($n, ['si', 's', '1', 'x'], true) ? '1' : '0';
    }

    /** Fecha desde número de serie de Excel o texto dd/mm/aaaa → "Y-m-d" */
    private function fecha(string $celda): ?string
    {
        $cell = $this->hoja->getCell($celda);
        $v = $cell->getValue();
        if ($v === null || $v === '') return null;

        if (is_numeric($v)) {
            return ExcelDate::excelToDateTimeObject((float) $v)->format('Y-m-d');
        }
        foreach (['d/m/Y', 'd-m-Y', 'Y-m-d', 'd.m.Y'] as $fmt) {
            $dt = DateTime::createFromFormat('!' . $fmt, trim((string) $v));
            if ($dt) return $dt->format('Y-m-d');
        }
        return null;
    }

    /** "9-57129292", 957129292, "+56957129292" → "57129292" (8 dígitos, como guarda el sistema) */
    private function celular(string $crudo): string
    {
        $d = preg_replace('/\D/', '', $crudo);
        return strlen($d) >= 8 ? substr($d, -8) : $d;
    }

    /* ================================================================
       UTILIDADES DE TEXTO
    ================================================================ */

    /** minúsculas, sin tildes, sin "°/ro/do/to…" tras números, espacios simples */
    private function norm(?string $s): string
    {
        $s = mb_strtolower(trim((string) $s), 'UTF-8');
        $s = strtr($s, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n', '°' => ' ', 'º' => ' ']);
        $s = preg_replace('/(\d)\s*(ro|do|er|to|mo|vo|no)\b/', '$1', $s);
        $s = preg_replace('/[()]/', ' ', $s);
        return trim(preg_replace('/\s+/', ' ', $s));
    }

    /** Busca $valor dentro de $opciones ignorando mayúsculas, tildes y espacios */
    private function elegir(string $valor, array $opciones): ?string
    {
        $v = str_replace(' ', '', $this->norm($valor));
        if ($v === '') return null;
        foreach ($opciones as $op) {
            if (str_replace(' ', '', $this->norm($op)) === $v) return $op;
        }
        return null;
    }

    private function titulo(string $s): string
    {
        return $s === '' ? '' : mb_convert_case(mb_strtolower($s, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    }

    private function aviso(string $campo, string $mensaje): void
    {
        $this->avisos[] = ['campo' => $campo, 'mensaje' => $mensaje];
    }
}
