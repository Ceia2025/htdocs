<?php
require_once __DIR__ . '/../models/Alumno.php';
require_once __DIR__ . '/../models/AntecedenteEscolar.php';


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

    public function storeStepperTest($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
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

        $alumnoId = $alumnoModel->create($data);

        // 🔹 Guardar contactos de emergencia
        if (!empty($data['emergencias'])) {
            $emergenciaModel = new AlumEmergencia();
            foreach ($data['emergencias'] as $e) {
                $emergenciaModel->create(
                    $alumnoId,
                    $e['nombre_contacto'] ?? null,
                    $e['telefono'] ?? null,
                    $e['direccion'] ?? null,
                    $e['relacion'] ?? null
                );
            }
        }

        // 🔹 Guardar antecedentes familiares
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

        // 🔹 Guardar antecedente escolar
        if (!empty($data['antecedente_escolar'])) {
            $escolarModel = new AntecedenteEscolar();
            $escolar = $data['antecedente_escolar'];
            $escolar['alumno_id'] = $alumnoId;
            $escolarModel->create($escolar);
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

        // Cargar modelos adicionales
        require_once __DIR__ . '/../models/AlumEmergencia.php';
        require_once __DIR__ . '/../models/AntecedenteFamiliar.php';
        require_once __DIR__ . '/../models/AntecedenteEscolar.php';

        $emergenciaModel = new AlumEmergencia();
        $familiarModel = new AntecedenteFamiliar();
        $escolarModel = new AntecedenteEscolar();

        // Obtener datos relacionados
        $contactos = $emergenciaModel->findByAlumno($id);
        $antecedentes = $familiarModel->findByAlumno($id);
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

            // Convertir deleted_at vacío a NULL
            if (isset($data['deleted_at']) && $data['deleted_at'] === '') {
                $data['deleted_at'] = null;
            }

            // Llamada al modelo para actualizar
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
