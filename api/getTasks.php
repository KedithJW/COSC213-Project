<?php
require __DIR__ . '/../repo/TaskRepo.php';
// Allow JSON requests
header("Content-Type: application/json");
$cardId = $_GET['cardId'] ?? null;

if($cardId === null) {
    echo json_encode([
        "success" => false,
        "error" => "cardId not provided"
    ]);
    exit;    
}

try {
    $taskRepo = new TaskRepo();
    $tasks = $taskRepo->getAllTasks($cardId); 

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