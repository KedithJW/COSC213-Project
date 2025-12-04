<?php
require_once '../repo/auth.php';
require __DIR__ . '/../repo/db_connect.php';
require __DIR__ . '/../repo/logservice.php';
require __DIR__ . '/../repo/bm_service.php';

//when user updates board 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $add_name = trim($_POST['add_name']);
    $board_name = trim($_POST['board_name']);
    $board_id = $_POST['id'];
    $user_id = $_SESSION['user_id'];

    if ($action == 'update') {
        if (!isset($board_id) || empty($board_id)) {
            echo "Error: Cannot update without a valid ID.";
        } else {
            $stmt = $pdo->prepare("UPDATE board SET name = ? WHERE id = ?");
            $stmt->execute([$board_name, $board_id]);

            log_activity($user_id, 'updated board name', 'board', $board_id, $board_name);

            header("Location: ../public/index.php");
            exit;
        }
    } elseif ($action == 'delete') {
        if (!isset($board_id) || empty($board_id)) {
            echo "Error: Cannot delete without a valid ID.";
        } else {

            // Delete Tasks associated with cards on this board
            $stmt = $pdo->prepare("DELETE FROM task WHERE card_id IN (SELECT id FROM card WHERE board_id = ?)");
            $stmt->execute([$board_id]);

            // Delete Cards on this board
            $stmt = $pdo->prepare("DELETE FROM card WHERE board_id = ?");
            $stmt->execute([$board_id]);

            // Delete Board Members
            $stmt = $pdo->prepare("DELETE FROM board_members WHERE board_id = ?");
            $stmt->execute([$board_id]);

            // Delete the Board
            $stmt = $pdo->prepare("DELETE FROM board WHERE id = ?");
            $stmt->execute([$board_id]);

            log_activity($user_id, 'deleted board', 'board', $board_id, $board_name);

            header("Location: ../public/index.php");
            exit;
        }
    } elseif ($action == 'add_member') {

        // Find user by username or email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$add_name, $add_name]);
        $user = $stmt->fetch();

        if ($user) {
            $added_user_id = $user['id'];

            // Check if the user is already a member of the board
            $stmt = $pdo->prepare("SELECT * FROM board_members WHERE board_id = ? AND user_id = ?");
            $stmt->execute([$board_id, $added_user_id]);
            $existing_member = $stmt->fetch();

            if (!$existing_member) {
                // Add user to board members
                $stmt = $pdo->prepare("INSERT INTO board_members (board_id, user_id) VALUES (?, ?)");
                $stmt->execute([$board_id, $added_user_id]);
                // Get member name for logging
                $board_member_name = get_board_member_name($board_id, $added_user_id);
                log_activity($user_id, "added member '$board_member_name' to board", 'board', $board_id, "Added user ID: $added_user_id");
                //notification to added member
                notification($added_user_id, null, $board_id, "You have been added to the board '$board_name'.");
            }
        }
    } else if ($action == 'remove') {
        $removed_user_id = $_POST['user_id_to_remove'];
        $board_remove_id = $_POST['board_id'];

        if ($removed_user_id && $board_remove_id) {
            // Get member name before removing
            $board_member_name = get_board_member_name($board_remove_id, $removed_user_id);

            // Remove user from board members
            $stmt = $pdo->prepare("DELETE FROM board_members WHERE board_id = ? AND user_id = ?");
            $stmt->execute([$board_remove_id, $removed_user_id]);

            // Log activity with corrected syntax
            log_activity($user_id, "removed member '$board_member_name' from board", 'board', $board_remove_id, "Removed user ID: $removed_user_id");
        }
    }
}
header("Location: ../public/index.php");
exit;
?>