<?php
require_once __DIR__ . "/../../core/helpers.php";
require_once __DIR__ . "/../../core/CSRF.php";

$pageTitle = "Edit Doctor";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";

$days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Edit Doctor</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php require_once __DIR__ . "/../partials/alerts.php"; ?>

            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">
                        <?php echo sanitize($doctor["name"]); ?>
                    </h3>
                </div>

                <form method="post" action="index.php?page=doctors&action=update">

                    <div class="card-body">

                        <input type="hidden"
                               name="csrf_token"
                               value="<?php echo CSRF::generateToken(); ?>">

                        <input type="hidden"
                               name="id"
                               value="<?php echo $doctor["id"]; ?>">

                        <div class="form-group">
                            <label>Email</label>
                            <input type="text"
                                   class="form-control"
                                   value="<?php echo sanitize($doctor["email"]); ?>"
                                   readonly>
                        </div>

                        <div class="form-group">
                            <label>Specialization</label>

                            <select name="specialization_id"
                                    class="form-control"
                                    required>

                                <?php while ($spec = $specializations->fetch_assoc()): ?>

                                    <option value="<?php echo $spec["id"]; ?>"
                                        <?php if ((int)$doctor["specialization_id"] === (int)$spec["id"]) echo "selected"; ?>>

                                        <?php echo sanitize($spec["name"]); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Consultation Fee</label>

                            <input type="number"
                                   step="0.01"
                                   name="consultation_fee"
                                   class="form-control"
                                   value="<?php echo sanitize($doctor["consultation_fee"]); ?>">
                        </div>

                        <div class="form-group">
                            <label>Bio</label>

                            <textarea name="bio"
                                      class="form-control"><?php echo sanitize($doctor["bio"] ?? ""); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Available Days</label>
                            <br>

                            <?php foreach ($days as $day): ?>

                                <label class="mr-3">

                                    <input type="checkbox"
                                           name="available_days[]"
                                           value="<?php echo $day; ?>"
                                           <?php if (in_array($day, $availableDays)) echo "checked"; ?>>

                                    <?php echo $day; ?>

                                </label>

                            <?php endforeach; ?>

                        </div>

                    </div>

                    <div class="card-footer">

                        <button type="submit"
                                class="btn btn-primary">
                            Update Doctor
                        </button>

                        <a href="index.php?page=doctors&action=index"
                           class="btn btn-secondary">
                            Back
                        </a>

                    </div>

                </form>

            </div>

        </div>
    </section>

</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>