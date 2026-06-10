<?php

require_once __DIR__ . "/../core/BaseModel.php";

class SpecializationModel extends BaseModel
{
    public function getAll()
    {
        $sql = "SELECT *
                FROM specializations
                ORDER BY name ASC";

        return $this->execute($sql);
    }

    public function findByName($name)
    {
        $sql = "SELECT *
                FROM specializations
                WHERE name = ?";

        $result = $this->execute($sql, "s", [$name]);

        return $result->fetch_assoc();
    }

    public function createSpecialization($name)
    {
        $sql = "INSERT INTO specializations (name)
                VALUES (?)";

        return $this->execute($sql, "s", [$name]);
    }

    public function deleteSpecialization($id)
    {
        $sql = "DELETE FROM specializations
                WHERE id = ?";

        return $this->execute($sql, "i", [$id]);
    }

    public function isSafeToDelete($id)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM doctors
                WHERE specialization_id = ?";

        $result = $this->execute($sql, "i", [$id]);
        $row = $result->fetch_assoc();

        return (int) $row["total"] === 0;
    }
}