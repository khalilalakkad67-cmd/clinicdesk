<?php
require_once __DIR__ . "/../../core/helpers.php";
require_once __DIR__ . "/../../core/CSRF.php";
?>

<h1>Edit User</h1>

<p>
    <a href="index.php?page=users&action=index">
        Back to Users
    </a>
</p>

<?php if (isset($_SESSION["flash"])): ?>
    <p style="color:red;">
        <?php echo sanitize($_SESSION["flash"]["message"]); ?>
    </p>
    <?php unset($_SESSION["flash"]); ?>
<?php endif; ?>

<form method="post" action="index.php?page=users&action=update">

    <input type="hidden"
           name="csrf_token"
           value="<?php echo CSRF::generateToken(); ?>">

    <input type="hidden"
           name="id"
           value="<?php echo $user["id"]; ?>">

    <p>
        Name:<br>
        <input type="text"
               name="name"
               value="<?php echo sanitize($user["name"]); ?>"
               required>
    </p>

    <p>
        Email:<br>
        <input type="email"
               value="<?php echo sanitize($user["email"]); ?>"
               readonly>
    </p>

    <p>
        Phone:<br>
        <input type="text"
               name="phone"
               value="<?php echo sanitize($user["phone"] ?? ""); ?>">
    </p>

    <p>
        New Password:<br>
        <input type="password"
               name="new_password">
        <br>
        <small>Leave empty to keep current password</small>
    </p>

    <p>
        Status:<br>

        <select name="is_active">
            <option value="1"
                <?php if ((int)$user["is_active"] === 1) echo "selected"; ?>>
                Active
            </option>

            <option value="0"
                <?php if ((int)$user["is_active"] === 0) echo "selected"; ?>>
                Inactive
            </option>
        </select>
    </p>

    <button type="submit">
        Update User
    </button>

</form>