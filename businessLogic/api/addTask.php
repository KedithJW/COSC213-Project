<?php
require __DIR__ . '/../services/ProjectService.php';
require_once __DIR__ . '/../services/TaskService.php';

$projectService = new ProjectService();
$project = $projectService->createProject("demo");

$taskService = new TaskService();

$input = readline("Add task? (y/n): ");
while($input == "y") {
  $name = readline("Task name: ");
  $task = $taskService->createTask($name);
  $projectService->addTask($project, $task);
  $input = readline("Add task? (y/n): ");
}
echo $project->getProject() . " tasks:" . PHP_EOL;
foreach($project->getTasks() as $task) {
  echo $task->getTask() . PHP_EOL;
}
