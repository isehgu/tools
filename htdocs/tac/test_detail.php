<?php
//This file updates test comment

	require_once "base_function.php";
  f_dbConnect();
  $rid = $_POST['rid'];
	
  if(!isset($_COOKIE['user'])){header('Location: tac_stats.php');}
	
  f_displayTestDetail($rid); //this function would echo out the html
?>