<?php
    require_once "base_function.php";
    require_once("models/config.php");
    global $loggedInUser;

    $trigger_id = $_GET['trigger_id'];
    trigger_get_row($trigger_id);
?>
