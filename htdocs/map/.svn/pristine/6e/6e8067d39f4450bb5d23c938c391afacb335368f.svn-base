<?php

    require_once "base_function.php";
    require_once("models/config.php");
    global $loggedInUser;

    $trigger_id = $_GET['trigger_id'];
    $value      = $_GET['value'];
    $label      = $_GET['label'];
    $user       = fetchUserDetails($loggedInUser->username);
    $user_id    = $user['id'];

    $host = 'tc-tac01.test.ise.com';
    $port = 18900;

    f_dbConnect();

    $app = '';
    $sql_query = "SELECT trigger_app FROM event_trigger WHERE trigger_id=$trigger_id";
    $result = $db->query($sql_query) or die($db->error);
    while($row = $result->fetch_assoc()){
        $app = $row['trigger_app'];
    }

    $message = array();
    $message['header']  = array(
        'type'   => 'trigger_action',
        'uid'    => $user_id,
        'action' => 'execute',
        'label'  => $label,
    );
    $temp_test = array();
    $temp_test['trigger_id']   = $trigger_id;
    $temp_test['release_name'] = $value;
    $temp_test['app']          = $app;
    $message['body'][]         = $temp_test;

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
    $result = socket_connect($socket,$host,$port) or die("Could not connect to MAP Server\n");

    socket_write($socket,$send_msg,strlen($send_msg)) or die("Could not send json data to MAP Server\n");
    $result = socket_read($socket,1024) or die("Could not read from MAP Server\n");
    socket_close($socket);
    if($result == 'ok'){echo 'ok';}
    else {echo "Action unsuccessful: $result";}
?>
