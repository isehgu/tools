<?php
  require_once "base_function.php";
	f_dbConnect();
  setcookie("user", "", time()-3600);
  header('Location: tac_stats.php');
?>