<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php?page=dashboard" class="brand-link">
        <span class="brand-text font-weight-light">ClinicDesk</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">

                <li class="nav-item">
                    <a href="index.php?page=dashboard" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <?php if (($currentUser["role"] ?? "") === "admin"): ?>
                    <li class="nav-item">
                        <a href="index.php?page=users&action=index" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?page=doctors&action=index" class="nav-link">
                            <i class="nav-icon fas fa-user-md"></i>
                            <p>Doctors</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?page=specializations&action=index" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Specializations</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?page=reports&action=index" class="nav-link">
                            <i class="nav-icon fas fa-file-csv"></i>
                            <p>Reports</p>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a href="index.php?page=appointments&action=index" class="nav-link">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Appointments</p>
                    </a>
                </li>

                <?php if (($currentUser["role"] ?? "") === "patient"): ?>
                    <li class="nav-item">
                        <a href="index.php?page=appointments&action=createForm" class="nav-link">
                            <i class="nav-icon fas fa-plus"></i>
                            <p>Book Appointment</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="index.php?page=prescriptions&action=viewMine" class="nav-link">
                            <i class="nav-icon fas fa-file-medical"></i>
                            <p>My Prescriptions</p>
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>
    </div>
</aside>