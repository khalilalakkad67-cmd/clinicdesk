<?php
require_once __DIR__ . "/../../core/helpers.php";
require_once __DIR__ . "/../../core/CSRF.php";

$pageTitle = "Specializations Management";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Specializations Management</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php require_once __DIR__ . "/../partials/alerts.php"; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Specialization</h3>
                </div>

                <form method="post" action="index.php?page=specializations&action=store">
                    <div class="card-body">

                        <input type="hidden"
                               name="csrf_token"
                               value="<?php echo CSRF::generateToken(); ?>">

                        <div class="form-group">
                            <label>Specialization Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   placeholder="Specialization name"
                                   required>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Add Specialization
                        </button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Specializations</h3>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($specialization = $specializations->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $specialization["id"]; ?></td>
                                    <td><?php echo sanitize($specialization["name"]); ?></td>
                                    <td>
                                        <form method="post"
                                              action="index.php?page=specializations&action=delete"
                                              style="display:inline;">

                                            <input type="hidden"
                                                   name="csrf_token"
                                                   value="<?php echo CSRF::generateToken(); ?>">

                                            <input type="hidden"
                                                   name="id"
                                                   value="<?php echo $specialization["id"]; ?>">

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this specialization?');">
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