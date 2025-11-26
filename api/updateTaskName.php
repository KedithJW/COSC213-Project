<?php
require __DIR__ . '/../repo/TaskRepo.php';

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
$taskId = $data['taskid'];
$taskName = $data['taskname'] ?? null; 

// Basic validation
if (!$taskName || !$taskId) {
    http_response_code(400);
    echo json_encode(["error" => "Missing task info"]);
    exit;
}

////// now pass to repo
try {
    $taskRepo = new TaskRepo(); 
    $taskRepo->updateTaskName($taskId, $taskName);

    // Example: save or process the task (for now just respond)
    $response = [
        "success" => true,
        "message" => "Task updated successfully",
        "taskName" => $taskName,
        "taskId" => $taskId
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