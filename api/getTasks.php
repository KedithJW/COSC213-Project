<?php
require __DIR__ . '/../repo/TaskRepo.php';

// Allow JSON requests
header("Content-Type: application/json");

try {
    $taskRepo = new TaskRepo();
    $tasks = $taskRepo->getAllTasks(); 

    echo json_encode([
        "success" => true,
        "tasks" => $tasks
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
?>