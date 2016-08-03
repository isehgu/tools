<?php

    require_once("models/config.php");
    require_once("ise/php/shared.php");
    require_once("ise/php/events.php");
    require_once("ise/php/tasks.php");
    require_once("ise/php/tests.php");
    require_once("ise/php/topbar.php");
    require_once("ise/php/tools.php");
    require_once("ise/php/templates.php");
    require_once("ise/php/triggers.php");
    require_once("ise/php/statistics.php");



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
        elseif ($type == 'tasks_by_release') f_displayTasksByRelease(); //Not implemented yet
        elseif ($type == 'tasks_by_template') f_displayTasksByTemplate();
        elseif ($type == 'templates_display') f_displayTemplates();
        elseif ($type == 'test_live') f_displayTestsLive();
        elseif ($type == 'test_user_history_by_label') f_displayTestsUserHistory('label');
        elseif ($type == 'test_user_history_by_test') f_displayTestsUserHistory('test');
        elseif ($type == 'test_complete_history') f_displayTestsCompleteHistory();
        elseif ($type == 'test_request') f_displaySubmitTestRequest();
        elseif ($type == 'manage_my_triggers') f_manageTriggers();
        elseif ($type == 'view_all_triggers') f_viewAllTriggers();
        elseif ($type == 'statistics_summary') f_displayStatisticsSummary();
        elseif ($type == 'statistics_detailed') f_displayStatisticsDetailed();
        elseif ($type == 'etcd') f_displayETCDDump();
        // elseif ($type == 'events') f_debug_events();
        else echo "<h1 class='font_syncopate'>$type is unknown</h1>";
    }
    //end of f_contentDisplay()



    ///////////////////////////////////////////////////////////////////////
    //input: None
    //output: Prints table of event_history
/*
    function f_debug_events(){
        global $db;

        $template_mapping = f_get_template_mapping();
        $event_mapping    = f_get_event_mapping();
        $release_mapping  = f_get_release_mapping();
        $user_mapping     = f_get_user_mapping();

        echo "
            <div class='row' id=''>
                <div class='small-12 columns' id='triggers_login_message'>
                    <table>
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>release_id</th>
                                <th>template_instance_id</th>
                                <th>event_id</th>
                                <th>event_time</th>
                                <th>task_id</th>
                                <th>template_id</th>
                                <th>user_id</th>
                            </tr>
                        </thead>
                        <tbody>
        ";

        $sql_query = "SELECT * FROM event_history ORDER BY event_time;";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc())
        {
            $id = $row['id'];
            $release_id = $release_mapping[$row['release_id']];
            $template_instance_id = $template_mapping[$row['template_instance_id']];
            $event_id = $event_mapping[$row['event_id']];
            $event_time = $row['event_time'];
            $task_id = $row['task_id'];
            $template_id = $template_mapping[$row['template_id']];
            $user_id = $user_mapping[$row['user_id']];
            echo "
                            <tr>
                                <td>$id</td>
                                <td>$release_id</td>
                                <td>$template_instance_id</td>
                                <td>$event_id</td>
                                <td>$event_time</td>
                                <td>$task_id</td>
                                <td>$template_id</td>
                                <td>$user_id</td>
                            </tr>
            ";
        }

        echo "
                        </tbody>
                    </table>
                </div>
            </div>";
    }
    */
?>

