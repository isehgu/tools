<?php
  require_once "base_function.php";
	f_dbConnect();
  
  $user = $_POST['username'];
  if(f_userAuthen($user))
  {
    setcookie("user", $user, time()+60*60*24*180); //6 months expiration on that cookie
    header('Location: index.php');
  }
  else
  {
    //echo "wrong user";
    header('Location: tac_stats.php');
  }

?>