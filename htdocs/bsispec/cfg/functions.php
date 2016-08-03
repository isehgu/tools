<?php
function db_connect($type) {
	switch ($type) {
		case "iors":
			$db = mssql_connect("dc-sql01.test.ise.com\SQM1,59897", "iors_tms_gts03", "iors_tms_gts03");
			mssql_select_db("iors_tms_gts03", $db);
		break;
		case "precise":
			$db = mssql_connect("dc-sql01.test.ise.com\SQM1,59897", "bsi_tms", "bsi_tms");
			mssql_select_db("bsi_tms", $db);
		break;
		case "cfg":
			$db = mssql_connect("dc-sql01.test.ise.com\SQM1,59897", "config_mt3", "config_mt3");
			mssql_select_db("config_mt3", $db);
		break;
		case "local":
			$db = mysql_pconnect("localhost", "root", "");
			mysql_select_db("bsispec", $db);
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
	fwrite($fh, $error);
	fwrite($fh, "\r\n");
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

?>
