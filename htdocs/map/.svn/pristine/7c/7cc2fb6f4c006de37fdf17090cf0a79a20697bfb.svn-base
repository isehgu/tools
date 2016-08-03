<?php
    require_once("shared.php");

    /****************************************************************/
    /* Triggers Pages */
    /****************************************************************/

    function f_manageTriggers()
    {
        global $db;
        global $loggedInUser;

        if(!isUserLoggedIn()){
            echo "
                <div class='row' id=''>
                    <div class='small-12 columns' id='triggers_login_message'>
                        <p id=''>
                            Please log in to view your triggers or to add new triggers
                        </p>
                    </div>
                </div>";
            return;
        }

        $template_mapping = f_get_template_mapping();
        $event_mapping    = f_get_event_mapping();

        // Figure out if user is logged in and has permissions. Use disabled button if no permissions.
        $btn_disable = 'disabled'; // By default it should be disabled, until tests are selected
        $btn_tooltip = '';
        $create_btn_disable = '';

        if(!isUserLoggedIn()){
            $btn_disable = 'disabled';
            $exec_btn_tooltip       = 'You must be logged in first before executing triggers';
            $create_btn_tooltip     = 'You must be logged in first before creating triggers';
            $activate_btn_tooltip   = 'You must be logged in first before activating triggers';
            $inactivate_btn_tooltip = 'You must be logged in first before inactivating triggers';
            $edit_btn_tooltip       = 'You must be logged in first before editing triggers';
            $cancel_btn_tooltip     = 'You must be logged in first before modifying triggers';
            $submit_btn_tooltip     = 'You must be logged in first before modifying triggers';
        }
        else{
            $exec_btn_tooltip       = 'Select a trigger from the table to enable this execute button';
            $create_btn_tooltip     = 'Create new trigger';
            $activate_btn_tooltip   = 'This button will be enabled if an inactive trigger is selected';
            $inactivate_btn_tooltip = 'This button will be enabled if an active trigger is selected';
            $edit_btn_tooltip       = 'Select a trigger from the table to enable this edit button';
            $cancel_btn_tooltip     = 'This button will be enabled if a trigger is being edited';
            $submit_btn_tooltip     = 'This button will be enabled if a trigger is being edited';
        }

        $tip_attr  = "data-tooltip aria-haspopup='true'";
        $tip_class = "has-tip tip-top radius";
        $tip_gb    = 'Release branch to be applied in deployment template. Generally a System git branch.';
        $tip_ct    = 'Code track to be matched against. * are allowed. Please include all leading zeroes.'.
            ' Examples:<br>010.000.030<br>010.0.*<br>010.*.*<br>*.*.*';
        $tip_tr    = 'Template in which the trigger event occurs';
        $tip_te    = 'Event which will cause activation';
        $tip_ta    = 'Template in which deploy and/or test will occur';
        $tip_de    = 'Deployment in target template will always occur';
        $tip_ts    = 'Tests to run in target template after a successful deployment';
        echo "
            <div class='row wide-row triggers_action_buttons_row' id='view_triggers_row'>
                <div class='small-12 columns' id='view_triggers_actions_wrapper'>

                    <div class='small-5 columns id=''>
                        <input id='trigger_show_inactive_checkbox' type='checkbox' title='Show/hide deactivated triggers'>
                        <label for='trigger_show_inactive_checkbox' class='' title='Show/hide deactivated triggers'>
                            Show Inactive Triggers
                        </label>
                    </div>

                    <div class='small-1 columns triggers_actions_divs' id='triggers_create_button_div'>
                        <a href='#' class='button small radius expand $create_btn_disable triggers_actions_buttons'
                        id='manage_triggers_create_button' title='$create_btn_tooltip'>
                            Create
                        </a>
                    </div>

                    <div class='small-1 columns triggers_actions_divs' id='triggers_exec_button_div'>
                        <a href='#' class='button success small radius expand $btn_disable triggers_actions_buttons'
                        id='manage_triggers_exec_button' title='$exec_btn_tooltip'>
                            Execute
                        </a>
                    </div>

                    <div class='small-1 columns triggers_actions_divs' id='triggers_activate_button_div'>
                        <a href='#' class='button success small radius expand $btn_disable triggers_actions_buttons'
                        id='manage_triggers_activate_button' title='$activate_btn_tooltip'>
                            Activate
                        </a>
                    </div>

                    <div class='small-1 columns triggers_actions_divs' id='triggers_inactivate_button_div'>
                        <a href='#' class='button alert success small radius expand $btn_disable triggers_actions_buttons'
                        id='manage_triggers_inactivate_button' title='$inactivate_btn_tooltip'>
                            Inactivate
                        </a>
                    </div>

                    <div class='small-1 columns triggers_actions_divs' id='triggers_edit_button_div'>
                        <a href='#' class='button small radius expand $btn_disable triggers_actions_buttons'
                        id='manage_triggers_edit_button' title='$edit_btn_tooltip'>
                            Edit
                        </a>
                    </div>
                    <div class='small-1 columns triggers_actions_divs' id='triggers_cancel_button_div'>
                        <a href='#' class='button alert small radius expand $btn_disable triggers_actions_buttons'
                        id='manage_triggers_cancel_button' title='$cancel_btn_tooltip'>
                            Cancel
                        </a>
                    </div>
                    <div class='small-1 columns triggers_actions_divs' id='triggers_submit_button_div'>
                        <a href='#' class='button success small radius expand $btn_disable triggers_actions_buttons'
                        id='manage_triggers_submit_button' title='$submit_btn_tooltip'>
                            Submit
                        </a>
                    </div>
                    </div>
                </div>
            </div>
            <div class='row wide-row' id='view_triggers_row'>
                <div class='small-12 columns' id='view_triggers_table_div'>
                    <table id='view_triggers_table'>
                        <thead>
                            <tr>
                                <th width='20%' $tip_attr title='$tip_gb' class='$tip_class'>Release Branch</th>
                                <th width='15%' $tip_attr title='$tip_ct' class='$tip_class'>Code Track</th>
                                <th width='13%' $tip_attr title='$tip_tr' class='$tip_class'>Trigger Template</th>
                                <th width='17%' $tip_attr title='$tip_te' class='$tip_class'>Trigger Event</th>
                                <th width='13%' $tip_attr title='$tip_ta' class='$tip_class'>Target Template</th>
                                <th width='7%'  $tip_attr title='$tip_de' class='$tip_class'>Deploy</th>
                                <th width='15%' $tip_attr title='$tip_ts' class='$tip_class'>Tests
                                    <p id='triggers_click_to_view_tests_text'>
                                        (Click to View Tests)
                                    </p>
                                </th>
                            </tr>
                        </thead>
                        <tbody id='view_triggers_table_body' class='view_triggers_searchable'>";

        $sql_query = "SELECT * FROM event_trigger WHERE trigger_state='active' ORDER BY code_track DESC";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc()){

            $target_template  = $template_mapping[ $row['target_template_id'] ];
            if($loggedInUser->checkPermission($target_template)){

                $trigger_id       = $row['trigger_id'];
                $git_branch       = $row['git_branch'];
                $code_track       = $row['code_track'];
                $trigger_template = $template_mapping[ $row['trigger_template_id'] ];
                $trigger_event    = ucwords( str_replace('_', ' ', $event_mapping[ $row['trigger_event_id'] ] ) );
                $target_task      = $row['target_task'];

                $target_task_deploy = 'No';
                if(substr($target_task,0,6) == 'Deploy'){
                    $target_task_deploy = 'Yes';
                }

                if(!$git_branch){
                    $git_branch = 'NA';
                }

                echo "
                                <tr class='view_triggers_row' id='view_triggers_row_$trigger_id' data-ise-trigger-editing='false'
                                    data-ise-trigger-id='$trigger_id' data-ise-trigger-active='true'>
                                    <td data-ise-trigger-field='git_branch' id='trigger_data_git_branch_$trigger_id'>
                                        $git_branch
                                    </td>
                                    <td data-ise-trigger-field='code_track' id='trigger_data_code_track_$trigger_id'>
                                        $code_track
                                    </td>
                                    <td data-ise-trigger-field='trigger_template' id='trigger_data_trigger_template_$trigger_id'>
                                        $trigger_template
                                    </td>
                                    <td data-ise-trigger-field='trigger_event' id='trigger_data_trigger_event_$trigger_id'>
                                        $trigger_event
                                    </td>
                                    <td data-ise-trigger-field='target_template' id='trigger_data_target_template_$trigger_id'>
                                        $target_template
                                    </td>
                                    <td data-ise-trigger-field='deploy' id='trigger_data_deploy_$trigger_id'>
                                        $target_task_deploy
                                    </td>";

                $sql_query2 = "SELECT * FROM trigger_to_test WHERE trigger_id=$trigger_id";
                $result2 = $db->query($sql_query2) or die($db->error);
                $num_tests_suites = $result2->num_rows;

                echo "
                                    <td data-ise-trigger-field='test' id='trigger_data_test_$trigger_id'>
                                        <span class='label' id=''>
                                            $num_tests_suites
                                        </span>
                                    </td>
                                </tr>";
            }
        }

        echo "
                        </tbody>
                    </table>
                </div>
            </div>";
    }

    function f_viewAllTriggers()
    {
        global $db;

        $template_mapping = f_get_template_mapping();
        $event_mapping    = f_get_event_mapping();

        $tip_attr  = "data-tooltip aria-haspopup='true'";
        $tip_class = "has-tip tip-top radius";
        $tip_gb    = 'Release branch to be applied in deployment template. Generally a System git branch.';
        $tip_ct    = 'Code track to be matched against. * are allowed. Please include all leading zeroes.'.
            ' Examples:<br>010.000.030<br>010.0.*<br>010.*.*<br>*.*.*';
        $tip_tr    = 'Template in which the trigger event occurs';
        $tip_te    = 'Event which will cause activation';
        $tip_ta    = 'Template in which deploy and/or test will occur';
        $tip_de    = 'Deployment in target template will always occur';
        $tip_ts    = 'Tests to run in target template after a successful deployment';
        echo "
            <div class='row wide-row'>
                <div class='small-12 columns' id=''>
                    <table id='view_triggers_table'>
                        <thead>
                            <tr>
                                <th width='20%' $tip_attr title='$tip_gb' class='$tip_class'>Release Branch</th>
                                <th width='15%' $tip_attr title='$tip_ct' class='$tip_class'>Code Track</th>
                                <th width='13%' $tip_attr title='$tip_tr' class='$tip_class'>Trigger Template</th>
                                <th width='17%' $tip_attr title='$tip_te' class='$tip_class'>Trigger Event</th>
                                <th width='13%' $tip_attr title='$tip_ta' class='$tip_class'>Target Template</th>
                                <th width='7%'  $tip_attr title='$tip_de' class='$tip_class'>Deploy</th>
                                <th width='15%' $tip_attr title='$tip_ts' class='$tip_class'>Tests
                                    <p id='triggers_click_to_view_tests_text'>
                                        (Click to View Tests)
                                    </p>
                                </th>
                            </tr>
                        </thead>
                        <tbody id='' class=''>";

        $sql_query = "SELECT * FROM event_trigger ORDER BY code_track DESC";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc()){

            $target_template  = $template_mapping[ $row['target_template_id'] ];
            $trigger_id       = $row['trigger_id'];
            $git_branch       = $row['git_branch'];
            $code_track       = $row['code_track'];
            $trigger_template = $template_mapping[ $row['trigger_template_id'] ];
            $trigger_event    = ucwords( str_replace('_', ' ', $event_mapping[ $row['trigger_event_id'] ] ) );
            $target_task      = $row['target_task'];

            $target_task_deploy = 'No';
            if(substr($target_task,0,6) == 'Deploy'){
                $target_task_deploy = 'Yes';
            }

            if(!$git_branch){
                $git_branch = 'NA';
            }

            echo "
                            <tr class='' id='view_triggers_row_$trigger_id' data-ise-trigger-editing='false'
                                data-ise-trigger-id='$trigger_id'>
                                <td data-ise-trigger-field='git_branch' id='trigger_data_git_branch_$trigger_id'>
                                    $git_branch
                                </td>
                                <td data-ise-trigger-field='code_track' id='trigger_data_code_track_$trigger_id'>
                                    $code_track
                                </td>
                                <td data-ise-trigger-field='trigger_template' id='trigger_data_trigger_template_$trigger_id'>
                                    $trigger_template
                                </td>
                                <td data-ise-trigger-field='trigger_event' id='trigger_data_trigger_event_$trigger_id'>
                                    $trigger_event
                                </td>
                                <td data-ise-trigger-field='target_template' id='trigger_data_target_template_$trigger_id'>
                                    $target_template
                                </td>
                                <td data-ise-trigger-field='deploy' id='trigger_data_deploy_$trigger_id'>
                                    $target_task_deploy
                                </td>";

            $sql_query2 = "SELECT * FROM trigger_to_test WHERE trigger_id=$trigger_id";
            $result2 = $db->query($sql_query2) or die($db->error);
            $num_tests_suites = $result2->num_rows;

            echo "
                                <td data-ise-trigger-field='test' id='trigger_tests_view_only_table_cell'>
                                    <span class='label' id=''>
                                        $num_tests_suites
                                    </span>
                                </td>
                            </tr>";

        }

        echo "
                        </tbody>
                    </table>
                </div>
            </div>";
    }

    function f_create_trigger_release_branch_textbox($def_value){
        if($def_value == 'NA'){
            $def_value = '';
        }
        $return_content = "
            <div class='small-12 columns centered' id='git_branch_textbox_div'>
                <input  type='text' value='$def_value' id='git_branch_textbox'/>
            </div>
        ";
        return $return_content;
    }

    function f_create_code_track_select($def_value){
        f_dbConnect();
        global $db;

        if($def_value){
            $sections = explode('.', $def_value);
            $code_track_1   = $sections[0];
            $code_track_2   = $sections[1];
            $code_track_3   = $sections[2];
            $code_track_1_v = $code_track_1;
            $code_track_2_v = $code_track_2;
            $code_track_3_v = $code_track_3;
        }
        else{
            $sql_query = "SELECT version FROM release_registration ORDER BY version DESC LIMIT 1";
            $result = $db->query($sql_query) or die($db->error);
            while($row = $result->fetch_assoc()){
                $version = $row['version'];
                $code_track_1   = substr($version,0,3);
                $code_track_2   = substr($version,4,3);
                $code_track_3   = substr($version,8,3);
                $code_track_1_v = '';
                $code_track_2_v = '';
                $code_track_3_v = '';
            }
        }

        $return_content  = "
            <div class='row' id='code_track_row'>
                <div class='small-4 columns centered code_track_textbox_div'>
                    <input type='text' value='$code_track_1_v' id='code_track_textbox1' maxlength='3'/>
                </div>
                <div class='small-4 columns centered code_track_textbox_div'>
                    <input type='text' value='$code_track_2_v' id='code_track_textbox2' maxlength='3'/>
                </div>
                <div class='small-4 columns centered code_track_textbox_div'>
                    <input type='text' value='$code_track_3_v' id='code_track_textbox3' maxlength='3'/>
                </div>
            </div>
        ";
        return $return_content;
    }

    function f_create_trigger_template_select($def_value){
        $return_content = "<select id='triggers_trigger_template_select'>";

        $template_mapping = f_get_template_mapping();

        foreach ($template_mapping as $key => $value) {
            $temp_selected = '';
            if($def_value == $value){
                $temp_selected = 'selected="selected"';
            }
            $return_content .= "<option $temp_selected value='$value'>$value</option>";
        }
        $return_content .= "<select>";

        return $return_content;
    }

    function f_create_target_template_select($def_value){
        global $loggedInUser;
        $return_content = "<select id='triggers_target_template_select'>";

        $template_mapping = f_get_template_mapping();

        foreach ($template_mapping as $key => $value) {
            if($loggedInUser->checkPermission($value)){
                $temp_selected = '';
                if($def_value == $value){
                    $temp_selected = 'selected="selected"';
                }
                $return_content .= "<option $temp_selected value='$value'>$value</option>";
            }
        }
        $return_content .= "<select>";

        return $return_content;
    }

    function f_create_trigger_event_select($def_value){
        $return_content = "<select id='triggers_trigger_event_select'>";

        $event_mapping = f_get_event_mapping();

        foreach ($event_mapping as $key => $value) {
            $value = ucwords( str_replace('_', ' ', $value ) );
            $temp_selected = '';
            if($def_value == $value){
                $temp_selected = 'selected="selected"';
            }
            $return_content .= "<option $temp_selected value='$value'>$value</option>";
        }
        $return_content .= "<select>";

        return $return_content;
    }

    function f_create_deploy_select($def_value){
        $return_content = "
            <input type='radio' name='trigger_deploy_radio_name' value='Yes' id='trigger_deploy_radio_yes'
                ".($def_value=='Yes'?'checked':'')." >
                    <label for='trigger_deploy_radio_yes'>Yes</label>
            <input type='radio' name='trigger_deploy_radio_name' value='No' id='trigger_deploy_radio_no'
                ".($def_value=='No'?'checked':'')." >
                    <label for='trigger_deploy_radio_no'>No</label>
        ";
        return $return_content;
    }

    function f_get_test_ids_for_trigger($trigger_id){
        f_dbConnect();
        global $db;
        $sql_query = "SELECT trigger_id, test_id, suite_id FROM trigger_to_test where ".
                     "trigger_id=$trigger_id";
        $result = $db->query($sql_query) or die($db->error);
        $test_ids = array();
        while($row = $result->fetch_assoc()){
            $test_id  = $row['test_id'];
            // $suite_id = $row['suite_id'];
            $test_ids[] = $test_id;
        }
        return $test_ids;
    }

    function trigger_get_row($trigger_id)
    {
        f_dbConnect();
        global $db;
        $template_mapping = f_get_template_mapping();
        $event_mapping    = f_get_event_mapping();

        if($trigger_id == 'new'){
            $sql_query = "SELECT * FROM event_trigger order by trigger_id DESC LIMIT 1";
        }
        else{
            $sql_query = "SELECT * FROM event_trigger WHERE trigger_id=$trigger_id";
        }

        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc()){

            $target_template  = $template_mapping[ $row['target_template_id'] ];
            $state           = $row['trigger_state'];
            $trigger_id       = $row['trigger_id'];
            $git_branch       = $row['git_branch'];
            $code_track       = $row['code_track'];
            $trigger_template = $template_mapping[ $row['trigger_template_id'] ];
            $trigger_event    = ucwords( str_replace('_', ' ', $event_mapping[ $row['trigger_event_id'] ] ) );
            $target_task      = $row['target_task'];

            $target_task_deploy = 'No';
            if(substr($target_task,0,6) == 'Deploy'){
                $target_task_deploy = 'Yes';
            }
            if($git_branch == ''){
                $git_branch = 'NA';
            }
            if($state == 'active'){
                $state = 'true';
            }
            else if($state == 'inactive'){
                $state = 'false';
            }

            echo "
                <tr class='view_triggers_row' id='view_triggers_row_$trigger_id' data-ise-trigger-editing='false'
                    data-ise-trigger-id='$trigger_id' data-ise-trigger-active='$state'>
                    <td data-ise-trigger-field='git_branch' id='trigger_data_git_branch_$trigger_id'>
                        $git_branch
                    </td>
                    <td data-ise-trigger-field='code_track' id='trigger_data_code_track_$trigger_id'>
                        $code_track
                    </td>
                    <td data-ise-trigger-field='trigger_template' id='trigger_data_trigger_template_$trigger_id'>
                        $trigger_template
                    </td>
                    <td data-ise-trigger-field='trigger_event' id='trigger_data_trigger_event_$trigger_id'>
                        $trigger_event
                    </td>
                    <td data-ise-trigger-field='target_template' id='trigger_data_target_template_$trigger_id'>
                        $target_template
                    </td>
                    <td data-ise-trigger-field='deploy' id='trigger_data_deploy_$trigger_id'>
                        $target_task_deploy
                    </td>";

            $sql_query2 = "SELECT * FROM trigger_to_test WHERE trigger_id=$trigger_id";
            $result2 = $db->query($sql_query2) or die($db->error);
            $num_tests_suites = $result2->num_rows;

            echo "
                    <td data-ise-trigger-field='test' id='trigger_data_test_$trigger_id'>
                        <span class='label' id=''>
                            $num_tests_suites
                        </span>
                    </td>
                </tr>";
        }
    }

    function trigger_get_inactive()
    {
        f_dbConnect();
        global $db;
        global $loggedInUser;
        $template_mapping = f_get_template_mapping();
        $event_mapping    = f_get_event_mapping();
        $sql_query = "SELECT * FROM event_trigger where trigger_state='inactive' ORDER BY code_track DESC";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc()){

            $target_template  = $template_mapping[ $row['target_template_id'] ];
            if($loggedInUser->checkPermission($target_template)){

                $trigger_id       = $row['trigger_id'];
                $git_branch       = $row['git_branch'];
                $code_track       = $row['code_track'];
                $trigger_template = $template_mapping[ $row['trigger_template_id'] ];
                $trigger_event    = ucwords( str_replace('_', ' ', $event_mapping[ $row['trigger_event_id'] ] ) );
                $target_task      = $row['target_task'];

                $target_task_deploy = 'No';
                if(substr($target_task,0,6) == 'Deploy'){
                    $target_task_deploy = 'Yes';
                }

                if(!$git_branch){
                    $git_branch = 'NA';
                }

                echo "
                                <tr class='view_triggers_row inactive_trigger' id='view_triggers_row_$trigger_id' data-ise-trigger-editing='false'
                                    data-ise-trigger-id='$trigger_id' data-ise-trigger-active='false'>
                                    <td data-ise-trigger-field='git_branch' id='trigger_data_git_branch_$trigger_id'>
                                        $git_branch
                                    </td>
                                    <td data-ise-trigger-field='code_track' id='trigger_data_code_track_$trigger_id'>
                                        $code_track
                                    </td>
                                    <td data-ise-trigger-field='trigger_template' id='trigger_data_trigger_template_$trigger_id'>
                                        $trigger_template
                                    </td>
                                    <td data-ise-trigger-field='trigger_event' id='trigger_data_trigger_event_$trigger_id'>
                                        $trigger_event
                                    </td>
                                    <td data-ise-trigger-field='target_template' id='trigger_data_target_template_$trigger_id'>
                                        $target_template
                                    </td>
                                    <td data-ise-trigger-field='deploy' id='trigger_data_deploy_$trigger_id'>
                                        $target_task_deploy
                                    </td>";

                $sql_query2 = "SELECT * FROM trigger_to_test WHERE trigger_id=$trigger_id";
                $result2 = $db->query($sql_query2) or die($db->error);
                $num_tests_suites = $result2->num_rows;

                echo "
                                    <td data-ise-trigger-field='test' id='trigger_data_test_$trigger_id'>
                                        <span class='label' id=''>
                                            $num_tests_suites
                                        </span>
                                    </td>
                                </tr>";
            }
        }
    }

    /****************************************************************/
    /* Triggers Pages */
    /****************************************************************/



?>
