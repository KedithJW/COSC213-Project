<?php
require_once __DIR__ . '/../repos/BoardRepo.php';
class Board {
  private $board_id;
  private $name;
  private $tasks = [];

  public function __construct($name, $id = null) {
    $boardRepo = new BoardRepo();
    if($id === null) {
      $this->board_id = $boardRepo->addBoard($name);
      $this->name = $name;
    }
    else {
      $this->board_id = (int)$id;
      $this->name = $name;

    }
  }

  public function getBoard(): string {
    return $this->name;
  }

  public static function getBoards() : array { //should return an array of board objects
    $boardRepo = new BoardRepo();
    $boardData = $boardRepo->getBoards(); // assigns associative array... now use to instantiate task objects
    foreach($boardData as $boardParameters) {
      $boardObject = new Board($boardParameters[1], $boardParameters[0]);
      $boardObjects[] = $boardObject; //add
    }
      return $boardObjects;  
  }
  // won't need below functions?
  // public function getTasks(): array {
  //   return $this->tasks;
  // }

  // public function addTask($task) {
  //   array_push($this->tasks, $task);
  // }
}