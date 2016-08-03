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
	function f_displayAll()
	{
		global $db;
		$result = $db->query("select * from tasks where rank is not null and complete = 0 order by rank") or die($db->error);
    $result_norank = $db->query("select * from tasks where rank is null and complete = 0 order by time desc") or die($db->error);
		echo "<table class='table table-bordered table-condensed'><thead><tr>";
		echo "<th>Rank</th>";
    echo "<th>ID</th>";
		echo "<th>Task</th>";
		echo "<th>Group</th>";
		echo "<th>Last Timestamp</th>";
		echo "<th>Assigned To</th>";
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
			
			$rank = $row['rank'];
			$task = $row['task'];
			$group = $row['task_group'];
			$time = $row['time'];
			$assignee_id = $row['assignee_id'];
			$id = $row['id'];
      $assignee = f_getAssigneeById($assignee_id);
			
			echo "<tr class='task_wrapper' id='$id'>";
			echo "<td>" . $rank. "</td>";
      echo "<td>" . $id. "</td>";
			echo "<td>" . $task . "</td>";
			echo "<td>" . $group . "</td>";
			echo "<td>" . $time . "</td>";
			echo "<td>" . $assignee . "</td>";
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
			
			$rank = $row['rank'];
			$task = $row['task'];
			$group = $row['task_group'];
			$time = $row['time'];
			$assignee_id = $row['assignee_id'];
			$id = $row['id'];
      $assignee = f_getAssigneeById($assignee_id);
			
			echo "<tr class='task_wrapper' id='$id'>";
			echo "<td>" . $rank. "</td>";
      echo "<td>" . $id. "</td>";
			echo "<td>" . $task . "</td>";
			echo "<td>" . $group . "</td>";
			echo "<td>" . $time . "</td>";
			echo "<td>" . $assignee . "</td>";
			//echo "<td><a href='#cmodal' class='btn btn-primary modalbtn' data-toggle='modal' id='c_$id'>Comment</a></td>";
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
    
    $rank = $row['rank'];
    $task = $row['task'];
    $group = $row['task_group'];
    $assignee_id = $row['assignee_id'];
    $id = $row['id'];
    $comment = $row['comment'];
    $assignee = f_getAssigneeById($assignee_id);

    echo "
      <form id='modform' method='post' action='task_update.php'>
        <input type='hidden' name='id' value='$id'>
        <label>Rank:</label><input class='input-mir' type='text' name='rank' placeholder='Enter Rank Here' value='$rank'>
        <label>Task:</label><input class='input-xxlarge' type='text' name='task' value=\"$task\">
        <label>Group:</label><input type='text' name='group' placeholder='Enter Group Here' value=\"$group\">
    ";
    f_displayFormAssignee($assignee);
    echo "<label>Comment:</label><textarea id='writeup_input' class='input-xxlarge' rows='5' placeholder='Enter Comment Here' name='comment'>$comment</textarea>
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
		$db->query("update tasks set task = '$task', task_group = '$group', assignee_id = $assignee_id, comment = '$comment' where id = $id") or die($db->error);
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
