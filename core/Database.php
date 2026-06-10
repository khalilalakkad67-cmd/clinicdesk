<?php

require_once __DIR__ . "/../config/database.php";

class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            throw new RuntimeException("Database connection failed.");
        }

        $this->conn->set_charset("utf8mb4");
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function query($sql, $types = "", $params = [])
    {
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Database query preparation failed.");
        }

        if (!empty($types) && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new RuntimeException("Database query execution failed.");
        }

        $result = $stmt->get_result();

        if ($result !== false) {
            return $result;
        }

        return true;
    }

    public function lastInsertId()
    {
        return $this->conn->insert_id;
    }
}