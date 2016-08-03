<?php
/*
 *
 *<div class='row'>
        <div class='small-12 columns'>
          <h1 class='font_syncopate'>10.0.040 : OAT</h1>
        </div>
      </div>

      <div class='row'>
        <div class='modal_content small-12 columns'>
          <table>
            <thead>
              <tr>
                <th>Latest Event</th>
                <th>Entry Time</th>
              </tr>
            </thead>
            <tbody>
              <tr class='generic_table_row'>
                <td>Test Started</td>
                <td>2014 15:00:23</td>
              </tr>
              <tr class='generic_table_row'>
                <td>Deploy Completed</td>
                <td>2014 14:00:23</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <a class="close-reveal-modal">&#215;</a>
*/

  require_once "base_function.php";
  f_dbConnect();
  $caller = $_POST['caller'];

  if($caller == 'rerun_history')
  {
    $group_id = $_POST['group_id'];
    $test_or_label = $_POST['test_or_label'];
    echo "
      <div class='row'>
            <div class='small-12 columns'>
              <h1 class='font_syncopate'>Rerun History</h1>
            </div>
          </div>

          <div class='row'>
            <div class='modal_content small-12 columns'>
    ";
    f_displayTestRerunHistory($group_id, $test_or_label);
    echo"
          </div>
        </div>
        <a class='close-reveal-modal'>&#215;</a>
    ";
  }//end of if($caller == 'rerun_history')

  if(($caller == 'templaterow') || ($caller == 'releaserow'))
  {
    $release_id = $_POST['release_id'];
    $template_id = $_POST['template_id'];
    $release_version = f_getReleaseFromId($release_id);
    $template_name = f_getTemplateFromId($template_id);
    if($caller == 'templaterow') $header = $release_version." : ".$template_name;
    if($caller == 'releaserow') $header = $template_name." : ".$release_version;

    echo "
      <div class='row'>
            <div class='small-12 columns'>
              <h1 class='font_syncopate'>$header</h1>
            </div>
          </div>

          <div class='row'>
            <div class='modal_content small-12 columns'>
              <table>
                <thead>
                  <tr>
                    <th>Event</th>
                    <th>Event Time</th>
                  </tr>
                </thead>
                <tbody>
    ";

    //This is a generic function that can work for either event history
    //by release, or event history by template.
    //first argument is the type
    f_displayHistoryModalRow($release_id,$template_id);

    echo"
        </tbody>
            </table>
          </div>
        </div>
        <a class='close-reveal-modal'>&#215;</a>

    ";
  }//end of if(($caller == 'templaterow') || ($caller == 'releaserow'))

?>