<?php

    require_once "base_function.php";
    require_once("models/config.php");
    global $loggedInUser;

    $task_id    = $_POST['task_id'];
    $user       = fetchUserDetails($loggedInUser->username);
    $user_id    = $user['id'];

    $host = 'tc-tac01.test.ise.com';
    $port = 18900;

    f_dbConnect();

    $message = array();
    $message['header']  = array(
        'type'        => 'task_action',
        'uid'         => $user_id,
        'action'      => 'subtask_update'
        );

    $temp_test = array();
    $temp_test['subtask'] = 'db_conversion';
    $temp_test['task_id'] = $task_id;
    $temp_test['status'] = 'passed';
    $message['body'][] = $temp_test;
    $temp_test = array();

    // Just for printing
    // foreach($message as $k1 => $v1){
    //     // echo $k1 . '->' . $v1 . ', ';
    //     if($k1 == 'header'){
    //         foreach($v1 as $k2 => $v2){
    //             echo $k2 . '->' . $v2 . ', ';
    //         }
    //     }
    //     if($k1 == 'body'){
    //         foreach($v1 as $k2 => $v2){
    //             echo $k2 . '->' . $v2 . ', ';
    //             foreach( $v2 as $k3 => $v3 ){
    //                 echo $k3 . '->' . $v3 . ', ';
    //             }
    //         }
    //     }
    // }

    // Send the message
    $send_msg = json_encode($message);
    $socket = socket_create(AF_INET,SOCK_STREAM,0) or die("Could not create socket\n");
    $result = socket_connect($socket,$host,$port) or die("Could not connect to M.A.P. Server\n");

    socket_write($socket,$send_msg,strlen($send_msg)) or die("Could not send json data to M.A.P. Server\n");
    $result = socket_read($socket,1024) or die("Could not read from M.A.P. Server\n");
    socket_close($socket);
    if($result == 'ok'){echo 'ok';}
    else {echo "Action unsuccessful: $result";}
?>
