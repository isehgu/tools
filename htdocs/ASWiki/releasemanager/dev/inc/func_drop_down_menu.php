<?php

// Function to create the application drop down menu
function drop_app() {
include 'db_connect.php';

$query="SELECT distinct(application) FROM releases";
$result=mysql_query($query) or die(mysql_error());

echo "<select name='application'>";
echo "<option value='a'>Application</option>";

while($row = mysql_fetch_array($result)){
	echo "<option value='".$row['application']."'>".$row['application']."</option>";
}
echo "</select>";

mysql_close();
}// End Function to create the application drop down menu

// Function to create the date drop down menu
function drop_date() {

echo "<select name='year'>";
echo "<option value='y'>Year</option>";
for ($y=2000; $y <= date('Y') ;$y++){
	echo "<option value='".$y."'>".$y."</option>";
}
echo "</select>";

echo "<select name='month'>";
echo "<option value='m'>Month</option>";
for ($m=1; $m <= 12 ;$m++){
	if ($m < 10 ) { echo "<option value='0".$m."'>".$m."</option>"; }
	else { echo "<option value='".$m."'>".$m."</option>"; }
}
echo "</select>";

echo "<select name='day'>";
echo "<option value='d'>Day</option>";
for ($d=1; $d <= 31 ;$d++){
	if ($d < 10 ) { echo "<option value='0".$d."'>".$d."</option>"; }
	else { echo "<option value='".$d."'>".$d."</option>"; }
}
echo "</select>";
}// End Function to create the date drop down menu

// Function to create the Sire search
function sire_search() {
echo "<input type='text' id='sire_search' name='sire_search' value='' size='35' />";
}// End Function to create the Sire search

// Function to create the RFC search
function rfc_search() {
echo "<input type='text' id='rfc_search' name='rfc_search' value='' size='35' />";
}// End Function to create the RFC search

?>