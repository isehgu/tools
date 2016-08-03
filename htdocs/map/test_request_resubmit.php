<?php

    require_once "base_function.php";
    require_once("models/config.php");
    global $loggedInUser;
    f_dbConnect();

    $group_id = $_GET['group_id'];
    $test_detail = f_getCompletedTestFromGroupId($group_id);

    $test_id   = $test_detail['test_id'];
    $label      = $test_detail['label'];
    $release_id = $test_detail['release_id'];
    $template_id = $test_detail['template_id'];
    $user       = fetchUserDetails($loggedInUser->username);
    $user_id    = $user['id'];

    $host = 'tc-tac01.test.ise.com';
    $port = 18900;

    f_dbConnect();

    $message = array();
    $message['header']  = array(
        'type'        => 'test_action',
        'uid'         => $user_id,
        'action'      => 'request',
        'release_id'  => $release_id,
        'template_id' => $template_id,
        'label'       => $label
        );
    $message['body'][] = array('test_id'=>$test_id,'group_id'=>$group_id);


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
    //echo $send_msg;

    $socket = socket_create(AF_INET,SOCK_STREAM,0) or die("Could not create socket\n");
    $result = socket_connect($socket,$host,$port) or die("Could not connect to M.A.P. Server\n");

    socket_write($socket,$send_msg,strlen($send_msg)) or die("Could not send json data to M.A.P. Server\n");
    $result = socket_read($socket,1024) or die("Could not read from M.A.P. Server\n");
    socket_close($socket);

    //$result = 'not ok';
    if($result == 'ok'){echo 'ok';}
    else {echo "Action unsuccessful: $result";}
?>
