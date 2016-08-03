<?php
	require_once "base_function.php";
	f_dbConnect();

	$newSi = $_POST["newSi"];
	$tid = $_POST["tid"];
	if(strlen($newSi) < 1){
		header("Location: http://asg.ise.com/task");
	}
	else{
	
		$sqlTask="update tasks set total_si = total_si+1 where tid = '$tid'";
		$sqlSi="insert into subitems(sdesc,tid)values('$newSi','$tid')";
		$resultSi=mysql_query($sqlSi);
		$resultTask=mysql_query($sqlTask);
		
		if($resultSi && $resultTask){
			header("Location: http://asg.ise.com/task/index.php?cf=input$tid");
		}
		else{
			echo "Insert failed: ".mysql_error();
		}
	}
?>

