<?php

require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../core/Auth.php";
require_once __DIR__ . "/../core/CSRF.php";
require_once __DIR__ . "/../core/Paginator.php";

class UserController
{
    private $userModel;

    public function __construct()
    {
        Auth::requireRole("admin");
        $this->userModel = new UserModel();
    }

   public function index()
{
    $page = (int) ($_GET["p"] ?? 1);

    $filters = [
        "search" => trim($_GET["search"] ?? ""),
        "role" => $_GET["role"] ?? ""
    ];

    $totalUsers = $this->userModel->countFilteredUsers($filters);

    $paginator = new Paginator(
        $totalUsers,
        ITEMS_PER_PAGE,
        $page
    );

    $users = $this->userModel->getUsersPaginated(
        ITEMS_PER_PAGE,
        $paginator->offset(),
        $filters
    );

    require_once __DIR__ . "/../views/users/index.php";
}

    public function createForm()
    {
        require_once __DIR__ . "/../views/users/create.php";
    }

    public function store()
    {
        if (!CSRF::validateToken($_POST["csrf_token"] ?? "")) {
            setFlash("danger", "Invalid request.");
            redirect(BASE_URL . "index.php?page=users&action=createForm");
        }

        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $role = $_POST["role"] ?? "patient";
        $phone = trim($_POST["phone"] ?? "");

        if (empty($name) || empty($email) || empty($password)) {
            setFlash("danger", "All fields are required.");
            redirect(BASE_URL . "index.php?page=users&action=createForm");
        }

        if ($this->userModel->findByEmail($email)) {
            setFlash("danger", "Email already exists.");
            redirect(BASE_URL . "index.php?page=users&action=createForm");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $this->userModel->createUser(
            $name,
            $email,
            $hashedPassword,
            $role,
            $phone
        );

        setFlash("success", "User created successfully.");

        redirect(BASE_URL . "index.php?page=users&action=index");
    }

    public function editForm()
    {
        $id = (int) ($_GET["id"] ?? 0);
        $user = $this->userModel->findById($id);

        if (!$user) {
            setFlash("danger", "User not found.");
            redirect(BASE_URL . "index.php?page=users&action=index");
        }

        require_once __DIR__ . "/../views/users/edit.php";
    }

    public function update()
    {
        if (!CSRF::validateToken($_POST["csrf_token"] ?? "")) {
            setFlash("danger", "Invalid request.");
            redirect(BASE_URL . "index.php?page=users&action=index");
        }

        $id = (int) ($_POST["id"] ?? 0);
        $name = trim($_POST["name"] ?? "");
        $phone = trim($_POST["phone"] ?? "");
        $isActive = (int) ($_POST["is_active"] ?? 1);
        $newPassword = $_POST["new_password"] ?? "";

        $user = $this->userModel->findById($id);

        if (!$user) {
            setFlash("danger", "User not found.");
            redirect(BASE_URL . "index.php?page=users&action=index");
        }

        if (empty($name)) {
            setFlash("danger", "Name is required.");
            redirect(BASE_URL . "index.php?page=users&action=editForm&id=" . $id);
        }

        $currentUser = Auth::currentUser();

        if ($id === (int) $currentUser["id"] && $isActive === 0) {
            setFlash("danger", "You cannot deactivate your own account.");
            redirect(BASE_URL . "index.php?page=users&action=editForm&id=" . $id);
        }

        $this->userModel->updateUser(
            $id,
            $name,
            $phone,
            $isActive
        );

        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $this->userModel->updatePassword($id, $hashedPassword);
        }

        setFlash("success", "User updated successfully.");

        redirect(BASE_URL . "index.php?page=users&action=index");
    }

    public function delete()
    {
        if (!CSRF::validateToken($_POST["csrf_token"] ?? "")) {
            setFlash("danger", "Invalid request.");
            redirect(BASE_URL . "index.php?page=users&action=index");
        }

        $id = (int) ($_POST["id"] ?? 0);
        $currentUser = Auth::currentUser();

        if ($id === (int) $currentUser["id"]) {
            setFlash("danger", "You cannot delete your own account.");
            redirect(BASE_URL . "index.php?page=users&action=index");
        }

        if ($id > 0) {
            $this->userModel->deleteUser($id);
            setFlash("success", "User deleted successfully.");
        }

        redirect(BASE_URL . "index.php?page=users&action=index");
    }
}