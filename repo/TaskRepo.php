<?php
//DONT NEED?
declare(strict_types=1);
class TaskRepo {

    private function connect() : PDO {
      $host = "localhost";
      $dbname = "213Project";
      $username = "root";
      $password = "root";

      $dsn = "mysql:host=$host;dbname=$dbname";
      $conn = new PDO($dsn, $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $conn;
    }

    // CREATE
    public function addTask($name, $cardId) { //adds Task
      try {
        $conn = $this->connect();
        $stmt = $conn->prepare("INSERT INTO Task (name, card_id) VALUES (:name, :card_id)");
        $stmt->execute([
          ':name' => $name,
          ':card_id' => $cardId
        ]);

        //echo "Record added successfully. ";
        return (int)$conn->lastInsertId();
      } catch(PDOException $error) {
        return $error->getMessage();
      }    
    }

    // UPDATE
    public function updateTaskName($id, $name) {
      try {
        $conn = $this->connect();
        $stmt = $conn->prepare("UPDATE Task SET name = :name WHERE id = :id");
        $stmt->execute([
          ':id' => $id,
          ':name' => $name
        ]);

        return true;
      } catch(PDOException $error) {
        return $error->getMessage();
      }    
    }

    // READ
    public function getAllTasks($cardId) : array {
      try {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT id, name, card_id, status, photo FROM Task WHERE card_id = :cardId");
        $stmt->bindParam(":cardId", $cardId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);      
      } catch(PDOException $error) {
          throw new Exception("Database error: " . $error->getMessage());
      } 
    }

    // DELETE
    public function deleteTask($id) {
      try {
        $conn = $this->connect();
        $stmt = $conn->prepare("DELETE FROM Task WHERE id = :id");
        $stmt->execute([
          ':id' => $id
        ]);
        return true;
      } catch(PDOException $error) {
          return $error->getMessage();
      } 
    }

    // UPDATE task status
    public function completeTask($id) {
      try {
        $conn = $this->connect();
        $stmt = $conn->prepare("UPDATE Task SET status = true WHERE id = :id");
        $stmt->execute([
          ':id' => $id
        ]);
        return true;
      } catch(PDOException $error) {
          return $error->getMessage();
      }  
    }

    public function updateTaskPhoto($taskId, $filename) {
    $conn = $this->connect();
    $stmt = $conn->prepare("UPDATE Task SET photo = :photo WHERE id = :taskId");
    $stmt->execute([
        ':photo' => $filename,
        ':taskId' => $taskId
    ]);
}

}

