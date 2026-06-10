<?php

require_once __DIR__ . "/../models/SpecializationModel.php";
require_once __DIR__ . "/../core/Auth.php";
require_once __DIR__ . "/../core/CSRF.php";

class SpecializationController
{
    private $specializationModel;

    public function __construct()
    {
        Auth::requireRole("admin");
        $this->specializationModel = new SpecializationModel();
    }

    public function index()
    {
        $specializations = $this->specializationModel->getAll();

        require_once __DIR__ . "/../views/specializations/index.php";
    }

    public function store()
    {
        if (!CSRF::validateToken($_POST["csrf_token"] ?? "")) {
            setFlash("danger", "Invalid request.");
            redirect(BASE_URL . "index.php?page=specializations&action=index");
        }

        $name = trim($_POST["name"] ?? "");

        if (empty($name)) {
            setFlash("danger", "Specialization name is required.");
            redirect(BASE_URL . "index.php?page=specializations&action=index");
        }

        if ($this->specializationModel->findByName($name)) {
            setFlash("danger", "Specialization already exists.");
            redirect(BASE_URL . "index.php?page=specializations&action=index");
        }

        $this->specializationModel->createSpecialization($name);

        setFlash("success", "Specialization created successfully.");

        redirect(BASE_URL . "index.php?page=specializations&action=index");
    }

    public function delete()
    {
        if (!CSRF::validateToken($_POST["csrf_token"] ?? "")) {
            setFlash("danger", "Invalid request.");
            redirect(BASE_URL . "index.php?page=specializations&action=index");
        }

        $id = (int) ($_POST["id"] ?? 0);

        if ($id > 0) {
            if (!$this->specializationModel->isSafeToDelete($id)) {
                setFlash("danger", "Cannot delete specialization because it is used by doctors.");
                redirect(BASE_URL . "index.php?page=specializations&action=index");
            }

            $this->specializationModel->deleteSpecialization($id);
            setFlash("success", "Specialization deleted successfully.");
        }

        redirect(BASE_URL . "index.php?page=specializations&action=index");
    }
}