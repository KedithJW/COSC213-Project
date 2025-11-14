<?php
require __DIR__ . '/../repo/TaskRepo.php';

// api/addTask.php

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

$taskName = $data['taskname'] ?? null;
$cardId = $data['cardid'] ?? null; 

// Basic validation
if (!$taskName) {
    http_response_code(400);
    echo json_encode(["error" => "Missing task name"]);
    exit;
}
if (!$cardId) {
    http_response_code(400);
    echo json_encode(["error" => "Missing card id"]);
    exit;
}

////// now pass to repo
try {
    $taskRepo = new TaskRepo(); 
    $taskId = "task-" . $taskRepo->addTask($taskName, $cardId);

    echo $taskId;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Server error: " . $e->getMessage()
    ]);
}
?>