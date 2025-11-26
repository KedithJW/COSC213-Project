<?php 


function get_board_members($board_id)
{
    global $pdo;
    $stmt = $pdo->prepare(
        "SELECT u.id, u.username, u.email, bm.* 
                FROM users u 
                JOIN board_members bm ON u.id = bm.user_id 
                WHERE bm.board_id = ?");
    $stmt->execute([$board_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_board_member_name($board_id, $user_id)
{
    global $pdo;
    $stmt = $pdo->prepare(
        "SELECT u.username 
                FROM users u 
                JOIN board_members bm ON u.id = bm.user_id 
                WHERE bm.board_id = ? AND bm.user_id = ?");
    $stmt->execute([$board_id, $user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['username'];
}

function is_board_owner($board_id, $user_id)
{
    global $pdo;
    $stmt = $pdo->prepare(
        "SELECT * 
                FROM board_members 
                WHERE board_id = ? 
                AND user_id = ?
                AND role = 'owner'");
    $stmt->execute([$board_id, $user_id]);
    return $stmt->fetch() !== false;
}
?>