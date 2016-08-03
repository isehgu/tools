<?php

// Function to modify an entry
function print_modify() {

include 'inc/db_connect.php';

if (isset($_GET['id']) && $_GET['id'] != ""){
$modify_id=$_GET['id'];

$query="SELECT * FROM releases WHERE id='$modify_id'";
$result=mysql_query($query) or die(mysql_error());

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Webgister</h1>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
echo "<div class='table'>";
echo "<img src='img/bg-th-left.gif' width='8' height='7' alt='' class='left' />";
echo "<img src='img/bg-th-right.gif' width='7' height='7' alt='' class='right' />";

echo "<table class='listing' cellpadding='0' cellspacing='0'>";
echo "<tr><th class='first'>SIRE Container</th><th>RFC #</th><th>Application</th><th>Component</th><th>Version</th><th>Zip File Name</th><th>Dev Release Date</th><th>Prod Install Date</th><th class='last'>Confirm Modification</th></tr>";
echo "<form action='confirm_modify.php' method='post'>";

while($row = mysql_fetch_array($result)){
echo "<tr>";
 echo "<input type='hidden' name='id_modify' value='".$row['id']."' />";
 echo "<td class='first style4'><input type='text' class='text' size='15' maxlength='150' name='modify_sir_number' value='".$row['sire']."' /></td>";
 echo "<td class='first style4'><input type='text' class='text' size='15' maxlength='150' name='modify_rfc_number' value='".$row['rfc']."' /></td>";
 echo "<td class='first style1'><input type='text' class='text' size='15' maxlength='150' name='modify_app_name' value='".$row['application']."' /></td>";
 echo "<td class='first style2'><input type='text' class='text' size='15' maxlength='150' name='modify_component' value='".$row['component']."' /></td>";
 echo "<td class='first style4'><input type='text' class='text' size='15' maxlength='150' name='modify_version' value='".$row['version']."' /></td>";
 echo "<td class='first style3'><input type='text' class='text' size='15' maxlength='150' name='modify_zip_name' value='".$row['zipfile']."' /></td>";
 echo "<td class='first style4'><input type='text' size='15' maxlength='150' class='text' name='modify_dev_date' value='".$row['dev_date']."' /></td>";
 echo "<td class='first style4'><input type='text' size='15' maxlength='150' class='text' name='modify_prod_date' value='".$row['prod_date']."' /></td>";
 echo "<td><input type='image' src='img/save-icon.gif' width='16' height='16' alt='save' /></form></td>";
echo "</tr>";
}
echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";

mysql_close();
	
}else{

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Register</h1>";
echo "<div class='breadcrumbs'><a href='#'>The database containes <b>0</b> rows</a></div>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
echo "<h3 style='color:red;'>Nothing to Modify.</h3>";
echo "</div>";
echo "</div>";

}
}// End Function to modify an entry

// Function to confirm the modification of an entry in the database
function print_confirm_modify() {

include 'inc/db_connect.php';

if (isset($_POST['modify_sir_number'])) {
	$modify_sir_number = $_POST['modify_sir_number']; }else{ $modify_sir_number = ''; }
if (isset($_POST['modify_rfc_number'])) {
	$modify_rfc_number = $_POST['modify_rfc_number']; }else{ $modify_rfc_number = ''; }
if (isset($_POST['modify_app_name'])) {
	$modify_app_name = $_POST['modify_app_name']; }else{ $modify_app_name = ''; }
if (isset($_POST['modify_component'])) {
	$modify_component = $_POST['modify_component']; }else{ $modify_component = ''; }
if (isset($_POST['modify_version'])) {
	$modify_version = $_POST['modify_version']; }else{ $modify_version = ''; }
if (isset($_POST['modify_zip_name'])) {
	$modify_zip_name = $_POST['modify_zip_name']; }else{ $modify_zip_name = ''; }
if (isset($_POST['modify_dev_date'])) {
	$modify_dev_date = $_POST['modify_dev_date']; }else{ $modify_dev_date = ''; }
if (isset($_POST['modify_prod_date'])) {
	$modify_prod_date = $_POST['modify_prod_date']; }else{ $modify_prod_date = ''; }
$modify_id=$_POST['id_modify'];

$query="UPDATE releases SET sire='$modify_sir_number',rfc='".strtoupper($modify_rfc_number)."',application='$modify_app_name',component='$modify_component',version='$modify_version',zipfile='$modify_zip_name',dev_date='$modify_dev_date',prod_date='$modify_prod_date',new_entry='0' WHERE id='$modify_id'";
$result=mysql_query($query) or die(mysql_error());

$queryc="SELECT * FROM releases WHERE id='$modify_id'";
$resultc=mysql_query($queryc) or die(mysql_error());

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Webgister</h1>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
echo "<h3 style='color:red;'>The following entry has been modified in the database!</h3>";
echo "<div class='table'>";
echo "<img src='img/bg-th-left.gif' width='8' height='7' alt='' class='left' />";
echo "<img src='img/bg-th-right.gif' width='7' height='7' alt='' class='right' />";

echo "<table class='listing' cellpadding='0' cellspacing='0'>";
echo "<tr><th class='first'>SIRE Container</th><th>RFC #</th><th>Application</th><th>Component</th><th>Version</th><th>Zip File Name</th><th>Dev Release Date</th><th>Prod Install Date</th><th>Modify</th><th class='last'>Delete</th></tr>";

while($row = mysql_fetch_array($resultc)){
echo "<tr>";
 if ($row['sire'] == "0"){ echo "<td></td>";} else {echo "<td class='first style4'><a href='https://sire.deutsche-boerse.de/cgiplus-bin/sire/sire.com?SVSYSCHID=".$row['sire']."&ACTION=VIEW&ENTITY=CSyschViewer&ROLE=Superset%3B' target='_blank'>".$row['sire']."</a></td>";}
 if ($row['rfc'] == "0"){ echo "<td></td>";} else {echo "<td class='first style4'><a href='https://ise.service-now.com/nav_to.do?uri=change_request.do?sysparm_query=number=".$row['rfc']."' target='_blank'>".$row['rfc']."</a></td>";}
 if ($row['application'] == "0"){ echo "<td></td>";} else {echo "<td class='first style1'>".$row['application']."</td>";}
 if ($row['component'] == "0"){ echo "<td></td>";} else {echo "<td class='first style2'>".$row['component']."</td>";}
 if ($row['version'] == "0"){ echo "<td></td>";} else {echo "<td class='first style4'>".$row['version']."</td>";}
 if ($row['zipfile'] == "0"){ echo "<td></td>";} else {echo "<td class='first style3'>".$row['zipfile']."</td>";}
 if ($row['dev_date'] == "0000-00-00"){ echo "<td></td>";} else {echo "<td class='first style4'>".$row['dev_date']."</td>";}
 if ($row['prod_date'] == "0000-00-00"){ echo "<td></td>";} else {echo "<td class='first style4'>".$row['prod_date']."</td>";}
 echo "<td><a href='modify.php?id=".$row['id']."&option=modify'><img src='img/edit-icon.gif' width='16' height='16' alt='modify' /></a></td>";
 echo "<td><a href='delete.php?id=".$row['id']."&option=delete'><img src='img/hr.gif' width='16' height='16' alt='delete' /></a></td>";
echo "</tr>";
}
echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";

mysql_close();
}// Function to confirm the modification of the an entry in the database

?>