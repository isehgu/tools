<?php
	require_once "base_function.php";
  f_dbConnect();
  $rid = $_GET['rid'];
  $action = $_GET['action'];
	$pwd = $_GET['pwd'];
  if(!isset($_COOKIE['user'])){header('Location: tac_stats.php');}
	
	$user = $_COOKIE['user'];
  $uid = f_getIdfromUser($user);
	
  //echo $rid ."----".$action;
  if(($action == 'cancel') || ($pwd == 'scroller'))
	{
		$message = array();
		$message['header']['type'] = 'test action';
		$message['header']['uid'] = $uid;
		$content = array('request_id'=>$rid,'action'=>$action);
		$message['body'][] = $content;
		
		$host = "localhost";
		$port = "42448";
		
		$send_msg = json_encode($message);
		
		$socket = socket_create(AF_INET,SOCK_STREAM,0) or die("Could not create socket\n");
		$result = socket_connect($socket,$host,$port) or die("Could not connect to T.A.C Server\n");
		
		
		socket_write($socket,$send_msg,strlen($send_msg)) or die("Could not send json data to T.A.C Server\n");
		
		$result = socket_read($socket,1024) or die("Could not read from T.A.C Server\n");
		
		socket_close($socket);
		if(($result == 'ok')){echo 'ok';}
		else {echo "Action unsuccessful: $result";}
		
		//echo $send_msg;
		//echo 'ok';
	}
	else echo "Wrong Password";
  
?>