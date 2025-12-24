<?php
require_once __DIR__ . '/../models/Role.php';

class RolesController
{
    private $roleModel;

    public function __construct()
    {
        $this->roleModel = new Role();
    }

    public function index()
    {
        $roles = $this->roleModel->getAll();
        require __DIR__ . '/../views/roles/index.php';
    }

    public function create($data)
    {
        if (!empty($data['nombre'])) {
            $this->roleModel->create($data['nombre']);
        }
        header("Location: index.php?action=roles");
        exit;
    }

    public function edit($id)
    {
        $role = $this->roleModel->getById($id);
        require __DIR__ . '/../views/roles/edit.php';
    }

    public function update($id, $data)
    {
        if (!empty($data['nombre'])) {
            $this->roleModel->update($id, $data['nombre']);
        }
        header("Location: index.php?action=roles");
        exit;
    }

    public function delete($id)
    {
        $this->roleModel->delete($id);
        header("Location: index.php?action=roles");
        exit;
    }
}
