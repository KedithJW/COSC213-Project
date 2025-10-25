<?php
require_once __DIR__ . '/../services/ProjectService.php';

//app
$input = readline("Create project? (y/n): ");
if($input == "y") {
  $name = readline("Project Name: ");
  $projectService = new ProjectService();
  
  $project = $projectService->createProject($name); //takes input, passes to pS
}
else
  return;

echo "Your projects: " . PHP_EOL;
echo $project->getProject() . PHP_EOL;
