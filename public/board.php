<?php
require_once '../repo/db_connect.php';
require_once '../repo/auth.php';
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title> Board - Project Manager</title>
</head>
    <body>
        <?php include "../templates/header.php"; ?>
    </body>
    
    <nav class="navbar navbar-expand-lg bg-primary navbar-dark">
        <div class="container">
            <span id="board-title" class="navbar-brand"></span>
            <button 
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navmenu"
            >
            <span class="navbar-toggler-icon"></span>
            </button>   
            
            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="#" class="nav-link">--members--</a>
                    </li><li class="nav-item">
                        <a href="#" class="nav-link">--share--</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- TASK CARDS -->
    <section class="p-5 bg-primary bg-gradient vh-100">
        <div id= "board-1" class="row g-0">
            <div id="add-card-container" class="col-auto px-2">
                <div class="card" style="width: 18rem;">
                    <a href="#" class="btn btn-dark add-card-btn">+ Add card</a>
                </div>
            </div>
            
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="board.js" defer></script>
</body>
</html>

