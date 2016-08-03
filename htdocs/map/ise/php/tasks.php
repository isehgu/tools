<?php
    require_once("shared.php");
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Display Open Task by Template
    function f_displayTasksByTemplate()
    {
        global $db;
        $count = 1; //number of sections displayed
        //excluding SIT from task display
        $sql_query = "select distinct template_id from template order by template_name";
        $result = $db->query($sql_query) or die($db->error);

        while($row = $result->fetch_assoc())
        {
            $template_id = '';
            $template_name = '';

            $template_id = $row['template_id'];
            $template_name = f_getTemplateFromId($template_id);
            echo "<div class='row wide-row'>";

            echo "
            <div class='small-12 columns'>
                <div class='template_header small-12 small-centered columns'>
                  <h1 class='font_syncopate'>$template_name</h1>
                </div>
                <div class='row'>
                  <div class='small-12 small-centered columns'>
                    <table>
                      <thead>
                        <tr>
                          <th width='5%'>ID</th>
                          <th width='28%'>Release</th>
                          <th width='15%'>Deploy</th>
                          <th width='15%'>DB Conversion</th>
                          <th width='15%'>Test</th>
                          <th width='15%'>Status</th>
                          <th width='7%'>Action</th>
                        </tr>
                      </thead>
                      <tbody>
            ";

            f_displayOpenTaskRow($template_id);

            echo "
                            </tbody>
                        </table>
                      </div>
                    </div>
                    <br>
                  </div>
              </div>

            ";
        }//end of while
    }
    //end of f_displayTasksByTemplate()
    ///////////////////////////////////////////////////////////////////////////////////////////
    /*Displaying open tasks for a given template
             *<tr>
                <td>20</td>
                <td>10.0.030</td>
                <td>Yes</td>
                <td>Yes</td>
                <td>In Progress</td>
            </tr>
      */
    function f_displayOpenTaskRow($template_id)
    {
        global $db;
        $authorized = f_verifyUserPermissionByTemplate($template_id);
        $deploy_status_array = array('Pending','In Progress','Passed','Failed','Skipped');
        $test_status_array = array('Pending','In Progress','Passed','Failed','Skipped');
        $tip_attr  = "data-tooltip aria-haspopup='true'";
        $tip_class = "has-tip tip-top radius";
        $sql_query = "select * from task_queue where template_id = $template_id order by task_id";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc())
        {
            $release_version = '';
            $deploy = '';
            $test = '';
            $status = '';
            $task_id = $row['task_id'];
            $release_version = f_getReleaseFromId($row['release_id']);
            $deploy_total_checkpoints = $row['deploy_total_checkpoints'];
            $deploy_checkpoint_count  = $row['deploy_checkpoint_count'];
            $deploy_stage = $row['deploy_stage'];
            $deploy_next_stage = $row['deploy_next_stage'];

            // Deploy Cell
            $deploy = $deploy_status_array[$row['deploy']];
            $deploy_progress = "";
            if($deploy=='In Progress'){
                $deploy_total_checkpoints = intval($deploy_total_checkpoints);
                $deploy_checkpoint_count = intval($deploy_checkpoint_count);
                $perc        = round( (($deploy_checkpoint_count/$deploy_total_checkpoints)*100), 2);
                $progress_title = "$perc% Complete".
                    "<br>Last Completed Step: $deploy_stage".
                    "<br>Current Step: $deploy_next_stage";
                $deploy_progress = "
                    <div $tip_attr class='progress success radius $tip_class' title='$progress_title'>
                        <span class='meter' style='width: $perc%'>"
                            .round($perc, 0)."%
                        </span>
                    </div>";
                $deploy = '';
            }

            // DB Conversion Cell - Only show the button under the right scenario
            if($row['db_conversion'] == 0){
                $db_conv = 'Pending';
            }
            elseif($row['db_conversion'] == 1 && $row['status'] == 2){
                if($authorized){
                    $db_conv = "<a href='#' class='button tiny radius task_db_conv_button'
                                id='task_db_conv_button_$task_id'
                                title='Click to indicate completion of database conversion'>
                                    Complete
                                </a>";
                }
                else{
                    $db_conv = 'In Progress';
                }
            }
            elseif($row['db_conversion'] == 2) {$db_conv = 'Passed';}
            elseif($row['db_conversion'] == 3) {$db_conv = 'Failed';}
            elseif($row['db_conversion'] == 4) {$db_conv = 'Skipped';}
            else {$db_conv = 'NA';}

            // Test Cell
            $test = $test_status_array[$row['test']];
            // If the test is in progress then add a progress bar to visually
            // indicate the progress. Also show proper stats.
            $test_progress = '';
            if($test=='In Progress'){
                $sql_query2 = "SELECT COUNT(*) total,
                    SUM(CASE WHEN status!=0 and status!=1 THEN 1 ELSE 0 END) exec,
                    SUM(CASE WHEN status=2 THEN 1 ELSE 0 END) pass,
                    SUM(CASE WHEN status=3 THEN 1 ELSE 0 END) fail,
                    SUM(CASE WHEN status=1 THEN 1 ELSE 0 END) running,
                    SUM(CASE WHEN status=0 THEN 1 ELSE 0 END) queued
                    FROM test_execution WHERE task_id=$task_id";
                $result2 = $db->query($sql_query2) or die($db->error);
                $row2 = $result2->fetch_assoc();
                $num_total   = intval($row2['total']);
                $num_exec    = intval($row2['exec']);
                $num_pass    = intval($row2['pass']);
                $num_fail    = intval($row2['fail']);
                $num_running = intval($row2['running']);
                $num_queued  = intval($row2['queued']);
                $perc        = round( (($num_exec/$num_total)*100), 2);
                $progress_title = "$perc% ($num_exec out of $num_total) Executed ".
                    "<br>$num_pass Passed<br>$num_fail Failed".
                    "<br>$num_running Running<br>$num_queued Queued";
                $test_progress = "
                    <div $tip_attr class='progress success radius $tip_class' title='$progress_title'>
                        <span class='meter' style='width: $perc%'>"
                            .round($perc, 0)."%
                        </span>
                    </div>";
                $test = '';
            }
            // For tasks whose test phase is still pending, show which tests are in queue
            elseif($test=='Pending'){
                $sql_query2 = "SELECT tc.name FROM task_to_test tt, test_case tc ".
                    "WHERE tt.task_id=$task_id AND tt.test_id=tc.test_id";
                $result2 = $db->query($sql_query2) or die($db->error);
                $num_tests = $result2->num_rows;
                $progress_title = "<ol>";
                while($row2 = $result2->fetch_assoc()){
                    $test_name = $row2['name'];
                    $progress_title .= "<li>$test_name</li>";
                }
                $progress_title .= "</ol>";
                $test_progress = "
                    <div $tip_attr class='$tip_class task_test_pending' title='$progress_title'>
                        ($num_tests test".($num_tests>1?"s":"").")
                    </div>
                ";
            }

            if    ($row['status'] == 0) $status = 'Pending';
            elseif($row['status'] == 1) $status = 'In Progress';
            else                        $status = 'Suspended';

            //Determine if the cancel_cross should be disabled
            if(!$authorized || $status != 'Pending')
            {
                $cancel_cross_class = 'disabled_cancel_cross';
                $cancel_btn_tooltip = 'Can&#39;t touch this';
            }
            else
            {
                $cancel_cross_class = 'cancel_cross';
                $cancel_btn_tooltip = 'Click to cancel';
            }

            echo "
                    <tr data-ise-task-id=$task_id>
                        <td>$task_id</td>
                        <td>$release_version</td>
                        <td>$deploy $deploy_progress</td>
                        <td>$db_conv</td>
                        <td>$test $test_progress </td>
                        <td>$status</td>
                        <td><i $tip_attr data-ise-task-id='$task_id' class='$cancel_cross_class $tip_class fi-x' title='$cancel_btn_tooltip'></i></td>
                    </tr>
            ";
        }//end of while
    }

?>
