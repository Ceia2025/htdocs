<?php
// 1) MODELOS BÁSICOS
require_once __DIR__ . '/../models/Alumno.php';
require_once __DIR__ . '/../models/AntecedenteEscolar.php';
require_once __DIR__ . '/../models/AlumEmergencia.php';

// 2) DOMPDF SIN COMPOSER GLOBAL
// Ajusta esta ruta si tu carpeta es distinta, pero por lo que mostraste debería ser esta:
require_once __DIR__ . '/../libs/dompdf/vendor/autoload.php';

// 3) USE de las clases de Dompdf (SIEMPRE aquí arriba, fuera de la clase)
use Dompdf\Dompdf;
use Dompdf\Options;



class AlumnosController
{
    private $alumnoModel;

    public function __construct()
    {
        $this->alumnoModel = new Alumno();
    }

    //Funcion mayor de edad
    private function calcularMayorEdad($fechaNacimiento)
    {
        $fechaNac = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNac)->y;
        return $edad >= 18 ? "Si" : "No";
    }

    // Mostrar todos los alumnos
    public function index()
    {
        require_once __DIR__ . "/../models/Alumno.php";
        $alumnoModel = new Alumno();
        $alumnos = $alumnoModel->getAll();

        include __DIR__ . "/../views/alumnos/index.php";
    }

    // Formulario de creación
    public function create()
    {
        require __DIR__ . '/../views/alumnos/create.php';
    }

    // Guardar alumno nuevo
    public function store($data)
    {
        if (!empty($data['run']) && !empty($data['nombre']) && !empty($data['apepat'])) {

            // Calcular mayor de edad
            if (!empty($data['fechanac'])) {
                $data['mayoredad'] = $this->calcularMayorEdad($data['fechanac']);
            } else {
                $data['mayoredad'] = "No"; // valor por defecto si no hay fecha
            }

            $this->alumnoModel->create($data);
        }
        header("Location: index.php?action=alumnos");
        exit;
    }

    public function storeStepper($data)
    {
        $alumnoModel = new Alumno();

        // ✅ Comprobar si el RUN ya existe antes de insertar
        if ($alumnoModel->existsByRun($data['run'])) {
            echo "<script>
            alert('⚠️ El RUN \"{$data['run']}\" ya está registrado. Por favor, verifícalo.');
            window.history.back();
        </script>";
            exit;
        }

        // 👉 Crear alumno principal
        $alumnoId = $alumnoModel->create($data);

        // 👉 Guardar contactos de emergencia
        if (!empty($data['emergencias'])) {
            $emergenciaModel = new AlumEmergencia();
            foreach ($data['emergencias'] as $e) {
                // Ignorar tarjetas completamente vacías
                if (empty($e['nombre_contacto']) && empty($e['telefono']) && empty($e['celular'])) {
                    continue;
                }
                $emergenciaModel->createFromArray($alumnoId, $e);
            }
        }

        // 👉 Guardar antecedentes familiares
        if (!empty($data['padre']) || !empty($data['madre'])) {
            $familiarModel = new AntecedenteFamiliar();
            $familiarModel->create(
                $alumnoId,
                $data['padre'] ?? null,
                $data['nivel_ciclo_p'] ?? null,
                $data['madre'] ?? null,
                $data['nivel_ciclo_m'] ?? null
            );
        }

        // 👉 Guardar antecedente escolar
        if (!empty($data['antecedente_escolar'])) {
            $escolarModel = new AntecedenteEscolar();
            $escolar = $data['antecedente_escolar'];
            $escolar['alumno_id'] = $alumnoId;
            $escolarModel->create($escolar);
        }

        // 👉 Guardar matrícula
        if (!empty($data['curso_id']) && !empty($data['anio_id'])) {
            $matriculaModel = new Matricula();
            $matriculaModel->create([
                'alumno_id' => $alumnoId,
                'curso_id' => $data['curso_id'],
                'anio_id' => $data['anio_id'],
                'fecha_matricula' => date('Y-m-d'),
            ]);
        }

        header("Location: index.php?action=alumnos");
        exit;
    }




    //Redireccion a la vista
    public function createStepper()
    {
        require_once __DIR__ . '/../views/alumnos/form_stepper.php';
    }




    //Perfil Alumno
    public function profile($id)
    {
        $alumno = $this->alumnoModel->getWithAntecedente($id);
        if (!$alumno) {
            echo "Alumno no encontrado";
            exit;
        }

        require_once __DIR__ . '/../models/AlumEmergencia.php';
        require_once __DIR__ . '/../models/AntecedenteFamiliar.php';
        require_once __DIR__ . '/../models/AntecedenteEscolar.php';
        require_once __DIR__ . '/../models/Matricula.php'; // 👈 agregar esto

        $emergenciaModel = new AlumEmergencia();
        $familiarModel = new AntecedenteFamiliar();
        $escolarModel = new AntecedenteEscolar();
        $matriculaModel = new Matricula(); // 👈 agregar esto

        $contactosGrupos = $emergenciaModel->findByAlumnoAgrupado($id);
        $contactos = $emergenciaModel->findByAlumno($id);
        $antecedentes = $familiarModel->findByAlumno($id);
        $matriculaActiva = $matriculaModel->getActivaByAlumno($id); // 👈 agregar esto

        require __DIR__ . '/../views/alumnos/perfil.php';
    }


    public function search()
    {
        try {
            $term = $_GET['term'] ?? '';
            $alumno = new Alumno();
            $results = $alumno->search($term);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($results);
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    // Formulario de edición
    public function edit($id)
    {
        $alumno = $this->alumnoModel->getById($id);
        require __DIR__ . '/../views/alumnos/edit.php';
    }

    // Actualizar alumno
    public function update($id, $data)
    {
        if (!empty($id) && !empty($data['run']) && !empty($data['nombre']) && !empty($data['apepat'])) {

            // Calcular mayor de edad si se envía fecha de nacimiento
            if (!empty($data['fechanac'])) {
                $data['mayoredad'] = $this->calcularMayorEdad($data['fechanac']);
            }

            // 🔥 Mantener el estado deleted_at actual (retirado/activo)
            $alumnoActual = $this->alumnoModel->getById($id);
            $data['deleted_at'] = $alumnoActual['deleted_at'];

            // Actualizar información normal
            $this->alumnoModel->update($id, $data);
        }

        header("Location: index.php?action=alumnos");
        exit;
    }


    //verificar si ya existe el rut del alumno
    public function checkRunExists()
    {
        if (!isset($_GET['run'])) {
            echo json_encode(['error' => 'Falta parámetro RUN']);
            exit;
        }

        $run = trim($_GET['run']);
        $alumnoModel = new Alumno();

        // Nueva función en el modelo
        $exists = $alumnoModel->runExists($run);

        header('Content-Type: application/json');
        echo json_encode(['exists' => $exists]);
        exit;
    }

    // 🔹 Marcar alumno como retirado
    public function retire($id)
    {
        if (!empty($id)) {
            error_log("🧠 Retirando alumno con ID: $id");
            $fechaActual = date('Y-m-d H:i:s');
            $this->alumnoModel->markAsRetired($id, $fechaActual);

            // Eliminar automáticamente todas sus matrículas
            require_once __DIR__ . '/../models/Matricula.php';
            $matriculaModel = new Matricula();
            //$matriculaModel->deleteByAlumno($id);

            error_log("🗑️ Matrículas eliminadas para alumno ID: $id");
        } else {
            error_log("⚠️ ID vacío en retire()");
        }
        header("Location: index.php?action=alumno_edit&id=$id");
        exit;
    }

    // 🔹 Reintegrar alumno
    public function restore($id)
    {
        if (!empty($id)) {
            $this->alumnoModel->restore($id);
        }
        header("Location: index.php?action=alumno_edit&id=$id");
        exit;
    }

    //Buscar al alumno en el momento en que se generan nuevas matriculas
    public function searchAjax()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $term = $_GET['term'] ?? '';

            if (strlen($term) < 2) {
                echo json_encode([]);
                exit;
            }

            $alumno = new Alumno();
            $results = $alumno->searchForAutocomplete($term);

            echo json_encode($results);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }


    public function pdf($alumno_id)
    {
        if (empty($alumno_id)) {
            die("ID de alumno no válido");
        }

        // Los modelos ya están cargados arriba, usamos directamente las clases
        $alumnoModel = new Alumno();
        $antecedenteModel = new AntecedenteEscolar();
        $emergenciaModel = new AlumEmergencia();

        // Obtener datos
        $alumno = $alumnoModel->getById($alumno_id);
        $escolar = $antecedenteModel->getByAlumnoId($alumno_id);
        $contactos = $emergenciaModel->findByAlumno($alumno_id);

        $edadAl30Junio = $this->calcularEdadAl30Junio($alumno['fechanac']);
        if (!$alumno) {
            die("Alumno no encontrado");
        }

        // Cargar HTML del template del PDF
        ob_start();
        require __DIR__ . '/../views/alumnos/pdfPlaceHolder.php';
        $html = ob_get_clean();

        // Configurar Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();

        // Descargar PDF
        $dompdf->stream("Alumno_{$alumno['run']}.pdf", ["Attachment" => true]);
    }



    private function calcularEdadAl30Junio($fechaNacimiento)
    {
        if (!$fechaNacimiento)
            return null;

        $fechaNac = new DateTime($fechaNacimiento);

        // Fecha fija de corte: 30 de junio del año actual
        $anioActual = date('Y');
        $fechaCorte = new DateTime("$anioActual-06-30");

        // Si el alumno nació después del 30 de junio de este año,
        // restamos un año porque todavía no cumple.
        $edad = $fechaCorte->diff($fechaNac)->y;

        return $edad;
    }


    // Vista de alumnos agrupados por año y curso
    public function listadoPorAnio()
    {
        $alumnoModel = new Alumno();
        $anios = $alumnoModel->getAniosConMatriculas();

        $anio_id = $_GET['anio_id'] ?? null;
        $anioSeleccionado = null;
        $alumnosPorCurso = [];
        $totalActivos = 0;
        $totalRetirados = 0;

        if ($anio_id) {
            $alumnos = $alumnoModel->getByAnioConCurso($anio_id);

            // Buscar descripción del año seleccionado
            foreach ($anios as $a) {
                if ($a['id'] == $anio_id) {
                    $anioSeleccionado = $a;
                    break;
                }
            }

            // Agrupar por curso
            foreach ($alumnos as $alumno) {
                $curso = $alumno['curso_nombre'];
                $alumnosPorCurso[$curso][] = $alumno;

                if (!empty($alumno['deleted_at'])) {
                    $totalRetirados++;
                } else {
                    $totalActivos++;
                }
            }
        }

        include __DIR__ . "/../views/alumnos/listado_por_anio.php";
    }

    public function searchAjaxMatricula()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $term = $_GET['term'] ?? '';

            if (strlen($term) < 2) {
                echo json_encode([]);
                exit;
            }

            $alumno = new Alumno();
            $results = $alumno->searchForMatricula($term);

            echo json_encode($results);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
    // Eliminar alumno
    public function delete($id)
    {
        if (!empty($id)) {
            $this->alumnoModel->delete($id);
        }
        header("Location: index.php?action=alumnos");
        exit;
    }
}
