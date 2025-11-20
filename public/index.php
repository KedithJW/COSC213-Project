<?php
//connect to db 
require_once '../repo/db_connect.php';

//if no one logged in redirect to login 
require_once '../repo/auth.php';

//check
$user_id = $_SESSION['user_id'];

// when user creates board 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_board'])) {
    $board_name = trim($_POST['board_name']); // extract board name from POST data

    // $stmt is a pdo statement object 

    //update board db 
    $stmt = $pdo->prepare("INSERT INTO board (name, owner_id) VALUES (?,?)");
    $stmt->execute([$board_name, $user_id]);
    $new_board_id = $pdo->lastInsertId();
    //update board_members
    $stmt = $pdo->prepare("INSERT INTO board_members (board_id, user_id, role) VALUES (?,?, 'owner')");
    $stmt->execute([$new_board_id, $user_id]);

    //send to new board 
    header("Location: board.php?id=" . $new_board_id);
    exit;
}

// get all boards this user is a member of
$stmt = $pdo->prepare("
    SELECT b.*, bm.role, u.username as owner_name
    FROM board b
    JOIN board_members bm ON b.id = bm.board_id
    LEFT JOIN users u ON b.owner_id = u.id
    WHERE bm.user_id = ?
    ORDER BY b.created_at DESC
");

$stmt->execute([$user_id]);
$boards = $stmt->fetchAll(); // array container for all boards 
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Index - Project Manager </title>
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
    <?php include "../templates/header.php"; ?>

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
                        <h2 class="text-left text-white" style="position:relative; font-size:4vh;">YOUR BOARDS</h2>


                        <!-- BOARD WRAPPER -->
                        <div class="board_wrapper" style="display: flex; gap: 10px; flex-wrap: wrap;">

                            <!-- Load user boards -->
                            <?php foreach ($boards as $board): ?>
                                <a href="board.php?id=<?= $board['id'] ?>&name=<?= urlencode($board['name']) ?>" class="btn btn-dark bg-gradient"
                                    style="height: 100px; width: 25%; opacity:0.8; margin-top: 50px; position:relative; max-width:225px; min-width: 225px; text-decoration: none;">

                                    <div class="btn-label bg-dark"
                                        style="position:absolute; left:0; right:0; bottom:0; padding:6px 8px; font-size:0.85rem; text-align:left; border-radius:4px;">
                                        <?= htmlspecialchars($board['name']) ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>

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
                                <div class="modal-content bg-primary bg-gradient text-white">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Create board</h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Create new board form -->
                                        <form id="createBoardForm" method="POST">
                                            <div class="mb-3">
                                                <label for="inputBoard" class="form-label">Board title</label>
                                                <input type="text" name="board_name" class="form-control"
                                                    id="inputBoard" required
                                                    value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                                            </div>
                                            <button type="submit" name="create_board" class="btn btn-dark "
                                                id="createBtn">Create</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Home pane (new) -->
                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                        aria-labelledby="v-pills-messages-tab" style="position: relative;">
                        <h2 class="text-left text-white" style="font-size:4vh;">HOME</h2>

                        <ul class="list-group bg-dark" style="margin-top:7vh;">
                            <li class="list-group-item ">Recent activity</li>
                            <li class="list-group-item list-group-item-info">Activity 1</li>
                            <li class="list-group-item list-group-item-info">Activity 2</li>

                        </ul>


                        <ul class="list-group bg-dark" style=" margin-top:7vh;">
                            <li class="list-group-item">Notifications</li>
                            <li class="list-group-item list-group-item-success">Notification 1</li>
                            <li class="list-group-item list-group-item-success">Notification 1</li>


                        </ul>
                    </div>

                </div>
            </main>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>
</body>

</html>