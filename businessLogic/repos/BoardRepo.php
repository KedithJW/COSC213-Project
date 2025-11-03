<?php
declare(strict_types=1);
class BoardRepo {

  public function addBoard($name) : int { //adds Board and returns Id
    $host = "localhost";
    $port = 8889;
    $dbname = "213Project";
    $username = "root";
    $password = "root";

    try {
      $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
      $conn = new PDO($dsn, $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $stmt = $conn->prepare("INSERT INTO Board (id, name) VALUES (:id, :name)");
      $stmt->bindParam(':id', $id);
      $stmt->bindParam(':name', $boardName);

      $id = null;
      $boardName = $name;
      $stmt->execute();

      echo "Board added successfully. ";
      return (int)$conn->lastInsertId();
    } catch(PDOException $error) {
      echo $error->getMessage();
    }    
  }

    public static function getBoards() : array {
    $host = "localhost";
    $port = 8889;
    $dbname = "213Project";
    $username = "root";
    $password = "root";

    try {
      $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
      $conn = new PDO($dsn, $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $stmt = $conn->prepare("SELECT * FROM Board");
      $stmt->execute();

      $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $rows = $stmt->fetchAll();
      foreach($rows as $row) {
        $boardParameters = [];
        $boardParameters[] = $row['id'];
        $boardParameters[] = $row['name'];
        $boardsData[] = $boardParameters;
      }
      echo "Records fetched" . "<br>";
      return $boardsData;
    } catch(PDOException $error) {
      echo $error->getMessage();
    } 
    }
}

