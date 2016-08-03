<?php
function db_connect($type) {
	switch ($type) {
		case "local":
			$db = mysql_connect("localhost", "opsmongui", "Iseoptions1");
			mysql_select_db("opsmongui", $db);
		break;
		case "core":
			$db = mysql_connect("11.3.3.234:10100", "gtsdbadmin", "gtsdbadmin");
			mysql_select_db("CMDB", $db);
		break;
		case "iors":
			$db = mssql_connect("pc-sqlv01-olrp\olrp,59719", "iors", "Iors2010");
			mssql_select_db("IORS", $db);
		break;
		case "precise":
			$db = mssql_connect("pc-sqlv01-olrp\olrp,59719", "precise", "Precise2010");
			mssql_select_db("PRECISE", $db);
		break;
		case "cfg":
			$db = mssql_connect("pc-sqlv01-olrp\olrp,59719", "iseapps_config", "Iseapps_config2010");
			mssql_select_db("ISEAPPS_CONFIG", $db);
		break;
		case "ct":
			$db = mssql_connect("pc-sqlv01-olrp\olrp,59719", "ct_admin", "ct_admin");
			mssql_select_db("CT", $db);
		break;
	}
	return $db;
}

#validVariable, checks isset and isblank
function vV($inputvar) {
	return (isset($inputvar) && $inputvar != ""); 
}

#log mySQL errors
function logProb($error) {
	$logfile = "errorLog.txt";
	$fh = fopen($logfile,'a');
	fwrite($fh, "\n".date("Y-m-d H:i:s")."\n");
	fwrite($fh, $error);
	fwrite($fh, "\n");
	fclose($fh);
}

#convert MM/DD/YYYY to YYYY-MM-DD HH:MM:SS
function mmddyyyytodatetime($mmddyyyy) {
	$darray=explode("/",$mmddyyyy);
	$m=$darray[0];
	$d=$darray[1];
	$y=$darray[2];
	return date("Y-m-d H:i:s",mktime(0,0,0,$m,$d,$y));
}

#convert MM/DD/YYYY HH:MM to YYYY-MM-DD HH:MM:SS
function mdyhmtodatetime($date, $time) {
	$darray=explode("/",$date);
	$m=$darray[0];
	$d=$darray[1];
	$y=$darray[2];
	
	$tarray=explode(":",$time);
	$h=$tarray[0];
	$i=$tarray[1];

	return date("Y-m-d H:i:s",mktime($h,$i,0,$m,$d,$y));	
}

#convert YYYY-MM-DD HH:MM:SS to MM/DD/YYYY 
function datetimetommddyyyy($datetime) {
	$y=substr($datetime,0,4);
	$m=substr($datetime,5,2);
	$d=substr($datetime,8,2);
	return date("m/d/Y",mktime(0,0,0,$m,$d,$y));
}

#convert YYYY-MM-DD HH:MM:SS to HH:MM
function datetimetohm($datetime) {
	$h=substr($datetime,11,2);
	$i=substr($datetime,13,2);
	return date("g:i A",mktime($h,$i,0,0,0,0));
}

#convert YYYY-MM-DD HH:MM:SS to MM/DD/YYYY HH:MM PM
function datetimetomdyhm($datetime) {
	$y=substr($datetime,0,4);
	$m=substr($datetime,5,2);
	$d=substr($datetime,8,2);
	$h=substr($datetime,11,2);
	$i=substr($datetime,14,2);
	return date("m/d/Y g:i A",mktime($h,$i,0,$m,$d,$y));
}

#convert YYYY-MM-DD HH:MM:SS to HH:MM:SS PM
function datetimetohms($datetime) {
	$y=substr($datetime,0,4);
	$m=substr($datetime,5,2);
	$d=substr($datetime,8,2);
	$h=substr($datetime,11,2);
	$i=substr($datetime,14,2);
	$s=substr($datetime,17,2);
	return date("g:i:s A",mktime($h,$i,$s,0,0,0));
}

function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function sec2hms ($sec, $padHours = false) {
    $hms = "";
    $hours = intval(intval($sec) / 3600); 
    // add hours to $hms (with a leading 0 if asked for)
    $hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":"  : $hours. ":";
    // dividing the total seconds by 60 will give us the number of minutes
    // in total, but we're interested in *minutes past the hour* and to get
    // this, we have to divide by 60 again and then use the remainder
    $minutes = intval(($sec / 60) % 60); 
    // add minutes to $hms (with a leading 0 if needed)
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";
    // seconds past the minute are found by dividing the total number of seconds
    // by 60 and using the remainder
    $seconds = intval($sec % 60); 
    // add seconds to $hms (with a leading 0 if needed)
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
    return $hms;
}

?>
