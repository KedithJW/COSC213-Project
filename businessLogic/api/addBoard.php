<?php
require_once __DIR__ . '/../services/BoardService.php';

//app
$input = readline("Create board? (y/n): ");
if($input == "y") {
  $name = readline("Board Name: ");
  $boardService = new boardService();
  
  $board = $boardService->createBoard($name); //takes input, passes to pS
}
else
  return;

echo "Your boards: " . PHP_EOL;
echo $board->getBoard() . PHP_EOL;
