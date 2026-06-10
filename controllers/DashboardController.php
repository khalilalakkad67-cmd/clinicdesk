<?php

require_once __DIR__ . "/../core/Auth.php";
require_once __DIR__ . "/../core/Database.php";

class DashboardController
{
    public function index()
    {
        Auth::requireRole("admin", "doctor", "patient");

        $user = Auth::currentUser();
        $db = Database::getInstance();

        if ($user["role"] === "admin") {

            $totalUsers = $db->query("SELECT COUNT(*) AS total FROM users")
                             ->fetch_assoc()["total"];

            $totalDoctors = $db->query("SELECT COUNT(*) AS total FROM doctors")
                               ->fetch_assoc()["total"];

            $totalAppointments = $db->query("SELECT COUNT(*) AS total FROM appointments")
                                    ->fetch_assoc()["total"];

            $totalPrescriptions = $db->query("SELECT COUNT(*) AS total FROM prescriptions")
                                     ->fetch_assoc()["total"];

            require_once __DIR__ . "/../views/dashboard/admin.php";

        } elseif ($user["role"] === "doctor") {

            $doctor = $db->query(
                "SELECT id FROM doctors WHERE user_id = ?",
                "i",
                [$user["id"]]
            )->fetch_assoc();

            $doctorId = $doctor["id"];

            $todayAppointments = $db->query(
                "SELECT COUNT(*) AS total
                 FROM appointments
                 WHERE doctor_id = ?
                 AND appt_date = CURDATE()",
                "i",
                [$doctorId]
            )->fetch_assoc()["total"];

            $monthlyAppointments = $db->query(
                "SELECT COUNT(*) AS total
                 FROM appointments
                 WHERE doctor_id = ?
                 AND MONTH(appt_date) = MONTH(CURDATE())
                 AND YEAR(appt_date) = YEAR(CURDATE())",
                "i",
                [$doctorId]
            )->fetch_assoc()["total"];

            $pendingAppointments = $db->query(
                "SELECT COUNT(*) AS total
                 FROM appointments
                 WHERE doctor_id = ?
                 AND status = 'pending'",
                "i",
                [$doctorId]
            )->fetch_assoc()["total"];

            $completedAppointments = $db->query(
                "SELECT COUNT(*) AS total
                 FROM appointments
                 WHERE doctor_id = ?
                 AND status = 'completed'",
                "i",
                [$doctorId]
            )->fetch_assoc()["total"];

            require_once __DIR__ . "/../views/dashboard/doctor.php";

        } elseif ($user["role"] === "patient") {

            $activeAppointments = $db->query(
                "SELECT COUNT(*) AS total
                 FROM appointments
                 WHERE patient_id = ?
                 AND status IN ('pending', 'confirmed')",
                "i",
                [$user["id"]]
            )->fetch_assoc()["total"];

            $completedAppointments = $db->query(
                "SELECT COUNT(*) AS total
                 FROM appointments
                 WHERE patient_id = ?
                 AND status = 'completed'",
                "i",
                [$user["id"]]
            )->fetch_assoc()["total"];

            $availablePrescriptions = $db->query(
                "SELECT COUNT(*) AS total
                 FROM prescriptions
                 JOIN appointments ON prescriptions.appointment_id = appointments.id
                 WHERE appointments.patient_id = ?",
                "i",
                [$user["id"]]
            )->fetch_assoc()["total"];

            require_once __DIR__ . "/../views/dashboard/patient.php";

        } else {

            redirect(BASE_URL . "index.php?page=error&action=403");
        }
    }
}