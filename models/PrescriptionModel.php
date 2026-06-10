<?php

require_once __DIR__ . "/../core/BaseModel.php";

class PrescriptionModel extends BaseModel
{
    public function createPrescription(
        $appointmentId,
        $diagnosis,
        $medications,
        $notes = null,
        $filePath = null
    ) {
        $sql = "INSERT INTO prescriptions
                (appointment_id, diagnosis, medications, notes, file_path)
                VALUES (?, ?, ?, ?, ?)";

        return $this->execute(
            $sql,
            "issss",
            [$appointmentId, $diagnosis, $medications, $notes, $filePath]
        );
    }

    public function findByAppointmentId($appointmentId)
    {
        $sql = "SELECT *
                FROM prescriptions
                WHERE appointment_id = ?";

        $result = $this->execute(
            $sql,
            "i",
            [$appointmentId]
        );

        return $result->fetch_assoc();
    }

    public function getByPatient($patientId)
    {
        $sql = "SELECT
                    prescriptions.*,
                    appointments.appt_date,
                    users.name AS doctor_name
                FROM prescriptions
                JOIN appointments
                    ON prescriptions.appointment_id = appointments.id
                JOIN doctors
                    ON appointments.doctor_id = doctors.id
                JOIN users
                    ON doctors.user_id = users.id
                WHERE appointments.patient_id = ?
                ORDER BY prescriptions.created_at DESC";

        return $this->execute(
            $sql,
            "i",
            [$patientId]
        );
    }

    public function deletePrescription($id)
    {
        $sql = "DELETE FROM prescriptions
                WHERE id = ?";

        return $this->execute(
            $sql,
            "i",
            [$id]
        );
    }
}