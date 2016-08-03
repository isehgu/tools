<?php
    require_once("shared.php");

    //Displaying the template page
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


?>
