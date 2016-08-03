<?php

    require_once("models/config.php");

    function f_dbConnect()
    {
        global $db;
        $db_connection = 'tc01';

        if($db_connection == 'tc01'){
            $dbhost = 'tc-tac01';
            $dbuser = 'admin';
            $dbpwd  = 'changeme';
            $dbname = 'map_db';
            $dbport = '18906';
        }

        $db = new mysqli($dbhost,$dbuser,$dbpwd,$dbname,$dbport);
        if(!$db) echo "Connection failed: ".$db->connect_error; //if condition here can also be -- if !$mysqli

    }

    //Input:    type of content required
    // history_by_release
    // history_by_template
    // tasks_by_creation
    // tasks_by_template
    // templates_display
    // Output: html content for the display type
    function f_contentDisplay($type)
    {
        if ($type == 'history_by_release') f_displayHistoryByRelease();
        elseif ($type == 'history_by_template') f_displayHistoryByTemplate();
        elseif ($type == 'tasks_by_release') f_displayTasksByRelease();
        elseif ($type == 'tasks_by_template') f_displayTasksByTemplate();
        elseif ($type == 'templates_display') f_displayTemplates();
        elseif ($type == 'test_live') f_displayTestsLive();
        elseif ($type == 'test_user_history') f_displayTestsUserHistory();
        elseif ($type == 'test_complete_history') f_displayTestsCompleteHistory();
        elseif ($type == 'test_request') f_displaySubmitTestRequest();
        elseif ($type == 'manage_my_triggers') f_manageTriggers();
        elseif ($type == 'view_all_triggers') f_viewAllTriggers();
        else echo "<h1 class='font_syncopate'>$type is unknown</h1>";
    }
    //end of f_contentDisplay()
    ///////////////////////////////////////////////////////////////////////
    //input: group id
    //output: echo table display of test rerun history
    function f_displayTestRerunHistory($group_id)
    {
        global $db;
        $test_execution_id_list = array();
        $sql_query = "select id from test_execution where group_id = $group_id order by id desc";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc())
        {
            $test_execution_id_list[] = $row['id'];
        }
        f_displayTestsHistoryTable($test_execution_id_list,1); //1 is for ready-only
    }//end of f_displayTestRerunHistory()
    ///////////////////////////////////////////////////////////////////////
    //input: group_id from the test_execution table
    //output: return an associative array containing the following info
    //about the completed/killed/canceled test -- test_id,label, release_id,
    //template_id
    //Keep in mind, completed test can be tied to a completed task
    //or an ongoing task
    //usage: test_request_resubmit.php
    function f_getCompletedTestFromGroupId($group_id)
    {
        global $db;
        $test_detail = array();
        $sql_query_completed_task = "select max(te.id) as execution_id,te.test_id,te.label,ct.release_id,ct.template_id from test_execution as te, completed_task as ct where te.task_id = ct.task_id and te.status > 1 and group_id = $group_id";
        $sql_query_ongoing_task = "select max(te.id) as execution_id,te.test_id,te.label,tq.release_id,tq.template_id from test_execution as te, task_queue as tq where te.task_id = tq.task_id and te.status > 1 and group_id = $group_id";

        $result = $db->query($sql_query_ongoing_task) or die($db->error);
        $row = $result->fetch_assoc(); //get data from ongoing task
        if(is_null($row['execution_id'])) //if test is not tied to an ongoing task
        {
            $result = $db->query($sql_query_completed_task) or die($db->error);
            $row = $result->fetch_assoc();
        }

        //At this point $row should contain test data either from
        //ongoing task, or completed task
        $test_detail['test_id'] = $row['test_id'];
        $test_detail['label'] = $row['label'];
        $test_detail['release_id'] = $row['release_id'];
        $test_detail['template_id'] = $row['template_id'];

        return $test_detail;

    }
    ///////////////////////////////////////////////////////////////////////
    //input: none
    //display test history for all templates
    function f_displayTestsCompleteHistory()
    {
        $template_id_list = f_getTemplateIdList();

        foreach($template_id_list as $template_id)
        {
            $template_name = f_getTemplateFromId($template_id);
            //echo $template_id . ": " . $user_template_list[$template_id];
            echo "
                <div class='row wide-row template_stying'><div class='small-12 columns'><h1>$template_name</h1></div></div>
            ";
            f_displayTestHistoryByRelease($template_id, 1); //read_only is true(1), false(0)
            // echo "<hr>";
        }//end of foreach
    }//end of f_displayTestsCompleteHistory()
    ///////////////////////////////////////////////////////////////////////
    //input: none
    //display test history in templates that the user has permission to
    function f_displayTestsUserHistory()
    {
        global $loggedInUser;

        if(!isUserLoggedIn()){
            echo "
                <div class='row' id=''>
                    <div class='small-12 columns' id='triggers_login_message'>
                        <p id=''>
                            Please log in to view this page
                        </p>
                    </div>
                </div>";
            return;
        }
        else
        {
            $uid = fetchUserDetails($loggedInUser->username);
            $uid = $uid['id'];
            $user_template_list = f_getUserTemplates($uid);

            foreach($user_template_list as $template_id=>$template_name)
            {
                //echo $template_id . ": " . $user_template_list[$template_id];
                echo "
                    <div class='row wide-row template_stying'><div class='small-12 columns'><h1>$template_name</h1></div></div>
                ";
                f_displayTestHistoryByRelease($template_id, 0); //read_only is true(1), false(0)
                // echo "<hr>";
            }
        }//end of else
    }//end of f_displayTestsUserHistory()
    ///////////////////////////////////////////////////////////////////////
    //input: template id, read_only (0 for false, 1 for true)
    //display entire section_wrapper for that template and all it's test history
    //grouped by releases
     //If read-only, approval drop down will not show
    function f_displayTestHistoryByRelease($template_id,$read_only)
    {
        global $db;
        $release_id_list = f_getReleaseIdListfromTemplateId($template_id);

        foreach($release_id_list as $release_id)
        {
            if(f_getTestCountByReleaseByTemplate($template_id,$release_id) < 1) continue; //if there's no test, don't display the release

            $release_name = f_getReleaseFromId($release_id);
            $suffix = $template_id.'_'.$release_id;
            echo "<div class='section_wrapper'><div class='row wide-row'>";
            echo "<div id='section_header_$suffix' class='small-11 columns small-offset-1 section_header header_text end'>
                    <h1 class='font_syncopate'>$release_name</h1>
                    </div></div>";
            //end of section header
            //section body
            echo "<div class='row wide-row'>";
            echo "<div id='section_body_$suffix' class='small-11 small-offset-1 columns section_body'>";

            //This part here gets all the test labels on this release
            //in this template, and display them as section wrappers
            $test_label_list = f_getTestLabelListByReleaseByTemplate($release_id,$template_id);
            f_displayTestHistoryByLabel($test_label_list, $read_only,$suffix);
            //end of test label handling
            echo "</div></div>"; //end of 1st section body (for release)
            echo "</div>"; //end of section wrapper
        }
    }//end of function f_displayTestHistoryByRelease($template_id)
    ///////////////////////////////////////////////////////////////////////
    //input: template id, release id
    //return a count of how many tests there are for that release in that template
    function f_getTestCountByReleaseByTemplate($template_id,$release_id)
    {
        global $db;
        $count = 0;
        $sql_query_current = "select count(*) as count from test_execution as te,task_queue as tq where te.task_id = tq.task_id and tq.template_id = $template_id and tq.release_id = $release_id";
        $sql_query_complete = "select count(*) as count from test_execution as te,completed_task as ct where te.task_id = ct.task_id and ct.template_id = $template_id and ct.release_id = $release_id";
        $result = $db->query($sql_query_current) or die($db->error);
        $row = $result->fetch_assoc();
        $count += $row['count'];

        //Now completed task
        $result = $db->query($sql_query_complete) or die($db->error);
        $row = $result->fetch_assoc();
        $count += $row['count'];
        return $count;
    }
    ///////////////////////////////////////////////////////////////////////
    //input: array of label=>(test_id,test_id...), read_only flag, suffix_tree
    //suffix_tree is for section_header and section_body's suffix
    //THIS IS FOR DISPLAY TEST HISTORY ON MY_TEST_DISPLAY and
    //COMPLETE_TEST_HISTORY ONLY!!!!!!!
    //display test history by label. Due to needed css, this won't display
    //nicely on an empty page. It assumes that it's a sub-group under the
    //release heading already.
    function f_displayTestHistoryByLabel($test_label_list, $read_only,$suffix_tree)
    {
        $counter = 1; //this for the suffix on section_header and section_body
        foreach($test_label_list as $label => $test_execution_id_list)
        {
            $label_suffix = $suffix_tree."_L$counter";

            //echo "<div class='row'>row</div>";


            echo "<div class='row'><div class='section_wrapper'>";

            echo "<div class='small-1 columns'></div>";
            echo "<div id='section_header_$label_suffix' class='small-11 small-offset-1 columns section_header header_text'>";
            echo "<h3 class='font_syncopate'>$label</h3>";
            echo "</div>";

            echo "<div class='row'>";
            echo "<div id='section_body_$label_suffix' class='small-11 small-offset-1 columns section_body'>";
            f_displayTestsHistoryTable($test_execution_id_list,$read_only);
            echo "</div></div>";

            echo "</div></div>";

            $counter++;
        }//end of foreach loop
    }//end f_displayTestHistoryByLabel($test_label_list, $read_only)
    ///////////////////////////////////////////////////////////////////////
    //input: an array of test execution id, $read_only flag
    //Display in html, a simple table with the following heading
    //test status, Test Name, Results, Approval, Start Time, End Time
    //If read_only is 1, then no drop down box on approval
    //20141114--HG--Added rerun column and button. _GET request is handled
    //via javascript, so no link here needed
    //20141210  HG  Link test results to TRF link, if test is for SIT.
    //              Test history row is now clickable to display rerun history
    function f_displayTestsHistoryTable($test_execution_id_list,$read_only)
    {
        global $db;
        $test_status_array = array('Queued','Running','Completed Pass','Completed Failed','Killed','System Error','Test Cancelled');
        $approval_status_array = array(0=>'Pending Approval',1=>'Approved',2=>'Not Approved',3=>'Approval Not Required');
        echo "
            <table>
                <thead>
                    <tr>
                        <th width='15%'>Test Status</th>
                        <th width='20%'>Test Name</th>
                        <th width='13%'>Test Results</th>
                        <th width='15%'>Approval</th>
                        <th width='15%'>Start Time</th>
                        <th width='15%'>End Time</th>
        ";
        if($read_only == 0) //if read_only is false, meaning it's read and write
        {
            echo "<th width='7%'>Rerun</th>";
        }
        echo "
                    <tr>
                </thead>
                <tbody>
        ";
        foreach($test_execution_id_list as $eid)
        {
            //echo "$eid";
            $test_status_class = '';
            $test_id = f_getTestIdFromTestExecutionId($eid);
            $rerun_exist = f_getRerunExist($eid); //true if this test had reruns, false if it doesn't
            if($rerun_exist) $test_status_class = 'strong';

            $sql_query = "select te.group_id,te.test_folder_path,te.status,te.report,tc.name,te.start_time,te.end_time,te.approval_status from test_execution as te,test_case as tc where tc.test_id = $test_id and te.id = $eid";
            $result = $db->query($sql_query) or die($db->error);
            while ($row = $result->fetch_assoc())
            {
                $test_status = '';
                $test_report = '';
                $test_name = '';
                $start = '';
                $end = '';
                $approval = '';
                $report_link = '';
                $report_anchor ='';
                $result_folder = '';
                $group_id = '';

                $test_status = $test_status_array[$row['status']];
                $test_report = $row['report'];
                $test_name = $row['name'];
                $start = $row['start_time'];
                $end = $row['end_time'];
                $approval = $approval_status_array[$row['approval_status']];
                $result_folder = $row['test_folder_path'];
                $group_id = $row['group_id'];

                if($test_report )
                {
                    $report_link = 'http://map/reports/'.$test_report;
                    $report_anchor = "<a href='$report_link' target='_blank'><i data-tooltip aria-haspopup='true' title='Robot report' class='has-tip tip-bottom radius fi-page-filled test_result_icon'></i></a>";
                }
                else
                {
                    $report_anchor = "<i data-tooltip aria-haspopup='true' title='No robot report available' class='has-tip tip-bottom radius fi-page-filled test_result_icon_null'></i>";
                }

                if($result_folder )
                {
                    if(strpos($result_folder,'dd-ap09') === false) //if result folder doesn't have dd-ap09, thus not SIT
                    {
                        $result_folder = "http://map/results/".$result_folder;
                    }
                    //if $result_folder has dd-ap09, then no manipulation is needed
                    $result_folder = "<a href='$result_folder' target='_blank'><i data-tooltip aria-haspopup='true' title='Test result files' class='has-tip tip-bottom radius fi-folder test_result_icon'></i>";
                }
                else
                {
                    $result_folder = "<i data-tooltip aria-haspopup='true' title='No test result files available' class='has-tip tip-bottom radius fi-folder test_result_icon_null'></i>";
                }

                echo "<tr class='generic_table_row'>";
                echo "<td class='$test_status_class test_history_row' data-ise-group-id='$group_id'>$test_status</td>";
                echo "<td class='test_history_row' data-ise-group-id='$group_id'>$test_name</td>";
                echo "<td><ul class='no_style_list'><li>$report_anchor</li><li>$result_folder</li></ul></td>";
                echo "<td class='test_history_row' data-ise-group-id='$group_id'>$approval</td>";
                echo "<td class='test_history_row' data-ise-group-id='$group_id'>$start</td>";
                echo "<td class='test_history_row' data-ise-group-id='$group_id'>$end</td>";
                if($read_only == 0) //read and write allowed
                {
                    echo "<td><i data-tooltip aria-haspopup='true' title='Rerun' class='has-tip tip-top radius fi-loop test_replay' data-ise-group-id='$group_id'></i>";
                }
                echo "</tr>";
            }//end of while loops

        }//end of foreach
        echo "</tbody></table>";
    }//end of f_displayTestsHistoryTable($tests,$read_only)
    ///////////////////////////////////////////////////////////////////////
    //input: test execution id
    //return true if this test execution has reruns under same group id, false if it doesn't
    function f_getRerunExist($test_execution_id)
    {
        global $db;
        $sql_query = "select count(*) as run_count from test_execution as te where group_id = (select group_id from test_execution where id = $test_execution_id)";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        //return true if test excution count is more than 1 under the same group id, otherwise false
        if ($row['run_count'] > 1) return True;
        else return False;
    }
    ///////////////////////////////////////////////////////////////////////
    //input: test execution id
    //return the corresponding test id
    function f_getTestIdFromTestExecutionId($test_execution_id)
    {
        global $db;
        $sql_query = "select test_id from test_execution where id = $test_execution_id";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        return $row['test_id'];
    }
    ///////////////////////////////////////////////////////////////////////
    //input: release id, template id, read-only (0 for false, 1 for true)
    //Return an array of arrays in the form of label=>(test_id 1, 2, 3)
    //for tests that are done (completed, cancelled, etc.), for the specified release in the specified template.
    //The list will be in descending order, by test_id. So later tests
    //come on top.
    //This list will contain only the latest test excution ids under the same group_id.
    //So if there are a few reruns under the same group_id, only the latest test execution id gets
    //stored in the array.
    //This is used in user_test_history display.
    function f_getTestLabelListByReleaseByTemplate($release_id,$template_id)
    {
        global $db;
        $label_list = array();
        //$sql_query_current searches through ongoing tasks
        //$sql_query_complete searches through completed tasks
        $sql_query_current = "select te.label,te.id from test_execution as te,task_queue as tq where te.task_id = tq.task_id and tq.release_id = $release_id and tq.template_id = $template_id and te.status > 1 order by te.id desc";
        $sql_query_complete = "select te.label,te.id from test_execution as te,completed_task as ct where te.task_id = ct.task_id and ct.release_id = $release_id and ct.template_id = $template_id order by te.id desc";
        $result_current = $db->query($sql_query_current) or die($db->error);
        $result_complete = $db->query($sql_query_complete) or die($db->error);

        //Get the label from ongoing tasks first, then the completed tasks
        //So they are in descending order
        if($result_current->num_rows > 0)
        {
            while ($row = $result_current->fetch_assoc())
            {
                $label = '';
                $test_id = '';
                $label = $row['label'];
                $test_execution_id = $row['id'];
                //echo "$test_id | ";
                $label_list[$label][] = $test_execution_id;
            }//end of while on ongoing tasks
        }


        //Get labels and test execution ids from completed tasks
        if($result_complete->num_rows > 0)
        {
            while ($row = $result_complete->fetch_assoc())
            {
                $label = '';
                $test_id = '';
                $label = $row['label'];
                $test_execution_id = $row['id'];
                if(!f_latestTest($test_execution_id)) continue; //if this is not the latest test execution, meaning if there are later reruns, then don't include this test execution id. we only want the latest
                //echo "$test_id | ";
                $label_list[$label][] = $test_execution_id;
            }//end of while on ongoing tasks
        }
        /*test code
        foreach($label_list as $key=>$values)
        {
            echo $key . ": ";
            echo "<br>";
            foreach($values as $value)
            {
                echo $value;
                echo " | ";
            }
            echo "<br>";
        }
        */
        return $label_list;
    } //end of f_getTestLabelListByReleaseByTemplate($release_id,$template_id)
    ///////////////////////////////////////////////////////////////////////
    //input: test execution id
    //return True, if the test excution id is the latest run of this test under the same group id
    //return False, if there is later test execution id under the same group id
    function f_latestTest($test_execution_id)
    {
        global $db;
        $sql_query = "select max(te.id) as latest_id from test_execution as te where group_id = (select group_id from test_execution where id = $test_execution_id)";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        //return true if the id is the latest, otherwise return false
        if ($row['latest_id'] == $test_execution_id) return True;
        else return False;
    }//f_latestTest($test_execution_id)
    ///////////////////////////////////////////////////////////////////////
    //input: user id
    //return an associative array of id=>template_name that this user has
    //permission to
    function f_getUserTemplates($uid)
    {
        global $db;
        $user_template_list = array();
        $content ='';
        $sql_query = "select t.template_id, t.template_name from uc_permissions as up, uc_user_permission_matches as uupm, template t where t.template_name=up.name and uupm.user_id=$uid and up.id=uupm.permission_id order by name";
        $result = $db->query($sql_query) or die($db->error);
        while ($row = $result->fetch_assoc())
        {
            $template_id = '';
            $template_name = '';
            $template_id = $row['template_id'];
            $template_name = $row['template_name'];
            //$content .= $template_id . ":" . $template_name . " | ";
            $user_template_list[$template_id] = $template_name;
        }
        //return $content;
        return $user_template_list;
    }
    ///////////////////////////////////////////////////////////////////////
    //Input: template id
    //Return true if the logged in user has rights to that template id. Otherwise return false
    function f_verifyUserPermissionByTemplate($template_id)
    {
        global $loggedInUser;
        $template_name = f_getTemplateFromId($template_id);
        if(isUserLoggedIn() && $loggedInUser->checkPermission($template_name)) return True;
        else return False;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    //Input: none
    //display html needed for all ongoing and pending test in all templates.
    //If a user has permission to a certain template, cancellation button will be active
    //The following fields should be displayed --
    //Select checkbox, Test Name, Task ID, Status, Template Instance Name, Start Time
    function f_displayTestsLive()
    {
        echo "<div class='row'><div class='small-12 columns'><h1>In Progress</h1></div></div>";
        f_displayTestsInProgress(); //displaying ongoing tests

        echo "<div class='row'><div class='small-12 columns'><!--<hr>--></div></div>";

        echo "<div class='row'><div class='small-12 columns'><h1>In Queue</h1></div></div>";
        f_displayTestsInQueue(); //displaying queued tests
    }
    //end of f_displayTestsLive()
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Input: none
    //display html needed for all pending test in all templates.
    //If a user has permission to a certain template, cancellation button will be active
    //The following fields should be displayed --
    //Template Name, Template Instance Name, Test Name, Task ID, Start Time
    function f_displayTestsInQueue()
    {
        $template_id_list = f_getTemplateIdList();

        foreach($template_id_list as $template_id)
        {
           f_displayTestsInQueueSingleTemplate($template_id);
        }

    }
    //end of f_displayTestsInQueue()
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Input: template id
    //display html needed for all pending tests for the specific template
    //If a user has permission to a certain template, cancellation button will be active
    //The following fields should be displayed --
    //Template Name, Template Instance Name, Test Name, Task ID, Start Time
    function f_displayTestsInQueueSingleTemplate($template_id)
    {
        global $db, $loggedInUser;
        $template_name = f_getTemplateFromId($template_id);
        //If user's not authorized, disabled should be added to buttons and checkbox for that template
        if(f_verifyUserPermissionByTemplate($template_id)) $disabled = '';
        else $disabled = 'disabled';

        $sql_query = "select te.task_id,tc.name,te.label,te.id from test_execution as te,test_case as tc,task_queue as tq where te.task_id = tq.task_id and tq.template_id = $template_id and te.test_id = tc.test_id and te.status = 0 order by te.task_id";
        $result = $db->query($sql_query) or die($db->error);

        if($result->num_rows){
            echo "
                <div class='section_wrapper live_test'>
                    <div class='row'>
                        <div id='section_header_$template_id' class='small-8 columns small-centered section_header header_text end small-centered'>
                            <div class='row' data-equalizer>
                                <div class='small-10 columns' data-equalizer-watch>
                                    <h1 class='font_syncopate'>$template_name</h1>
                                </div>
                                <div class='small-2 columns' data-equalizer-watch>
                                    <h1 class='font_syncopate'>$result->num_rows</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div id='section_body_$template_id' class='small-8 columns small-centered section_body end'>
                            <button class='alert tiny cancel_selected $disabled' data-ise-template-id='$template_id'>Cancel Selected</button>
                            <button class='alert tiny cancel_all $disabled' data-ise-template-id='$template_id'>Cancel All</button>
                            <table>
                                <thead>
                                    <tr>
                                        <th width='10%'>Select</th>
                                        <th width='15%'>Task ID</th>
                                        <th width='35%'>Label</th>
                                        <th width='40%'>Test Name</th>
                                    <tr>
                                </thead>
                                <tbody>
            ";
            while ($row = $result->fetch_assoc())
            {
                $task_id = '';
                $test_name = '';
                $test_label = '';
                $execution_id = '';

                $task_id = $row['task_id'];
                $test_name = $row['name'];
                $test_label = $row['label'];
                $execution_id = $row['id'];

                echo "
                    <tr class='generic_table_row'>
                        <td class='checkbox_td'><input  class='checkbox_test' data-ise-test-execution-id='$execution_id' type='checkbox' $disabled></td>
                        <td>$task_id</td>
                        <td>$test_label</td>
                        <td>$test_name</td>
                    </tr>
                ";
            }//end of while
            echo "</table></div></div></div>"; //ending the section for this template
        }
    }
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Input: none
    //display html needed for all ongoing all templates
    //The following fields should be displayed --
    //Template Name, Template Instance Name, Test Name, Task ID, Start Time
    function f_displayTestsInProgress()
    {
        global $loggedInUser,$db;
        $sql_query = "select te.task_id,tc.name,te.start_time,te.label,t.template_name,tq.template_instance_id from test_execution as te,template as t,task_queue as tq, test_case as tc where te.task_id = tq.task_id and tq.template_id = t.template_id and te.test_id = tc.test_id and te.status = 1 order by t.template_name,tq.template_instance_id,te.task_id";
        $result = $db->query($sql_query) or die ($db->error);
        echo "
            <div class='row'>
                <div class='small-12 small-centered columns'>
                    <table>
                        <thead>
                            <tr>
                                <th width='10%'>Template</th>
                                <th width='10%'>Instance ID</th>
                                <th width='10%'>Task ID</th>
								<th width='25%'>Label</th>
                                <th width='25%'>Test Name</th>
                                <th width='20%'>Start Time</th>
                            </tr>
                        </thead>
                        <tbody>
        ";

        while($row = $result->fetch_assoc())
        {
            $template_name = '';
            $instance_id = '';
            $test_name = '';
            $task_id = '';
            $start_time = '';
			$label = '';

            $template_name = $row['template_name'];
            $instance_id = $row['template_instance_id'];
            $test_name = $row['name'];
            $task_id = $row['task_id'];
            $start_time = $row['start_time'];
			$label = $row['label'];

            echo "
                <tr>
                    <td>$template_name</td>
                    <td>$instance_id</td>
                    <td>$task_id</td>
					<td>$label</td>
                    <td>$test_name</td>
                    <td>$start_time</td>
                </tr>
            ";
        }//End of while



        echo "
                        </tbody>
                    </table>
                </div>
            </div>
        ";
    }
    //end of f_displayTestsInProgress()
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Input: none
    //get all template ID and store in a list, then return that list
    function f_getTemplateIdList()
    {
        global $db;
        $template_id_list = array();
        $sql_query = "select template_id from template order by template_name";
        $result = $db->query($sql_query) or die ($db->error);
        while ($row = $result->fetch_assoc())
        {
            $template_id_list[] = $row['template_id'];
        }
        return $template_id_list;
    }
    //end of f_getTemplateIdList()
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
            $task_id = '';

            $task_id = $row['task_id'];
            $release_version = f_getReleaseFromId($row['release_id']);
            $deploy = $deploy_status_array[$row['deploy']];

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
            if(!$authorized || $status == 'In Progress')
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
                        <td>$deploy</td>
                        <td>$db_conv</td>
                        <td>$test $test_progress </td>
                        <td>$status</td>
                        <td><i $tip_attr data-ise-task-id='$task_id' class='$cancel_cross_class $tip_class fi-x' title='$cancel_btn_tooltip'></i></td>
                    </tr>
            ";
        }//end of while
    }
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Html content for display event history by release
    function f_displayHistoryByTemplate()
    {
        global $db;
        $sql_query = "select distinct template_id from template order by template_name";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc())
        {
            $template_id = '';
            $template_instance_list = array();
            $release_id_list = array();

            $template_id = $row['template_id'];
            $template_name = f_getTemplateFromId($template_id);
            $release_id_list = f_getReleaseIdListfromTemplateId($template_id);//get a list of releases on this template in event history table
            //$template_instance_list = f_getTemplateInstanceIdFromTemplateId($template_id);
            echo "
                    <div class='row wide-row'>
                    <div class='small-12 small-centered columns'><!--Event History-->
                      <div class='section_wrapper'>
                        <div class='row'><!--start of header row-->
                          <div id='section_header_$template_name' class='small-12 columns section_header small-centered'>
                            <div class='row'>
                              <div class='small-6 columns header_text'>
                                <h1 class='font_syncopate'>$template_name</h1>
                              </div>
                              <div class='small-6 columns header_stat'>
                                <ul>
            ";
            //Displaying -- <li>10.0.040 : Test Started</li>
            foreach ($release_id_list as $release_id)
            {
                f_displayLatestEventPerReleaseByTemplate($template_id, $release_id);
            }

            echo "
                    </ul>
                                    </div>
                                </div>
                            </div>
                        </div><!--end of header row-->
                        <div class='row'><!--start of body row-->
                            <div id='section_body_$template_name' class='section_body'>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Release</th>
                                            <th>Latest Event</th>
                                            <th>Entry Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            ";
            //Displaying rows for latest events by release from that template
            foreach ($release_id_list as $release_id)
            {
                f_displayLatestDetailEventPerReleaseByTemplate($release_id,$template_id);
            }

            echo "
                    </tbody>
                                    </table>
                                </div>
                            </div><!--end of body row-->
                        </div>
                    </div><!--End of event history-->
                </div>
            ";

        }//end of while
    }
    //end of f_displayHistoryByTemplate()
    ///////////////////////////////////////////////////////////////////////////////////////////
    /*
     *<tr class='event_history_release_row' data-ise-release-id='1' data-ise-template-id='2' >
            <td>10.0.040</td>
            <td>Test Started</td>
            <td>2014 15:00:23</td>
        </tr>
  */
    function f_displayLatestDetailEventPerReleaseByTemplate($release_id,$template_id)
    {
        global $db;
        $release_version = f_getReleaseFromId($release_id);
        //$sql_query = "select event_id,event_time from event_history where (template_instance_id in (select instance_id from template_instance where template_id = $template_id) or template_id in (select template_id from event_history where template_instance_id = 0 and template_id = $template_id)) and release_id in ($release_id,0) order by id desc limit 1";

        $sql_query = "select event_id,event_time from event_history where (template_instance_id in (select instance_id from template_instance where template_id = $template_id) or template_id in (select template_id from event_history where template_instance_id = 0 and template_id = $template_id)) and release_id = $release_id order by id desc limit 1";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        $event_name = f_getEventFromId($row['event_id']);
        $event_time = $row['event_time'];
        echo "<tr class='event_history_release_row' data-ise-release-id='$release_id' data-ise-template-id='$template_id' >
            <td>$release_version</td>
            <td>$event_name</td>
            <td>$event_time</td>
        </tr>";
    }
    //end of f_displayLatestDetailEventPerReleaseByTemplate($release_id,$template_id)
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Return a list of the latest 10 releases on this template in event history table
    //this list contains all releases that had events occurred IN this
    //specific template
    function f_getReleaseIdListfromTemplateId($template_id)
    {
        global $db;
        $list = array();
        $sql_query = "select distinct release_id from event_history where (template_instance_id in (select instance_id from template_instance where template_id = $template_id) or template_id in (select template_id from event_history where template_instance_id = 0 and template_id = $template_id)) and release_id != 0 order by release_id desc limit 20";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc())
        {
            $list[] = $row['release_id'];
        }
        return $list;
    }
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Displaying -- <li>10.0.040 : Test Started</li>
    function f_displayLatestEventPerReleaseByTemplate($template_id, $release_id)
    {
        global $db;
        $release_version = f_getReleaseFromId($release_id);
        //$sql_query = "select event_id from event_history where (template_instance_id in (select instance_id from template_instance where template_id = $template_id) or template_id in (select template_id from event_history where template_instance_id = 0 and template_id = $template_id)) and release_id in ($release_id,0) order by id desc limit 1";

        $sql_query = "select event_id from event_history where (template_instance_id in (select instance_id from template_instance where template_id = $template_id) or template_id in (select template_id from event_history where template_instance_id = 0 and template_id = $template_id)) and release_id = $release_id order by id desc limit 1";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        $event_name = f_getEventFromId($row['event_id']);
        //echo $sql_query;
        echo "<li>$release_version: $event_name</li>";
    }
    //end of f_displayLatestEventPerReleaseByTemplate($template_id, $release_id)
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Return a list of templateInstanceIds base on Template ID
    function f_getTemplateInstanceIdFromTemplateId($template_id)
    {
        global $db;
        $sql_query = "select distinct instance_id from template_instance where template_id = $template_id";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc())
        {
            $list[] = $row['instance_id'];
        }
        return $list;
    }
    //end of f_getTemplateInstanceIdFromTemplateId($template_id)
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Input: nothing
    //Output: html content for display event history by release

    function f_displayHistoryByRelease()
    {
        global $db;
        $sql_query = "select distinct release_id from event_history where release_id != 0 order by release_id desc";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc())
        {
            $release_id = '';
            $release_version = '';
            $release_version_dash = '';
            $template_id_list = array();
            $template_id = '';


            $release_id = $row['release_id'];
            $release_version = f_getReleaseFromId($release_id);
            $release_version_dash = preg_replace( "/\./", "-", $release_version);
            //Get a list of unique template IDs that has instances acted on this release
            $template_id_list = f_getTemplateIdListByReleaseId($release_id);

            echo "
                    <div class='row wide-row'>
                        <div class='small-12 small-centered columns'><!--Event History-->
                            <div class='section_wrapper'>
                                <div class='row'><!--start of header row-->
                                    <div id='section_header_$release_version_dash' class='small-12 columns section_header small-centered'>
                                        <div class='row'>
                                            <div class='small-8 columns header_text'>
                                                <h3 class='font_syncopate'>$release_version</h3>
                                            </div>
                                            <div class='small-4 columns header_stat'>
                                                <ul>
                    ";
                    /*Displaying on the latest event from each available template base on the release
                    <li>BAT: Test Complete</li>
                    <li>OAT: Deploy Complete</li>
                    <li>PAT: Test Started</li>
                    */
                    foreach ($template_id_list as $template_id)
                    {
                        f_displayLatestEventPerTemplateByRelease($release_id, $template_id);
                    }

                    echo "
                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--end of header row-->
                                <div class='row'><!--start of body row-->
                                    <div id='section_body_$release_version_dash' class='section_body'>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Template</th>
                                                    <th>Latest Event</th>
                                                    <th>Entry Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                    ";
                    /*print <tr> here
                     *<tr class='event_history_template_row' data-ise-release='$release_version' data-ise-template='$template' >
                                                    <td>PAT</td>
                                                    <td>Deploy Completed</td>
                                                    <td>2014 15:00:23</td>
                                                </tr>
                    */
                    foreach ($template_id_list as $template_id)
                    {
                        f_displayLatestDetailEventPerTemplateByRelease($release_id, $template_id);
                    }

                    echo "
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!--end of body row-->
                            </div>
                        </div><!--End of event history-->
                    </div>
                    ";
        }
    }
    //end of f_displayHistoryByRelease()
    ///////////////////////////////////////////////////////////////////////////////////////////
    /*print <tr> here
    <tr class='event_history_template_row' data-ise-release='$release_version' data-ise-template='$template' >
        <td>PAT</td>
        <td>Deploy Completed</td>
        <td>2014 15:00:23</td>
    </tr>
    */
    function f_displayLatestDetailEventPerTemplateByRelease($release_id, $template_id)
    {
        global $db;
        //$sql_query = "select ed.event_name,eh.event_time from event_history as eh, event_definition as ed where eh.release_id in ($release_id,0) and (eh.template_instance_id in (select instance_id from template_instance where template_id = $template_id) or eh.template_id in (select template_id from event_history where template_instance_id = 0 and template_id = $template_id)) and eh.event_id = ed.event_id order by eh.id desc limit 1";

        $sql_query = "select ed.event_name,eh.event_time from event_history as eh, event_definition as ed where eh.release_id =$release_id and (eh.template_instance_id in (select instance_id from template_instance where template_id = $template_id) or eh.template_id in (select template_id from event_history where template_instance_id = 0 and template_id = $template_id)) and eh.event_id = ed.event_id order by eh.id desc limit 1";
        $result = $db->query($sql_query) or die($db->error);
        //Display template only if there's event in that template for the specified release
        //So to avoid have "BAT: "
        if($result->num_rows)
        {
            $row = $result->fetch_assoc();
            $event_name = $row['event_name'];
                    $event_name = preg_replace('/_/',' ',$event_name);
                    $event_name = ucwords($event_name);
            $event_time = $row['event_time'];
            $template_name = f_getTemplateFromId($template_id);
            echo "<tr class='event_history_template_row' data-ise-release-id='$release_id' data-ise-template-id='$template_id' >
                    <td>$template_name</td>
                    <td>$event_name</td>
                    <td>$event_time</td>
                </tr>
            ";
        }

    }
    //end of f_displayLatestDetailEventPerTemplateByRelease($release_id, $template_id)
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Get a list of unique template IDs that has instances acted on this release
    function f_getTemplateIdListByReleaseId($release_id)
    {
        global $db;
        $template_id_list = array();
        $sql_query = "select template_id from template";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc())
        {
            $template_id_list[] = $row['template_id'];
        }
        return $template_id_list; //returning a list of template ids
    }
    //end of f_getTemplateIdListByRelease($release_id)
    ///////////////////////////////////////////////////////////////////////////////////////////
    /*Displaying on the latest event from the specified template base on the release id
                    <li>BAT: Test Complete</li>
                    */
    function f_displayLatestEventPerTemplateByRelease($release_id, $template_id)
    {
        global $db;
        //$sql_query = "select ed.event_name from event_history as eh, event_definition as ed where eh.release_id in ($release_id,0) and (eh.template_instance_id in (select instance_id from template_instance where template_id = $template_id) or eh.template_id in (select template_id from event_history where template_instance_id = 0 and template_id = $template_id)) and eh.event_id = ed.event_id order by eh.id desc limit 1";

        $sql_query = "select ed.event_name from event_history as eh, event_definition as ed where eh.release_id =$release_id and (eh.template_instance_id in (select instance_id from template_instance where template_id = $template_id) or eh.template_id in (select template_id from event_history where template_instance_id = 0 and template_id = $template_id)) and eh.event_id = ed.event_id order by eh.id desc limit 1";
        $result = $db->query($sql_query) or die($db->error);
        //Display template only if there's event in that template for the specified release
        //So to avoid have "BAT: "
        if($result->num_rows)
        {
            $row = $result->fetch_assoc();
            $event_name = $row['event_name'];
                    $event_name = preg_replace('/_/',' ',$event_name);
                    $event_name = ucwords($event_name);
            $template_name = f_getTemplateFromId($template_id);
            echo "<li>$template_name: $event_name";
        }
    }
    //end of f_displayLatestEventPerTemplateByRelease
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Return Template name base on template ID
    function f_getTemplateFromId($template_id)
    {
        global $db;
        $sql_query = "select template_name from template where template_id = $template_id";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        return $row['template_name'];
    }
    //end of f_getTemplateFromId()
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Find release version base on release id
    //Iteration 1 -- it's ONLY Core.
    function f_getReleaseFromId($release_id)
    {
        global $db;
        $sql_query = "select version from release_registration where release_id = $release_id";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc(); //since release_id is unique, only one entry can be returned
        $version = $row['version'];
        return $version;
    }
    //end of f_getReleaseFromId()
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Find Events from event id
    function f_getEventFromId($event_id)
    {
        global $db;
        $sql_query = "select event_name from event_definition where event_id = $event_id";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc(); //since release_id is unique, only one entry can be returned
        $event_name = $row['event_name'];
                $event_name = preg_replace('/_/',' ',$event_name);
                $event_name = ucwords($event_name);
        return $event_name;
    }
    //end of f_getEventFromId()
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Find template name base on the template instance id
    function f_getTemplateFromInstanceId($template_instance_id)
    {
        global $db;
        $sql_query = "select template_name from template where template_id in (select template_id from template_instance where instance_id = $template_instance_id)";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc(); //since release_id is unique, only one entry can be returned
        $template_name = $row['template_name'];
        return $template_name;
    }
    //end of f_getTemplateFromInstanceId($template_instance_id)
    ///////////////////////////////////////////////////////////////////////////////////////////
    //This is a generic function that can work for either event history.
  //By release row, or event history by template, they are the same thing.
  //It's always display all events on a release in an env(template).
  function f_displayHistoryModalRow($release_id,$template_id)
    {
        global $db;
        //$sql_query = "select event_id,event_time from event_history where release_id in ($release_id,0) and (template_instance_id in (select instance_id from template_instance where template_id = $template_id) or template_id in (select template_id from event_history where template_instance_id = 0 and template_id = $template_id)) order by id desc";

        $sql_query = "select event_id,event_time from event_history where release_id = $release_id and (template_instance_id in (select instance_id from template_instance where template_id = $template_id) or template_id in (select template_id from event_history where template_instance_id = 0 and template_id = $template_id)) order by id desc";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc())
        {
            $event_name = '';
            $event_time = '';

            $event_name = f_getEventFromId($row['event_id']);
            $event_time = $row['event_time'];

            echo "
                    <tr class='generic_table_row'>
                        <td>$event_name</td>
                        <td>$event_time</td>
          </tr>
            ";
        }//end of while
    }
    //end of f_displayHistoryModalRow($type,$release_id,$template_id)


    function f_displayTemplates()
    {
        global $loggedInUser;
        $return_str = '';
        global $db;
        $sql_query = "SELECT * FROM template;";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc())
        {
            $template_id = $row['template_id'];
            $template_name = $row['template_name'];
            $template_status = $row['template_status'];

            // Figure out if user is logged in and has permissions. Use disabled button if no permissions.
            $btn_disable = '';
            $btn_tooltip = '';

            if(!isUserLoggedIn()){
                $btn_disable = 'disabled';
                $btn_tooltip = 'Unfortunately, you do not have sufficient privileges to change'.
                    ' the state of this template. You must be logged in first and have the necessary'.
                    ' permissions.';
            }
            else{
                if(!$loggedInUser->checkPermission($template_name)){
                    $btn_disable = 'disabled';
                    $btn_tooltip = 'Unfortunately, you do not have sufficient privileges to change'.
                        ' the state of this template';
                }
                else{
                    if($template_status == 0){ //if template is not suspended
                        $btn_tooltip = 'Click to suspend the template. This will prevent pending or'.
                            ' future tasks from running on the template. If there is a running task,'.
                            ' it will complete without interruption.';
                    }
                    else{
                        $btn_tooltip = 'Click to unsuspend the template. If there are any queued'.
                        ' tasks, they will start running.';
                    }
                }
            }

            if($template_status == 0) //if template is not suspended
            {
                $return_str = $return_str .  "<tr>
                    <td><h3>$template_name</h3></td>
                    <td class='btn_td suspend_td' data-ise-template-id='$template_id'->
                      <a href='#' class='button alert small radius $btn_disable' title='$btn_tooltip'>
                        Suspend
                      </a>
                    </td>
                    </tr>";
            }
            else{
                $return_str = $return_str .  "<tr>
                <td><h3>$template_name</h3></td>
                <td class='btn_td unsuspend_td' data-ise-template-id='$template_id'->
                  <a href='#' class='button small radius $btn_disable' title='$btn_tooltip'>
                    Unsuspend
                  </a>
                </td>
                </tr>";
            }
        }//end of while
        return $return_str;
    }

    /****************************************************************/
    /* User Section in top-bar */
    /****************************************************************/

    function f_userHeaderDisplay()
    {
        if(isUserLoggedIn()) {
            global $loggedInUser;
            $name = $loggedInUser->displayname;

            echo "
            <ul class='right' id='top_bar_right'>" .
                f_user_logged_in_display($name) .
            "</ul>";
        }
        else {
            echo "
            <ul class='right' id='top_bar_right'>" .
                f_user_login_form("<a href='#'>.</a>") .
            "</ul>";
        }
    }

    function f_user_logged_in_display($name)
    {
        $tooltip_account  = 'View information about your user account such as allotted '.
            'permissions and registration details';
        $tooltip_settings = 'View and change your email and/or password';
        $tooltip_logout   = 'Logout of the MAP system. You will still be able to access read-only information.';
        return "<li class='has-dropdown'>
                    <a href='#'>$name</a>
                    <ul class='dropdown' id='user_dropdown'>
                        <li><a href='uc_account.php' title='$tooltip_account'>My Account</a></li>
                        <li><a href='uc_user_settings.php' title='$tooltip_settings'>My Settings</a></li>
                        <li><a href='uc_logout.php' title='$tooltip_logout'>Logout</a></li>
                    </ul>
                </li>";
    }

    function f_user_login_form($error_contents)
    {
        return "
        <li class='has-form' id='user_login_form'>
            <form data-abide>
                <div class='row collapse'>

                    <div class='small-1 columns top_bar_error' id='id_top_bar_error'>$error_contents</div>
                    <div class='small-2 columns large-offset-3 medium-offset-2 small-offset-1' id='top_bar_username_div'>
                        <input id='data-ise-username' type='text' placeholder='username' required>
                        <small class='error'>Required</small>
                    </div>
                    <div class='small-2 columns' id='top_bar_password_div'>
                        <input id='data-ise-password' type='password' placeholder='password'
                        onkeydown='if (event.keyCode == 13) submit_user_login(this, event)' required>
                        <small class='error'>Required</small>
                    </div>
                    <div class='small-2 columns' id='top_bar_login_div'>
                        <a href='#' class='button tiny' id='login_button'>Login</a>
                    </div>
                    <div class='small-2 columns end' id='top_bar_register_div'>
                        <a href='uc_register.php' class='button tiny' id='login_button'>Register</a>
                    </div>

                </div>
            </form>
        </li>";
    }

    /****************************************************************/
    /* Submit Test Request Page */
    /****************************************************************/

    function f_displaySubmitTestRequest()
    {
        global $db;

        // Get count of tests
        $sql_query = "SELECT tc.name, tc.description, tc.template_id, tm.template_name ".
            "FROM test_case tc, template tm ".
            "WHERE tc.status='active' and  tm.template_id=tc.template_id order by tc.name";
        $result = $db->query($sql_query) or die($db->error);
        $num_rows = $result->num_rows;

        // Figure out if user is logged in and has permissions. Use disabled button if no permissions.
        $btn_disable = 'disabled'; // By default it should be disabled, until tests are selected
        $btn_tooltip = '';

        if(!isUserLoggedIn()){
            $btn_disable = 'disabled';
            $btn_tooltip = 'You must be logged in first before submitting test requests';
        }
        else{
            $btn_tooltip = 'Select tests from the table to enable this button';
        }

        // Filter row
        echo "
            <div class='row' id='test_request_submit_button_row'>
                <div class='small-12 columns' id='test_request_submit_button_wrapper'>

                    <div class='small-5 columns' id='test_request_search_div'>
                        <input id='test_request_search' type='text' placeholder='Search through tests'
                        title='The entire table will be searched for every character you type. This is a regular expression based search.' />
                    </div>

                    <div class='small-7 columns' id='test_request_submit_button_div'>
                        <a href='#' class='button small radius $btn_disable' id='test_request_submit_button'
                        title='$btn_tooltip'>
                            Submit Selected Test Requests
                        </a>
                    </div>

                </div>
            </div>

            <div class='row' id='test_request_filter_row'>
                <div class='small-12 columns' id='test_request_filter_wrapper'>
                    <div class='small-3 columns' id='test_request_filter_label_div'>
                        <label id='test_request_filter_label'>Filter Tests to Display</label>
                    </div>
                    <div class='small-7 columns test_request_checkboxes_style' id='test_request_checkboxes_div'>
                        <input id='str_checkbox_all' type='checkbox' checked title='Show/hide all tests'>
                            <label for='str_checkbox_all' class='str_checkbox_labels' title='Show/hide all tests'>All</label>";

        // Get templates for displaying checkboxes. Don't include SIT.
        $sql_query = "SELECT * FROM template WHERE template_id != 1 ORDER BY template_name";
        $result = $db->query($sql_query) or die($db->error);

        while($row = $result->fetch_assoc())
        {
            $name = $row['template_name'];
            // str = Submit Test Request
            echo "
                        <input id='str_checkbox_t_$name' type='checkbox' checked title='Show/hide $name tests'>
                            <label for='str_checkbox_t_$name' class='str_checkbox_labels' title='Show/hide $name tests'>$name</label>";
        }

        echo "
                    </div>
                    <div class='small-2 columns' id='test_request_table_count' title='Number of
                        tests available for selection'>
                        $num_rows tests
                    </div>
                </div>
            </div>";
        f_displayTestRequestTable();
    }

    function f_displayTestRequestTable()
    {
        global $db;

        echo "
            <div class='row' id='test_request_table_row'>
                <div class='small-12 columns' id='test_request_table_div'>
                    <table id='test_request_table'>
                        <thead>
                            <tr>
                                <th width='46%'>Name</th>
                                <th width='46%'>Description</th>
                                <th width='8%'>Template</th>
                            </tr>
                        </thead>
                        <tbody id='test_request_table_body' class='test_request_searchable'>";

        $sql_query = "SELECT tc.test_id, tc.name, tc.description, tc.template_id, tm.template_name ".
            "FROM test_case tc, template tm ".
            "WHERE tc.status='active' and  tm.template_id=tc.template_id order by tc.name";
        $result = $db->query($sql_query) or die($db->error);

        while($row = $result->fetch_assoc()){
            $test_id       = $row['test_id'];
            $name          = $row['name'];
            $description   = $row['description'];
            $template_name = $row['template_name'];
            $template_id   = $row['template_id'];
            echo "
                <tr class='str_row_$template_name' id='test_link'
                data-ise-str-test-id='$test_id'
                data-ise-str-test-name='$name'
                data-ise-str-test-template-id='$template_id'>
                    <td class='test_request_table_name'>$name</td>
                    <td class='test_request_table_desc'>$description</td>
                    <td>$template_name</td>
                </tr>";
        }

        echo "
            </tbody>
        </table></div></div>";

    }

    function f_is_user_logged_in()
    {
        return isUserLoggedIn();
    }

    function f_app_version_dropdown($include_label)
    {
        f_dbConnect();
        global $db;

        $return_content = $include_label?'<label>Version':'';

        $return_content .= "
                <select id='test_request_selected_tests_version_select'>";

        $sql_query = "SELECT release_id, version FROM release_registration ORDER BY application, version DESC";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc()){
            $release_id = $row['release_id'];
            $version = $row['version'];
            $return_content .= "
                    <option value='$release_id'>$version</option>";
        }

        $return_content .= "  <select>";
        $return_content .= $include_label?'</label>':'';

        return $return_content;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    // Input:   None
    // Returns: Dictionary mapping template ID to template name
    //          key: Template ID, Value: Template Name
    // Usage:   Call this function and capture the return value into a variable. Then use that
    //          variable for all of your lookups. For example:
    //              $template_mapping = f_get_template_mapping();
    //              echo '$template_mapping[1]'; // where 1 is the template ID you provide
    function f_get_template_mapping(){
        global $db;
        if(!$db){
            f_dbConnect();
        }

        $sql_query = "SELECT template_id, template_name FROM template";
        $result = $db->query($sql_query) or die($db->error);
        $template_mapping = array();
        while($row = $result->fetch_assoc())
            $template_mapping[ $row['template_id'] ] = $row['template_name'];
        return $template_mapping;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    // Input:   None
    // Returns: Dictionary mapping event ID to event name
    //          key: Event ID, Value: Event Name
    // Usage:   Call this function and capture the return value into a variable. Then use that
    //          variable for all of your lookups. For example:
    //              $event_mapping = f_get_event_mapping();
    //              echo '$event_mapping[1]'; // where 1 is the Event ID you provide
    function f_get_event_mapping(){
        global $db;
        if(!$db){
            f_dbConnect();
        }

        $sql_query = "SELECT event_id, event_name FROM event_definition";
        $result = $db->query($sql_query) or die($db->error);
        $event_mapping = array();
        while($row = $result->fetch_assoc())
            $event_mapping[ $row['event_id'] ] = $row['event_name'];
        return $event_mapping;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    // Input:   None
    // Returns: Dictionary mapping template name to event ID
    //          key: Template Name, Value: Template ID
    // Usage:   Call this function and capture the return value into a variable. Then use that
    //          variable for all of your lookups. For example:
    //              $template_mapping_inv = f_get_template_mapping_inv();
    //              echo '$template_mapping_inv['OAT']'; // where 'OAT' is the Template name you provide
    function f_get_template_mapping_inv(){
        global $db;
        if(!$db){
            f_dbConnect();
        }

        $sql_query = "SELECT template_id, template_name FROM template";
        $result = $db->query($sql_query) or die($db->error);
        $template_mapping = array();
        while($row = $result->fetch_assoc())
            $template_mapping[ $row['template_name'] ] = $row['template_id'];
        return $template_mapping;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    // Input:   None
    // Returns: Dictionary mapping event name to event ID
    //          key: Event Name, Value: Event ID
    // Usage:   Call this function and capture the return value into a variable. Then use that
    //          variable for all of your lookups. For example:
    //              $event_mapping_inv = f_get_event_mapping_inv();
    //              echo '$event_mapping_inv['Release Promoted']'; // where 'Release Promoted' is the Event name you provide
    function f_get_event_mapping_inv(){
        global $db;
        if(!$db){
            f_dbConnect();
        }

        $sql_query = "SELECT event_id, event_name FROM event_definition";
        $result = $db->query($sql_query) or die($db->error);
        $event_mapping = array();
        while($row = $result->fetch_assoc())
            $event_mapping[ $row['event_name'] ] = $row['event_id'];
        return $event_mapping;
    }


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

    ///////////////////////////////////////////////////////////////////////////////////////////
?>

