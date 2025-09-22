<?php
require_once __DIR__ . '/../models/Alumno.php';

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
        $alumnos = $this->alumnoModel->getAll();
        require __DIR__ . '/../views/alumnos/index.php';
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
    //Perfil Alumno
    public function profile($id)
    {
        $alumno = $this->alumnoModel->getById($id);
        if (!$alumno) {
            echo "Alumno no encontrado";
            exit;
        }
        require __DIR__ . '/../views/alumnos/perfil.php';
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
