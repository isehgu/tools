<?php
  require_once "base_function.php";
  f_dbConnect();
  if(!isset($_COOKIE['user'])){header('Location: tac_stats.php');}
  $rid = $_GET['rid'];
  
  echo f_historyRow($rid); //return the history table row html for that rid
  
?>

