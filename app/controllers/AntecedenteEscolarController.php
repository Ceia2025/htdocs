<?php
require_once __DIR__ . '/../models/AntecedenteEscolar.php';

class AntecedenteEscolarController
{
    private $model;

    public function __construct()
    {
        $this->model = new AntecedenteEscolar();
    }

    // Listar
    public function index()
    {
        $antecedentes = $this->model->getAll();
        require __DIR__ . '/../views/antecedente_escolar/index.php';
    }

    // Mostrar formulario crear
    public function create()
    {
        require __DIR__ . '/../views/antecedente_escolar/create.php';
    }

    // Guardar nuevo
    public function store($data)
    {
        $this->model->create($data);
        header("Location: index.php?action=antecedente_escolar");
        exit;
    }

    // Editar
    public function edit($id)
    {
        $antecedente = $this->model->getById($id);
        require __DIR__ . '/../views/antecedente_escolar/edit.php';
    }

    // Editar antecedente escolar desde perfil del alumno
    public function editProfile($alumno_id)
    {
        require_once __DIR__ . '/../models/Alumno.php';
        $alumnoModel = new Alumno();
        $alumno = $alumnoModel->getById($alumno_id);

        if (!$alumno) {
            header("Location: index.php?action=alumnos");
            exit;
        }

        // Buscar antecedente escolar asociado
        $antecedente = $this->model->getByAlumnoId($alumno_id);

        // Si no existe, preparar datos vacíos
        if (!$antecedente) {
            $antecedente = [
                'id' => null,
                'alumno_id' => $alumno_id,
                'procedencia_colegio' => '',
                'comuna' => '',
                'ultimo_curso' => '',
                'ultimo_anio_cursado' => '',
                'cursos_repetidos' => '',
                'pertenece_20' => 0,
                'informe_20' => 0,
                'embarazo' => 0,
                'semanas' => null,
                'info_salud' => '',
                'eva_psico' => '',
                'prob_apren' => '',
                'pie' => '',
                'chile_solidario' => 0,
                'chile_solidario_cual' => '',
                'fonasa' => '',
                'grupo_fonasa' => '',
                'isapre' => '',
                'seguro_salud' => ''
            ];
        }

        $back = "alumno_profile";
        require __DIR__ . '/../views/antecedente_escolar/editProfile.php';
    }

    // Actualizar antecedente escolar desde perfil
    public function updateProfile($data)
    {
        $alumno_id = $data['alumno_id'] ?? null;

        if (!$alumno_id) {
            header("Location: index.php?action=alumnos");
            exit;
        }

        // Verificar si ya existe registro
        $existente = $this->model->getByAlumnoId($alumno_id);

        if ($existente && !empty($existente['id'])) {
            // Actualizar
            $this->model->update($existente['id'], $data);
        } else {
            // Crear nuevo
            $data['alumno_id'] = $alumno_id;
            $this->model->create($data);
        }

        // Redirigir de vuelta al perfil
        header("Location: index.php?action=alumno_profile&id=" . urlencode($alumno_id));
        exit;
    }



    // Actualizar
    public function update($id, $data)
    {
        $this->model->update($id, $data);
        header("Location: index.php?action=antecedente_escolar");
        exit;
    }

    // Eliminar
    public function delete($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=antecedente_escolar");
        exit;
    }
}
