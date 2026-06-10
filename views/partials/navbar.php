<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <span class="nav-link">
                <?php echo htmlspecialchars($currentUser["name"] ?? "User"); ?>
                (<?php echo htmlspecialchars($currentUser["role"] ?? ""); ?>)
            </span>
        </li>
    </ul>
</nav>