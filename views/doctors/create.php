<?php
require_once __DIR__ . "/../../core/CSRF.php";
require_once __DIR__ . "/../../core/helpers.php";

$pageTitle = "Create Doctor";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Create Doctor</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php require_once __DIR__ . "/../partials/alerts.php"; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Doctor Information</h3>
                </div>

                <form method="post" action="index.php?page=doctors&action=store">
                    <div class="card-body">

                        <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Specialization</label>
                            <select name="specialization_id" class="form-control" required>
                                <option value="">Select Specialization</option>

                                <?php while ($spec = $specializations->fetch_assoc()): ?>
                                    <option value="<?php echo $spec["id"]; ?>">
                                        <?php echo sanitize($spec["name"]); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Consultation Fee</label>
                            <input type="number" step="0.01" name="consultation_fee" value="0" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Bio</label>
                            <textarea name="bio" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Available Days</label><br>

                            <?php foreach (["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"] as $day): ?>
                                <label class="mr-3">
                                    <input type="checkbox" name="available_days[]" value="<?php echo $day; ?>">
                                    <?php echo $day; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Save Doctor
                        </button>

                        <a href="index.php?page=doctors&action=index" class="btn btn-secondary">
                            Back
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </section>

</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>