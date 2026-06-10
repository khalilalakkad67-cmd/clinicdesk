<?php
require_once __DIR__ . "/../../core/helpers.php";
require_once __DIR__ . "/../../core/CSRF.php";
?>

<h1>Add Prescription</h1>

<p>
    <a href="index.php?page=appointments&action=index">Back to Appointments</a>
</p>

<p>
    Appointment ID: <?php echo sanitize($appointment["id"]); ?><br>
    Patient: <?php echo sanitize($appointment["patient_name"]); ?><br>
    Doctor: <?php echo sanitize($appointment["doctor_name"]); ?><br>
    Date: <?php echo sanitize($appointment["appt_date"]); ?><br>
    Time: <?php echo sanitize($appointment["appt_time"]); ?>
</p>

<form method="post"
      action="index.php?page=prescriptions&action=store"
      enctype="multipart/form-data">

    <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
    <input type="hidden" name="appointment_id" value="<?php echo $appointment["id"]; ?>">

    <p>
        Diagnosis:<br>
        <textarea name="diagnosis" required></textarea>
    </p>

    <p>
        Medications:<br>
        <textarea name="medications" required></textarea>
    </p>

    <p>
        Notes:<br>
        <textarea name="notes"></textarea>
    </p>

    <p>
        Prescription PDF:<br>
        <input type="file" name="prescription_file" accept="application/pdf">
    </p>

    <button type="submit">Save Prescription</button>
</form>