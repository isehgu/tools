<?php
    require_once("shared.php");

///////////////////////////////////////////////////////////////////////////////////////////
    //Html content for display event history by release
    function f_displayHistoryByTemplate()
    {
        // $time_start = microtime_float();
        global $db;
        $release_list = f_get_release_mapping();
        $template_list = f_get_template_mapping();
        $sql_query = "select distinct template_id from template order by template_name";
        $result = $db->query($sql_query) or die($db->error);
        while($row = $result->fetch_assoc())
        {
            $time_start = microtime_float();
            $template_id = '';
            $template_instance_list = array();
            $release_id_list = array();

            $template_id = $row['template_id'];
            $template_name = $template_list[$template_id];
            // $release_id_list = f_getReleaseIdListfromTemplateId($template_id);//get a list of releases on this template in event history table
            // echo"<br>";
            // $time_end = microtime_float();
            // $time = $time_end - $time_start;
            // echo "PHP run time is $time seconds\n";
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

            // echo"<br>";
            // $time_end = microtime_float();
            // $time = $time_end - $time_start;
            // echo "PHP run time is $time seconds\n";

            //events_list is a hash of release_id => [event_name,event_time]
            $events_list = f_getLatestEventPerReleaseByTemplate($template_id);



            //Displaying -- <li>10.0.040 : Test Started</li>
            foreach($events_list as $release_id=>$event)
            {
                $release_version = $release_list[$release_id];
                $event_name = $event[0];
                $event_name = preg_replace('/_/',' ',$event_name);
                $event_name = ucwords($event_name);

                echo "<li>$release_version : $event_name";
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
            // foreach ($release_id_list as $release_id)
            // {
            //     f_displayLatestDetailEventPerReleaseByTemplate($release_id,$template_id);
            // }

            foreach($events_list as $release_id=>$event)
            {
                $release_version = $release_list[$release_id];
                $event_name = $event[0];
                $event_name = preg_replace('/_/',' ',$event_name);
                $event_name = ucwords($event_name);
                $event_time = $event[1];
                echo "<tr class='event_history_release_row' data-ise-release-id='$release_id' data-ise-template-id='$template_id' >
                    <td>$release_version</td>
                    <td>$event_name</td>
                    <td>$event_time</td>
                </tr>";
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
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        echo "PHP run time is $time seconds\n";
    }
    //end of f_displayHistoryByTemplate()
///////////////////////////////////////////////////////////////////////////////////////////
    //Input: nothing
    //Output: html content for display event history by release

    function f_displayHistoryByRelease()
    {
        // $time_start = microtime_float();
        global $db;
        $template_list = f_get_template_mapping();
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


                    // $time_end = microtime_float();
                    // $time = $time_end - $time_start;
                    // echo "PHP run time is $time seconds\n";
            echo "
                    <div class='row wide-row'>
                        <div class='small-12 small-centered columns'><!--Event History-->
                            <div class='section_wrapper'>
                                <div class='row'><!--start of header row-->
                                    <div id='section_header_$release_version_dash' data-ise-release-id='$release_id' class='small-12 columns section_header small-centered'>
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

                    //$event_list is a hash that contains the latest event_name, and event_time by template_id for
                    //the given release_id
                    $events_list = f_getLatestEventPerTemplateByRelease($release_id);
                    foreach($events_list as $template_id=>$event)
                    {
                        $template_name = $template_list[$template_id];
                        $event_name = $event[0];
                        $event_name = preg_replace('/_/',' ',$event_name);
                        $event_name = ucwords($event_name);

                        echo "<li>$template_name: $event_name";
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
                    //foreach loop for the section body table
                    foreach($events_list as $template_id=>$event)
                    {
                        $template_name = $template_list[$template_id];
                        $event_name = $event[0];
                        $event_name = preg_replace('/_/',' ',$event_name);
                        $event_name = ucwords($event_name);
                        $event_time = $event[1];
                        echo "<tr class='event_history_template_row' data-ise-release-id='$release_id' data-ise-template-id='$template_id' >
                                <td>$template_name</td>
                                <td>$event_name</td>
                                <td>$event_time</td>
                            </tr>
                        ";
                    }//end of foreach loop on the section body

                    echo "
                                </tbody>
                                        </table>
                                    </div>
                                </div><!--end of body row-->
                            </div><!--end of section_wrapper-->
                        </div><!--End of event history-->
                    </div>
                    ";

        }//end of while loop

    }
    //end of f_displayHistoryByRelease()
///////////////////////////////////////////////////////////////////////
    //input: None
    //output: returns latest id in event_history
    function f_get_last_event_id_from_db(){
        global $db;
        $sql_query = 'SELECT id FROM event_history ORDER BY id DESC LIMIT 1';
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        return $row['id'];
    }
    ///////////////////////////////////////////////////////////////////////////////////////////
    //Displaying -- <li>10.0.040 : Test Started</li>
    function f_displayLatestEventPerReleaseByTemplate($template_id, $release_id)
    {
        global $db;
        $release_version = f_getReleaseFromId($release_id);
        //$sql_query = "select event_id from event_history where (template_instance_id in (select instance_id from template_instance where template_id = $template_id) or template_id in (select template_id from event_history where template_instance_id = 0 and template_id = $template_id)) and release_id in ($release_id,0) order by id desc limit 1";

        $sql_query = "select event_id from event_history where template_id = $template_id and release_id = $release_id order by id desc limit 1";
        $result = $db->query($sql_query) or die($db->error);
        $row = $result->fetch_assoc();
        $event_name = f_getEventFromId($row['event_id']);
        //echo $sql_query;
        echo "<li>$release_version: $event_name</li>";
    }
    //end of f_displayLatestEventPerReleaseByTemplate($template_id, $release_id)
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

?>
