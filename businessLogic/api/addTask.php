<?php
require __DIR__ . '/../services/BoardService.php';
require_once __DIR__ . '/../services/TaskService.php';

$taskService = new TaskService();


  $task = $taskService->createTask("Test Boards", 1);
  echo $task->getTask() . PHP_EOL;

// $host = "localhost";
//   $databaseName = "213Project";
//   $username = "root";
//   $password = "root";
  
//   try {
//     $conn = new PDO("mysql:host=$host;dbname=$databaseName", $username, $password);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     $sql = "INSERT INTO Task (board_id, name)
//             VALUES (1, 'big ol task')";
//     $conn->exec($sql);
//     echo "Record added successfully";
//   } catch(PDOException $error) {
//     echo $sql . "<br>" . $e->getMessage();
//   }