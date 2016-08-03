<?php 	
	include("../cfg/functions.php");

$db=db_connect("local");
$sqlA="UPDATE cfg SET value='".date("Y-m-d", strtotime('yesterday'))." 23:59:59';";
$resultA=mysql_query($sqlA, $db);

echo "Reloaded BSISpec configuration: [ OK ]<br/>";

echo date("Y-m-d", strtotime('yesterday'))." 23:59:59<br/>";

?>