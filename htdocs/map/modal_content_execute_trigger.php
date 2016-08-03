<?php
    require_once "base_function.php";
    f_dbConnect();
    global $db;
    $trigger_id = $_POST['trigger_id'];

    $app = '';
    $version_list = array();

    $sql_query = "SELECT * FROM event_trigger WHERE trigger_id=$trigger_id";
    $result = $db->query($sql_query) or die($db->error);
    $row = $result->fetch_assoc();
    $app = $row['trigger_app'];
    $version = $row['git_branch']?$row['git_branch']:$row['code_track'];
    $search = str_replace('*', '.*', $version);
    $search = '/^'.$search.'$/';
    $release = $app=='Core'?'Code Track':'Release Branch';

    $sql_query = "SELECT version from release_registration where application='$app' ORDER BY version";
    $result = $db->query($sql_query) or die($db->error);
    while($row = $result->fetch_assoc()){
        $version_list[] = $row['version'];
    }

    $matched_results = array();
    $matches = array();
    // Perform pattern matching
    foreach($version_list as $key => $value){
        if(preg_match($search, $value, $matches)){
            $matched_results[] = $value;
        }
    }

    echo "
        <div class='row'>
            <div class='small-8 columns small-offset-2' id=''>
                <h4>Trigger Execution</h4>
                <p>We've searched for matching '$release'(s) for $version and found the following.
                Pick the deployments you would like to perform.</p>
            </div>
        </div>
        <div class='row'>
            <div class='small-4 columns small-offset-2' id=''>
                <ol>
    ";

    foreach($matched_results as $key => $value){
        echo"
                    <li>
                        <input id='exec_trigger_checkbox_$value' type='checkbox' title=''
                        data-ise-trigger-exec-trigger=$trigger_id
                        data-ise-trigger-exec-value=$value>
                        <label for='exec_trigger_checkbox_$value' class='' title=''>$value</label>
                    </li>
        ";
    }
    if(count($matched_results) > 0){
        echo "  </ol>";
    }
    else{
        echo "  </ol>
                <p>No matches found</p>
        ";
    }

    $btn_disable   = 'disabled';
    $btn_tooltip   = 'Select at least one trigger and provide a label to execute.';
    $label_tooltip = 'Select at least one trigger to be able to provide a label.';
    echo "

            </div>
            <div class='small-4 columns end' id=''>
                <input id='trigger_exec_label' type='text' placeholder='Provide a label' disabled='$btn_disable'
                title='$label_tooltip'/>
                <a href='#' class='button success small radius $btn_disable expand' id='trigger_modal_exec_button'
                title='$btn_tooltip'>
                    Execute
                </a>
            </div>
        </div>
        <a class='close-reveal-modal' id='triggers_tests_modal_close_button'>&#215;</a>
    ";

?>