<?php
  require_once "base_function.php";
	f_dbConnect();
	if(!isset($_COOKIE['user'])){header('Location: tac_stats.php');}
  //This page should take one $_POST['type'] where type is test or suite.
  //Then echo out the json_encoded array of test cases or test suites.
  //Data will be extracted from database.
  $type = $_POST['type'];
  $result = f_tableDisplay($type);
  echo $result;

?>