<?php
require __DIR__ . '/../repo/CardRepo.php';

// Allow JSON requests
header("Content-Type: application/json");

try {
    $cardRepo = new CardRepo();
    $cards = $cardRepo->getAllCards(); 

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