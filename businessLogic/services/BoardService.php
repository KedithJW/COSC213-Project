<?php
require_once __DIR__ . '/../models/Board.php';

class BoardService {
  public function createBoard($name): Board {
    $board = new Board($name);
    return $board;
  }
  public function addTask($board, $task): void {
    $board->addTask($task);
  }
}

