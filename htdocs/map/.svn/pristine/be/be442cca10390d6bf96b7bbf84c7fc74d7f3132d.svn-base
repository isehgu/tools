<?php

    require_once "base_function.php";


    $type = $_GET['field'];
    $def_value = $_GET['def_value'];

    if($type == 'git_branch'){
        echo f_create_trigger_release_branch_textbox($def_value);
    }
    if($type == 'code_track'){
        echo f_create_code_track_select($def_value);
    }
    elseif($type == 'trigger_template'){
        echo f_create_trigger_template_select($def_value);
    }
    elseif($type == 'trigger_event'){
        echo f_create_trigger_event_select($def_value);
    }
    elseif($type == 'target_template'){
        echo f_create_target_template_select($def_value);
    }
    elseif($type == 'deploy'){
        // echo f_create_deploy_select($def_value);
        echo $def_value;
    }
    elseif($type == 'test'){
        // echo f_create_deploy_select($def_value);
        echo "
            <span class='label' id=''>
                $def_value (click to edit tests)
            </span>";
    }

?>
