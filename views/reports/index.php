<?php
require_once __DIR__ . "/../../core/helpers.php";

$pageTitle = "Appointment Reports";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Appointment Reports</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php require_once __DIR__ . "/../partials/alerts.php"; ?>

            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">Generate Report</h3>
                </div>

                <form method="get" action="index.php">

                    <div class="card-body">

                        <input type="hidden" name="page" value="reports">
                        <input type="hidden" name="action" value="index">

                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Start Date</label>

                                    <input type="date"
                                           name="start_date"
                                           class="form-control"
                                           value="<?php echo sanitize($startDate); ?>"
                                           required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>End Date</label>

                                    <input type="date"
                                           name="end_date"
                                           class="form-control"
                                           value="<?php echo sanitize($endDate); ?>"
                                           required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Doctor</label>

                                    <select name="doctor_id" class="form-control">

                                        <option value="">All Doctors</option>

                                        <?php while ($doctor = $doctors->fetch_assoc()): ?>

                                            <option value="<?php echo $doctor["id"]; ?>"
                                                <?php if ((string)$doctorId === (string)$doctor["id"]) echo "selected"; ?>>

                                                <?php echo sanitize($doctor["name"]); ?>

                                            </option>

                                        <?php endwhile; ?>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>

                                    <select name="status" class="form-control">

                                        <option value="">All Statuses</option>

                                        <option value="pending" <?php if ($status === "pending") echo "selected"; ?>>
                                            Pending
                                        </option>

                                        <option value="confirmed" <?php if ($status === "confirmed") echo "selected"; ?>>
                                            Confirmed
                                        </option>

                                        <option value="completed" <?php if ($status === "completed") echo "selected"; ?>>
                                            Completed
                                        </option>

                                        <option value="cancelled" <?php if ($status === "cancelled") echo "selected"; ?>>
                                            Cancelled
                                        </option>

                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Generate Report
                        </button>
                    </div>

                </form>

            </div>

            <?php if ($reports): ?>

                <div class="card">

                    <div class="card-header">

                        <h3 class="card-title">
                            Report Results
                        </h3>

                        <div class="card-tools">

                            <a href="index.php?page=reports&action=exportCsv&start_date=<?php echo urlencode($startDate); ?>&end_date=<?php echo urlencode($endDate); ?>&doctor_id=<?php echo urlencode($doctorId); ?>&status=<?php echo urlencode($status); ?>"
                               class="btn btn-success btn-sm">

                                <i class="fas fa-file-csv"></i>
                                Export CSV

                            </a>

                        </div>

                    </div>

                    <div class="card-body table-responsive">

                        <table class="table table-bordered table-striped">

                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Specialization</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php while ($row = $reports->fetch_assoc()): ?>

                                    <tr>

                                        <td><?php echo sanitize($row["patient_name"]); ?></td>

                                        <td><?php echo sanitize($row["doctor_name"]); ?></td>

                                        <td><?php echo sanitize($row["specialization_name"]); ?></td>

                                        <td><?php echo sanitize($row["appt_date"]); ?></td>

                                        <td><?php echo sanitize($row["appt_time"]); ?></td>

                                        <td>

                                            <?php
                                            $badge = "secondary";

                                            if ($row["status"] === "pending") {
                                                $badge = "warning";
                                            } elseif ($row["status"] === "confirmed") {
                                                $badge = "info";
                                            } elseif ($row["status"] === "completed") {
                                                $badge = "success";
                                            } elseif ($row["status"] === "cancelled") {
                                                $badge = "danger";
                                            }
                                            ?>

                                            <span class="badge badge-<?php echo $badge; ?>">
                                                <?php echo sanitize($row["status"]); ?>
                                            </span>

                                        </td>

                                        <td><?php echo sanitize($row["reason"]); ?></td>

                                    </tr>

                                <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            <?php endif; ?>

        </div>
    </section>

</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>