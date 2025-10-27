<?php
require_once __DIR__ . '/../models/AntecedenteFamiliar.php';

class AntecedenteFamiliarController
{
    private $model;

    public function __construct()
    {
        $this->model = new AntecedenteFamiliar();
    }

    // Listar todos
    public function index()
    {
        $antecedentes = $this->model->getAll();
        require __DIR__ . '/../views/antecedentefamiliar/index.php';
    }

    // Mostrar formulario crear
    public function createForm()
    {
        require __DIR__ . '/../views/antecedentefamiliar/create.php';
    }

    public function editProfile($alumno_id)
    {
        $modelAlumno = new Alumno();
        $alumno = $modelAlumno->getById($alumno_id);
        if (!$alumno) {
            header("Location: index.php?action=alumnos");
            exit;
        }

        $antecedente = $this->model->findByAlumno($alumno_id);
        if (!$antecedente) {
            // Si no existe registro, puedes crear uno vacío para editar
            $antecedente = [
                'id' => null,
                'alumno_id' => $alumno_id,
                'padre' => '',
                'nivel_ciclo_p' => '',
                'madre' => '',
                'nivel_ciclo_m' => ''
            ];
        }

        $back = "alumno_profile";
        require __DIR__ . '/../views/antecedentefamiliar/editProfile.php';
    }


    
    // Actualizar antecedentes familiares desde el perfil
    public function updateProfile($data)
    {
        $alumno_id = $data['alumno_id'] ?? null;
        if (!$alumno_id) {
            header("Location: index.php?action=alumnos");
            exit;
        }

        // Buscar si ya existe
        $antecedenteExistente = $this->model->findByAlumno($alumno_id);

        if ($antecedenteExistente && !empty($antecedenteExistente['id'])) {
            // Actualizar registro existente
            $this->model->update(
                $antecedenteExistente['id'],
                $alumno_id,
                $data['padre'] ?? 'Desconocido',
                $data['nivel_ciclo_p'] ?? null,
                $data['madre'] ?? 'Desconocido',
                $data['nivel_ciclo_m'] ?? null
            );
        } else {
            // Crear nuevo si no existe
            $this->model->create(
                $alumno_id,
                $data['padre'] ?? 'Desconocido',
                $data['nivel_ciclo_p'] ?? null,
                $data['madre'] ?? 'Desconocido',
                $data['nivel_ciclo_m'] ?? null
            );
        }

        // Redirigir de nuevo al perfil del alumno
        header("Location: index.php?action=alumno_profile&id=" . urlencode($alumno_id));
        exit;
    }

    // Guardar nuevo
    public function create($data)
    {
        if (!empty($data['alumno_id'])) {
            $this->model->create(
                $data['alumno_id'],
                $data['padre'] ?? 'Desconocido',
                $data['nivel_ciclo_p'] ?? null,
                $data['madre'] ?? 'Desconocido',
                $data['nivel_ciclo_m'] ?? null
            );
        }
        header("Location: index.php?action=antecedentefamiliar");
        exit;
    }

    // Editar por ID
    public function edit($id)
    {
        $antecedente = $this->model->getById($id);
        require __DIR__ . '/../views/antecedentefamiliar/edit.php';
    }

    // Actualizar
    public function update($id, $data)
    {
        if (!empty($data['alumno_id'])) {
            $this->model->update(
                $id,
                $data['alumno_id'],
                $data['padre'] ?? 'Desconocido',
                $data['nivel_ciclo_p'] ?? null,
                $data['madre'] ?? 'Desconocido',
                $data['nivel_ciclo_m'] ?? null
            );
        }
        header("Location: index.php?action=antecedentefamiliar");
        exit;
    }

    // Eliminar
    public function delete($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=antecedentefamiliar");
        exit;
    }
}
