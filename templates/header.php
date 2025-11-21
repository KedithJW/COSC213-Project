<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$username = $_SESSION['username'];
$profilePic = $_SESSION['profile_picture'] ?? 'default.jpg';
?>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand flex-shrink-0 px-1">Project Task Manager</a>

        <button class="navbar-toggler flex-shrink-0" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse flex-shrink-0" id="navmenu">
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <button type="button" class="btn btn-primary px-2 me-3" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-bell" viewBox="0 0 16 16">
                            <path
                                d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6" />
                        </svg>
                        <span class="badge bg-danger">
                            
                        1 <!-- Placeholder for notification count - replace with dynamic value -->

                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">

                    <!-- Placeholder for notifications - replace with dynamic content -->
                        <li><a class="dropdown-item"> Place holder notification</a></li>
                    </ul>
                </li>

                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2">Hey <?= htmlspecialchars($username) ?></span>
                        <img src="uploads/profiles/<?= htmlspecialchars($profilePic) ?>?v=<?= time() ?>"
                            onerror="this.onerror=null;this.src='uploads/profiles/default.jpg';" class="rounded-circle"
                            style="width: 32px; height: 32px; object-fit: cover;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="myAccount.php">View My Account</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>