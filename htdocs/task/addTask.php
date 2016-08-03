<?php
	require_once "base_function.php";
	f_dbConnect();

	$newTask = $_POST["newTask"];
	
	$sql="insert into tasks(tdesc)values('$newTask')";
	$result=mysql_query($sql);
	
	if($result){
		header("Location: http://asg.ise.com/task/index.php?cf=inputTask");
	}
	else{
		echo "Insert failed: ".mysql_error();
	}
?>

