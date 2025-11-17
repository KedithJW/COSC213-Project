<?php
require __DIR__ . '/../repo/TaskRepo.php';

// Allow JSON requests
header("Content-Type: application/json");

$rawData = file_get_contents("php://input"); // JSON uses this instead of $_POST

// Decode the JSON into a PHP associative array
$data = json_decode($rawData, true);
$taskId = $data['taskid'] ?? null; 

if (!$taskId) {
    http_response_code(400);
    echo json_encode(["error" => "Missing task id"]);
    exit;
}

////// now pass to repo

try {
    $taskRepo = new TaskRepo();
    $result = $taskRepo->deleteTask($taskId);

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