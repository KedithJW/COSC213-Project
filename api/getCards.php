<?php
require __DIR__ . '/../repo/CardRepo.php';
// Allow JSON requests
header("Content-Type: application/json");
$boardId = $_GET['boardId'] ?? null;

if($boardId === null) {
    echo json_encode([
        "success" => false,
        "error" => "boardId not provided"
    ]);
    exit;    
}

try {
    $cardRepo = new CardRepo();
    $cards = $cardRepo->getAllCards($boardId); 

    echo json_encode([
        "success" => true,
        "cards" => $cards
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
?>