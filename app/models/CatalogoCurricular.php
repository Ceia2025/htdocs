<?php

class CatalogoCurricular
{
    private const NIVELES = ['3° Nivel Básico', '1° Nivel Medio', '2° Nivel Medio', 'Nivel Medio'];

    private static ?array $cache = null;

    private static function cargar(): array
    {
        if (self::$cache === null) {
            self::$cache = ['asignaturas' => [], 'instrumentos' => [], 'tipos' => []];

            $rutas = [
                __DIR__ . '/../utils/planificacion_completa.json',
                __DIR__ . '/../data/datos_OA_asignaturas.json',
            ];

            foreach ($rutas as $ruta) {
                if (!is_file($ruta)) {
                    continue;
                }
                $json = json_decode((string) file_get_contents($ruta), true);
                if (is_array($json) && !empty($json['asignaturas']) && is_array($json['asignaturas'])) {
                    self::$cache = $json;
                    break;
                }
            }
        }
        return self::$cache;
    }

    public static function asignaturas(): array
    {
        return self::cargar()['asignaturas'] ?? [];
    }

    public static function instrumentos(): array
    {
        return self::cargar()['instrumentos'] ?? [];
    }

    public static function tipos(): array
    {
        return self::cargar()['tipos'] ?? [];
    }

    public static function porId(string $id): ?array
    {
        foreach (self::asignaturas() as $a) {
            if (($a['id'] ?? null) === $id) {
                return $a;
            }
        }
        return null;
    }

    public static function existe(string $id): bool
    {
        return self::porId($id) !== null;
    }

    /**
     * Devuelve [nivel => [[id, asignatura, categoria, total_oa], ...]]
     * respetando el orden de niveles de la aplicación.
     */
    public static function agrupadasPorNivel(): array
    {
        $grupos = [];
        foreach (self::NIVELES as $nivel) {
            $grupos[$nivel] = [];
        }
        foreach (self::asignaturas() as $a) {
            $nivel = $a['nivel'] ?? 'Otros';
            $grupos[$nivel][] = [
                'id'         => $a['id'],
                'asignatura' => $a['asignatura'],
                'categoria'  => $a['categoria'] ?? 'General',
                'total_oa'   => count($a['oas'] ?? []),
            ];
        }
        return array_filter($grupos);
    }

    /** Bases Curriculares EPJA 2024: ámbitos de habilidades del siglo XXI y actitudes. */
    public static function bases(): array
    {
        return [
            'ambitos' => [
                'Maneras de pensar'           => ['Creatividad e innovación', 'Pensamiento crítico', 'Metacognición'],
                'Maneras de trabajar'         => ['Comunicación', 'Colaboración'],
                'Herramientas para trabajar'  => ['Alfabetización digital', 'Uso de la información'],
                'Maneras de vivir en el mundo' => ['Ciudadanía local y global', 'Vida y carrera', 'Responsabilidad personal y social'],
            ],
            'actitudes' => [
                'Maneras de pensar'           => ['Perseverancia', 'Apertura a otras perspectivas', 'Conciencia del error como oportunidad', 'Flexibilidad', 'Autorreflexión', 'Aprendizaje permanente'],
                'Maneras de trabajar'         => ['Colaboración', 'Responsabilidad', 'Liderazgo', 'Empatía', 'Autonomía', 'Honestidad'],
                'Herramientas para trabajar'  => ['Aprovechamiento de recursos', 'Interés por la tecnología', 'Gestión responsable del tiempo', 'Ética digital'],
                'Maneras de vivir en el mundo' => ['Perseverancia en metas', 'Participación razonada', 'Búsqueda del bien común', 'Honestidad y responsabilidad'],
            ],
        ];
    }

    /** Asignatura del nivel anterior (para el módulo 1 de nivelación). */
    public static function nivelAnterior(array $asignatura): ?array
    {
        $prefijos = ['1° Nivel Medio' => '3NB_', '2° Nivel Medio' => '1NM_'];
        $prefijo = $prefijos[$asignatura['nivel'] ?? ''] ?? null;
        $pos = strpos((string) ($asignatura['id'] ?? ''), '_');
        if ($prefijo === null || $pos === false) {
            return null;
        }
        $nombre = substr($asignatura['id'], $pos + 1);
        $alias = ['EDUCACION CIUDADANA' => 'HISTORIA Y GEOGRAFIA'];

        return self::porId($prefijo . $nombre)
            ?? (isset($alias[$nombre]) ? self::porId($prefijo . $alias[$nombre]) : null);
    }

    /** Fuente de OA y conocimientos de un módulo: el 0 usa el nivel anterior. */
    public static function fuenteModulo(string $catalogoId, int $numero): ?array
    {
        $actual = self::porId($catalogoId);
        if ($actual === null) {
            return null;
        }
        return $numero === 0 ? (self::nivelAnterior($actual) ?? $actual) : $actual;
    }
}