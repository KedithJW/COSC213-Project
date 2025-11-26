<?php
require_once '../repo/TaskRepo.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $taskId = $_POST['taskid'] ?? null;
    if (!$taskId) {
        echo json_encode(["success" => false, "error" => "Task ID not provided"]);
        exit;
    }

    $uploadDir = __DIR__ . "/../public/uploads/tasks/";
    $ext = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));

    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
        $newFilename = $taskId . '_' . time() . '.' . $ext;
        $targetFile  = $uploadDir . $newFilename;
    error_log("Upload error code: " . $_FILES['photo']['error']);
    error_log("Temp file: " . $_FILES['photo']['tmp_name']);
    error_log("Target file: " . $targetFile);

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $taskRepo = new TaskRepo();
            $taskRepo->updateTaskPhoto($taskId, $newFilename);

            echo json_encode([
                "success" => true,
                "photo"   => "uploads/tasks/" . $newFilename,
                "taskid"  => $taskId
            ]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to save photo"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Only JPG, JPEG, and PNG allowed"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}
?>