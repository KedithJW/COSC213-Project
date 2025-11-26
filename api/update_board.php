<?php

require __DIR__ . '/../repo/db_connect.php';
require __DIR__ . '/../repo/logservice.php';
//if no one logged in redirect to login 
require_once '../repo/auth.php';
//check


//when user updates board 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $board_name = trim($_POST['board_name']); 
    $board_id = $_POST['id'];
    $user_id = $_SESSION['user_id'];

    if ($action == 'update') {
        if (!isset($board_id) || empty($board_id)) {
            echo "Error: Cannot update without a valid ID.";
        } else {
            $stmt = $pdo->prepare("UPDATE board SET name = ? WHERE id = ?");
            $stmt->execute([$board_name, $board_id]);

            //log_activity ( user_id, action, target_type, target_id )
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
            
            // Delete the Board
            $stmt = $pdo->prepare("DELETE FROM board WHERE id = ?");
            $stmt->execute([$board_id]);


            //log_activity ( user_id, action, target_type, target_id )
            log_activity($user_id, 'deleted board', 'board', $board_id, $board_name);

            header("Location: ../public/index.php");
            exit;
        }
    }
}

header("Location: ../public/index.php");
exit;
?>