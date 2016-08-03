<?php
	function f_dbConnect()
	{
		global $db;
		$dbhost = 'localhost';
		$dbuser = 'root';
		$dbpwd = '';
		$dbname = 'tasket';
		
		$db = new mysqli($dbhost,$dbuser,$dbpassword,$dbname);
		if(!$db) echo "Connection failed: ".$db->connect_error; //if condition here can also be -- if !$mysqli
		
	}
	function f_displayTotal()
	{
		global $db;
		$total = 0;
		$result = $db->query("select count(*) as total from tasks where complete = 0") or die($db->error);
		while($row = $result->fetch_assoc())
		{
			$total = $row['total'];
			echo "<p class='lead pull-left'>Total number of Open Tasks: ". $total. "</p>";
		}
	
	}
	
	//This is to display the available hours per user on top of the page.
	function f_displayHours()
	{
		global $db;
		$result = $db->query("select id,available_hours from personnel") or die($db->error);
		$jason = 0;
		$carlos = 0;
		while($row = $result->fetch_assoc())
		{
			$uid = 0;
			$uid = $row['id'];
			if ($uid == 2) $carlos = $row['available_hours'];
			elseif ($uid == 3) $jason = $row['available_hours'];
		}

		echo "
					<form class='form-inline' method='post' action='hours.php'>
						<div class='span3'>
							Jason's Available Hrs <input class='input-mini' type='text' name='jason' value='$jason'>
						</div>
						<div class='span3'>
							Carlos' Availbe Hrs <input class='input-mini' type='text' name='carlos' value='$carlos'>

						</div>
						<div class='span1'>
							<input type='submit' class='btn btn-primary'>
						</div>
						
					</form>	
				
		";
	}
	
	//User's remain hours
	function f_displayRemainHours()
	{
		global $db;
		$result = $db->query("select id,available_hours from personnel") or die($db->error);
		$jason = 0;
		$carlos = 0;
		while($row = $result->fetch_assoc())
		{
			$uid = 0;
			$uid = $row['id'];
			if ($uid == 2) $carlos = $row['available_hours'];
			elseif ($uid == 3) $jason = $row['available_hours'];
		}
		//Now that we have the available hours, time to subtract from tasks
		$result = $db->query("select assignee_id,required_hours from tasks where rank is not null and complete = 0") or die($db->error);
		while($row = $result->fetch_assoc())
		{
			$uid = 0;
			$uid = $row['assignee_id'];
			if($uid == 2) $carlos = $carlos - $row['required_hours'];
			elseif ($uid == 3) $jason = $jason - $row['required_hours'];																						 
		}
		
		echo "<div class='span3'><strong>Jason's Remaining Hours: </strong>$jason</div>";
		echo "<div class='span3'><strong>Carlos' Remaining Hours: </strong>$carlos</div>";
	}
	
	//Setting the available hours per user
	function  f_setHours($user)
	{
		global $db;
		$user_id = array(
									"jason"=>3,
									"carlos"=>2
								);
		//Time to update the personnel table
		foreach($user as $name => $hours)
		{
			$uid = 0; //resetting uid
			$uid = $user_id[$name];
			//Updating database's personnel table
			$result = $db->query("update personnel set available_hours = $hours where id = $uid") or die($db->error);
			//echo "update personnel set available_hours = $hours where id = $uid";
		}
	}
	function f_displayAll()
	{
		global $db;
		$result = $db->query("select * from tasks where rank is not null and complete = 0 order by rank") or die($db->error);
		$result_norank = $db->query("select * from tasks where rank is null and complete = 0 order by time desc") or die($db->error);
		echo "<table id='tasktable' class='table table-bordered table-condensed'><thead><tr>";
		echo "<th><i class='icon-resize-vertical'></i>Rank</i></th>";
		echo "<th><i class='icon-resize-vertical'></i>ID</th>";
		echo "<th><i class='icon-resize-vertical'></i>Task</th>";
		echo "<th><i class='icon-resize-vertical'></i>Group</th>";
		echo "<th><i class='icon-resize-vertical'></i>Last Timestamp</th>";
		echo "<th><i class='icon-resize-vertical'></i>Assigned To</th>";
		echo "<th><i class='icon-resize-vertical'></i>Hours</th>";
		//echo "<th>Comment</th>";
		echo "</tr></thead>";
		echo "<tbody>";
		while($row = $result->fetch_assoc())
		{
			$rank = "";
			$task = "";
			$group = "";
			$timestamp = "";
			$assignee_id = "";
			$id = "";
			$hours = 0;
			
			$rank = $row['rank'];
			$task = $row['task'];
			$group = $row['task_group'];
			$time = $row['time'];
			$assignee_id = $row['assignee_id'];
			$id = $row['id'];
			$hours = $row['required_hours'];
			$assignee = f_getAssigneeById($assignee_id);
			
			switch ($assignee)
			{
				case "Carlos Bautista":
					$color_class = 'carlos_color';
					break;
				case "Han Gu":
					$color_class = 'han_color';
					break;
				case "Jason Wasserzug":
					$color_class = 'jason_color';
					break;
				case "PAT Testing":
					$color_class = 'pat_color';
					break;
				default:
					$color_class = '';
			}
			echo "<tr class='task_wrapper $color_class' id='$id'>";
			echo "<td>" . $rank. "</td>";
		echo "<td>" . $id. "</td>";
			echo "<td>" . $task . "</td>";
			echo "<td>" . $group . "</td>";
			echo "<td>" . $time . "</td>";
			echo "<td>" . $assignee . "</td>";
			echo "<td>" . $hours . "</td>";
			//echo "<td><a href='#cmodal' class='btn btn-primary modalbtn' data-toggle='modal' id='c_$id'>Comment</a></td>";
			echo "</tr>";
		
		}
    
		while($row = $result_norank->fetch_assoc())
		{
			$rank = "";
			$task = "";
			$group = "";
			$timestamp = "";
			$assignee_id = "";
			$id = "";
			$hours = 0;
			
			$rank = $row['rank'];
			$task = $row['task'];
			$group = $row['task_group'];
			$time = $row['time'];
			$assignee_id = $row['assignee_id'];
			$id = $row['id'];
			$hours = $row['required_hours'];
			$assignee = f_getAssigneeById($assignee_id);
			
			switch ($assignee)
			{
				case "Carlos Bautista":
					$color_class = 'carlos_color';
					break;
				case "Han Gu":
					$color_class = 'han_color';
					break;
				case "Jason Wasserzug":
					$color_class = 'jason_color';
					break;
				case "PAT Testing":
					$color_class = 'pat_color';
					break;
				default:
					$color_class = '';
			}
			
			echo "<tr class='task_wrapper $color_class' id='$id'>";
			echo "<td>" . $rank. "</td>";
			echo "<td>" . $id. "</td>";
			echo "<td>" . $task . "</td>";
			echo "<td>" . $group . "</td>";
			echo "<td>" . $time . "</td>";
			echo "<td>" . $assignee . "</td>";
			echo "<td>" . $hours . "</td>";
			//echo "<td><a href='#cmodal' class='btn btn-primary modalbtn' data-toggle='modal' id='c_$id'>Comment</a></td>";
			echo "</tr>";
		
		}
    
		echo "</tbody></table>";
	}
	
	//Displaying Completed Tasks -- latest 50
	function f_displayComplete()
	{
		global $db;
		$result = $db->query("select * from tasks where complete = 1 order by time desc limit 50") or die($db->error);
		echo "<table id='completetasktable' class='table table-bordered table-condensed'><thead><tr>";
		echo "<th>Rank</th>";
		echo "<th>ID</th>";
		echo "<th>Task</th>";
		echo "<th>Group</th>";
		echo "<th>Last Timestamp</th>";
		echo "<th>Assigned To</th>";
		echo "<th><i class='icon-resize-vertical'></i>Hours</th>";
		echo "</tr></thead>";
		echo "<tbody>";
		while($row = $result->fetch_assoc())
		{
			$rank = "";
			$task = "";
			$group = "";
			$timestamp = "";
			$assignee_id = "";
			$id = "";
			$hours = 0;
			
			$rank = $row['rank'];
			$task = $row['task'];
			$group = $row['task_group'];
			$time = $row['time'];
			$assignee_id = $row['assignee_id'];
			$id = $row['id'];
			$assignee = f_getAssigneeById($assignee_id);
			$hours = $row['required_hours'];
			
			switch ($assignee)
			{
				case "Carlos Bautista":
					$color_class = 'carlos_color';
					break;
				case "Han Gu":
					$color_class = 'han_color';
					break;
				case "Jason Wasserzug":
					$color_class = 'jason_color';
					break;
				case "PAT Testing":
					$color_class = 'pat_color';
					break;
				default:
					$color_class = '';
			}
			
			echo "<tr class='completed_task_wrapper $color_class' id='$id'>";
			echo "<td>" . $rank. "</td>";
			echo "<td>" . $id. "</td>";
			echo "<td>" . $task . "</td>";
			echo "<td>" . $group . "</td>";
			echo "<td>" . $time . "</td>";
			echo "<td>" . $assignee . "</td>";
			echo "<td>" . $hours . "</td>";
			echo "</tr>";
		
		}    
		echo "</tbody></table>";
	}

	function f_updateRank($rank, $id)
	{
		global $db;
		if(empty($rank)) //if user removed rank when updating the task, then set the rank to null
		{
			$db->query("update tasks set rank = NULL where id = $id") or die($db->error);
			return;
		}
		//Now if a rank is updated, then check if there's an existing task with that rank, if so, bump it down
		$result = $db->query("select id from tasks where rank = $rank and id <> $id and complete = 0 limit 1") or die($db->error);
		if($result->num_rows <= 0) //this rank is NOT held by any task, including itself, so just update that task with the rank
		{
			$db->query("update tasks set rank = $rank where id = $id") or die($db->error);
		}
		else //If the desired rank is already taken, bump the existing rank holder back one rank
		{
			while($row = $result->fetch_assoc())
			{
				$newId = $row['id'];
			}
			f_updateRank($rank+1,$newId); //bump the existing SIR to rank+1
			$db->query("update tasks set rank = $rank where id = $id") or die($db->error); //update the task with rank
		}
	
	}

	//Add a task and return the id on it
	function f_addTask($task,$group)
	{
		global $db;
		if(!get_magic_quotes_gpc()) //if special characters are not automatically escaped with a backslash, then manually do it.
		{
			$task = addslashes($task);
			$group = addslashes($group);
		}
		$db->query("insert into tasks (task,task_group) values('$task','$group')") or die($db->error);
		return $db->insert_id;
		
	}
  
  //Take a task id, and display a form with values set to those in the database
  function f_displayTask($id)
  {
    global $db;
    $result = $db->query("select * from tasks where id = $id limit 1") or die ($db->error);
    $row = $result->fetch_assoc();
    
    $rank = "";
    $task = "";
    $group = "";
    $timestamp = "";
    $assignee_id = "";
    $id = "";
    $comment ="";
		$complete = 0;
		$hours = 0;
    
    $rank = $row['rank'];
    $task = $row['task'];
    $group = $row['task_group'];
    $assignee_id = $row['assignee_id'];
    $id = $row['id'];
    $comment = $row['comment'];
    $assignee = f_getAssigneeById($assignee_id);
		$complete = $row['complete'];
		$hours = $row['required_hours'];

    echo "
      <form id='modform' method='post' action='task_update.php'>
        <input type='hidden' name='id' value='$id'>
	";
	if($complete) echo "<label>Rank:</label><input class='input-mini' type='text' name='rank' placeholder='Enter Rank Here' value='$rank' disabled>";
	else echo "<label>Rank:</label><input class='input-mini' type='text' name='rank' placeholder='Enter Rank Here' value='$rank'>";
	echo "
        <label>Task:</label><input class='input-xxlarge' type='text' name='task' value=\"$task\">
        <label>Group:</label><input type='text' name='group' placeholder='Enter Group Here' value=\"$group\">
    ";
    f_displayFormAssignee($assignee);
    echo "
		<label>Hours:</label><input class='input-mini' type='text' name='required_hours' value=\"$hours\">
		<label>Comment:</label><textarea id='writeup_input' class='input-xxlarge' rows='5' placeholder='Enter Comment Here' name='comment'>$comment</textarea>
        <button class='btn btn-primary' type='submit'>Submit</button>
    </form>";
    
  }
  
  //Return an array of all the names in the personnel table
  function f_getAssignees()
  {
    global $db;
    $personnel = array();
    $personnel[] = 'None'; //None is always the first to show
    $result = $db->query("select name from personnel order by name") or die($db->error);
    while($row = $result->fetch_assoc())
    {
      if($row['name'] == 'None') continue; //None is already the first element, so skip over it  
      $personnel[] = $row['name'];
    }
    
    return $personnel;
  }
  
  //Echoing <select> html statements for assignee
  function f_displayFormAssignee($assignee)
  {
    $personnel = f_getAssignees();
    echo "<label>Assignee:</label><select name='assignee'>";
    foreach ($personnel as $person)
    {
      if($person == $assignee) echo "<option selected='selected' value='$person'>$person</option>";
      else echo "<option value='$person'>$person</option>";
    }//End of foreach
    echo "</select>";
  }
  
  //Take the person's id and return the name
  function f_getAssigneeById($assignee_id)
  {
    global $db;
    $result = $db->query("select name from personnel where id = $assignee_id limit 1") or die($db->error);
    $row = $result->fetch_assoc();
    return $row['name'];
  }
  
  //Assume $_POST variables can be accessed, update the task
	function f_updateTask()
	{
		global $db;
		
		$rank = $_POST["rank"];
		$task = $_POST["task"];
		$group = $_POST["group"];
		$id = $_POST["id"];
		$comment = $_POST["comment"];
		$assignee = $_POST["assignee"];
		$assignee_id = f_getIdByAssignee($assignee);
		$hours = $_POST['required_hours'];
		
		if(!get_magic_quotes_gpc()) //if auto-backslash is off, then do it manually
		{
			$task = addslashes($task);
			$group = addslashes($group);
			$comment = addslashes($comment);
		}
		f_updateRank($rank,$id); //update rank
		
		//Update the task except rank
    //echo "update tasks set task = '$task', task_group = '$group', assignee = $assignee_id, comment = '$comment' where id = $id";
    //echo "<br>";
		$db->query("update tasks set required_hours = $hours, task = '$task', task_group = '$group', assignee_id = $assignee_id, comment = '$comment' where id = $id") or die($db->error);
	}
  
  //Return assignee's id from the personnel table base on the name
  function f_getIdByAssignee($assignee)
  {
    global $db;
    $result = $db->query("select id from personnel where name = '$assignee' limit 1") or die($db->error);
    $row = $result->fetch_assoc();
    return $row['id'];
  }
  
  //Set task with $id to complete
  function f_taskComplete($id)
  {
    global $db;
    $db->query("update tasks set complete = 1 where id = $id") or die($db->error);
  }
  //Set task with $id to open
  function f_taskReopen($id)
  {
    global $db;
    $db->query("update tasks set complete = 0,rank = null where id = $id") or die($db->error);
  }
  //Delete task with $id
  function f_taskDelete($id)
  {
    global $db;
    $db->query("delete from tasks where id = $id") or die($db->error);
  }
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////// OOOooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO/////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function f_displayComment($id)
	{
		global $db;
		$result = $db->query("select comment from tasks where id = $id") or die($db->error);
		
		while ($row = $result->fetch_assoc())
		{
			$comment = $row['comment'];
			
		}
		return $comment;
	}
		
	function f_updateComment($id,$comment)
	{
		global $db;
		$comment = addslashes($comment);
		$db->query("update tasks set comment = concat(comment,'$comment') where id = $id")or die($db->error);
		if ($db->affected_rows){return 1;} //if effected row from update statement is more than 0, then update complete
		else
		{
			$db->query("update tasks set comment = '$comment' where id = $id") or die($db->error);
		}
		return 1;
	
	}
  
  function f_displayTestProgress($start, $end)
	{
		$result = $db->query("select users.login as Tester, count(distinct tcversion_id, testplan_id) as Completed from testlink.executions left join testlink.users on executions.tester_id = users.id where testplan_id IN (select id from testlink.testplans where testproject_id IN (376,2,1239) and active <> 0) and execution_ts >= '$start' and execution_ts < '$end' group by tester_id") or die($db->error);
		
		echo "<table class='table table-bordered table-striped table-condensed'><thead><tr>";
		echo "<th>Tester</th>";
		echo "<th>Completed Test Cases</th>";
		echo "</tr></thead>";
		echo "<tbody>";
		while($row = $result->fetch_assoc())
		{
			$tester = "";
			$completed = 0;
			
			$tester = $row['Tester'];
			$completed = $row['Completed'];
			echo "<tr>";
			echo "<td>" . $tester . "</td>";
			echo "<td>" . $completed . "</td>";
			echo "</tr>";
		}
		echo "</tbody></table>";
	}
	
	function f_displayTotalCompleted()
	{
		$result = $db->query("select count(distinct tcversion_id, testplan_id) as Completed from testlink.executions where testplan_id IN (select id from testlink.testplans where testproject_id IN (376,2,1239) and active <> 0) and execution_ts >= '2012-10-29 00:00:00'") or die($db->error);
		while($row = $result->fetch_assoc())
		{
			$total = $row['Completed'];
			echo $total;
		}
	}
	
	function f_displayTotalTest()
	{
		$result = $db->query("select count(*) as Total from testlink.testplan_tcversions tt left join testlink.tcversions t on tt.tcversion_id = t.id
where testplan_id IN (select id from testlink.testplans where testproject_id IN (376,2,1239) and active <> 0)") or die($db->error);
		while($row = $result->fetch_assoc())
		{
			$total = $row['Total'];
			echo $total;
		}
	}
	




?>
