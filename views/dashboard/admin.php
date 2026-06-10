<?php
require_once __DIR__ . "/../../core/CSRF.php";

$pageTitle = "Admin Dashboard";
require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Admin Dashboard</h1>
            <p>Welcome Admin</p>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php require_once __DIR__ . "/../partials/alerts.php"; ?>

            <div class="row">

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $totalUsers; ?></h3>
                            <p>Total Users</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $totalDoctors; ?></h3>
                            <p>Total Doctors</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $totalAppointments; ?></h3>
                            <p>Total Appointments</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo $totalPrescriptions; ?></h3>
                            <p>Total Prescriptions</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Management</h3>
                </div>

                <div class="card-body">
                    <a class="btn btn-primary" href="index.php?page=users&action=index">Manage Users</a>
                    <a class="btn btn-success" href="index.php?page=doctors&action=index">Manage Doctors</a>
                    <a class="btn btn-warning" href="index.php?page=appointments&action=index">Manage Appointments</a>
                    <a class="btn btn-info" href="index.php?page=specializations&action=index">Manage Specializations</a>
                    <a class="btn btn-secondary" href="index.php?page=reports&action=index">Reports</a>
                </div>
            </div>

            <form method="post" action="index.php?page=auth&action=logout">
                <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                <button type="submit" class="btn btn-danger">
                    Logout
                </button>
            </form>

        </div>
    </section>

</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>