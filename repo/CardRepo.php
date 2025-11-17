<?php
//DONT NEED?
declare(strict_types=1);
class CardRepo {

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
    public function addCard($name) { //adds Card
      try {
        $conn = $this->connect();
        $stmt = $conn->prepare("INSERT INTO Card (name) VALUES (:name)");
        $stmt->execute([
          ':name' => $name
        ]);

        //echo "Record added successfully. ";
        return (int)$conn->lastInsertId();
      } catch(PDOException $error) {
        return $error->getMessage();
      }    
    }

    // UPDATE
    public function updateCard($id, $name) {
      try {
        $conn = $this->connect();
        $stmt = $conn->prepare("UPDATE Card SET name = :name WHERE id = :id");
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
    public function getAllCards($boardId) : array {
      try {
        $conn = $this->connect();
        $stmt = $conn->prepare("SELECT id, name, board_id FROM Card WHERE board_id = :boardId");
        $stmt->bindParam(":boardId", $boardId);
        $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      } catch(PDOException $error) {
          throw new Exception("Database error: " . $error->getMessage());
      } 
    }

    // DELETE
    public function deleteCard($id) {
      try {
        $conn = $this->connect();

        $conn->beginTransaction(); // running as transaction ensures that queries run together
        $stmtTask = $conn->prepare("DELETE FROM Task WHERE card_id = :id");
        $stmtTask->execute([
          ':id' => $id
        ]);
        $stmtCard = $conn->prepare("DELETE FROM Card WHERE id = :id");
        $stmtCard->execute([
          ':id' => $id
        ]);
        $conn->commit();
        return true;
      } catch(PDOException $error) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
          return $error->getMessage();
      } 
    }
}

