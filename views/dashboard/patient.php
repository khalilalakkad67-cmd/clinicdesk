<?php
require_once __DIR__ . "/../../core/helpers.php";

$pageTitle = "Patient Dashboard";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Patient Dashboard</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php require_once __DIR__ . "/../partials/alerts.php"; ?>

            <div class="row">

                <div class="col-lg-4 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $activeAppointments; ?></h3>
                            <p>Active Appointments</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $completedAppointments; ?></h3>
                            <p>Completed Appointments</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $availablePrescriptions; ?></h3>
                            <p>Available Prescriptions</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Quick Actions
                    </h3>
                </div>

                <div class="card-body">
                    <a href="index.php?page=appointments&action=createForm"
                       class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Book Appointment
                    </a>

                    <a href="index.php?page=appointments&action=index"
                       class="btn btn-info">
                        <i class="fas fa-calendar-alt"></i>
                        My Appointments
                    </a>

                    <a href="index.php?page=prescriptions&action=viewMine"
                       class="btn btn-success">
                        <i class="fas fa-file-medical"></i>
                        My Prescriptions
                    </a>
                </div>
            </div>

        </div>
    </section>

</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>