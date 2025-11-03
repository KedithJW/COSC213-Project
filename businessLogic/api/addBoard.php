<?php
require_once __DIR__ . '/../services/BoardService.php';
require_once __DIR__ . '/../services/TaskService.php';

$boardService = new boardService();

$board = $boardService->createBoard("board1");
echo $board->getBoard() . PHP_EOL;

