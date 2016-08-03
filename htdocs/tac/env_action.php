<?php
	require_once "base_function.php";
  f_dbConnect();
	
  $eid = $_GET['eid'];
  $action = $_GET['action'];
	$reason = $_GET['reason'];
	if(!isset($_COOKIE['user'])){header('Location: tac_stats.php');}
  
  $user = $_COOKIE['user'];
  $uid = f_getIdfromUser($user);
  
  //echo $rid ."----".$action;
  
  $message = array();
  $message['header']['type'] = 'env action';
	$message['header']['reason'] = $reason;
	$message['header']['uid'] = $uid;
  $message['body'][] = array('env_id'=>$eid,'action'=>$action);
  
  $host = "localhost";
	$port = "42448";
  
  $send_msg = json_encode($message);
  
	$socket = socket_create(AF_INET,SOCK_STREAM,0) or die("Could not create socket\n");
	$result = socket_connect($socket,$host,$port) or die("Could not connect to T.A.C Server\n");
  
  
  socket_write($socket,$send_msg,strlen($send_msg)) or die("Could not send json data to T.A.C Server\n");
	
	$result = socket_read($socket,1024) or die("Could not read from T.A.C Server\n");
	
	socket_close($socket);
  if($result == 'ok'){echo 'ok';}
  else {echo "Action unsuccessful: $result";}
  
  //echo $send_msg;
	//echo 'ok';
?>