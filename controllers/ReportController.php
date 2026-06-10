<?php

require_once __DIR__ . "/../core/Auth.php";
require_once __DIR__ . "/../core/Database.php";

class ReportController
{
    public function index()
    {
        Auth::requireRole("admin");

        $db = Database::getInstance();

        $doctors = $db->query(
            "SELECT doctors.id, users.name
             FROM doctors
             JOIN users ON doctors.user_id = users.id
             ORDER BY users.name ASC"
        );

        $startDate = $_GET["start_date"] ?? "";
        $endDate = $_GET["end_date"] ?? "";
        $doctorId = $_GET["doctor_id"] ?? "";
        $status = $_GET["status"] ?? "";

        $reports = null;

        if (!empty($startDate) && !empty($endDate)) {

            $conditions = [
                "appointments.appt_date BETWEEN ? AND ?"
            ];

            $types = "ss";
            $params = [$startDate, $endDate];

            if (!empty($doctorId)) {
                $conditions[] = "appointments.doctor_id = ?";
                $types .= "i";
                $params[] = (int) $doctorId;
            }

            if (!empty($status)) {
                $conditions[] = "appointments.status = ?";
                $types .= "s";
                $params[] = $status;
            }

            $where = implode(" AND ", $conditions);

            $sql = "SELECT
                        appointments.*,
                        p.name AS patient_name,
                        duser.name AS doctor_name,
                        specializations.name AS specialization_name
                    FROM appointments
                    JOIN users p ON appointments.patient_id = p.id
                    JOIN doctors d ON appointments.doctor_id = d.id
                    JOIN users duser ON d.user_id = duser.id
                    JOIN specializations ON d.specialization_id = specializations.id
                    WHERE $where
                    ORDER BY appointments.appt_date DESC,
                             appointments.appt_time DESC";

            $reports = $db->query($sql, $types, $params);
        }

        require_once __DIR__ . "/../views/reports/index.php";
    }

    public function exportCsv()
    {
        Auth::requireRole("admin");

        $db = Database::getInstance();

        $startDate = $_GET["start_date"] ?? "";
        $endDate = $_GET["end_date"] ?? "";
        $doctorId = $_GET["doctor_id"] ?? "";
        $status = $_GET["status"] ?? "";

        if (empty($startDate) || empty($endDate)) {
            redirect(BASE_URL . "index.php?page=reports&action=index");
        }

        $conditions = [
            "appointments.appt_date BETWEEN ? AND ?"
        ];

        $types = "ss";
        $params = [$startDate, $endDate];

        if (!empty($doctorId)) {
            $conditions[] = "appointments.doctor_id = ?";
            $types .= "i";
            $params[] = (int) $doctorId;
        }

        if (!empty($status)) {
            $conditions[] = "appointments.status = ?";
            $types .= "s";
            $params[] = $status;
        }

        $where = implode(" AND ", $conditions);

        $sql = "SELECT
                    p.name AS patient_name,
                    duser.name AS doctor_name,
                    specializations.name AS specialization_name,
                    appointments.appt_date,
                    appointments.appt_time,
                    appointments.status,
                    appointments.reason
                FROM appointments
                JOIN users p ON appointments.patient_id = p.id
                JOIN doctors d ON appointments.doctor_id = d.id
                JOIN users duser ON d.user_id = duser.id
                JOIN specializations ON d.specialization_id = specializations.id
                WHERE $where
                ORDER BY appointments.appt_date DESC,
                         appointments.appt_time DESC";

        $reports = $db->query($sql, $types, $params);

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=appointments_report.csv");

        $output = fopen("php://output", "w");

        fputcsv($output, [
            "Patient Name",
            "Doctor Name",
            "Specialization",
            "Date",
            "Time",
            "Status",
            "Reason"
        ]);

        while ($row = $reports->fetch_assoc()) {
            fputcsv($output, [
                $row["patient_name"],
                $row["doctor_name"],
                $row["specialization_name"],
                $row["appt_date"],
                $row["appt_time"],
                $row["status"],
                $row["reason"]
            ]);
        }

        fclose($output);
        exit;
    }
}