<?php
	require_once "base_function.php";
	f_dbConnect();

	$tid = $_POST["tid"];
	$sql = "select sid from subitems where status = 0 and tid = '$tid'";
	$sidArray = array();
	$result = mysql_query($sql) or die(mysql_error());
	//echo mysql_num_rows($result);
	if(mysql_num_rows($result) <= 0){
		echo "empty result";
	}
	while($row = mysql_fetch_array($result))
	{
		//echo "I am in the loop<br>";
		$status = null;
		$sid = $row['sid'];
		$status = $_POST["$sid"];
		if(!isset($status)){ //if user didn't select either complete or remove, just move to the next sid
			//echo "I am in null status<br>";
			continue;
		}
		else{ //Now user selected something
			if($status == 'complete'){
				$sqlStatus = "update subitems set status = 1 where sid = '$sid'";
				$sqlTask = "update tasks set total_complete = total_complete+1 where tid = '$tid'";
				$resultSi=mysql_query($sqlStatus) or die(mysql_error()); //update subitems table
				$resultTask=mysql_query($sqlTask) or die(mysql_error()); //update tasks table
			}
			else{ //Now status can only be remove
				//echo "I am in remove $sid<br>";
				$sqlStatus = "delete from subitems where sid = '$sid'";
				$sqlTask = "update tasks set total_si = total_si-1 where tid = '$tid'";
				$resultSi=mysql_query($sqlStatus) or die(mysql_error()); //update subitems table
				//echo "I am after resultSi<br>";
				$resultTask=mysql_query($sqlTask) or die(mysql_error()); //update tasks table
			}//End of updating
		}//End of updating user selection of complete or remove
	}
	header("Location: http://asg.ise.com/task");
?>

