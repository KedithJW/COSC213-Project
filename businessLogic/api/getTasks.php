<?php
require __DIR__ . '/../services/TaskService.php';

$taskService = new TaskService();

echo "getTasks: Getting tasks...<br>";
foreach($taskService->getTasks(1) as $task) {
    //echo $task['name'] . PHP_EOL; // want to work with task objects not arrays
    echo $task->getTask() . "<br>";
}