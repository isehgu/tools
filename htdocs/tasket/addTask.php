<?php
    require_once 'base_function.php';
    f_dbConnect();

    $task = $_POST['task'];
    $group = $_POST['group']; 
    $id = f_addTask($task,$group);
    
    header("Location: index.php");
?>