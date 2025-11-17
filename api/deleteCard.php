<?php
require __DIR__ . '/../repo/CardRepo.php';

// Allow JSON requests
header("Content-Type: application/json");

$rawData = file_get_contents("php://input"); // JSON uses this instead of $_POST

// Decode the JSON into a PHP associative array
$data = json_decode($rawData, true);
$cardId = $data['cardid'] ?? null; 

if (!$cardId) {
    http_response_code(400);
    echo json_encode(["error" => "Missing card id"]);
    exit;
}

////// now pass to repo

try {
    $cardRepo = new CardRepo();
    $result = $cardRepo->deleteCard($cardId);

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => $result]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error: " . $e->getMessage()]);
}
exit;
?>