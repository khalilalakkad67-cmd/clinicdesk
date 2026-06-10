<?php

require_once __DIR__ . "/Database.php";

class BaseModel
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    protected function execute($sql, $types = "", $params = [])
    {
        return $this->db->query($sql, $types, $params);
    }

    protected function lastInsertId()
    {
        return $this->db->lastInsertId();
    }
}