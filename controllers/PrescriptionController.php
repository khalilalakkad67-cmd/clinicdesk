<?php

require_once __DIR__ . "/../models/PrescriptionModel.php";
require_once __DIR__ . "/../models/AppointmentModel.php";
require_once __DIR__ . "/../core/Auth.php";
require_once __DIR__ . "/../core/CSRF.php";
require_once __DIR__ . "/../models/DoctorModel.php";

class PrescriptionController
{
    private $prescriptionModel;
    private $appointmentModel;

    public function __construct()
    {
        $this->prescriptionModel = new PrescriptionModel();
        $this->appointmentModel = new AppointmentModel();
    }

    public function createForm()
    {
        Auth::requireRole("doctor", "admin");

        $appointmentId = (int) ($_GET["appointment_id"] ?? 0);

        $appointment = $this->appointmentModel->findById($appointmentId);

        if (!$appointment) {
            redirect(BASE_URL . "index.php?page=appointments&action=index");
        }

        $existingPrescription = $this->prescriptionModel->findByAppointmentId($appointmentId);

        if ($existingPrescription) {
            setFlash("danger", "Prescription already exists for this appointment.");
            redirect(BASE_URL . "index.php?page=appointments&action=index");
        }

        require_once __DIR__ . "/../views/prescriptions/create.php";
    }

    public function store()
{
    Auth::requireRole("doctor", "admin");

    if (!CSRF::validateToken($_POST["csrf_token"] ?? "")) {
        setFlash("danger", "Invalid request.");
        redirect(BASE_URL . "index.php?page=appointments&action=index");
    }

    $appointmentId = (int) ($_POST["appointment_id"] ?? 0);
    $diagnosis = trim($_POST["diagnosis"] ?? "");
    $medications = trim($_POST["medications"] ?? "");
    $notes = trim($_POST["notes"] ?? "");
    $filePath = null;

    if (empty($diagnosis) || empty($medications)) {
        setFlash("danger", "Diagnosis and medications are required.");
        redirect(BASE_URL . "index.php?page=appointments&action=index");
    }

    $existingPrescription = $this->prescriptionModel->findByAppointmentId($appointmentId);

    if ($existingPrescription) {
        setFlash("danger", "Prescription already exists for this appointment.");
        redirect(BASE_URL . "index.php?page=appointments&action=index");
    }

    if (isset($_FILES["prescription_file"]) && $_FILES["prescription_file"]["error"] !== UPLOAD_ERR_NO_FILE) {

        if ($_FILES["prescription_file"]["error"] !== UPLOAD_ERR_OK) {
            setFlash("danger", "File upload failed.");
            redirect(BASE_URL . "index.php?page=appointments&action=index");
        }

        if ($_FILES["prescription_file"]["size"] > MAX_PDF_SIZE) {
            setFlash("danger", "PDF file is too large.");
            redirect(BASE_URL . "index.php?page=appointments&action=index");
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $_FILES["prescription_file"]["tmp_name"]);
        finfo_close($finfo);

        if ($mimeType !== "application/pdf") {
            setFlash("danger", "Only PDF files are allowed.");
            redirect(BASE_URL . "index.php?page=appointments&action=index");
        }

        $uploadDir = __DIR__ . "/../public/uploads/prescriptions/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = "prescription_" . $appointmentId . "_" . time() . ".pdf";
        $destination = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES["prescription_file"]["tmp_name"], $destination)) {
            setFlash("danger", "Could not save uploaded file.");
            redirect(BASE_URL . "index.php?page=appointments&action=index");
        }

        $filePath = $fileName;
    }

    $this->prescriptionModel->createPrescription(
        $appointmentId,
        $diagnosis,
        $medications,
        $notes,
        $filePath
    );

    setFlash("success", "Prescription created successfully.");

    redirect(BASE_URL . "index.php?page=appointments&action=index");
}

    public function viewMine()
    {
        Auth::requireRole("patient");

        $user = Auth::currentUser();

        $prescriptions = $this->prescriptionModel->getByPatient($user["id"]);

        require_once __DIR__ . "/../views/prescriptions/index.php";
    }
    public function download()
{
    Auth::requireRole("admin", "doctor", "patient");

    $appointmentId = (int) ($_GET["appointment_id"] ?? 0);

    $prescription = $this->prescriptionModel->findByAppointmentId($appointmentId);
    $appointment = $this->appointmentModel->findById($appointmentId);

    if (!$prescription || !$appointment || empty($prescription["file_path"])) {
        setFlash("danger", "Prescription file not found.");
        redirect(BASE_URL . "index.php?page=prescriptions&action=viewMine");
    }

    $user = Auth::currentUser();

    if ($user["role"] === "patient" && (int)$appointment["patient_id"] !== (int)$user["id"]) {
        redirect(BASE_URL . "index.php?page=dashboard");
    }

    if ($user["role"] === "doctor") {
        $doctorModel = new DoctorModel();
        $doctor = $doctorModel->findByUserId($user["id"]);

        if (!$doctor || (int)$appointment["doctor_id"] !== (int)$doctor["id"]) {
            redirect(BASE_URL . "index.php?page=dashboard");
        }
    }

    $file = __DIR__ . "/../public/uploads/prescriptions/" . $prescription["file_path"];

    if (!file_exists($file)) {
        setFlash("danger", "File does not exist on server.");
        redirect(BASE_URL . "index.php?page=prescriptions&action=viewMine");
    }

    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=prescription.pdf");
    header("Content-Length: " . filesize($file));

    readfile($file);
    exit;
}
}