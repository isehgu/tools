<?php

function number_updated(){
	include 'inc/db_connect.php';

	$query="SELECT COUNT(sire) as cntSire FROM releases WHERE new_entry='1'";
	$res=mysql_query($query);
	$row=mysql_fetch_array($res);
	
	if ($row['cntSire'] != 0){
		if ($row['cntSire'] == 1){
			echo "<div id='left-corner-red'>";
			echo "<a href='sir_import.php' id='red-link'>There is 1 new entry in the database</a>";
			echo "</div>";
		} else {
			echo "<div id='left-corner-red'>";
			echo "<a href='sir_import.php' id='red-link'>There are ".$row['cntSire']." new entries in the database</a>";
			echo "</div>";
		}
	} else {
		echo "<div id='left-corner'>";
		echo "There is no new entry in the database";
		echo "</div>";
	}
	mysql_close();
}

function last_update(){
	include 'inc/db_connect.php';

	$query="SELECT sire_date_update FROM updates";
	$res=mysql_query($query);
	$row=mysql_fetch_array($res);

	$time=explode(" ",$row['sire_date_update']);
	
	echo "Last SIRe update was done on ".$time[0]." at ".$time[1];
	
	echo "<p><a href='connect_sir.php' class='button' id='sire-db' onclick='this.blur();'><span><b>Get new SIRe</b></span></a></p>";
	mysql_close();
}

?>