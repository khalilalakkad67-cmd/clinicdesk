<?php

require_once __DIR__ . "/../core/BaseModel.php";

class DoctorModel extends BaseModel
{
    public function findById($doctorId)
    {
        $sql = "SELECT
                    doctors.*,
                    users.name,
                    users.email,
                    users.phone,
                    specializations.name AS specialization_name
                FROM doctors
                JOIN users ON doctors.user_id = users.id
                JOIN specializations ON doctors.specialization_id = specializations.id
                WHERE doctors.id = ?";

        $result = $this->execute($sql, "i", [$doctorId]);

        return $result->fetch_assoc();
    }

    public function findByUserId($userId)
    {
        $sql = "SELECT
                    doctors.*,
                    users.name,
                    users.email,
                    users.phone,
                    specializations.name AS specialization_name
                FROM doctors
                JOIN users ON doctors.user_id = users.id
                JOIN specializations ON doctors.specialization_id = specializations.id
                WHERE doctors.user_id = ?";

        $result = $this->execute($sql, "i", [$userId]);

        return $result->fetch_assoc();
    }

    public function getAll()
    {
        $sql = "SELECT
                    doctors.*,
                    users.name,
                    users.email,
                    users.phone,
                    specializations.name AS specialization_name
                FROM doctors
                JOIN users ON doctors.user_id = users.id
                JOIN specializations ON doctors.specialization_id = specializations.id
                ORDER BY users.name ASC";

        return $this->execute($sql);
    }

    public function createDoctor($userId, $specializationId, $bio, $consultationFee, $availableDays)
    {
        $sql = "INSERT INTO doctors
                (user_id, specialization_id, bio, consultation_fee, available_days)
                VALUES (?, ?, ?, ?, ?)";

        $this->execute(
            $sql,
            "iisds",
            [$userId, $specializationId, $bio, $consultationFee, $availableDays]
        );

        return $this->lastInsertId();
    }

    public function updateDoctor($doctorId, $specializationId, $bio, $consultationFee, $availableDays)
    {
        $sql = "UPDATE doctors
                SET specialization_id = ?,
                    bio = ?,
                    consultation_fee = ?,
                    available_days = ?
                WHERE id = ?";

        return $this->execute(
            $sql,
            "isdsi",
            [$specializationId, $bio, $consultationFee, $availableDays, $doctorId]
        );
    }

    public function deleteDoctor($doctorId)
    {
        $doctor = $this->findById($doctorId);

        if (!$doctor) {
            return false;
        }

        $sql = "DELETE FROM users
                WHERE id = ?";

        return $this->execute($sql, "i", [$doctor["user_id"]]);
    }

    public function getAvailableDays($doctorId)
    {
        $sql = "SELECT available_days
                FROM doctors
                WHERE id = ?";

        $result = $this->execute($sql, "i", [$doctorId]);
        $doctor = $result->fetch_assoc();

        if (!$doctor) {
            return [];
        }

        return explode(",", $doctor["available_days"]);
    }
}