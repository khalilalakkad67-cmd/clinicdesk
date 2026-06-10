<?php
require_once __DIR__ . "/../../core/helpers.php";
require_once __DIR__ . "/../../core/CSRF.php";
?>

<h1>Book Appointment</h1>

<?php if (isset($_SESSION["flash"])): ?>
    <p style="color:red;">
        <?php echo sanitize($_SESSION["flash"]["message"]); ?>
    </p>
    <?php unset($_SESSION["flash"]); ?>
<?php endif; ?>

<p>
    <a href="index.php?page=appointments&action=index">
        Back to Appointments
    </a>
</p>

<form method="post"
      action="index.php?page=appointments&action=store">

    <input type="hidden"
           name="csrf_token"
           value="<?php echo CSRF::generateToken(); ?>">

    <p>
        Doctor:
        <select name="doctor_id" required>

            <option value="">
                Select Doctor
            </option>

            <?php while ($doctor = $doctors->fetch_assoc()): ?>

                <option value="<?php echo $doctor["id"]; ?>">
                    <?php echo sanitize($doctor["name"]); ?>
                    -
                    <?php echo sanitize($doctor["specialization_name"]); ?>
                </option>

            <?php endwhile; ?>

        </select>
    </p>

    <p>
        Date:
        <input type="date"
               name="appt_date"
               required>
    </p>

    <p>
        Time:
        <input type="time"
               name="appt_time"
               required>
    </p>

    <p>
        Reason:
        <textarea name="reason"></textarea>
    </p>

    <button type="submit">
        Book Appointment
    </button>

</form>