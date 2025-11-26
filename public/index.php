<?php
//connect to db 
require_once '../repo/db_connect.php';

//add log_activity
require __DIR__ . '/../repo/logservice.php';

// add members service
require __DIR__ . '/../repo/bm_service.php';

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

    //log_activity ( user_id, action, target_type, target_id )
    log_activity($user_id, 'created board', 'board', $new_board_id, $board_name);

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

//get notifications
$notifications = get_activity_logs($user_id);

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

        .delete-board-btn:hover {
            background-color: #8c8c8cff !important;
            color: white !important;
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

            <!-- MAIN CONTENT (USERS BOARDS) -->
            <main class="col py-3 bg-primary bg-gradient">
                <div class="tab-content" id="v-pills-tabContent" style="margin-left:1vh; margin-top: 4vh;">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                        aria-labelledby="v-pills-home-tab">
                        <!-- Boards Content -->
                        <h2 class="text-left text-white" style="position:relative; font-size:4vh;">YOUR BOARDS</h2>


                        <!-- BOARD WRAPPER -->
                        <div class="board_wrapper" style="display: flex; gap: 10px; flex-wrap: wrap;">

                            <?php foreach ($boards as $board): ?>

                                <!-- Each Board Display(Button) -->
                                <div style="height: 100px; max-width:225px; min-width: 225px; margin-top: 50px; position: relative;">

                                    <!-- Board Link -->
                                    <a href="board.php?id=<?= $board['id'] ?>&name=<?= urlencode($board['name']) ?>"
                                        class="btn btn-dark bg-gradient"
                                        style="display: block; height: 100%; width: 100%; opacity:0.8; text-decoration: none;">

                                        <div class="btn-label bg-dark"
                                            style="position:absolute; left:0; right:0; bottom:0; padding:6px 8px; font-size:0.85rem; text-align:left; border-radius:4px;">
                                            <?= htmlspecialchars($board['name']) ?>
                                        </div>
                                    </a>

                                    <!-- Edit Board Button -->
                                    <button type="button" class="delete-board-btn" data-bs-toggle="modal"
                                        data-bs-target="#myEditModal<?= $board['id'] ?>"
                                        style="z-index: 5; position:absolute; top:5px; right:5px; border: none; border-radius: 8px; color: white; background: #0808086a;"
                                        data-board-id="<?= $board['id'] ?>"
                                        data-board-name="<?= htmlspecialchars($board['name']) ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </button>

                                </div>
                                <!-- End of each board display/button -->

                                <!-- MODAL DELETE/UPDATE FOR EACH BOARD  -->
                                <div class="modal fade" id="myEditModal<?= $board['id'] ?>" tabindex="-1"
                                    aria-labelledby="myFormModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content bg-primary bg-gradient text-white">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <?= htmlspecialchars($board['name']) ?>
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">

                                                <!-- Update board form -->
                                                <form action="../api/update_board.php"
                                                    id="updateBoardForm<?= $board['id'] ?>" method="POST">
                                                    <div class="mb-3 w-100">

                                                        <label for="inputBoard<?= $board['id'] ?>" class="form-label">Board title</label>
                                                        <input type="hidden" name="id" value="<?= $board['id'] ?>"> <!-- Hidden field for board ID -->
                                                        <!-- Board Name Input -->
                                                        <input type="text" name="board_name" class="form-control"
                                                            id="inputBoard<?= $board['id'] ?>"
                                                            value="<?= htmlspecialchars($board['name']) ?>">

                                                        <br>

                                                        <label for="inputBoardMember<?= $board['id'] ?>"class="form-label">Add Member</label>
                                                        <!-- Member Name Input -->
                                                        <input type="text" name="add_name" class="form-control"
                                                            id="inputBoardMember<?= $board['id'] ?>"
                                                            placeholder="Member username/email to add">
                                                        <!-- Board Members List -->
                                                        <?php $boardMembers = get_board_members($board['id']); ?>
                                                        <!-- Check if the current user is the owner of the board -->
                                                        <?php $isOwner = is_board_owner($board['id'], $user_id); ?>

                                                        <br>
                                                        <!-- Board Members Display -->
                                                        <p style="margin-bottom:10px;">Board Members</p>
                                                        <?php foreach ($boardMembers as $member): ?>

                                                            <div class="d-flex"
                                                                style="margin-top:5px; padding:5px; border:1px solid #444; border-radius:4px; background-color:#222;">
                                                                <span class="fw-bold"><?= htmlspecialchars($member['username']) ?></span>
                                                                <span class="ms-2" style="font-size:0.85rem; color:#aaa;"><?= htmlspecialchars($member['email']) ?></span>
                                                                <span class="ms-3" style="font-size:0.85rem; color:#aaa;"><?= htmlspecialchars($member['role'] ?? '') ?></span>

                                                                <?php
                                                                // Only display the 'Remove' button if the viewer is the owner AND the member is not the owner
                                                                if ($isOwner && $member['user_id'] != $user_id):?>
                                                                    <!-- Remove Member Button(ONLY FOR OWNER) -->
                                                                    <form action="../api/update_board.php" method="POST"
                                                                        class="ms-auto">
                                                                        <input type="hidden" name="board_id"
                                                                            value="<?= $board['id'] ?>">
                                                                        <input type="hidden" name="user_id_to_remove"
                                                                            value="<?= $member['user_id'] ?>">
                                                                        <button type="submit" name="action"
                                                                            class="btn btn-danger ms-auto" value="remove"
                                                                            style="font-size:0.7rem; padding: 0.15rem 0.5rem;">Remove</button>
                                                                    </form>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <!-- Action Buttons -->
                                                    <div class="d-flex mt-4">
                                                        <button type="submit" name="action" class="btn btn-dark"
                                                            id="updateBtn<?= $board['id'] ?>" value="update">Update</button>

                                                        <button type="submit" name="action" class="btn btn-danger ms-2"
                                                            id="deleteBtn<?= $board['id'] ?>" value="delete">Delete</button>

                                                        <button type="submit" name="action" class="btn btn-success ms-auto"
                                                            id="addMemberBtn<?= $board['id'] ?>" value="add_member">Add Member</button>
                                                    </div>
                                                </form>
                                                <!-- END Update board form -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <!-- Create New Board Button -->
                            <button type="button" class="btn btn-dark create-board-btn" data-bs-toggle="modal"
                                data-bs-target="#myFormModal"
                                style="height: 100px; opacity:0.8; margin-top: 50px;  max-width:225px; min-width: 225px;"
                                id="createBoard">
                                Create new board
                            </button>
                        </div>
                        <!-- End BOARD WRAPPER -->
                    

                        <!-- MODAL CREATE -->
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
                                        <!-- END Create new board form -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END BOARD WRAPPER -->

                    </div>

                    <!-- Home pane (new) -->
                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                        aria-labelledby="v-pills-messages-tab" style="position: relative;">
                        <h2 class="text-left text-white" style="font-size:4vh;">HOME</h2>
                    
                        <!-- Recent Activity Log -->
                        <ul class="list-group bg-dark" style="margin-top:7vh;">
                            <li class="list-group-item ">Recent activity</li>

                            <!-- Load user activity -->
                            <?php $activity_logs = get_activity_logs($user_id, 10); // Get activity logs instead
                            foreach ($activity_logs as $activity): ?>
                                <li class="list-group-item list-group-item-info">
                                    You <?= htmlspecialchars($activity['action'] ?? '') ?>
                                    <span class="fw-bold"><?= htmlspecialchars($activity['target_name'] ?? '') ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <!-- End Home pane -->

                </div>
            </main>

        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
            </script>
        <!-- JavaScript to handle modal data population (Leaving in case)-->
        <!-- <script>
            const editModal = document.getElementById('myEditModal');
            const updateBoardForm = document.getElementById('updateBoardForm');

            document.querySelectorAll('.delete-board-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const boardId = this.getAttribute('data-board-id');
                    const boardName = this.getAttribute('data-board-name');

                    const modalTitle = editModal.querySelector('.modal-title');
                    const hiddenIdInput = updateBoardForm.querySelector('input[name="id"]');
                    const nameInput = updateBoardForm.querySelector('input[name="board_name"]');


                    modalTitle.textContent = boardName;
                    hiddenIdInput.value = boardId;
                    nameInput.value = boardName;
                });
            });

            editModal.addEventListener('hidden.bs.modal', function () {
                updateBoardForm.reset();
                updateBoardForm.querySelector('input[name="id"]').value = '';
                editModal.querySelector('.modal-title').textContent = '';
            });

        </script> -->
   
</body>

</html>