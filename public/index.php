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
            <!-- SIDEBAR -->
            <aside class="col-12 col-md-auto p-5 bg-primary bg-gradient text-white" style="width:14rem;">
                <div class="d-flex align-items-start">
                    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <!-- Nav Buttons -->
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

            <!-- MAIN CONTENT -->
            <main class="col py-3 bg-primary bg-gradient">
                <div class="tab-content" id="v-pills-tabContent" style="margin-left:1vh; margin-top: 4vh;">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                        aria-labelledby="v-pills-home-tab">
                        <!-- Boards Content -->
                        <h2 class="text-left text-white" style="postion:relative; font-size:4vh;">YOUR BOARDS</h2>


                        <!-- BOARD WRAPPER -->
                        <div class="board_wrapper" style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button type="button" class="btn btn-dark create-board-btn" data-bs-toggle="modal"
                                data-bs-target="#myFormModal"
                                style="height: 100px; opacity:0.8; margin-top: 50px;  max-width:225px; min-width: 225px;"
                                id="createBoard">
                                Create new board
                            </button>
                        </div>

                        <!-- MODAL -->
                        <div class="modal fade" id="myFormModal" tabindex="-1" aria-labelledby="myFormModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Create board</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="createBoardForm">
                                            <div class="mb-3">
                                                <label for="inputBoard" class="form-label">Board title</label>
                                                <input type="text" class="form-control" id="inputBoard">
                                            </div>
                                            <button type="button" class="btn btn-primary" id="createBtn">Create</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- JS -->
                        <script>
                            let buttonCount = 0; // R

                            document.getElementById("createBtn").addEventListener("click", function () {
                                buttonCount++;
                                const input = document.getElementById("inputBoard");
                                const wrapper = document.querySelector(".board_wrapper");
                                const createBtn = document.getElementById("createBoard");
                                const buttonText = input.value || `New Board ${buttonCount}`;
                                const newBtn = document.createElement("button");

                                newBtn.textContent = "";
                                newBtn.className = "btn btn-dark";
                                newBtn.style = "height: 100px; width: 25%; opacity:0.8; margin-top: 50px; position:relative; max-width:225px; min-width: 225px;";


    
                                // label pinned to the bottom of the button
                                const label = document.createElement("div");
                                label.className = "btn-label bg-dark bg-gradient";
                                label.textContent = buttonText; 
                                label.style.cssText =
                                    "position:absolute; left:0; right:0; bottom:0; padding:6px 8px; " +
                                    "font-size:0.85rem; text-align:center; border-radius:4px";

                                newBtn.appendChild(label);
                                // Insert new button BEFORE the Create button
                                wrapper.insertBefore(newBtn, createBtn);

                                // Clear input
                                input.value = "";

                                // Close modal
                                const myModalEl = document.getElementById('myFormModal');
                                const modal = bootstrap.Modal.getInstance(myModalEl);
                                modal.hide();
                            });
                        </script>
                    </div>
                </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>
</body>

</html>