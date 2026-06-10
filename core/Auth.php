<?php

require_once __DIR__ . "/helpers.php";

class Auth
{
    public static function login($user)
    {
        session_regenerate_id(true);

        $_SESSION["user"] = [
            "id" => $user["id"],
            "name" => $user["name"],
            "role" => $user["role"]
        ];
    }

    public static function logout()
    {
        session_unset();
        session_destroy();

        redirect(BASE_URL . "index.php?page=auth&action=login");
    }

    public static function check()
    {
        return isset($_SESSION["user"]);
    }

    public static function currentUser()
    {
        return $_SESSION["user"] ?? null;
    }

    public static function role()
    {
        return $_SESSION["user"]["role"] ?? "";
    }

    public static function requireRole(...$roles)
    {
        if (!self::check()) {
            redirect(BASE_URL . "index.php?page=auth&action=login");
        }

        if (!in_array(self::role(), $roles)) {
            redirect(BASE_URL . "index.php?page=error&action=403");
        }
    }
}