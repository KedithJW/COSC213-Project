<?php
require_once __DIR__ . '/../models/Project.php';

class ProjectService {
  public function createProject($name): Project {
    $project = new Project($name);
    return $project;
  }
  public function addTask($project, $task): void {
    $project->addTask($task);
  }
}
