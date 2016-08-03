<?php

    require_once "base_function.php";
    require_once("models/config.php");
    global $loggedInUser;

    $trigger_id       = $_GET['trigger_id'];
    $git_branch       = $_GET['new_git_branch'];
    $code_track       = $_GET['new_code_track'];
    $trigger_template = $_GET['new_trigger_template'];
    $trigger_event    = $_GET['new_trigger_event'];
    $target_template  = $_GET['new_target_template'];
    $new_test_list    = $_GET['new_test_list'];
    $user             = fetchUserDetails($loggedInUser->username);
    $user_id          = $user['id'];

    $suite_ids = array();
    $test_ids = json_decode($new_test_list);
    // $test_ids = f_get_test_ids_for_trigger($trigger_id);
    if(count($test_ids) > 0){
        $target_task = 'Deploy,Test';
    }
    else{
        $target_task = 'Deploy';
    }

    $template_mapping_inv = f_get_template_mapping_inv();
    $event_mapping_inv    = f_get_event_mapping_inv();

    $trigger_template = $template_mapping_inv[$trigger_template];
    $target_template  = $template_mapping_inv[$target_template];
    $trigger_event    = strtolower( str_replace(' ', '_', $trigger_event) );
    $trigger_event    = $event_mapping_inv[$trigger_event];

    $host = 'tc-tac01.test.ise.com';
    $port = 18900;

    f_dbConnect();

    $action = $trigger_id=='new'?'create':'update';

    if($git_branch && !$code_track){
        $app = 'git';
    }
    else if(!$git_branch && $code_track){
        $app = 'Core';
    }

    $message = array();
    $message['header']  = array(
        'type'        => 'trigger_action',
        'uid'         => $user_id,
        'action'      => $action
        );

    $temp_test = array();
    if($action == 'update'){
        $temp_test['trigger_id']          = $trigger_id;
    }
    $temp_test['state']               = 'active';
    $temp_test['type']                = 'default';
    $temp_test['trigger_template_id'] = $trigger_template;
    $temp_test['app']                 = $app;
    $temp_test['git_branch']          = $git_branch;
    $temp_test['code_track']          = $code_track;
    $temp_test['trigger_event_id']    = $trigger_event;
    $temp_test['target_template_id']  = $target_template;
    $temp_test['target_task']         = $target_task;
    $temp_test['test_list']           = $test_ids;
    $temp_test['suite_list']          = $suite_ids;

    $message['body'][] = $temp_test;
    $temp_test = array();

    // Just for printing
    // foreach($message as $k1 => $v1){
    //     if($k1 == 'header'){
    //         foreach($v1 as $k2 => $v2){
    //             echo $k2 . '->' . $v2 . ', ';
    //         }
    //     }
    //     if($k1 == 'body'){
    //         foreach($v1 as $k2 => $v2){
    //             foreach( $v2 as $k3 => $v3 ){
    //                 echo $k3 . '3->' . $v3 . ', ';
    //                 if($k3=='test_list'){
    //                     foreach( $v3 as $i ){
    //                         echo $i;
    //                     }
    //                 }
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
