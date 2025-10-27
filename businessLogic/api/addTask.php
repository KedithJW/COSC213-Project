<?php
require __DIR__ . '/../services/BoardService.php';
require_once __DIR__ . '/../services/TaskService.php';

$boardService = new BoardService();
$board = $boardService->createBoard("demo");

$taskService = new TaskService();

$input = readline("Add task? (y/n): ");
while($input == "y") {
  $name = readline("Task name: ");
  $task = $taskService->createTask($name);
  $boardService->addTask($board, $task);
  $input = readline("Add task? (y/n): ");
}
echo $board->getBoard() . " tasks:" . PHP_EOL;
foreach($board->getTasks() as $task) {
  echo $task->getTask() . PHP_EOL;
}
