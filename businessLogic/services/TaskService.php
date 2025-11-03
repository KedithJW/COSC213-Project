<?php
require_once __DIR__ . '/../models/Task.php';

class TaskService {
  public function createTask($name, $boardId): Task {
    $task = new Task($name, $boardId);
    return $task; //returns task to addTask
  }

  public function getTasks($boardId) : array {
    echo "TaskService getting tasks..." . "<br>";
    return Task::getTasks($boardId);
  }
}
