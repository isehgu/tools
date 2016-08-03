<?php
    require_once "base_function.php";
    f_dbConnect();
    global $db;
    $trigger_id = $_POST['trigger_id'];

    $return_data = array();
    $sql_query = "SELECT * FROM trigger_to_test WHERE trigger_id=$trigger_id";
    $result = $db->query($sql_query) or die($db->error);
    while($row = $result->fetch_assoc()){
        $return_data[] = intval($row['test_id']);
    }
    echo json_encode($return_data);
?>
