<?php

require_once "../base_function.php";
f_dbConnect();
if(!isset($_COOKIE['user'])){header('Location: tac_stats.php');}
$type = $_GET['type'];

function get_data(){
    global $db;
    $data = array();

    $sql_query = "select * from stats_weekly order by id";
    $result = $db->query($sql_query) or die($db->error);

    while($row = $result->fetch_assoc()){
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
        $test_count = $row['test_count'];

        if (preg_match("/\d\d\d\d-(\d)(\d)-(\d)(\d)/", $start_date, $matches)){
            $m1 = $matches[1];
            $m2 = $matches[2];
            $d1 = $matches[3];
            $d2 = $matches[4];
            if($m1 == 0){$m1 = "";}
            if($d1 == 0){$d1 = "";}
            $start_date = $m1.$m2."/".$d1.$d2;
        }
        if (preg_match("/\d\d\d\d-(\d)(\d)-(\d)(\d)/", $end_date, $matches)){
            $m1 = $matches[1];
            $m2 = $matches[2];
            $d1 = $matches[3];
            $d2 = $matches[4];
            if($m1 == 0){$m1 = "";}
            if($d1 == 0){$d1 = "";}
            $end_date = $m1.$m2."/".$d1.$d2;
        }

        $data[] = array("start_date" => $start_date,
            "end_date" => $end_date, "test_count"  => $test_count);
    }

    echo json_encode($data);
}
get_data();
?>
