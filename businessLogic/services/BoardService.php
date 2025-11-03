<?php
require_once __DIR__ . '/../models/Board.php';

class BoardService {
  public function createBoard($name): Board {
    $board = new Board($name);
    return $board;
  }
  public function getBoards(): array {
    return Board::getBoards();
  }
}

// <?php
// require_once __DIR__ . '/../models/Task.php';

// class TaskService {
//   public function createTask($name): Task {
//     $task = new Task($name);
//     return $task; //returns task to addTask
//   }

//   public function getTasks() : array {
//     echo "TaskService getting tasks..." . "<br>";
//     return Task::getTasks();
//   }
// }