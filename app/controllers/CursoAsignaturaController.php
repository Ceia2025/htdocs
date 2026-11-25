<?php
require_once __DIR__ . '/../models/CursoAsignatura.php';
require_once __DIR__ . '/../models/Cursos.php';        // Para listar cursos
require_once __DIR__ . '/../models/Asignaturas.php';   // Para listar asignaturas
require_once __DIR__ . '/../models/Anio.php';          // Para listar años

class CursoAsignaturaController
{
    private $model;

    public function __construct()
    {
        $this->model = new CursoAsignatura();
    }

    /* ================================================================
       LISTADO (malla por año) — crear, editar y copiar se hacen en modales
    ================================================================ */
    public function index()
    {
        $anios       = (new Anio())->getAll();
        $cursos      = (new Cursos())->getAll();
        $asignaturas = (new Asignaturas())->getAll();

        // Año seleccionado: el de la URL, o el año académico en curso
        $anio_id  = isset($_GET['anio_id']) && $_GET['anio_id'] !== '' ? (int) $_GET['anio_id'] : $this->model->anioActualId();
        $curso_id = isset($_GET['curso_id']) && $_GET['curso_id'] !== '' ? (int) $_GET['curso_id'] : null;

        $cursoAsignaturas = $anio_id ? $this->model->getMalla($anio_id, $curso_id) : [];
        $totalesPorAnio   = $this->model->contarPorAnio();

        // Para el modal "Vincular": qué asignaturas ya tiene cada curso en este año
        $mallaCompleta = $anio_id ? ($curso_id ? $this->model->getMalla($anio_id) : $cursoAsignaturas) : [];
        $asignadasPorCurso = [];
        foreach ($mallaCompleta as $fila) {
            $asignadasPorCurso[(int) $fila['curso_id']][] = (int) $fila['asignatura_id'];
        }

        $anioSeleccionado = null;
        foreach ($anios as $a) {
            if ((int) $a['id'] === (int) $anio_id) {
                $anioSeleccionado = $a;
                break;
            }
        }

        $flashOk    = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        require __DIR__ . '/../views/curso_asignaturas/index.php';
    }

    // Las páginas separadas de crear/editar se reemplazaron por modales en el listado
    public function create()
    {
        $this->volver($_GET['anio_id'] ?? null, $_GET['curso_id'] ?? null);
    }

    public function edit($id)
    {
        $fila = $this->model->getById($id);
        $this->volver($fila['anio_id'] ?? null, $fila['curso_id'] ?? null);
    }

    /* ================================================================
       GUARDAR (una o varias asignaturas a la vez)
    ================================================================ */
    public function store($data)
    {
        $anio_id  = (int) ($data['anio_id'] ?? 0);
        $curso_id = (int) ($data['curso_id'] ?? 0);
        $ids      = $data['asignatura_id'] ?? [];
        $ids      = is_array($ids) ? $ids : [$ids];
        $ids      = array_values(array_unique(array_filter(array_map('intval', $ids))));

        if (!$anio_id || !$curso_id || !$ids) {
            $_SESSION['flash_error'] = 'Selecciona el año, el curso y al menos una asignatura.';
            $this->volver($anio_id, $curso_id);
        }

        $agregadas = 0;
        $repetidas = 0;
        foreach ($ids as $asignatura_id) {
            if ($this->model->create($curso_id, $asignatura_id, $anio_id)) {
                $agregadas++;
            } else {
                $repetidas++;
            }
        }

        $msg = "✅ Se agregaron {$agregadas} asignatura(s) a la malla.";
        if ($repetidas) {
            $msg .= " {$repetidas} ya estaban y se omitieron.";
        }
        $_SESSION['flash_success'] = $msg;
        $this->volver($anio_id, $curso_id);
    }

