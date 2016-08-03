<?php
	include('include/firmcon.php');
	$localdb=db_connect('local');
	
	#default is only 1 firm per userview
	$symbol=$_GET['symbol'];
	
	$todayYMD=date('Y-m-d');
	
	# default ALERTMODE = NO
	$alertmode=(vV($_GET['alertid']));
	
	# default ALERT CONTEXT = Do NOT show alert context (ie. connections not involved in alert)
	$wc_acontext=(vV($_GET['acontext'])) ? "" : " AND sa.timestamp IS NOT NULL ";
	
	# default NOCONN = Show all possible connections
	$wc_noconn=(vV($_GET['noconn'])) ? " AND connected IS NOT NULL " : "";
	
	# default ALLDTI = Do NOT show Precise/FIX DTI's
	$wc_alldti=(vV($_GET['alldti'])) ? "" : " AND u.id NOT IN (SELECT DISTINCT out_identifier FROM adapter) ";
	
	# default LLO mode = NO
	$llomode=$wc_llo=(vV($_GET['llo'])) ? " AND (e.process REGEXP 'Gateway-3[0123456789]-[12]_001' OR e.process REGEXP 'Gateway-2[456789]-[12]_001') " : "";
	
	if ($alertmode) {
		$alertid=$_GET['alertid'];
		
		$sqlU="SELECT valueA FROM cfg WHERE keyA='threshold' AND keyB='timespan';";
		$resultU=mysql_query($sqlU, $localdb);
		if (!$resultU) logProb("COULD NOT READ THRESHOLD VALUES FROM CFG DATABASE...\n".$sqlU."\n".mysql_error());
		$rowU=mysql_fetch_array($resultU);
		$timespan=$rowU['valueA'];
		
		#$sql="SELECT u.name as loginname, u.logintype, u.exp1 as uexp1, u.exp2 as uexp2, COALESCE(c.exp1,'- -') as cexp1, f.symbol, f.bu, COALESCE(c.node,'- -') as node, COALESCE(c.process,'- -') as process, COALESCE(c.connected,-1) as connected, c.timestamp, sa.timestamp as alerttime FROM user u LEFT JOIN (SELECT logintype, SUM(actiontype) as connected, node, process, userid, MAX(timestamp) as timestamp, MAX(exp1) as exp1 FROM connection WHERE timestamp > '".$todayYMD." 00:00:00' GROUP BY userid HAVING SUM(actiontype) IN (0,1)) c ON u.id=c.userid LEFT JOIN firm f ON f.id=u.firmid LEFT JOIN (SELECT ccc.timestamp, uuu.id FROM connection ccc, user uuu, firm fff, (SELECT ff.symbol, aa.triggered as triggered, DATE_ADD(aa.triggered, INTERVAL ".$timespan." SECOND) as posttrigger FROM alert aa, firm ff WHERE aa.firmid=ff.id AND aa.triggered > '".$todayYMD." 00:00:00' AND aa.id=".$alertid.") aaa WHERE uuu.id=ccc.userid AND uuu.firmid=fff.id AND fff.symbol=aaa.symbol AND ccc.actiontype=-1 AND ccc.timestamp >= aaa.triggered AND ccc.timestamp <= aaa.posttrigger) sa ON sa.id=u.id WHERE f.symbol='".$symbol."' ".$wc_noconn." ".$wc_alldti." ".$wc_acontext." GROUP BY u.name ORDER BY u.logintype, f.bu, connected DESC, loginname;";		
		$sql="SELECT u.name as loginname, u.logintype, u.exp1 as uexp1, u.exp2 as uexp2, COALESCE(sa.exp1,'- -') as cexp1, f.symbol, f.bu, COALESCE(sa.node,'- -') as node, COALESCE(sa.process,'- -') as process, COALESCE(c.connected,-1) as connected, c.timestamp, sa.timestamp as alerttime FROM user u LEFT JOIN (SELECT logintype, SUM(actiontype) as connected, userid, MAX(timestamp) as timestamp FROM connection WHERE timestamp > '".$todayYMD." 00:00:00' GROUP BY userid HAVING SUM(actiontype) IN (0,1)) c ON u.id=c.userid LEFT JOIN firm f ON f.id=u.firmid LEFT JOIN (SELECT ccc.timestamp, uuu.id, ccc.node, ccc.process, ccc.exp1 FROM connection ccc, user uuu, firm fff, (SELECT ff.symbol, aa.triggered as triggered, DATE_ADD(aa.triggered, INTERVAL ".$timespan." SECOND) as posttrigger FROM alert aa, firm ff WHERE aa.firmid=ff.id AND aa.triggered > '".$todayYMD." 00:00:00' AND aa.id=".$alertid.") aaa WHERE uuu.id=ccc.userid AND uuu.firmid=fff.id AND fff.symbol=aaa.symbol AND ccc.actiontype=-1 AND ccc.timestamp >= aaa.triggered AND ccc.timestamp <= aaa.posttrigger) sa ON sa.id=u.id WHERE f.symbol='".$symbol."' ".$wc_noconn." ".$wc_alldti." ".$wc_acontext." GROUP BY u.name ORDER BY u.logintype, f.bu, connected DESC, loginname;";		
			
		$uvdata[0]=array("label"=>"Type", 					"width"=>'70',	"dbfield"=>"logintype", 	"active"=>true);
		$uvdata[1]=array("label"=>"User Login", 			"width"=>'250',	"dbfield"=>"loginname", 	"active"=>true);
		$uvdata[2]=array("label"=>"BU", 					"width"=>'70',	"dbfield"=>"bu", 			"active"=>true);
		$uvdata[3]=array("label"=>"Alert Trigger",			"width"=>'200',	"dbfield"=>"alerttime", 	"active"=>$alertmode);
		$uvdata[4]=array("label"=>"Session", 				"width"=>'80',	"dbfield"=>"cexp1", 		"active"=>true);
		$uvdata[5]=array("label"=>"Node", 					"width"=>'100',	"dbfield"=>"node", 			"active"=>true);
		$uvdata[6]=array("label"=>"Process", 				"width"=>'130',	"dbfield"=>"process", 		"active"=>true);
		$uvdata[7]=array("label"=>"Current Connectivity", 	"width"=>'200',	"dbfield"=>"connected", 	"active"=>true);
	} else {
		if ($llomode) {
			$sql="SELECT u.name as loginname, u.logintype, u.exp1 as uexp1, u.exp2 as uexp2, COALESCE(c.exp1,'- -') as cexp1, f.symbol, f.bu, COALESCE(e.node,'- -') as node, COALESCE(e.process,'- -') as process, COALESCE(c.connected,-1) as connected, timestamp FROM user u LEFT JOIN (SELECT logintype, SUM(actiontype) as connected, node, process, userid, MAX(timestamp) as timestamp, MAX(exp1) as exp1 FROM connection WHERE timestamp > '".$todayYMD." 00:00:00' GROUP BY userid HAVING SUM(actiontype) IN (0,1)) c ON u.id=c.userid LEFT JOIN (SELECT userid, node, process FROM connection ORDER BY timestamp DESC) e ON e.userid=u.id LEFT JOIN firm f ON f.id=u.firmid WHERE connected IS NOT NULL ".$wc_llo." ".$wc_noconn." ".$wc_alldti." GROUP BY u.name ORDER BY u.logintype, f.bu, connected DESC, loginname;";
		} else {
			$sql="SELECT u.name as loginname, u.logintype, u.exp1 as uexp1, u.exp2 as uexp2, COALESCE(c.exp1,'- -') as cexp1, f.symbol, f.bu, COALESCE(e.node,'- -') as node, COALESCE(e.process,'- -') as process, COALESCE(c.connected,-1) as connected, timestamp FROM user u LEFT JOIN (SELECT logintype, SUM(actiontype) as connected, node, process, userid, MAX(timestamp) as timestamp, MAX(exp1) as exp1 FROM connection WHERE timestamp > '".$todayYMD." 00:00:00' GROUP BY userid HAVING SUM(actiontype) IN (0,1)) c ON u.id=c.userid LEFT JOIN (SELECT userid, node, process FROM connection ORDER BY timestamp DESC) e ON e.userid=u.id LEFT JOIN firm f ON f.id=u.firmid WHERE f.symbol='".$symbol."' ".$wc_noconn." ".$wc_alldti." GROUP BY u.name ORDER BY u.logintype, f.bu, connected DESC, loginname;";
		}
		$uvdata[0]=array("label"=>"Type", 					"width"=>'70',	"dbfield"=>"logintype", 	"active"=>true);
		$uvdata[1]=array("label"=>"User Login", 			"width"=>'250',	"dbfield"=>"loginname", 	"active"=>true);
		$uvdata[2]=array("label"=>"BU", 					"width"=>'70',	"dbfield"=>"bu", 			"active"=>true);
		$uvdata[3]=array("label"=>"Alert Trigger",			"width"=>'200',	"dbfield"=>"alerttime", 	"active"=>$alertmode);
		$uvdata[4]=array("label"=>"Current Connectivity", 	"width"=>'200',	"dbfield"=>"connected", 	"active"=>true);
		$uvdata[5]=array("label"=>"Session", 				"width"=>'80',	"dbfield"=>"cexp1", 		"active"=>true);
		$uvdata[6]=array("label"=>"Node", 					"width"=>'100',	"dbfield"=>"node", 			"active"=>true);
		$uvdata[7]=array("label"=>"Process", 				"width"=>'130',	"dbfield"=>"process", 		"active"=>true);
	}
	
	$result=mysql_query($sql, $localdb);
	#logProb($sql."\n".mysql_error());
	if (!$result) { logProb($sql."\n".mysql_error()); }
	
	# construct summary description - $userviewdescription
	if ($alertmode) {
		$alertcount=mysql_num_rows($result);
		$disconnectionphrase=($alertcount>1)?$alertcount." ".$symbol." disconnections":$alertcount." ".$symbol." disconnection";
		
		$sqlU="SELECT triggered, DATE_ADD(triggered, INTERVAL ".$timespan." SECOND) as posttriggered FROM alert WHERE id=".$alertid.";";
		$resultU=mysql_query($sqlU, $localdb);
		if (!$resultU) logProb("COULD NOT READ TRIGGER VALUE FROM LOCAL DATABASE...\n".$sqlU."\n".mysql_error());
		$rowU=mysql_fetch_array($resultU);		
		
		$userviewdescription="Displaying ".$disconnectionphrase." that triggered an alert between ".datetimetohms($rowU['triggered']).
			" and ".datetimetohms($rowU['posttriggered'])."";
	} else if ($llomode) {
		$userviewdescription="Displaying all Low Latency Offering connectivity";
	} else {
		$userviewdescription="Displaying ".$symbol." connectivity";
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>firmcon 0.8</title>
	<script type="text/javascript" src="cfg/jquery-1.5.min.js"></script>
	<script type="text/javascript" src="js/userview.js"></script>
	<link href="css/firmcon.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div id="firmconhd" class="fullbar">
        <div id="firmcontitle">FIRMCON: Firm Connectivity Monitor - Userview</div>
		<div id="userviewtopnav"><a href="firmcon.php"><img src="img/home_icon&48.png" width="26" /></a></div>
    </div>
	
	<div id="userwrapperouter"><div id="userwrapperinner" >
		<div class="userviewcontainer" id="userviewcontrolwrapper"></div>
		<div class="userviewcontainer" id="userviewdatawrapper">
			<div class="userviewdatacontainer" id="userviewdataheader">
				<table cellspacing="0" cellpadding="0" ><tr>
					<?php
						for ($i=0; $i<count($uvdata); $i++) {
							if ($uvdata[$i]['active']) {
								echo "<td align='left' width='".$uvdata[$i]['width']."'>".$uvdata[$i]['label']."</td>";
							}
						}
					?>
				</tr></table>
			</div>
			<div class="userviewdatacontainer" id="userviewdatatable">
				<table cellspacing="0" cellpadding="0" >
				<?php while ($row=mysql_fetch_array($result)) { 
					switch ($row['logintype']) {
						case 1: $typephrase="Precise"; break;
						case 2: $typephrase="FIX"; break;
						case 3: $typephrase="DTI"; break;
					}
					switch ($row['connected']) {
						case -1: $connectedclass="NOCONN"; $connectedphrase="No Connections"; break;
						case 0: $connectedclass="DISCONN"; $connectedphrase="Disconnected at ".datetimetohms($row['timestamp']).""; break;
						case 1: $connectedclass="CONN"; $connectedphrase="Connected at ".datetimetohms($row['timestamp']).""; break;
					}
					echo "<tr>";
					for ($i=0; $i<count($uvdata); $i++) {
						if ($uvdata[$i]['active']) {
							switch ($uvdata[$i]['dbfield']) {
								case "logintype": 
									echo "<td valign='center' width='".$uvdata[$i]['width']."'>".$typephrase."</td>";
								break;
								case "loginname":
									$loginname=($row['logintype']==1)? $row['loginname']." (".$row['uexp1']." ".$row['uexp2'].")" : $row['loginname'];
									echo "<td valign='center' width='".$uvdata[$i]['width']."'>".$loginname."</td>";
								break;
								case "connected": 
									echo "<td valign='center' width='".$uvdata[$i]['width']."' class='".$connectedclass."'>".$connectedphrase."</td>";
								break;
								case "alerttime": 
									$alertphrase=(vV($row['alerttime']))?"Disconnected at ".datetimetohms($row['alerttime']) : "- -";
									echo "<td valign='center' width='".$uvdata[$i]['width']."' class='DISCONN'>".$alertphrase."</td>"; 
								break;
								case "node":
									$nodename=($row['logintype']==3)? strtolower(substr($row['node'],0,2)."-".substr($row['node'],2)) : strtolower($row['node']);
									echo "<td valign='center' width='".$uvdata[$i]['width']."'>".$nodename."</td>";
								break;
								default: echo "<td valign='center' width='".$uvdata[$i]['width']."'>".$row[''.$uvdata[$i]['dbfield'].'']."</td>";
							}
						}
					}
					echo "</tr>";					
				}  ?>
				</table>
			</div>
			<div class="userviewdatacontainer" id="userviewdatasummary"><?php echo $userviewdescription; ?></div>
		</div>
	</div></div>
    
	<div id="firmconft" class="fullbar">
        <div id="firmconsummaryhd">Connectivity Summary</div>
		<div id="firmconsummarybd">
			<div class="firmconsummaryfield">Low Latency: <div class="firmconsummaryvalue" id="lloconn">0</div></div>
			<div class="firmconsummaryfield">DTI: <div class="firmconsummaryvalue" id="totalconn3">0</div></div>
			<div class="firmconsummaryfield">FIX: <div class="firmconsummaryvalue" id="totalconn2">0</div></div>
			<div class="firmconsummaryfield">Precise: <div class="firmconsummaryvalue" id="totalconn1">0</div></div>
		</div>
    </div>
</body>
</html>