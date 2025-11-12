<?php
require_once __DIR__ . '/../models/Nota.php';
require_once __DIR__ . '/../models/Asignaturas.php';
require_once __DIR__ . '/../models/Matricula.php';

class NotasController
{
    private $model;

    public function __construct()
    {
        $this->model = new Nota();
    }

    // Listado general
    public function index()
    {
        $notas = $this->model->getAll();
        require __DIR__ . '/../views/notas/index.php';
    }

    // ✅ Formulario ingreso masivo (solo asignaturas del curso actual)
    public function createGroup($curso_id, $anio_id)
    {
        // 🔹 Obtener alumnos matriculados en el curso y año
        $alumnos = $this->model->getByCursoYAnio($curso_id, $anio_id);

        // 🔹 Obtener las asignaturas correspondientes al curso actual
        require_once __DIR__ . '/../models/CursoAsignatura.php';
        $cursoAsignaturaModel = new CursoAsignatura();
        $asignaturas = $cursoAsignaturaModel->getAsignaturasPorCurso($curso_id);

        // 🔹 Detectar semestre actual automáticamente
        $mesActual = intval(date('n'));
        $semestreActual = ($mesActual >= 3 && $mesActual <= 7) ? 1 : 2;

        // 🔹 Enviar datos a la vista
        require __DIR__ . '/../views/notas/createGroup.php';
    }


    public function storeGroup($curso_id, $anio_id, $data)
    {
        // 🧩 1️⃣ Detectar semestre actual o recibido
        $semestre = intval($data['semestre'] ?? (($mes = date('n')) >= 3 && $mes <= 7 ? 1 : 2));

        // 🧩 2️⃣ Registrar depuración (NO DETIENE)
        // Esto crea un archivo log en /app/storage/debug_notas.log
        $logFile = __DIR__ . '/../storage/debug_notas.log';
        if (!is_dir(dirname($logFile)))
            mkdir(dirname($logFile), 0777, true);

        $logData = [
            'fecha' => date('Y-m-d H:i:s'),
            'curso_id' => $curso_id,
            'anio_id' => $anio_id,
            'semestre' => $semestre,
            'asignatura_id' => $data['asignatura_id'] ?? 'NO SET',
            'notas_recibidas' => $data['notas'] ?? [],
        ];
        file_put_contents($logFile, print_r($logData, true) . "\n--------------------\n", FILE_APPEND);

        // 🧩 3️⃣ Intentar guardar las notas
        try {
            $this->model->createMultiple(
                $curso_id,
                $anio_id,
                $data['asignatura_id'],
                $data['fecha'],
                $data['notas'],
                $semestre
            );
        } catch (Exception $e) {
            // Guardamos errores si algo falla al guardar
            file_put_contents($logFile, "❌ Error: " . $e->getMessage() . "\n", FILE_APPEND);
        }

        // 🧩 4️⃣ Redirigir normalmente al perfil académico
        $primerAlumno = array_key_first($data['notas']);
        if ($primerAlumno) {
            $matricula_id = $data['notas'][$primerAlumno]['matricula_id'] ?? null;
            if ($matricula_id) {
                header("Location: index.php?action=notas_index&matricula_id={$matricula_id}&semestre={$semestre}");
                exit;
            }
        }

        header("Location: index.php?action=perfil_curso&id=$curso_id&anio_id=$anio_id&semestre=$semestre");
        exit;
    }

    // Perfil académico
    public function indexProfile($matricula_id)
    {
        $semestre = intval($_GET['semestre'] ?? 1);
        $notas = $this->model->getByMatriculaAndSemestre($matricula_id, $semestre);
        require __DIR__ . '/../views/notas/indexProfile.php';
    }

    // Editar nota
    public function edit($id)
    {
        $nota = $this->model->getById($id);

        $asignaturasModel = new Asignaturas();
        $asignaturas = $asignaturasModel->getAll();

        require __DIR__ . '/../views/notas/edit.php';
    }


    // Listado de notas por alumno (vista individual)
    // ✅ Listado de notas por alumno (vista individual)
    public function indexByAlumno($matricula_id)
    {
        require_once __DIR__ . '/../models/Matricula.php';
        require_once __DIR__ . '/../models/CursoAsignatura.php';
        require_once __DIR__ . '/../models/Alumno.php';
        require_once __DIR__ . '/../models/Anio.php';
        require_once __DIR__ . '/../models/Cursos.php';

        $matriculaModel = new Matricula();
        $cursoAsignaturaModel = new CursoAsignatura();
        $alumnoModel = new Alumno();
        $anioModel = new Anio();
        $cursoModel = new Cursos();

        $matricula = $matriculaModel->getById($matricula_id);
        if (!$matricula) {
            echo "<h2 class='text-center text-red-400 mt-10'>No se encontró la matrícula.</h2>";
            exit;
        }

        // 🔹 Datos relacionados
        $alumno = $alumnoModel->getById($matricula['alumno_id']);
        $curso = $cursoModel->getById($matricula['curso_id']);
        $anio = $anioModel->getById($matricula['anio_id']);
        $asignaturas = $cursoAsignaturaModel->getAsignaturasPorCurso($curso['id']);

        // 🔹 Determinar semestre actual y seleccionado
        $mesActual = intval(date('n'));
        $semestreActual = ($mesActual >= 3 && $mesActual <= 7) ? 1 : 2;
        $semestreSeleccionado = intval($_GET['semestre'] ?? $semestreActual);

        // 🔹 Obtener notas del alumno filtradas por semestre
        $notas = $this->model->getByMatriculaAndSemestre($matricula_id, $semestreSeleccionado);

        // 🔹 Cargar vista de notas
        require __DIR__ . '/../views/notas/index.php';
    }

    // Actualizar
    public function update($id, $data)
{
    $val = floatval($data['nota']);
    if ($val < 1.0 || $val > 7.0) {
        echo "<script>alert('La nota debe estar entre 1.0 y 7.0'); window.history.back();</script>";
        exit;
    }

    $data['semestre'] = intval($data['semestre'] ?? 1);
    $this->model->update($id, $data);

    // 🔹 Redirección mejorada: volver al perfil académico del alumno
    $matricula_id = $data['matricula_id'] ?? null;
    $semestre = $data['semestre'] ?? 1;

    if ($matricula_id) {
        header("Location: index.php?action=perfil_academico&id={$matricula_id}&semestre={$semestre}");
        exit;
    }

    // Si no hay matrícula, fallback al listado de notas
    header("Location: index.php?action=notas");
    exit;
}

    // Eliminar
    public function delete($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=notas");
        exit;
    }
}
