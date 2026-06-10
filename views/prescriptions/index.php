<?php
require_once __DIR__ . "/../../core/helpers.php";
?>

<h1>My Prescriptions</h1>

<p>
    <a href="index.php?page=dashboard">Back to Dashboard</a>
</p>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Doctor</th>
        <th>Date</th>
        <th>Diagnosis</th>
        <th>Medications</th>
        <th>Notes</th>
        <th>PDF</th>
    </tr>

    <?php while ($prescription = $prescriptions->fetch_assoc()): ?>
        <tr>
            <td><?php echo $prescription["id"]; ?></td>
            <td><?php echo sanitize($prescription["doctor_name"]); ?></td>
            <td><?php echo sanitize($prescription["appt_date"]); ?></td>
            <td><?php echo sanitize($prescription["diagnosis"]); ?></td>
            <td><?php echo sanitize($prescription["medications"]); ?></td>
            <td><?php echo sanitize($prescription["notes"]); ?></td>
            <td>
                <?php if (!empty($prescription["file_path"])): ?>
                    <a href="index.php?page=prescriptions&action=download&appointment_id=<?php echo $prescription["appointment_id"]; ?>">
                        Download PDF
                    </a>
                <?php else: ?>
                    No PDF
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>