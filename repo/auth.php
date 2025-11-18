<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
} 
  /* Makes sure the user is logged in if not kicks them to the login page */
?>
