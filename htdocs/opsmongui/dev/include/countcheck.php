<?php 	
	include("../cfg/functions.php");

$extdbA=db_connect("precise");
$sqlA="SELECT (SELECT COUNT(*) FROM as_audit_details a, as_events s WHERE a.evt_id=s.evt_id AND a.result='Success' AND a.entity='Session' AND a.user_action='Login' AND s.evt_timestamp> '".date("Y-m-d")." 00:00:00') - (SELECT COUNT(*) FROM as_audit_details a, as_events s WHERE a.evt_id=s.evt_id AND a.result='Success' AND a.entity='Session' AND a.user_action='Logout' AND s.evt_timestamp> '".date("Y-m-d")." 00:00:00') 'precisecount'";
$resultA=mssql_query($sqlA, $extdbA);
$rowA=mssql_fetch_array($resultA);
echo "TOTAL PRECISE: [".$rowA['precisecount']."]<br/>";

$extdbB=db_connect("cfg");
$sqlB="SELECT (SELECT COUNT(*) FROM dbo.ops_events WHERE machine_name like '%bsi02%' AND result='Success' AND user_action='Login' AND evt_ts > '".date("Y-m-d")." 00:00:00')
-(SELECT COUNT(*) FROM dbo.ops_events WHERE machine_name like '%bsi02%' AND result='Success' AND user_action='Logout' AND evt_ts > '".date("Y-m-d")." 00:00:00') 'iorscount'";
$resultB=mssql_query($sqlB, $extdbB);
$rowB=mssql_fetch_array($resultB);
echo "TOTAL IORS: [".$rowB['iorscount']."]<br/>";

$extdbC=db_connect("core");
$sqlC="SELECT COUNT(*) as corecount FROM CMDB.SessionStateActive S, CMDB.Process P WHERE P.ProcessNameID=S.GatewayID AND S.IsActive=1  AND P.ProcessName LIKE 'Gateway%%'";
$resultC=mysql_query($sqlC, $extdbC);
$rowC=mysql_fetch_array($resultC);
echo "TOTAL CORE GW: [".$rowC['corecount']."]<br/>";

echo date("Y-m-d H:i:s", strtotime('now'))."<br/>";
echo date("Y-m-d H:i:s", strtotime('-1 second'))."<br/>";

?>