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

        <button class="navbar-toggler flex-shrink-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#navmenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse flex-shrink-0" id="navmenu">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2">Hey <?= htmlspecialchars($username) ?></span>
                        <img src="/uploads/profiles/<?= htmlspecialchars($profilePic) ?>?v=<?= time() ?>"
                             onerror="this.onerror=null;this.src='/uploads/profiles/default.jpg';"
                             class="rounded-circle"
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