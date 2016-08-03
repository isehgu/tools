<?php
	require_once "base_function.php";
	f_dbConnect();

	$tid = $_GET["tid"];
	
	$sql="update tasks set closed = 1 where tid = '$tid'";
	$result=mysql_query($sql);
	
	if($result){
		header("Location: http://asg.ise.com/task");
	}
	else{
		echo "Insert failed: ".mysql_error();
	}
?>

