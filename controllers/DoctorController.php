<?php

require_once __DIR__ . "/../models/DoctorModel.php";
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/SpecializationModel.php";
require_once __DIR__ . "/../core/Auth.php";
require_once __DIR__ . "/../core/CSRF.php";

class DoctorController
{
    private $doctorModel;
    private $userModel;
    private $specializationModel;

    public function __construct()
    {
        Auth::requireRole("admin");

        $this->doctorModel = new DoctorModel();
        $this->userModel = new UserModel();
        $this->specializationModel = new SpecializationModel();
    }

    public function index()
    {
        $doctors = $this->doctorModel->getAll();

        require_once __DIR__ . "/../views/doctors/index.php";
    }

    public function createForm()
    {
        $specializations = $this->specializationModel->getAll();

        require_once __DIR__ . "/../views/doctors/create.php";
    }

    public function store()
    {
        if (!CSRF::validateToken($_POST["csrf_token"] ?? "")) {
            setFlash("danger", "Invalid request.");
            redirect(BASE_URL . "index.php?page=doctors&action=createForm");
        }

        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $phone = trim($_POST["phone"] ?? "");
        $specializationId = (int) ($_POST["specialization_id"] ?? 0);
        $bio = trim($_POST["bio"] ?? "");
        $consultationFee = (float) ($_POST["consultation_fee"] ?? 0);
        $availableDaysArray = $_POST["available_days"] ?? [];

        if (empty($name) || empty($email) || empty($password) || $specializationId <= 0 || empty($availableDaysArray)) {
            setFlash("danger", "All required fields must be filled.");
            redirect(BASE_URL . "index.php?page=doctors&action=createForm");
        }

        if ($this->userModel->findByEmail($email)) {
            setFlash("danger", "Email already exists.");
            redirect(BASE_URL . "index.php?page=doctors&action=createForm");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $newUserId = $this->userModel->createUser(
            $name,
            $email,
            $hashedPassword,
            "doctor",
            $phone
        );

        $availableDays = implode(",", $availableDaysArray);

        $this->doctorModel->createDoctor(
            $newUserId,
            $specializationId,
            $bio,
            $consultationFee,
            $availableDays
        );

        setFlash("success", "Doctor created successfully.");

        redirect(BASE_URL . "index.php?page=doctors&action=index");
    }

    public function editForm()
    {
        $id = (int) ($_GET["id"] ?? 0);

        $doctor = $this->doctorModel->findById($id);

        if (!$doctor) {
            setFlash("danger", "Doctor not found.");
            redirect(BASE_URL . "index.php?page=doctors&action=index");
        }

        $specializations = $this->specializationModel->getAll();
        $availableDays = explode(",", $doctor["available_days"]);

        require_once __DIR__ . "/../views/doctors/edit.php";
    }

    public function update()
    {
        if (!CSRF::validateToken($_POST["csrf_token"] ?? "")) {
            setFlash("danger", "Invalid request.");
            redirect(BASE_URL . "index.php?page=doctors&action=index");
        }

        $doctorId = (int) ($_POST["id"] ?? 0);
        $specializationId = (int) ($_POST["specialization_id"] ?? 0);
        $bio = trim($_POST["bio"] ?? "");
        $consultationFee = (float) ($_POST["consultation_fee"] ?? 0);
        $availableDaysArray = $_POST["available_days"] ?? [];

        if ($doctorId <= 0 || $specializationId <= 0 || empty($availableDaysArray)) {
            setFlash("danger", "All required fields must be filled.");
            redirect(BASE_URL . "index.php?page=doctors&action=index");
        }

        $availableDays = implode(",", $availableDaysArray);

        $this->doctorModel->updateDoctor(
            $doctorId,
            $specializationId,
            $bio,
            $consultationFee,
            $availableDays
        );

        setFlash("success", "Doctor updated successfully.");

        redirect(BASE_URL . "index.php?page=doctors&action=index");
    }

    public function delete()
    {
        if (!CSRF::validateToken($_POST["csrf_token"] ?? "")) {
            setFlash("danger", "Invalid request.");
            redirect(BASE_URL . "index.php?page=doctors&action=index");
        }

        $doctorId = (int) ($_POST["id"] ?? 0);

        if ($doctorId > 0) {
            $this->doctorModel->deleteDoctor($doctorId);
            setFlash("success", "Doctor deleted successfully.");
        }

        redirect(BASE_URL . "index.php?page=doctors&action=index");
    }
}