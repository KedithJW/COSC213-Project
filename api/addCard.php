<?php
require __DIR__ . '/../repo/CardRepo.php';

// Allow JSON requests
header("Content-Type: application/json");

$rawData = file_get_contents("php://input"); // JSON uses this instead of $_POST

// Decode the JSON into a PHP associative array
$data = json_decode($rawData, true);

if ($data === null) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid JSON received"]);
    exit;
}

$cardName = $data['cardname'] ?? null; 
$boardId = $data['boardid'] ?? null;
// Basic validation
if (!$cardName) {
    http_response_code(400);
    echo json_encode(["error" => "Missing card name"]);
    exit;
}
if (!$boardId) {
    http_response_code(400);
    echo json_encode(["error" => "Missing card id"]);
    exit;
}

////// now pass to repo
try {
    $cardRepo = new CardRepo(); 
    $cardId = "card-" . $cardRepo->addCard($cardName, (int)$boardId);
    echo json_encode([
        "cardId" => $cardId
    ]);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Server error: " . $e->getMessage()
    ]);
    exit;
}
?>