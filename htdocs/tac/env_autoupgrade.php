<?php
	require_once "base_function.php";
  f_dbConnect();
  $env_upgrade_id = $_GET['env_upgrade_id'];
  $status = $_GET['status'];
	
  if(!isset($_COOKIE['user'])){header('Location: tac_stats.php');}
	
	$user = $_COOKIE['user'];
  $uid = f_getIdfromUser($user);

	$message = array();
	$message['header']['type'] = 'auto upgrade';
	$message['header']['uid'] = $uid;
	$content = array('env_upgrade_id'=>$env_upgrade_id,'status'=>$status);
	$message['body'][] = $content;
	
	$host = "localhost";
	$port = "42448";
	
	$send_msg = json_encode($message);
	
	$socket = socket_create(AF_INET,SOCK_STREAM,0) or die("Could not create socket\n");
	$result = socket_connect($socket,$host,$port) or die("Could not connect to T.A.C Server\n");
	
	
	socket_write($socket,$send_msg,strlen($send_msg)) or die("Could not send json data to T.A.C Server\n");
	
	$result = socket_read($socket,1024) or die("Could not read from T.A.C Server\n");
	
	socket_close($socket);
	if($result == 'ok'){header('Location: upgrade.php');}
  else {echo "Test Request unsuccessful: $result";}
	
	//echo $send_msg;
	//echo 'ok';


?>