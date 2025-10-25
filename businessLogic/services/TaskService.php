<?php
require_once __DIR__ . '/../models/Task.php';

class TaskService {
  public function createTask($name): Task {
    $task = new Task($name);
    return $task;
  }
}
