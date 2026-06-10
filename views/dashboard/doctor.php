<?php
require_once __DIR__ . "/../../core/helpers.php";

$pageTitle = "Doctor Dashboard";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Doctor Dashboard</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php require_once __DIR__ . "/../partials/alerts.php"; ?>

            <div class="row">

                <div class="col-lg-3 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $todayAppointments; ?></h3>
                            <p>Today's Appointments</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-12">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><?php echo $monthlyAppointments; ?></h3>
                            <p>This Month</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-12">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $pendingAppointments; ?></h3>
                            <p>Pending</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $completedAppointments; ?></h3>
                            <p>Completed</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
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
                    <a href="index.php?page=appointments&action=index"
                       class="btn btn-info">
                        <i class="fas fa-calendar-alt"></i>
                        My Schedule
                    </a>
                </div>
            </div>

        </div>
    </section>

</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>