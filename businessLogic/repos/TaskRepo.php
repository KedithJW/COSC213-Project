<?php
//DONT NEED?
declare(strict_types=1);
class TaskRepo {

  public function addTask($name, $boardId) : int { //adds Task and returns Id
    $host = "localhost";
    $port = 8889;
    $dbname = "213Project";
    $username = "root";
    $password = "root";

    try {
      $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
      $conn = new PDO($dsn, $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $stmt = $conn->prepare("INSERT INTO Task (id, name, board_id) VALUES (:id, :name, :board_id)");
      $stmt->bindParam(':id', $id);
      $stmt->bindParam(':name', $taskName);
      $stmt->bindParam(':board_id', $board_id);

      $id = null;
      $taskName = $name;
      $board_id = $boardId;
      $stmt->execute();

      echo "Record added successfully. ";
      return (int)$conn->lastInsertId();
    } catch(PDOException $error) {
      echo $error->getMessage();
    }    
  }

    public static function getTasks($boardId) : array {
    $host = "localhost";
    $port = 8889;
    $dbname = "213Project";
    $username = "root";
    $password = "root";

    try {
      $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
      $conn = new PDO($dsn, $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $stmt = $conn->prepare("SELECT * FROM Task WHERE board_id = $boardId");
      $stmt->execute();

      $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $rows = $stmt->fetchAll();
      foreach($rows as $row) {
        $taskParameters = [];
        $taskParameters[] = $row['id'];
        $taskParameters[] = $row['name'];
        $taskParameters[] = $row['board_id'];
        $tasksData[] = $taskParameters;
      }
      echo "Records fetched" . "<br>";
      return $tasksData;
    } catch(PDOException $error) {
      echo $error->getMessage();
    } 
    }
}

