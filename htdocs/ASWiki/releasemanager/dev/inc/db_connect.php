<?php
//Database Information
$dbhost = "localhost";
$dbname = "rregisterdb_dev";
$dbuser = "root";
$dbpass = "";

//Connect to database
mysql_connect ( $dbhost, $dbuser, $dbpass)or die("Could not connect: ".mysql_error());
mysql_select_db($dbname) or die(mysql_error());


?>
