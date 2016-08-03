<?php
  require_once "base_function.php";
  f_dbConnect();
  if(!isset($_COOKIE['user'])){header('Location: tac_stats.php');}
  $type = $_GET['type'];
  
  echo f_statCount($type);

?>