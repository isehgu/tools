<?php
	function f_dbConnect()
	{
		$dbhost = 'localhost';
		$dbuser = 'root';
		$dbpwd = 'Iseoptions1';
		$dbname = 'asg_sirs';
		
		$con = mysql_connect($dbhost, $dbuser, $dbpwd);
		if(!$con){ die('Couldn\'t connect to database: '.mysql_error()); }
		if(!(mysql_select_db($dbname, $con))){ die('Couldn\'t select database: '.mysql_error()); }
	}
	function f_displayTotal($application)
	{
		$total = 0;
		$result = mysql_query("select count(*) as total from sirs where u_application = '$application';") or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{
			$total = $row['total'];
			echo "Total number of SIRs: ". $total;
		}
	
	}
	function f_displayAll($application)
	{
		$result = mysql_query("select * from sirs s left join sirs_custom sc on s.u_id = sc.sir_id where s.u_application = '$application' order by s.asg_pri,s.u_id;") or die(mysql_error());
		echo "<table class='table table-bordered table-condensed'><thead><tr>";
		echo "<th>Rank</th>";
		echo "<th>SIR Number</th>";
		echo "<th>ASG Preferred Release</th>";
		echo "<th>Comment</th>";
		echo "<th>Application</th>";
		echo "<th>Submit Date</th>";
		echo "<th>To Be Fix in Release</th>";
		echo "<th>Requested Release</th>";
		echo "<th>Submitter</th>";
		echo "<th>Classification</th>";
		echo "<th>Priority</th>";
		echo "<th>Current State</th>";
		echo "<th>Funcational Area</th>";
		echo "<th>Short Description</th>";
		echo "<th>Current Owner</th>";
		echo "<th>Fix in Configuration</th>";
		echo "<th>Updated On</th>";
		echo "<th>Updated By</th>";
		echo "</tr></thead>";
		echo "<tbody>";
		while($row = mysql_fetch_array($result))
		{
			$asg_pri = "";
			$id = "";
			$app = "";
			$subDate = "";
			$fixRel = "";
			$reqRel = "";
			$submitter = "";
			$class = "";
			$pri = "";
			$cState = "";
			$fArea = "";
			$description = "";
			$owner = "";
			$fixConfig = "";
			$updateOn = "";
			$updateBy = "";
			$pref_release = "";
			
			$asg_pri = $row['asg_pri'];
			$id = $row['u_id'];
			$app = $row['u_application'];
			$subDate = $row['u_submit_date'];
			$fixRel = $row['u_to_be_fixed_in_release'];
			$reqRel = $row['u_requested_release'];
			$submitter = $row['u_submitter'];
			$class = $row['u_classification'];
			$pri = $row['u_priority'];
			$cState = $row['u_current_state'];
			$fArea = $row['u_functional_area'];
			$description = $row['u_short_description'];
			$owner = $row['u_current_owner'];
			$fixConfig = $row['u_fixed_in_configuration'];
			$updateOn = $row['sys_updated_on'];
			$updateBy = $row['sys_updated_by'];
			$pref_release = $row['pref_release'];
			
			if($pri == 'High')
			{
				echo "<tr id='High'>";
			}
			else if($pri == 'Medium')
			{
				echo "<tr id='Medium'>";
			}
			else # low now
			{
				echo "<tr id='Low'>";
			}
			
			if (!empty($asg_pri)) //if $asg_pri is non-empty
			{
				echo "<td class='alert-error'>" . $asg_pri . "</td>";
			}
			else
			{
				echo "<td>" . $asg_pri . "</td>";
			}
			
			
			echo "<td>" . $id . "</td>";
			echo "<td class='pref_release' id='p_$id'>" . $pref_release . "</td>";
			echo "<td><a href='cmodal' class='btn btn-primary modalbtn' data-toggle='modal' id='c_$id'>Comment</a></td>";
			echo "<td>" . $app . "</td>";
			echo "<td>" . $subDate . "</td>";
			echo "<td>" . $fixRel . "</td>";
			echo "<td>" . $reqRel . "</td>";
			echo "<td>" . $submitter . "</td>";
			echo "<td>" . $class . "</td>";
			echo "<td>" . $pri . "</td>";
			echo "<td>" . $cState . "</td>";
			echo "<td>" . $fArea . "</td>";
			echo "<td>" . $description . "</td>";
			echo "<td>" . $owner . "</td>";
			echo "<td>" . $fixConfig . "</td>";
			echo "<td>" . $updateOn . "</td>";
			echo "<td>" . $updateBy . "</td>";
			echo "</tr>";
		
		}
		echo "</tbody></table>";
	}

	function f_updateRank($rank, $sir,$app)
	{
		if(empty($rank))  //this is simply removing a rank from a SIR, and set it back to null
		{
			mysql_query("update sirs set asg_pri = NULL where u_id = $sir") or die(mysql_error());
			return;
		}
		
		$result = mysql_query("select u_id from sirs where u_application = '$app' and asg_pri = $rank limit 1") or die(mysql_error());
		if(mysql_num_rows($result) <= 0) //this rank is NOT held by any SIR
		{
			mysql_query("update sirs set asg_pri = $rank where u_id = $sir") or die(mysql_error());
		}
		else //If the desired rank is already taken, bump the existing rank holder back one rank
		{
			while($row = mysql_fetch_array($result))
			{
				$newSir = $row['u_id'];
			}
			f_updateRank($rank+1,$newSir,$app); //bump the existing SIR to rank+1
			mysql_query("update sirs set asg_pri = $rank where u_id = $sir") or die(mysql_error());
		}
	
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////// OOOooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO/////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function f_displayTestProgress($start, $end)
	{
		$result = mysql_query("select users.login as Tester, count(distinct tcversion_id, testplan_id) as Completed from testlink.executions left join testlink.users on executions.tester_id = users.id where testplan_id IN (select id from testlink.testplans where testproject_id IN (376,2,1239) and active <> 0) and execution_ts >= '$start' and execution_ts < '$end' group by tester_id") or die(mysql_error());
		
		echo "<table class='table table-bordered table-striped table-condensed'><thead><tr>";
		echo "<th>Tester</th>";
		echo "<th>Completed Test Cases</th>";
		echo "</tr></thead>";
		echo "<tbody>";
		while($row = mysql_fetch_array($result))
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
		$result = mysql_query("select count(distinct tcversion_id, testplan_id) as Completed from testlink.executions where testplan_id IN (select id from testlink.testplans where testproject_id IN (376,2,1239) and active <> 0) and execution_ts >= '2012-10-29 00:00:00'") or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{
			$total = $row['Completed'];
			echo $total;
		}
	}
	
	function f_displayTotalTest()
	{
		$result = mysql_query("select count(*) as Total from testlink.testplan_tcversions tt left join testlink.tcversions t on tt.tcversion_id = t.id
where testplan_id IN (select id from testlink.testplans where testproject_id IN (376,2,1239) and active <> 0)") or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{
			$total = $row['Total'];
			echo $total;
		}
	}
	




?>
