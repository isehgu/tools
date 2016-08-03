<?php
	require_once "base_function.php";
  f_dbConnect();
  $execution_ids = explode(',',$_POST['execution_ids']);
  $action = $_POST['action'];
  $uid = fetchUserDetails($loggedInUser->username);
  $uid = $uid['id'];
  if($action == 'cancel')
	{
		$message = array();
		$message['header']['type'] = 'test_action';
		$message['header']['uid'] = $uid;
		$message['header']['action'] = $action;
		$content = array();
		$temp_array = array();
		foreach($execution_ids as $eid)
		{
			$temp_array['execution_id'] = $eid;
			$message['body'][] = $temp_array;
		}
		$host = "tc-tac01.test.ise.com";
		$port = "18900";

		$send_msg = json_encode($message);

		//echo $send_msg;
		//echo 'ok';

		$socket = socket_create(AF_INET,SOCK_STREAM,0) or die("Could not create socket\n");
		$result = socket_connect($socket,$host,$port) or die("Could not connect to M.A.P. Server\n");


		socket_write($socket,$send_msg,strlen($send_msg)) or die("Could not send json data to M.A.P. Server\n");

		$result = socket_read($socket,1024) or die("Could not read from M.A.P. Server\n");

		socket_close($socket);

		if((trim($result) == 'ok')){echo 'ok';}
		else {echo "Action unsuccessful: $result";}
	}
	else echo "Invalid Action";

?>
