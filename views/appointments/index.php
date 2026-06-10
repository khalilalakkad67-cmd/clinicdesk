<?php
require_once __DIR__ . "/../../core/helpers.php";
require_once __DIR__ . "/../../core/CSRF.php";

$pageTitle = "Appointments";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";

$currentUser = Auth::currentUser();

$statusFilter = $_GET["status"] ?? "";
$startDateFilter = $_GET["start_date"] ?? "";
$endDateFilter = $_GET["end_date"] ?? "";
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Appointments</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php require_once __DIR__ . "/../partials/alerts.php"; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Appointments</h3>
                </div>

                <div class="card-body">
                    <form method="get" action="index.php">
                        <input type="hidden" name="page" value="appointments">
                        <input type="hidden" name="action" value="index">

                        <div class="row">

                            <div class="col-md-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All</option>
                                    <option value="pending" <?php if ($statusFilter === "pending") echo "selected"; ?>>pending</option>
                                    <option value="confirmed" <?php if ($statusFilter === "confirmed") echo "selected"; ?>>confirmed</option>
                                    <option value="completed" <?php if ($statusFilter === "completed") echo "selected"; ?>>completed</option>
                                    <option value="cancelled" <?php if ($statusFilter === "cancelled") echo "selected"; ?>>cancelled</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control"
                                       value="<?php echo sanitize($startDateFilter); ?>">
                            </div>

                            <div class="col-md-3">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control"
                                       value="<?php echo sanitize($endDateFilter); ?>">
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                <a href="index.php?page=appointments&action=index" class="btn btn-secondary">Reset</a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Appointments List</h3>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>

                                <?php if ($currentUser["role"] === "admin" || $currentUser["role"] === "doctor"): ?>
                                    <th>Update Status</th>
                                    <th>Prescription</th>
                                <?php endif; ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($appointment = $appointments->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $appointment["id"]; ?></td>
                                    <td><?php echo sanitize($appointment["patient_name"] ?? "-"); ?></td>
                                    <td><?php echo sanitize($appointment["doctor_name"] ?? "-"); ?></td>
                                    <td><?php echo sanitize($appointment["appt_date"]); ?></td>
                                    <td><?php echo sanitize($appointment["appt_time"]); ?></td>

                                    <td>
                                        <?php
                                        $status = $appointment["status"];
                                        $badgeClass = "secondary";

                                        if ($status === "pending") {
                                            $badgeClass = "warning";
                                        } elseif ($status === "confirmed") {
                                            $badgeClass = "info";
                                        } elseif ($status === "completed") {
                                            $badgeClass = "success";
                                        } elseif ($status === "cancelled") {
                                            $badgeClass = "danger";
                                        }
                                        ?>

                                        <span class="badge badge-<?php echo $badgeClass; ?>">
                                            <?php echo sanitize($status); ?>
                                        </span>
                                    </td>

                                    <?php if ($currentUser["role"] === "admin" || $currentUser["role"] === "doctor"): ?>
                                        <td>
                                            <form method="post" action="index.php?page=appointments&action=updateStatus" class="form-inline">
                                                <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                                                <input type="hidden" name="id" value="<?php echo $appointment["id"]; ?>">

                                                <select name="status" class="form-control form-control-sm mr-2">
                                                    <option value="pending" <?php if ($appointment["status"] === "pending") echo "selected"; ?>>pending</option>
                                                    <option value="confirmed" <?php if ($appointment["status"] === "confirmed") echo "selected"; ?>>confirmed</option>
                                                    <option value="completed" <?php if ($appointment["status"] === "completed") echo "selected"; ?>>completed</option>
                                                    <option value="cancelled" <?php if ($appointment["status"] === "cancelled") echo "selected"; ?>>cancelled</option>
                                                </select>

                                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                            </form>
                                        </td>

                                        <td>
                                            <?php if ($appointment["status"] === "completed"): ?>

                                                <?php if (!empty($appointment["prescription_id"])): ?>

                                                    <span class="badge badge-success">Prescription Added</span>

                                                <?php else: ?>

                                                    <a class="btn btn-sm btn-success"
                                                       href="index.php?page=prescriptions&action=createForm&appointment_id=<?php echo $appointment["id"]; ?>">
                                                        Add Prescription
                                                    </a>

                                                <?php endif; ?>

                                            <?php else: ?>

                                                <span class="text-muted">-</span>

                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>

                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <nav>
    <ul class="pagination">

        <?php
        $queryParams = $_GET;
        unset($queryParams["p"]);
        ?>

        <?php if ($paginator->hasPrev()): ?>
            <?php $queryParams["p"] = $paginator->currentPage() - 1; ?>

            <li class="page-item">
                <a class="page-link"
                   href="index.php?<?php echo http_build_query($queryParams); ?>">
                    Previous
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $paginator->totalPages(); $i++): ?>
            <?php $queryParams["p"] = $i; ?>

            <li class="page-item <?php if ($i === $paginator->currentPage()) echo 'active'; ?>">
                <a class="page-link"
                   href="index.php?<?php echo http_build_query($queryParams); ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if ($paginator->hasNext()): ?>
            <?php $queryParams["p"] = $paginator->currentPage() + 1; ?>

            <li class="page-item">
                <a class="page-link"
                   href="index.php?<?php echo http_build_query($queryParams); ?>">
                    Next
                </a>
            </li>
        <?php endif; ?>

    </ul>
</nav>
                </div>
            </div>

        </div>
    </section>

</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>