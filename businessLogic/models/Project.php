<?php
class Project {
  private $name;
  private $tasks = [];

  public function __construct($name) {
    $this->name = $name;
  }

  public function getProject(): string {
    return $this->name;
  }

  public function getTasks(): array {
    return $this->tasks;
  }

  public function addTask($task) {
    array_push($this->tasks, $task);
  }
}
