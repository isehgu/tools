<?php
	chdir('../');
	include("include/firmcon.php");
	
	$localdb=db_connect("local");
	$action=$_GET['action'];
		
	switch ($action) {
		case "getbrowsercfg":
			$sql="SELECT keyB, valueA FROM cfg WHERE keyA IN ('interval');";
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb("COULD NOT READ INTERVAL VALUES FROM CFG DATABASE...\n".$sql."\n".mysql_error());
			while ($row=mysql_fetch_array($result)) {
				switch ($row['keyB']) {
					case "browserpoll": $browserpoll=$row['valueA']; break;
					case "backendpoll": $backendpoll=$row['valueA']; break;
				}
			}
			$response=array("browserpoll"=>$browserpoll,"backendpoll"=>$backendpoll);
			echo json_encode($response);
		break;
		case "refresh": # get all connections since, well, the 'since' - for each disconnection...do disconnect logic
			refreshFirmcon($_GET['type'],$_GET['interval']); # refreshFirmcon will echo the JSON, see firmcon.php
		break;
		case "refreshllo": # added 2/12/12 for Low Latency Offering tracking
		    refreshLLO();
		break;
		case "refreshAlerts":
			$sql="SELECT keyB, valueA FROM cfg WHERE keyA IN ('threshold');";
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb("COULD NOT READ THRESHOLD VALUES FROM CFG DATABASE...\n".$sql."\n".mysql_error());
			while ($row=mysql_fetch_array($result)) {
				switch ($row['keyB']) {
					case "timespan": $timespan=$row['valueA']; break;
					case "percentage": $percentage=$row['valueA']; break;
					case "mintime": $mintime=$row['valueA']; break;
					case "maxtime": $maxtime=$row['valueA']; break;
					case "minconn": $minconn=$row['valueA']; break;
					case "marketopen": $marketopen=$row['valueA']; break;
					case "marketclose": $marketclose=$row['valueA']; break;
				}
			}			
			refreshAlerts($_GET['interval'], $timespan, (1-$percentage), $mintime, $maxtime, $minconn, $marketopen, $marketclose); 
			# refreshAlerts will echo the JSON, see firmcon.php
		break;
		case "search":
			$searchterm=preg_replace('/\*/','%',$_GET['term']);
			$sql="SELECT symbol FROM firm WHERE name LIKE '".$searchterm."' OR symbol LIKE '".$_GET['term']."' GROUP BY symbol;";
			$result=mysql_query($sql,$localdb);
			if (!$result) logProb("SEARCH FAILED IN DATABASE...\n".$sql."\n".mysql_error());
			$i=0;
			while ($row=mysql_fetch_array($result)) {
				$response[$i++]=array("sym"=>$row['symbol']);
			}
			if (vV($response)) { echo json_encode($response); } else { echo 0; }
		break;
		case "processEOD":
			# Lock all lastloads
			$sql="UPDATE cfg SET locked=1 WHERE keyA='lastload' AND keyB IN ('connection','alert');";
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb("Locking Lastload configurations failed...\n".$sql."\n".mysql_error());
			
			$sql="INSERT INTO historic_connection SELECT NULL, u.name, f.name, f.symbol, u.logintype, c.actiontype, c.node, c.process, c.timestamp, c.exp1 FROM user u, connection c, firm f WHERE u.id=c.userid AND f.id=u.firmid AND u.id NOT IN (SELECT out_identifier FROM adapter) ORDER BY timestamp;";
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb("Could not load connection data for EOD processing...");
			
		break;
		case "livereset":
		case "processSOD":
			# Lock and Reset Alert Lastloads
			$sql="UPDATE cfg SET locked=1, valueA=NULL, valueB=NULL, timeA=NULL, timeB=NULL WHERE keyA='lastload' AND keyB='alert';";
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb("Locking & Resetting Lastload configurations (alert) failed...\n".$sql."\n".mysql_error());
			
			# Lock and Reset Connection Lastloads
			$sql="UPDATE cfg SET locked=1, valueB=NULL, timeA=NULL, timeB=NULL WHERE keyA='lastload' AND keyB='connection';";
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb("Locking & Resetting Lastload configurations (connection) failed...\n".$sql."\n".mysql_error());
			
			# Empty the alert table			
			$sql="TRUNCATE alert;";
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb("Emptying Alert Table Failed...\n".$sql."\n".mysql_error());
			
			# Empty the connection table
			$sql="TRUNCATE connection;";
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb("Emptying Connection Table Failed...\n".$sql."\n".mysql_error());
			
			if ($action=="processSOD") {
				# Selects from system databases and inserts into local databases
				loadStaticDataExternal("firm"); 
				loadStaticDataExternal("user"); 
				loadStaticDataExternal("adapter");
			}
			
			# Unlock the lastloads
			$sql="UPDATE cfg SET locked=0 WHERE keyA='lastload' AND keyB IN ('connection','alert');";
			$result=mysql_query($sql, $localdb);
			if (!$result) logProb("Unlocking Lastload configurations failed...\n".$sql."\n".mysql_error());
		break;
	}
?>