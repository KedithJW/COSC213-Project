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
$cardId = $data['cardid'];
$cardName = $data['cardname'] ?? null; 

// Basic validation
if (!$cardName || !$cardId) {
    http_response_code(400);
    echo json_encode(["error" => "Missing card info"]);
    exit;
}

////// now pass to repo
try {
    $cardRepo = new CardRepo(); 
    $cardRepo->updateCard($cardId, $cardName);

    // Example: save or process the card (for now just respond)
    $response = [
        "success" => true,
        "message" => "card updated successfully",
        "cardName" => $cardName
    ];

    // Return JSON response
    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Server error: " . $e->getMessage()
    ]);
}
?>