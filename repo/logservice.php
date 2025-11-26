<?php

//Create Logs 
function log_activity($user_id, $action, $target_type = null, $target_id = null, $target_name = null)
{
    require __DIR__ . '/../repo/db_connect.php';
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, target_type, target_id, created_at, target_name) VALUES (?, ?, ?, ?, NOW(), ?)");
    $stmt->execute([$user_id, $action, $target_type, $target_id, $target_name]);
}

//Read Logs 

function get_activity_logs($user_id, $limit = 10)
{
    require __DIR__ . '/../repo/db_connect.php';
    $stmt = $pdo->prepare("SELECT * FROM activity_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
    $stmt->bindParam(1, $user_id, PDO::PARAM_INT); // Bind user_id as integer
    $stmt->bindParam(2, $limit, PDO::PARAM_INT); // Bind limit as integer <- If removed will cause error


    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>