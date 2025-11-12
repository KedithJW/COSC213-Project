<?php

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        .nav-pills {
            --bs-nav-link-color: #fff;
            --bs-nav-link-hover-color: #fff;
        }

        .nav-pills .nav-link:not(.active):hover {
            background-color: rgba(255, 255, 255, .18);
        }
    </style>
</head>

<body>
    <!-- NAVBAR / HEADER -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">
            <!-- Left: Brand -->
            <a href="#" class="navbar-brand flex-shrink-0 px-5" style="white-space: nowrap;">APP NAME</a>
            <!-- Toggler -->
            <button class="navbar-toggler flex-shrink-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible Content -->
            <div class="collapse navbar-collapse flex-shrink-0" id="navmenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="#learn" class="nav-link">Create</a>
                    </li>
                    <!-- Account Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Account
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="myAccount.php">View My Account</a></li>
                            <li><a class="dropdown-item" href="login.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- DASHBOARD -->
    <div class="container-fluid">
        <div class="row min-vh-100">
            <aside class="col-12 col-md-auto p-5 bg-primary bg-gradient text-white" style="width:14rem;">
                <div class="d-flex align-items-start">
                    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home"
                            aria-selected="true">Boards</button>
                        <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages"
                            aria-selected="false">Home</button> 
                    </div>
                </div>
                <hr style="width: 100%;">
                <ul class="nav nav-pills">
                </ul>
            </aside>
            <main class="col py-3 bg-primary">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                        aria-labelledby="v-pills-home-tab">
                        <!-- Boards Content -->
                        <h2>Boards</h2>
                        <p>Content for Board goes here.</p>
                    </div>
                </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>