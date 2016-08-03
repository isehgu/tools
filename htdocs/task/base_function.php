<?php
	function f_dbConnect()
	{
		$dbhost = 'localhost';
		$dbuser = 'root';
		$dbpwd = '';
		$dbname = 'taskprogress';
		
		$con = mysql_connect($dbhost, $dbuser, $dbpwd);
		if(!$con){ die('Couldn\'t connect to database: '.mysql_error()); }
		if(!(mysql_select_db($dbname, $con))){ die('Couldn\'t select database: '.mysql_error()); }
	}
	
	function f_displayMain()
	{
		$completion = 0;
		$result = mysql_query("select * from tasks where closed != 1 order by tid desc") or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{
			$tid = $row['tid'];
			$tdesc = $row['tdesc'];
			$total_si = $row['total_si'];
			$total_comp = $row['total_complete'];
			if($total_si != 0){
				$completion = (int)(($total_comp/$total_si) * 100)."%"; //Completion percentage
			}
			else{
				$completion = "0%";
			}
			f_displayTask($tid,$tdesc,$total_si,$total_comp,$completion);
		}
	}//End of f_display
	
	function f_displayTask($tid,$tdesc,$total_si,$total_comp,$completion)
	{
		echo"
			<div class='row-fluid'>
				<div class='span7'>
					<span style='font-size:14px'><strong>$tdesc</strong> -- <span class='badge badge-info'>$total_comp</span> out of <span class='badge badge-inverse'>$total_si</span> Completed</span>
					<a class='btn btn-success btn-mini pull-right' href='closeTask.php?tid=$tid'><i class='icon-check icon-white'></i> Close</a>
					<div class='progress'>
						<div class='bar' style='width: $completion;' title='$completion'></div>
					</div>
				</div>
				<div class='span5'>
					<div></br></div>
					<form class='form myform' action='addSi.php' method='post'>
						<div class='input-append'>
							<input type='text' name='newSi' class='input myinput' id='input$tid' placeholder='Max 100 Characters' maxlength='100'>
							<input type='hidden' name='tid' value='$tid'>
							<button type='submit' class='btn btn-primary btn-mini'><i class='icon-edit icon-white'></i> Add</button>
							<button type='button' class='btn btn-primary btn-mini' data-toggle='collapse' data-target='#$tid'>
							<i class='icon-th-list icon-white'></i> Show Detail
							</button>
							
						</div>					
					</form>
				</div>
				</br>
			</div>
			
			<div id='$tid' class='collapse'>
				<form class='form' action='updateSi.php' method='post'>
					<table class='table table-hover table-condensed table-mine'>
						<thead>
							<tr>
								<th style='width: 8%'>Completed</th>
								<th style='width: 7%'>Remove</th>
								<th style='width: 5%'>ID</th>
								<th style='width: 80%'>Description</th>
							</tr>
						</thead>
						<tbody>
							"; //End of the echo
		f_displaySi($tid);
		
		echo "									
						</tbody>
					</table>
					<input type='hidden' name='tid' value='$tid'>					
					<button type='submit' class='btn btn-primary btn-small'>Save</button>
					<button type='reset' class='btn btn-primary btn-small' value='Reset'>Reset</button>
				</form>
			</div>"; //End of the echo
	
	} //End of f_displayTask
	
	function f_displaySi($tid)
	{
		$result = mysql_query("select * from subitems where tid = '$tid' order by status,sid") or die(mysql_error());
		while($row = mysql_fetch_array($result))
		{
			$sid = $row['sid'];
			$sdesc = $row['sdesc'];
			$status = $row['status'];
			
			if ($status < 1){ //if status is not completed
				echo "
				<tr>
				<td><input type='radio' name='$sid' value='complete'></td>
				<td><input type='radio' name='$sid' value='remove'></td>
				<td>$sid</td>
				<td>$sdesc</td>
				</tr>
				"; //end of echo
			}
			else{ //if status is completed
				echo "
				<tr>
				<td><input type='radio' name='$sid' value='complete' disabled checked></td>
				<td>N/A</td>
				<td>$sid</td>
				<td><del>$sdesc</del></td>
				</tr>
				"; //end of echo
			}
		}
	}
	
?>
