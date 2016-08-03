<?php 	
include("../cfg/functions.php");

$db=db_connect("local");
$sql="SELECT * FROM cfg";
$result=mysql_query($sql, $db);
if (!$result) logProb("COULD NOT READ VALUES FROM CFG DATABASE...\n".$sql."\n".mysql_error());
while ($row=mysql_fetch_array($result)) {
	if ($row['keyA']=="lastload" && $row['keyB']=="firm") { $lastloadfirm=$row['timeA']; }
	if ($row['keyA']=="lastload" && $row['keyB']=="user") { $lastloaduser=$row['timeA']; }
	if ($row['keyA']=="lastload" && $row['keyB']=="adapter") { $lastloadadapter=$row['timeA']; }
	if ($row['keyA']=="lastload" && $row['keyB']=="connection" && $row['valueA']=='1') 
		{ $lastloadprecise=$row['timeA']; $lockedprecise=$row['locked']; }
	if ($row['keyA']=="lastload" && $row['keyB']=="connection" && $row['valueA']=='2') 
		{ $lastloadfix=$row['timeA']; $lockedfix=$row['locked']; }
	if ($row['keyA']=="lastload" && $row['keyB']=="connection" && $row['valueA']=='3') 
		{ $lastloaddti=$row['timeA']; $lockeddti=$row['locked']; }
	if ($row['keyA']=="lastload" && $row['keyB']=="alert") 
		{ $lastloadalert=$row['timeA']; $lockedalert=$row['locked']; }	
	if ($row['keyA']=="threshold" && $row['keyB']=="percentage") { $percentage=$row['valueA']; }
	if ($row['keyA']=="threshold" && $row['keyB']=="timespan") { $timespan=$row['valueA']; }
	if ($row['keyA']=="threshold" && $row['keyB']=="mintime") { $mintime=$row['valueA']; }
	if ($row['keyA']=="threshold" && $row['keyB']=="maxtime") { $maxtime=$row['valueA']; }
	if ($row['keyA']=="threshold" && $row['keyB']=="minconn") { $minconn=$row['valueA']; }
	if ($row['keyA']=="threshold" && $row['keyB']=="marketopen") { $marketopen=$row['valueA']; }
	if ($row['keyA']=="threshold" && $row['keyB']=="marketclose") { $marketclose=$row['valueA']; }
	if ($row['keyA']=="interval" && $row['keyB']=="browserpoll") { $browserpoll=$row['valueA']; }
	if ($row['keyA']=="interval" && $row['keyB']=="backendpoll") { $backendpoll=$row['valueA']; }
}	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>firmcon configuration</title>
	<script type="text/javascript" src="../cfg/jquery-1.5.min.js"></script>
	<style type="text/css">
		body { font-family:Helvetica; }
		td { background-color:#CCC; }
		td.header { background-color:#555; color:#FFF; font-size:24px; font-weight:bold; }
		.description { font-style:italic; }
	</style>
</head>
<body>
<h2>FIRMCON Configuration</h2>
<table cellpadding="5" >
	<tr><td align="center" colspan="4" class="header">Static Data "Lastloads"</td></tr>
	<tr>
		<td><img src="../img/clock_icon&48.png" /></td>
		<td valign="center" width="150"><strong>Firm Lastload</strong></td>
		<td width="300" class="description">Firm Lastload represents the timestamp of the last load of static data for firms from the RDB database to the FIRMCON database.</td>
		<td align="center" width="150"><?php echo ($lastloadfirm); ?></td>
	</tr>
	<tr>
		<td><img src="../img/clock_icon&48.png" /></td>
		<td valign="center" width="150"><strong>Adapter Lastload</strong></td>
		<td width="300" class="description">Adapter Lastload represents the timestamp of the last load of static data for adapters from the Precise and Iors BSI databases to the FIRMCON database.</td>
		<td align="center" width="150"><?php echo ($lastloadadapter); ?></td>
	</tr>
	<tr>
		<td><img src="../img/clock_icon&48.png" /></td>
		<td valign="center" width="150"><strong>User Lastload</strong></td>
		<td width="300" class="description">User Lastload represents the timestamp of the last load of static data for individual users from the VIEWDB, CLEARTRUST, and IORS databases to the FIRMCON database.</td>
		<td align="center" width="150"><?php echo ($lastloaduser); ?></td>
	</tr>	
	<tr><td align="center" colspan="4" class="header">Polling Rates</td></tr>
	<tr>
		<td><img src="../img/stop_watch_icon&48.png" /></td>
		<td valign="center" width="150"><strong>Backend Polling Rate</strong></td>
		<td width="300" class="description">Backend Polling Rate represents how often connection information will be loaded from live databases to the FIRMCON database.</td>
		<td width="150"><?php echo ($backendpoll)." seconds"; ?></td>
	</tr>
	<tr>
		<td><img src="../img/stop_watch_icon&48.png" /></td>
		<td valign="center" width="150"><strong>Browser Polling Rate</strong></td>
		<td width="300" class="description">Browser Polling Rate represents how often each FIRMCON session will refresh connection data from the FIRMCON database.</td>
		<td width="150"><?php echo ($browserpoll/1000)." seconds"; ?></td>
	</tr>
	<tr><td align="center" colspan="4" class="header">Connection "Lastloads"</td></tr>
	<tr><td align="left" colspan="4" class="description" width="650">Lastloads become locked when the respective database connection is inaccessible.<br/>Manual intervention is required to unlock the connection type and try again.</td></tr>
	<tr>
		<td><img src="../img/clock_icon&48.png" /></td>
		<td valign="center" width="150"><strong>Precise Lastload</strong></td>
		<td width="300" class="description">Precise Lastload represents the timestamp of the last load of PRECISE connection data from the Precise database to the FIRMCON database.</td>
		<td align="center" width="150"><?php echo ($lastloadprecise); ?><br/><img src="../img/<?php if ($lockedprecise==0) { echo "padlock_open_icon&48"; } else { echo "padlock_closed_icon&48"; } ?>.png" width="24" /></td>
	</tr>
	<tr>
		<td><img src="../img/clock_icon&48.png" /></td>
		<td valign="center" width="150"><strong>FIX Lastload</strong></td>
		<td width="300" class="description">FIX Lastload represents the timestamp of the last load of FIX connection data from the Iseapps_Config database to the FIRMCON database.</td>
		<td width="150" align="center" ><?php echo ($lastloadfix); ?><br/><img src="../img/<?php if ($lockedfix==0) { echo "padlock_open_icon&48"; } else { echo "padlock_closed_icon&48"; } ?>.png" width="24" /></td>
	</tr>
	<tr>
		<td><img src="../img/clock_icon&48.png" /></td>
		<td valign="center" width="150"><strong>DTI Lastload</strong></td>
		<td width="300" class="description">DTI Lastload represents the timestamp of the last load of DTI connection data from the CMDB database to the FIRMCON database.</td>
		<td width="150" align="center" ><?php echo ($lastloaddti); ?><br/><img src="../img/<?php if ($lockeddti==0) { echo "padlock_open_icon&48"; } else { echo "padlock_closed_icon&48"; } ?>.png" width="24" /></td>
	</tr>
	<tr><td align="center" colspan="4" class="header">Alert Settings</td></tr>
	<tr>
		<td><img src="../img/attention_icon&48.png" /></td>
		<td valign="center" width="150"><strong>Alert Lastload</strong></td>
		<td width="300" class="description">Alert Lastload represents the timestamp of the last calculation of alerts from the FIRMCON database.</td>
		<td width="150" align="center" ><?php echo ($lastloadalert); ?><br/><img src="../img/<?php if ($lockedalert==0) { echo "padlock_open_icon&48"; } else { echo "padlock_closed_icon&48"; } ?>.png" width="24" /></td>
	</tr>
	<tr>
		<td><img src="../img/attention_icon&48.png" /></td>
		<td valign="center" width="150"><strong>Threshold</strong></td>
		<td width="300" class="description">The Threshold setting is the maximum percentage of disconnections that will <strong>not</strong> trigger an alert. If more disconnections exist over the configured timespan (see below), an alert will be triggered. Alerts are triggered per login type (eg. Precise, FIX, or DTI).</td>
		<td width="150"><?php echo ($percentage*100)."%"; ?></td>
	</tr>
	<tr>
		<td><img src="../img/attention_icon&48.png" /></td>
		<td valign="center" width="150"><strong>Timespan</strong></td>
		<td width="300" class="description">Timespan is the amount of time that FIRMCON checks for threshold violations following each disconnect. The timespan begins with each disconnection. FIRMCON takes a snapshot of total connections at both ends of the snapshot to determine if the threshold is breached and an alert is triggered.</td>
		<td width="150"><?php echo ($timespan)." seconds"; ?></td>
	</tr>
	<tr>
		<td><img src="../img/attention_icon&48.png" /></td>
		<td valign="center" width="150"><strong>Minimum Connections</strong></td>
		<td width="300" class="description">Minimum Connections represents the minimum number of existing connections per login type (eg. Precise, FIX, or DTI) required to trigger an alert.</td>
		<td width="150"><?php echo ($minconn); ?></td>
	</tr>
	<tr>
		<td><img src="../img/attention_icon&48.png" /></td>
		<td valign="center" width="150"><strong>Minimum Alert Display Time</strong></td>
		<td width="300" class="description">The Minimum Alert Display Time is the minimum amount of time a triggered alert will be visible in the alert bar. If the alert condition is resolved before the Minimum Alert Display Time, the firm's box will remain in the alert bar until this time elapses. If the alert condition is resolved after this time, the firm's box will be removed from the alert bar.</td>
		<td width="150"><?php echo ($mintime)." minutes"; ?></td>
	</tr>
	<tr>
		<td><img src="../img/attention_icon&48.png" /></td>
		<td valign="center" width="150"><strong>Maximum Alert Display Time</strong></td>
		<td width="300" class="description">The Maximum Alert Display Time is the maximum amount of time a triggered alert will be visible in the alert bar. If the alert condition is resolved before the Maximum Alert Display Time, the firm's box will be removed from the alert bar.</td>
		<td width="150"><?php echo ($maxtime)." minutes"; ?></td>
	</tr>	
	<tr>
		<td><img src="../img/attention_icon&48.png" /></td>
		<td valign="center" width="150"><strong>Market Open Time</strong></td>
		<td width="300" class="description">Market Open Time represents the starting time for recording alerts. Disconnections that would otherwise qualify for an alert will not be recorded before this time.</td>
		<td width="150"><?php echo $marketopen; ?></td>
	</tr>
	<tr>
		<td><img src="../img/attention_icon&48.png" /></td>
		<td valign="center" width="150"><strong>Market Close Time</strong></td>
		<td width="300" class="description">Market Close Time represents the ending time for recording alerts. Disconnections that would otherwise qualify for an alert will not be recorded after this time.</td>
		<td width="150"><?php echo $marketclose; ?></td>
	</tr>
	
</table>
</body>
</html>