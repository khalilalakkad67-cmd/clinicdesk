<?php

session_start();

require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/core/helpers.php";

$page = $_GET["page"] ?? "auth";
$action = $_GET["action"] ?? "loginForm";
if (
    !($page === "reports" && $action === "exportCsv") &&
    !($page === "prescriptions" && $action === "download")
) {
    echo '<link rel="stylesheet" href="public/assets/style.css">';
}

if ($page === "auth") {

    require_once __DIR__ . "/controllers/AuthController.php";
    $controller = new AuthController();

    if ($action === "loginForm") {
        $controller->loginForm();
    } elseif ($action === "login") {
        $controller->login();
    } elseif ($action === "logout") {
        $controller->logout();
    } else {
        echo "404 - Action not found";
    }

} elseif ($page === "dashboard") {

    require_once __DIR__ . "/controllers/DashboardController.php";
    $controller = new DashboardController();
    $controller->index();

} elseif ($page === "users") {

    require_once __DIR__ . "/controllers/UserController.php";
    $controller = new UserController();

    if ($action === "index") {
        $controller->index();
    } elseif ($action === "createForm") {
        $controller->createForm();
    } elseif ($action === "store") {
        $controller->store();
    } elseif ($action === "editForm") {
        $controller->editForm();
    } elseif ($action === "update") {
        $controller->update();
    } elseif ($action === "delete") {
        $controller->delete();
    } else {
        echo "404 - Action not found";
    }

} elseif ($page === "doctors") {

    require_once __DIR__ . "/controllers/DoctorController.php";
    $controller = new DoctorController();

    if ($action === "index") {
        $controller->index();
    } elseif ($action === "createForm") {
        $controller->createForm();
    } elseif ($action === "store") {
        $controller->store();
    } elseif ($action === "editForm") {
        $controller->editForm();
    } elseif ($action === "update") {
        $controller->update();
    } elseif ($action === "delete") {
        $controller->delete();
    } else {
        echo "404 - Action not found";
    }

} elseif ($page === "specializations") {

    require_once __DIR__ . "/controllers/SpecializationController.php";
    $controller = new SpecializationController();

    if ($action === "index") {
        $controller->index();
    } elseif ($action === "store") {
        $controller->store();
    } elseif ($action === "delete") {
        $controller->delete();
    } else {
        echo "404 - Action not found";
    }

} elseif ($page === "appointments") {

    require_once __DIR__ . "/controllers/AppointmentController.php";
    $controller = new AppointmentController();

    if ($action === "index") {
        $controller->index();
    } elseif ($action === "createForm") {
        $controller->createForm();
    } elseif ($action === "store") {
        $controller->store();
    } elseif ($action === "updateStatus") {
        $controller->updateStatus();
    } else {
        echo "404 - Action not found";
    }

} elseif ($page === "prescriptions") {

    require_once __DIR__ . "/controllers/PrescriptionController.php";
    $controller = new PrescriptionController();

    if ($action === "createForm") {
        $controller->createForm();
    } elseif ($action === "store") {
        $controller->store();
    } elseif ($action === "viewMine") {
        $controller->viewMine();
    } elseif ($action === "download") {
        $controller->download();
    } else {
        echo "404 - Action not found";
    }

} elseif ($page === "reports") {

    require_once __DIR__ . "/controllers/ReportController.php";
    $controller = new ReportController();

    if ($action === "index") {
        $controller->index();
    } elseif ($action === "exportCsv") {
        $controller->exportCsv();
    } else {
        echo "404 - Action not found";
    }

} else {

    echo "404 - Page not found";
}