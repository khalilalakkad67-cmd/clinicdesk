<?php

require_once __DIR__ . "/../core/BaseModel.php";

class UserModel extends BaseModel
{
    public function createUser($name, $email, $password, $role, $phone = null)
    {
        $sql = "INSERT INTO users
                (name, email, password, role, phone)
                VALUES (?, ?, ?, ?, ?)";

        $this->execute($sql, "sssss", [$name, $email, $password, $role, $phone]);

        return $this->lastInsertId();
    }

    public function findById($id)
    {
        $sql = "SELECT *
                FROM users
                WHERE id = ?";

        $result = $this->execute($sql, "i", [$id]);

        return $result->fetch_assoc();
    }

    public function findByEmail($email)
    {
        $sql = "SELECT *
                FROM users
                WHERE email = ?";

        $result = $this->execute($sql, "s", [$email]);

        return $result->fetch_assoc();
    }

    public function getAllUsers()
    {
        $sql = "SELECT *
                FROM users
                ORDER BY id DESC";

        return $this->execute($sql);
    }

    private function buildFilters($filters, &$types, &$params)
    {
        $conditions = [];

        if (!empty($filters["role"])) {
            $conditions[] = "role = ?";
            $types .= "s";
            $params[] = $filters["role"];
        }

        if (!empty($filters["search"])) {
            $conditions[] = "(name LIKE ? OR email LIKE ?)";
            $types .= "ss";

            $searchValue = "%" . $filters["search"] . "%";

            $params[] = $searchValue;
            $params[] = $searchValue;
        }

        return $conditions;
    }

    public function countFilteredUsers($filters = [])
    {
        $types = "";
        $params = [];

        $conditions = $this->buildFilters($filters, $types, $params);

        $where = "";

        if (!empty($conditions)) {
            $where = "WHERE " . implode(" AND ", $conditions);
        }

        $sql = "SELECT COUNT(*) AS total
                FROM users
                $where";

        $result = $this->execute($sql, $types, $params);

        return (int) $result->fetch_assoc()["total"];
    }

    public function getUsersPaginated($limit, $offset, $filters = [])
    {
        $types = "";
        $params = [];

        $conditions = $this->buildFilters($filters, $types, $params);

        $where = "";

        if (!empty($conditions)) {
            $where = "WHERE " . implode(" AND ", $conditions);
        }

        $types .= "ii";
        $params[] = $limit;
        $params[] = $offset;

        $sql = "SELECT *
                FROM users
                $where
                ORDER BY id DESC
                LIMIT ? OFFSET ?";

        return $this->execute($sql, $types, $params);
    }

    public function updateUser($id, $name, $phone, $isActive)
    {
        $sql = "UPDATE users
                SET name = ?, phone = ?, is_active = ?
                WHERE id = ?";

        return $this->execute(
            $sql,
            "ssii",
            [$name, $phone, $isActive, $id]
        );
    }

    public function updatePassword($id, $hashedPassword)
    {
        $sql = "UPDATE users
                SET password = ?
                WHERE id = ?";

        return $this->execute(
            $sql,
            "si",
            [$hashedPassword, $id]
        );
    }

    public function deleteUser($id)
    {
        $sql = "DELETE FROM users
                WHERE id = ?";

        return $this->execute($sql, "i", [$id]);
    }
}