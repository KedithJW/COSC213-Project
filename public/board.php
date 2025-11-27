<?php
require_once '../repo/db_connect.php';
require_once '../repo/auth.php';
?>

<?php
    include __DIR__ . "/../templates/header.html";
?>

    <!-- PROJECT HEADER -->
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
            </div>
        </div>
    </nav>

    <!-- TASK CARDS -->
    <section class="p-5 bg-primary bg-gradient min-vh-100">
        <div id= "board-1" class="row g-0">
            <div id="add-card-container" class="col-auto px-2 py-2">
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

