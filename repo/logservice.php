<?php

//Create Logs 
function log_activity($user_id, $action, $target_type = null, $target_id = null, $target_name = null)
{
    global $pdo;
    $stmt = $pdo->prepare(
        "INSERT INTO activity_logs (user_id, action, target_type, target_id, created_at, target_name) 
                VALUES (?, ?, ?, ?, NOW(), ?)"
    );
    $stmt->execute([$user_id, $action, $target_type, $target_id, $target_name]);
}

//Read Logs 
function get_activity_logs($user_id, $limit = 10)
{
    global $pdo;
    $stmt = $pdo->prepare(
        "SELECT al.*, b.name AS target_name 
                FROM activity_logs al 
                LEFT JOIN board b ON al.target_id = b.id AND al.target_type = 'board'
                WHERE al.user_id = :user_id 
                ORDER BY al.created_at DESC 
                LIMIT :limit"
    );
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); // Bind user_id as integer
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT); // Bind limit as integer <- If removed will cause error
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function notification($recipient_user_id, $notification_msg, $source_activity_id = null, $board_id = null)
{
    global $pdo;
    $stmt = $pdo->prepare(
        "INSERT INTO notifications (recipient_user_id, source_activity_id, board_id, notification_msg, created_at) 
                VALUES (?, ?, ?, ?, NOW())"
    );
    $stmt->execute([$recipient_user_id, $notification_msg, $source_activity_id, $board_id]);
}


function mark_all_notifications_read($user_id)
{
    global $pdo;

    //$uid = (int) $user_id;
    $stmt = $pdo->prepare(
        "UPDATE notifications 
                SET is_read = 1 
                WHERE recipient_user_id = :user_id
                AND (is_read = 0 OR is_read IS NULL)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); // Bind user_id as integer
    $stmt->execute();
    return $stmt->rowCount();
}


function count_unread_notifications($user_id)
{
    global $pdo;

    //$uid = (int) $user_id;
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) as unread_count 
                FROM notifications
                WHERE recipient_user_id = :user_id
                AND (is_read = 0 OR is_read IS NULL)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); // Bind user_id as integer
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int) $result['unread_count'];
}
function get_user_notifications($user_id, $limit = 4)
{
    global $pdo;
    $stmt = $pdo->prepare(
        "SELECT * FROM notifications
                WHERE recipient_user_id = :user_id
                ORDER BY created_at DESC
                LIMIT :limit");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); // Bind user_id as integer
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT); // Bind limit as integer <- If removed will cause error
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>