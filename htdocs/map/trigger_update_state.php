<?php

    require_once "base_function.php";
    require_once("models/config.php");
    global $loggedInUser;

    $trigger_id = $_GET['trigger_id'];
    $state      = $_GET['state'];
    $user       = fetchUserDetails($loggedInUser->username);
    $user_id    = $user['id'];

    $host = 'tc-tac01.test.ise.com';
    $port = 18900;

    $message = array();
    $message['header']  = array(
        'type'   => 'trigger_action',
        'uid'    => $user_id,
        'action' => 'update_state'
    );

    $temp_test = array();
    $temp_test['trigger_id'] = $trigger_id;
    $temp_test['state']      = $state;
    $message['body'][] = $temp_test;

    // Send the message
    $send_msg = json_encode($message);
    $socket = socket_create(AF_INET,SOCK_STREAM,0) or die("Could not create socket\n");
    $result = socket_connect($socket,$host,$port) or die("Could not connect to MAP Server\n");

    socket_write($socket,$send_msg,strlen($send_msg)) or die("Could not send json data to MAP Server\n");
    $result = socket_read($socket,1024) or die("Could not read from MAP Server\n");
    socket_close($socket);
    if($result == 'ok'){echo 'ok';}
    else {echo "Action unsuccessful: $result";}


?>
