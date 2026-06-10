<?php

require_once __DIR__ . "/../models/AppointmentModel.php";
require_once __DIR__ . "/../models/DoctorModel.php";
require_once __DIR__ . "/../core/Auth.php";
require_once __DIR__ . "/../core/CSRF.php";
require_once __DIR__ . "/../core/Paginator.php";

class AppointmentController
{
    private $appointmentModel;
    private $doctorModel;

    public function __construct()
    {
        Auth::requireRole("admin", "doctor", "patient");

        $this->appointmentModel = new AppointmentModel();
        $this->doctorModel = new DoctorModel();
    }

    public function index()
    {
        $user = Auth::currentUser();

        $page = (int) ($_GET["p"] ?? 1);

        $filters = [
            "status" => $_GET["status"] ?? "",
            "start_date" => $_GET["start_date"] ?? "",
            "end_date" => $_GET["end_date"] ?? ""
        ];

        if ($user["role"] === "admin") {

            $totalAppointments = $this->appointmentModel->countAll($filters);

            $paginator = new Paginator(
                $totalAppointments,
                ITEMS_PER_PAGE,
                $page
            );

            $appointments = $this->appointmentModel->getAll(
                $filters,
                ITEMS_PER_PAGE,
                $paginator->offset()
            );

        } elseif ($user["role"] === "patient") {

            $totalAppointments = $this->appointmentModel->countByPatient(
                $user["id"],
                $filters
            );

            $paginator = new Paginator(
                $totalAppointments,
                ITEMS_PER_PAGE,
                $page
            );

            $appointments = $this->appointmentModel->getByPatient(
                $user["id"],
                $filters,
                ITEMS_PER_PAGE,
                $paginator->offset()
            );

        } else {

            $doctor = $this->doctorModel->findByUserId($user["id"]);

            $totalAppointments = $this->appointmentModel->countByDoctor(
                $doctor["id"],
                $filters
            );

            $paginator = new Paginator(
                $totalAppointments,
                ITEMS_PER_PAGE,
                $page
            );

            $appointments = $this->appointmentModel->getByDoctor(
                $doctor["id"],
                $filters,
                ITEMS_PER_PAGE,
                $paginator->offset()
            );
        }

        require_once __DIR__ . "/../views/appointments/index.php";
    }

    public function createForm()
    {
        Auth::requireRole("patient");

        $doctors = $this->doctorModel->getAll();

        require_once __DIR__ . "/../views/appointments/create.php";
    }

    public function store()
    {
        Auth::requireRole("patient");

        if (!CSRF::validateToken($_POST["csrf_token"] ?? "")) {
            setFlash("danger", "Invalid request.");
            redirect(BASE_URL . "index.php?page=appointments&action=createForm");
        }

        $user = Auth::currentUser();

        $doctorId = (int) ($_POST["doctor_id"] ?? 0);
        $date = $_POST["appt_date"] ?? "";
        $time = $_POST["appt_time"] ?? "";
        $reason = trim($_POST["reason"] ?? "");

        if ($doctorId <= 0 || empty($date) || empty($time)) {
            setFlash("danger", "Please fill all required fields.");
            redirect(BASE_URL . "index.php?page=appointments&action=createForm");
        }

        if ($date < date("Y-m-d")) {
            setFlash("danger", "Appointment date cannot be in the past.");
            redirect(BASE_URL . "index.php?page=appointments&action=createForm");
        }

        if ($this->appointmentModel->hasConflict($doctorId, $date, $time)) {
            setFlash("danger", "This slot is already booked.");
            redirect(BASE_URL . "index.php?page=appointments&action=createForm");
        }

        $this->appointmentModel->createAppointment(
            $user["id"],
            $doctorId,
            $date,
            $time,
            $reason
        );

        setFlash("success", "Appointment booked successfully.");

        redirect(BASE_URL . "index.php?page=appointments&action=index");
    }

    public function updateStatus()
    {
        Auth::requireRole("admin", "doctor");

        if (!CSRF::validateToken($_POST["csrf_token"] ?? "")) {
            setFlash("danger", "Invalid request.");
            redirect(BASE_URL . "index.php?page=appointments&action=index");
        }

        $appointmentId = (int) ($_POST["id"] ?? 0);
        $status = $_POST["status"] ?? "";

        $allowedStatuses = ["pending", "confirmed", "completed", "cancelled"];

        if ($appointmentId > 0 && in_array($status, $allowedStatuses)) {
            $this->appointmentModel->updateStatus($appointmentId, $status);
            setFlash("success", "Appointment status updated.");
        }

        redirect(BASE_URL . "index.php?page=appointments&action=index");
    }
}