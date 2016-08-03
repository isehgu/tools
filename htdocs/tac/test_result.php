<?php
  require_once "base_function.php";
  f_dbConnect();
  if(!isset($_COOKIE['user'])){header('Location: tac_stats.php');}
  $rid = $_GET['rid'];
  $report = f_getReport($rid);
  echo file_get_contents($report);

?>