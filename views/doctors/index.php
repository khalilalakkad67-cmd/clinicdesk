<?php
require_once __DIR__ . "/../../core/helpers.php";
require_once __DIR__ . "/../../core/CSRF.php";

$pageTitle = "Doctors Management";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Doctors Management</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php require_once __DIR__ . "/../partials/alerts.php"; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Doctors List</h3>

                    <div class="card-tools">
                        <a href="index.php?page=doctors&action=createForm" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Doctor
                        </a>
                    </div>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Specialization</th>
                                <th>Fee</th>
                                <th>Available Days</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($doctor = $doctors->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $doctor["id"]; ?></td>
                                    <td><?php echo sanitize($doctor["name"]); ?></td>
                                    <td><?php echo sanitize($doctor["email"]); ?></td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?php echo sanitize($doctor["specialization_name"]); ?>
                                        </span>
                                    </td>
                                    <td><?php echo sanitize($doctor["consultation_fee"]); ?></td>
                                    <td><?php echo sanitize($doctor["available_days"]); ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-warning"
                                           href="index.php?page=doctors&action=editForm&id=<?php echo $doctor["id"]; ?>">
                                            Edit
                                        </a>

                                        <form method="post"
                                              action="index.php?page=doctors&action=delete"
                                              style="display:inline;">

                                            <input type="hidden" name="id" value="<?php echo $doctor["id"]; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this doctor?');">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>