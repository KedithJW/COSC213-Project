<?php
require_once __DIR__ . '/../repos/TaskRepo.php';
class Task {
  private int $task_id;
  private int $board_id;
  private string $name;
  //private boolean $completed;

  public function __construct($name, $boardId, $id = null) { //id optional... no id when new task, provide id when retrieving existing task
    $taskRepo = new TaskRepo();
    if($id === null) {
      $this->task_id = $taskRepo->addTask($name, $boardId); //adds task to db AND assigns id because addTask() returns id
      $this->board_id = $boardId;
      $this->name = $name;
    }
    else {
      $this->task_id = (int)$id;
      $this->board_id = $boardId;
      $this->name = $name;
    }
  }

    public function getTask() : string {
      return "{$this->task_id}: {$this->name}";
    }

    public static function getTasks($boardId) : array { //should return an array of task objects
      $taskRepo = new TaskRepo();
      $taskData = $taskRepo->getTasks($boardId); // assigns associative array... now use to instantiate task objects
      foreach($taskData as $taskParameters) {
        $taskObject = new Task($taskParameters[1], $taskParameters[2], $taskParameters[0]);
        $taskObjects[] = $taskObject; //add
      }
      return $taskObjects;

      //move to taskrepo
      // $host = "localhost";
      // $port = 8889;
      // $dbname = "213Project";
      // $username = "root";
      // $password = "root";
      
      // try {
      //   echo "Task getting tasks...";
      //   $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
      //   $conn = new PDO($dsn, $username, $password);
      //   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //   $stmt = $conn->prepare("SELECT name FROM Task");
      //   $stmt->execute();

      //   $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
      //   $rows = $stmt->fetchAll();
              
      //   return $rows;
      // } catch(PDOException $error) {
      //   echo $error->getMessage();
      // }  
    }
}

//class TaskRepository {

  // $host = "localhost";
  // $databaseName = "213Project";
  // $username = "root";
  // $password = "root";
  
  // try {
  //   $conn = new PDO("mysql:host=$host;dbname=$databaseName", $username, $password);
  //   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //   $sql = 
  //   $conn->exec($sql);
  //   echo "Record added successfully";
  // } catch(PDOException $error) {
  //   echo $sql . "<br>" . $e->getMessage();
  // }

//   private PDO $conn;

//   public function __construct(PDO $conn) {
//     $this->conn = $conn;
//   }

//   // CREATE
//   public function addTask(string $name, int $board_id): bool {
//     $sql = "INSERT INTO Task (name, board_id, completed) VALUES (:name, :board_id, false)";
//     $stmt = $this->conn->prepare($sql);
//     return $stmt->execute([
//       ':name' => $name,
//       ':board_id' => $board_id
//     ]);
//   }

//   // READ (get all tasks for a board)
//   public function getTasksByBoard(int $board_id): array {
//     $sql = "SELECT * FROM Task WHERE board_id = :board_id";
//     $stmt = $this->conn->prepare($sql);
//     $stmt->execute([':board_id' => $board_id]);
    
//     $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     $tasks = [];
//     foreach ($rows as $row) {
//       $task = new Task($row['name']);
//       $tasks[] = $task;
//     }
//     return $tasks;
//   }

//   // READ single task
//   public function getTaskById(int $task_id): ?Task {
//     $sql = "SELECT * FROM Task WHERE task_id = :id";
//     $stmt = $this->conn->prepare($sql);
//     $stmt->execute([':id' => $task_id]);
//     $row = $stmt->fetch(PDO::FETCH_ASSOC);
//     return $row ? new Task($row['name']) : null;
//   }

//   // UPDATE (mark task completed)
//   public function markCompleted(int $task_id): bool {
//     $sql = "UPDATE Task SET completed = true WHERE task_id = :id";
//     $stmt = $this->conn->prepare($sql);
//     return $stmt->execute([':id' => $task_id]);
//   }

//   // DELETE
//   public function deleteTask(int $task_id): bool {
//     $sql = "DELETE FROM Task WHERE task_id = :id";
//     $stmt = $this->conn->prepare($sql);
//     return $stmt->execute([':id' => $task_id]);
//   }
// }

