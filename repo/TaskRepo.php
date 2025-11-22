<?php
//DONT NEED?
declare(strict_types=1);
class TaskRepo {

    private function connect() : PDO {
      $host = "localhost";
      $port = 8889;
      $dbname = "213Project";
      $username = "root";
      $password = "root";

      $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
      $conn = new PDO($dsn, $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $conn;
    }

    // CREATE
    public function addTask($name, $description ,$cardId) { //adds Task
      try {
        $conn = $this->connect();
        $stmt = $conn->prepare("INSERT INTO Task (name, card_id, description) VALUES (:name, :card_id, :description)");
        $stmt->execute([
          ':name' => $name,
          ':description' => $description,
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

    public function updateTaskDescription($id, $description) {
      try {
        $conn = $this->connect();
        $stmt = $conn->prepare("UPDATE Task SET description = :description WHERE id = :id");
        $stmt->execute([
          ':id' => $id,
          ':description' => $description
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
        $stmt = $conn->prepare("SELECT id, name, card_id, status, description FROM Task WHERE card_id = :cardId ORDER BY status");
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
}

