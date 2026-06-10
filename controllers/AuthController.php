<?php

require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../core/Auth.php";
require_once __DIR__ . "/../core/CSRF.php";

class AuthController
{
    public function loginForm()
    {
        require_once __DIR__ . "/../views/auth/login.php";
    }

    public function login()
    {
        if (!CSRF::validateToken($_POST["csrf_token"] ?? "")) {
            setFlash("danger", "Invalid request.");
            redirect(BASE_URL . "index.php?page=auth&action=loginForm");
        }

        $email = filter_var($_POST["email"] ?? "", FILTER_SANITIZE_EMAIL);
        $password = $_POST["password"] ?? "";

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, trim($user["password"]))) {
            setFlash("danger", "Invalid credentials.");
            redirect(BASE_URL . "index.php?page=auth&action=loginForm");
        }

        if ((int) $user["is_active"] !== 1) {
            setFlash("danger", "Account suspended. Contact admin.");
            redirect(BASE_URL . "index.php?page=auth&action=loginForm");
        }

        Auth::login($user);

        redirect(BASE_URL . "index.php?page=dashboard");
    }

    public function logout()
    {
        if (!CSRF::validateToken($_POST["csrf_token"] ?? "")) {
            redirect(BASE_URL . "index.php?page=dashboard");
        }

        Auth::logout();
    }
}