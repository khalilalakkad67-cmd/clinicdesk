<?php
require_once __DIR__ . "/../../core/CSRF.php";
?>

<h1>Create User</h1>

<p>
    <a href="index.php?page=users&action=index">Back to Users</a>
</p>

<form method="post" action="index.php?page=users&action=store">

    <input type="hidden"
           name="csrf_token"
           value="<?php echo CSRF::generateToken(); ?>">

    <p>
        Name:
        <input type="text" name="name" required>
    </p>

    <p>
        Email:
        <input type="email" name="email" required>
    </p>

    <p>
        Password:
        <input type="password" name="password" required>
    </p>

    <p>
        Phone:
        <input type="text" name="phone">
    </p>

    <p>
        Role:
        <select name="role">
            <option value="admin">Admin</option>
            <option value="doctor">Doctor</option>
            <option value="patient">Patient</option>
        </select>
    </p>

    <button type="submit">Save</button>

</form>