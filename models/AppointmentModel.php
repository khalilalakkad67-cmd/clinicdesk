<?php

require_once __DIR__ . "/../core/BaseModel.php";

class AppointmentModel extends BaseModel
{
    public function hasConflict($doctorId, $date, $time)
    {
        $sql = "SELECT id
                FROM appointments
                WHERE doctor_id = ?
                AND appt_date = ?
                AND appt_time = ?";

        $result = $this->execute($sql, "iss", [$doctorId, $date, $time]);

        return $result->num_rows > 0;
    }

    public function createAppointment($patientId, $doctorId, $date, $time, $reason)
    {
        $sql = "INSERT INTO appointments
                (patient_id, doctor_id, appt_date, appt_time, reason)
                VALUES (?, ?, ?, ?, ?)";

        return $this->execute($sql, "iisss", [$patientId, $doctorId, $date, $time, $reason]);
    }

    private function buildFilterConditions($filters, &$types, &$params)
    {
        $conditions = [];

        if (!empty($filters["status"])) {
            $conditions[] = "appointments.status = ?";
            $types .= "s";
            $params[] = $filters["status"];
        }

        if (!empty($filters["start_date"])) {
            $conditions[] = "appointments.appt_date >= ?";
            $types .= "s";
            $params[] = $filters["start_date"];
        }

        if (!empty($filters["end_date"])) {
            $conditions[] = "appointments.appt_date <= ?";
            $types .= "s";
            $params[] = $filters["end_date"];
        }

        return $conditions;
    }

    public function countAll($filters = [])
    {
        $types = "";
        $params = [];

        $conditions = $this->buildFilterConditions($filters, $types, $params);

        $where = "";

        if (!empty($conditions)) {
            $where = "WHERE " . implode(" AND ", $conditions);
        }

        $sql = "SELECT COUNT(*) AS total
                FROM appointments
                $where";

        $result = $this->execute($sql, $types, $params);

        return (int) $result->fetch_assoc()["total"];
    }

    public function countByPatient($patientId, $filters = [])
    {
        $types = "i";
        $params = [$patientId];

        $conditions = ["appointments.patient_id = ?"];
        $conditions = array_merge($conditions, $this->buildFilterConditions($filters, $types, $params));

        $where = "WHERE " . implode(" AND ", $conditions);

        $sql = "SELECT COUNT(*) AS total
                FROM appointments
                $where";

        $result = $this->execute($sql, $types, $params);

        return (int) $result->fetch_assoc()["total"];
    }

    public function countByDoctor($doctorId, $filters = [])
    {
        $types = "i";
        $params = [$doctorId];

        $conditions = ["appointments.doctor_id = ?"];
        $conditions = array_merge($conditions, $this->buildFilterConditions($filters, $types, $params));

        $where = "WHERE " . implode(" AND ", $conditions);

        $sql = "SELECT COUNT(*) AS total
                FROM appointments
                $where";

        $result = $this->execute($sql, $types, $params);

        return (int) $result->fetch_assoc()["total"];
    }

    public function getAll($filters = [], $limit = null, $offset = null)
    {
        $types = "";
        $params = [];

        $conditions = $this->buildFilterConditions($filters, $types, $params);

        $where = "";

        if (!empty($conditions)) {
            $where = "WHERE " . implode(" AND ", $conditions);
        }

        $pagination = "";

        if ($limit !== null && $offset !== null) {
            $pagination = "LIMIT ? OFFSET ?";
            $types .= "ii";
            $params[] = $limit;
            $params[] = $offset;
        }

        $sql = "SELECT
                    appointments.*,
                    p.name AS patient_name,
                    duser.name AS doctor_name,
                    prescriptions.id AS prescription_id
                FROM appointments
                JOIN users p ON appointments.patient_id = p.id
                JOIN doctors d ON appointments.doctor_id = d.id
                JOIN users duser ON d.user_id = duser.id
                LEFT JOIN prescriptions ON prescriptions.appointment_id = appointments.id
                $where
                ORDER BY appointments.appt_date DESC,
                         appointments.appt_time DESC
                $pagination";

        return $this->execute($sql, $types, $params);
    }

    public function getByPatient($patientId, $filters = [], $limit = null, $offset = null)
    {
        $types = "i";
        $params = [$patientId];

        $conditions = ["appointments.patient_id = ?"];
        $conditions = array_merge($conditions, $this->buildFilterConditions($filters, $types, $params));

        $where = "WHERE " . implode(" AND ", $conditions);

        $pagination = "";

        if ($limit !== null && $offset !== null) {
            $pagination = "LIMIT ? OFFSET ?";
            $types .= "ii";
            $params[] = $limit;
            $params[] = $offset;
        }

        $sql = "SELECT
                    appointments.*,
                    p.name AS patient_name,
                    duser.name AS doctor_name,
                    prescriptions.id AS prescription_id
                FROM appointments
                JOIN users p ON appointments.patient_id = p.id
                JOIN doctors d ON appointments.doctor_id = d.id
                JOIN users duser ON d.user_id = duser.id
                LEFT JOIN prescriptions ON prescriptions.appointment_id = appointments.id
                $where
                ORDER BY appointments.appt_date DESC,
                         appointments.appt_time DESC
                $pagination";

        return $this->execute($sql, $types, $params);
    }

    public function getByDoctor($doctorId, $filters = [], $limit = null, $offset = null)
    {
        $types = "i";
        $params = [$doctorId];

        $conditions = ["appointments.doctor_id = ?"];
        $conditions = array_merge($conditions, $this->buildFilterConditions($filters, $types, $params));

        $where = "WHERE " . implode(" AND ", $conditions);

        $pagination = "";

        if ($limit !== null && $offset !== null) {
            $pagination = "LIMIT ? OFFSET ?";
            $types .= "ii";
            $params[] = $limit;
            $params[] = $offset;
        }

        $sql = "SELECT
                    appointments.*,
                    p.name AS patient_name,
                    duser.name AS doctor_name,
                    prescriptions.id AS prescription_id
                FROM appointments
                JOIN users p ON appointments.patient_id = p.id
                JOIN doctors d ON appointments.doctor_id = d.id
                JOIN users duser ON d.user_id = duser.id
                LEFT JOIN prescriptions ON prescriptions.appointment_id = appointments.id
                $where
                ORDER BY appointments.appt_date DESC,
                         appointments.appt_time DESC
                $pagination";

        return $this->execute($sql, $types, $params);
    }

    public function updateStatus($appointmentId, $status)
    {
        $sql = "UPDATE appointments
                SET status = ?
                WHERE id = ?";

        return $this->execute($sql, "si", [$status, $appointmentId]);
    }

    public function findById($id)
    {
        $sql = "SELECT
                    appointments.*,
                    p.name AS patient_name,
                    duser.name AS doctor_name,
                    prescriptions.id AS prescription_id
                FROM appointments
                JOIN users p ON appointments.patient_id = p.id
                JOIN doctors d ON appointments.doctor_id = d.id
                JOIN users duser ON d.user_id = duser.id
                LEFT JOIN prescriptions ON prescriptions.appointment_id = appointments.id
                WHERE appointments.id = ?";

        $result = $this->execute($sql, "i", [$id]);

        return $result->fetch_assoc();
    }
}