    /* ================================================================
       ACTUALIZAR (cambiar la asignatura de una fila)
    ================================================================ */
    public function update($id, $data)
    {
        $fila = $this->model->getById($id);
        if (!$fila) {
            $_SESSION['flash_error'] = 'La asignatura de la malla ya no existe.';
            $this->volver();
        }

        $nuevaAsignatura = (int) ($data['asignatura_id'] ?? 0);
        if (!$nuevaAsignatura || $nuevaAsignatura === (int) $fila['asignatura_id']) {
            $this->volver($fila['anio_id'], $fila['curso_id']);
        }

        $uso = $this->model->usoDe((int) $id);
        if ($uso['notas'] > 0 || $uso['planificaciones'] > 0) {
            $_SESSION['flash_error'] = "No se puede cambiar: esta asignatura ya tiene {$uso['notas']} nota(s) y {$uso['planificaciones']} planificación(es) en este curso y año. Agrega la nueva asignatura en vez de reemplazarla.";
            $this->volver($fila['anio_id'], $fila['curso_id']);
        }

        if ($this->model->existe((int) $fila['curso_id'], $nuevaAsignatura, (int) $fila['anio_id'], (int) $id)) {
            $_SESSION['flash_error'] = 'Esa asignatura ya está en la malla de este curso para este año.';
            $this->volver($fila['anio_id'], $fila['curso_id']);
        }

        $this->model->update($id, $nuevaAsignatura);
        $_SESSION['flash_success'] = '✅ Asignatura actualizada.';
        $this->volver($fila['anio_id'], $fila['curso_id']);
    }

    /* ================================================================
       ELIMINAR (quitar de la malla, protegido si tiene notas o planificaciones)
    ================================================================ */
    public function destroy($id)
    {
        $fila = $this->model->getById($id);
        if (!$fila) {
            $this->volver();
        }

        $uso = $this->model->usoDe((int) $id);
        if ($uso['notas'] > 0 || $uso['planificaciones'] > 0) {
            $_SESSION['flash_error'] = "No se puede quitar: esta asignatura ya tiene {$uso['notas']} nota(s) y {$uso['planificaciones']} planificación(es) en este curso y año.";
            $this->volver($fila['anio_id'], $fila['curso_id']);
        }

        $this->model->delete($id);
        $_SESSION['flash_success'] = '✅ Asignatura quitada de la malla.';
        $this->volver($fila['anio_id'], $fila['curso_id']);
    }

    /* ================================================================
       COPIAR MALLA DE UN AÑO A OTRO
    ================================================================ */
    public function copiar($data)
    {
        $origen   = (int) ($data['anio_origen'] ?? 0);
        $destino  = (int) ($data['anio_destino'] ?? 0);
        $curso_id = !empty($data['curso_id']) ? (int) $data['curso_id'] : null;

        if (!$origen || !$destino || $origen === $destino) {
            $_SESSION['flash_error'] = 'Elige un año de origen y un año de destino distintos.';
            $this->volver($destino ?: $origen, $curso_id);
        }

        try {
            $agregadas = $this->model->copiarMalla($origen, $destino, $curso_id);
            $_SESSION['flash_success'] = $agregadas > 0
                ? "✅ Malla copiada: se agregaron {$agregadas} asignatura(s). Ahora ajusta lo que cambia este año."
                : 'La malla de destino ya tenía todas esas asignaturas: no se agregó nada.';
        } catch (Throwable $e) {
            error_log('copiar malla: ' . $e->getMessage());
            $_SESSION['flash_error'] = 'No se pudo copiar la malla.';
        }

        $this->volver($destino, $curso_id);
    }

    /* ================================================================
       UTILIDAD
    ================================================================ */
    private function volver($anio_id = null, $curso_id = null)
    {
        $url = 'index.php?action=curso_asignaturas';
        if ($anio_id) {
            $url .= '&anio_id=' . (int) $anio_id;
        }
        if ($curso_id) {
            $url .= '&curso_id=' . (int) $curso_id;
        }
        header("Location: $url");
        exit;
    }
}
