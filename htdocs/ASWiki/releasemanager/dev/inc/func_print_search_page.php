<?php

// Function to print the search
function print_search($application, $month, $day, $year){

include 'db_connect.php';

if (isset($_POST['application'])){
	$app=$_POST['application'];
}else{ $app="a";}

if (isset($_POST['month'])){
	$month=$_POST['month'];
}else{ $month="m";}
if (isset($_POST['day'])){
	$day=$_POST['day'];
}else{ $day="d";}
if (isset($_POST['year'])){
	$year=$_POST['year'];
}else{ $year="y";}

if (isset($_POST['sire_search']) && $_POST['sire_search'] != ""){
	$sire_s=$_POST['sire_search'];
}else{ $sire_s="s";}

if (isset($_POST['rfc_search']) && $_POST['rfc_search'] != ""){
	$rfc_s=$_POST['rfc_search'];
}else{ $rfc_s="r";}


// Check the app status	
if ($app == "a"){
	$app="noapp";
}
if ($sire_s == "s"){
	$sire_s="nosire";
}
if ($rfc_s == "r"){
	$rfc_s="norfc";
}

// Check the date status
if ($month != "m" && $day != "d" && $year != "y"){
	$date=$year."-".$month."-".$day;
	if ($month == "01") {$datem=$year."-January-".$day;}
	else if ($month == "02") {$datem=$year."-February-".$day;}
	else if ($month == "03") {$datem=$year."-March-".$day;}
	else if ($month == "04") {$datem=$year."-April-".$day;}
	else if ($month == "05") {$datem=$year."-May-".$day;}
	else if ($month == "06") {$datem=$year."-June-".$day;}
	else if ($month == "07") {$datem=$year."-July-".$day;}
	else if ($month == "08") {$datem=$year."-August-".$day;}
	else if ($month == "09") {$datem=$year."-September-".$day;}
	else if ($month == "10") {$datem=$year."-October-".$day;}
	else if ($month == "11") {$datem=$year."-November-".$day;}
	else if ($month == "12") {$datem=$year."-December-".$day;}
}else if ($month != "m" && $day == "d" && $year == "y") {
	$date="-".$month."-";
	if ($month == "01") {$datem="January";}
	else if ($month == "02") {$datem="February";}
	else if ($month == "03") {$datem="March";}
	else if ($month == "04") {$datem="April";}
	else if ($month == "05") {$datem="May";}
	else if ($month == "06") {$datem="June";}
	else if ($month == "07") {$datem="July";}
	else if ($month == "08") {$datem="August";}
	else if ($month == "09") {$datem="September";}
	else if ($month == "10") {$datem="October";}
	else if ($month == "11") {$datem="November";}
	else if ($month == "12") {$datem="December";}
}else if ($month == "m" && $day == "d" && $year != "y") {
	$date=$year."-";
	$datem=$year;
}else if ($month != "m" && $day == "d" && $year != "y") {
	$date=$year."-".$month."-";
	if ($month == "01") {$datem=$year."-January";}
	else if ($month == "02") {$datem=$year."-February";}
	else if ($month == "03") {$datem=$year."-March";}
	else if ($month == "04") {$datem=$year."-April";}
	else if ($month == "05") {$datem=$year."-May";}
	else if ($month == "06") {$datem=$year."-June";}
	else if ($month == "07") {$datem=$year."-July";}
	else if ($month == "08") {$datem=$year."-August";}
	else if ($month == "09") {$datem=$year."-September";}
	else if ($month == "10") {$datem=$year."-October";}
	else if ($month == "11") {$datem=$year."-November";}
	else if ($month == "12") {$datem=$year."-December";}
}else{
	$date="nodate";
}

// Print the correct info
if ($app != "noapp" && $date != "nodate"){
	$appmsg = "The application selected is: <b>".$app."</b>";
	$datemsg = "The date selected is: <b>".$datem."</b>";
	
	$query="SELECT * FROM releases where application=\"$app\" and prod_date LIKE \"%$date%\" ORDER BY prod_date DESC";
	$result=mysql_query($query) or die(mysql_error());
	
} else if ($app != "noapp" && $date == "nodate"){
	$appmsg = "The application selected is: <b>".$app."</b>";
	$datemsg = "No date was selected";
	
	$query="SELECT * FROM releases where application=\"$app\" ORDER BY prod_date DESC";
	$result=mysql_query($query) or die(mysql_error());

}else if ($app == "noapp" && $date != "nodate"){
	$appmsg = "No application was selected";
	$datemsg = "The date selected is: <b>".$datem."</b>";
	
	$query="SELECT * FROM releases where prod_date LIKE \"%$date%\" ORDER BY prod_date DESC";
	$result=mysql_query($query) or die(mysql_error());
	
} else if ($app == "noapp" && $date == "nodate" && $sire_s != "nosire" && $rfc_s == "norfc") {
	$appmsg = "You search for the Sire # ".$sire_s;

	$query="SELECT * FROM releases where sire='$sire_s'";
	$result=mysql_query($query) or die(mysql_error());

} else if ($app == "noapp" && $date == "nodate" && $sire_s == "nosire" && $rfc_s != "norfc") {
	$appmsg = "You search for the RFC # ".$rfc_s;

	$query="SELECT * FROM releases where sire='$rfc_s'";
	$result=mysql_query($query) or die(mysql_error());

}else if ($app == "noapp" && $date == "nodate"){
	$appmsg = "No application was selected";
	$datemsg = "No date was selected";
	$result="noquery";
}

if ($result != "noquery"){
// Print the result if any
$num_rows = mysql_num_rows($result);

if ($num_rows != "0") {

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Webgister</h1>";
echo "<div class='breadcrumbs'>Your query returned <b>".$num_rows."</b> rows<br />";
echo $appmsg."<br />";
echo $datemsg."</div>";
echo "</div><br />";
echo "<div class='select-bar'>";
echo "<form action='print.php' name='trackunread' method='post'>";
echo "Search for the application: ";
drop_app();
echo "<BR />";
echo "Search for the date: ";
drop_date(); 
echo "<BR />";
echo "Search for the Sire #: ";
sire_search();
echo "<BR />";
echo "Search for the RFC #: ";
rfc_search();
echo "<BR />";
echo "<input type='submit' /></form>";
echo "</div>";
echo "<form action='process.php' method='post'>";
echo "<div class='table'>";


echo "<img src='img/bg-th-left.gif' width='8' height='7' alt='' class='left' />";
echo "<img src='img/bg-th-right.gif' width='7' height='7' alt='' class='right' />";


echo "<table class='listing' cellpadding='0' cellspacing='0' id='ipb' >";
echo "<tr><th class='first'><input type='checkbox' onclick='checkAllFields(1);' id='checkitAll' /></th><th>SIRE Container</th><th>RFC #</th><th>Application</th><th>Component</th><th>Version</th><th>Zip File Name</th><th>Dev Release Date</th><th>Prod Install Date</th><th>Modify</th><th class='last'>Delete</th></tr>";

$i="0";
while($row = mysql_fetch_array($result)){
if ($i == 0){ echo "<tr>"; }else{ echo "<tr class='bg'>";}
 echo "<td><input type='checkbox' id='CheckAll".$row['id']."' name='CheckAll[]'   value='".$row['id']."' onclick='checkAllFields(2);doInputs(this);'></td>";
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
if ($i == 0){ $i="1"; } else { $i="0"; }
}

echo "</table>";
echo "<label id='Hide_me' style='visibility:hidden;'><input type='submit' id='Submit' name='Modify' alt='Modify' value='Modify' style='border: 0px solid #FFFFFF; background-color:#FFFFFF;background-image: url(img/bg-orange-button.gif); height: 35px; width: 75px;text-align:center;color:#fff;text-transform:uppercase;font-weight:bold;line-height:27px;' /><input type='submit' id='Submit' name='Delete' alt='Delete' value='Delete' style='border: 0px solid #FFFFFF; background-color:#FFFFFF;background-image: url(img/bg-orange-button.gif); height: 35px; width: 75px;text-align:center;color:#fff;text-transform:uppercase;font-weight:bold;line-height:27px;' /></label>";
echo "</form>";
echo "</div>";
echo "</div>";
echo "</div>";
} else {

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<h1>Release Webgister</h1>";
echo "<div class='breadcrumbs'><br />";
echo $appmsg."<br />";
echo $datemsg."</div>";
echo "</div><br />";
echo "<div class='select-bar'>";
echo "<form action='print.php' method='post'>";
echo "Search for the application: ";
drop_app();
echo "<BR />";
echo "Search for the date: ";
drop_date(); 
echo "<BR />";
echo "Search for the Sire #: ";
sire_search();
echo "<BR />";
echo "Search for the RFC #: ";
rfc_search();
echo "<BR />";
echo "<input type='submit' /></form>";
echo "</div>";
echo "<div class='table'>";
echo "No result matching your criterias";

echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";
}
} else {
echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<h1>Release Register</h1>";
echo "<div class='breadcrumbs'><br />";
echo $appmsg."<br />";
echo $datemsg."</div>";
echo "</div><br />";
echo "<div class='select-bar'>";
echo "<form action='print.php' method='post'>";
echo "Search for the application: ";
drop_app();
echo "<BR />";
echo "Search for the date: ";
drop_date(); 
echo "<BR />";
echo "Search for the Sire #: ";
sire_search();
echo "<BR />";
echo "Search for the RFC #: ";
rfc_search();
echo "<BR />";
echo "<input type='submit' /></form>";
echo "</div>";
echo "No criterias were selected";
echo "<div class='table'>";
echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";

}
//mysql_close();
}// End Function to print the search

// Function to print all the releases on the search homepage
function print_search_home() {

include 'inc/db_connect.php';

if (!isset($_GET['order'])) { $order=1;}

if ((isset($_GET['sort']) && isset($_GET['order'])) && ($_GET['order'] == '1' || $_GET['order'] == '2') && ($_GET['sort'] == "sire" || $_GET['sort'] == "rfc" || $_GET['sort'] == "application" || $_GET['sort'] == "component" || $_GET['sort'] == "version" || $_GET['sort'] == "zipfile" 	|| $_GET['sort'] == "dev_date" || $_GET['sort'] == "prod_date")){

$app=$_GET['sort'];
$order=$_GET['order'];

if ($order == '1'){
$orderq="ASC";
$order=$order+1;
}else{
$orderq="DESC";
$order="1";
}
switch($app){
	case sire:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
	};break;
	case rfc:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
	};break;
	case application:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
	};break;
	case component:
	{
		$query="SELECT * FROM releases ORDER BY $app,application $orderq";
	};break;
	case version:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
	};break;
	case zipfile:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
	};break;
	case dev_date:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
	};break;
	case prod_date:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
	};break;

}
$result=mysql_query($query) or die(mysql_error());

$num_rows = mysql_num_rows($result);
} else {
$query="SELECT * FROM releases ORDER BY prod_date DESC";
$result=mysql_query($query) or die(mysql_error());

$num_rows = mysql_num_rows($result);
$app="prod_date";
$order="2";
}

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Webgister</h1>";
echo "<div class='breadcrumbs'>The database containes <b>".$num_rows."</b> rows</div>";
echo "</div><br />";
echo "<div class='select-bar'>";
echo "<form action='print.php' method='post'>";
echo "Search for the application: ";
drop_app();
echo "<BR />";
echo "Search for the date: ";
drop_date(); 
echo "<BR />";
echo "Search for the Sire #: ";
sire_search();
echo "<BR />";
echo "Search for the RFC #: ";
rfc_search();
echo "<BR />";
echo "<input type='submit' /></form>";
echo "</div>";
echo "<form action='process.php' method='post'>";
echo "<div class='table'>";
echo "<img src='img/bg-th-left.gif' width='8' height='7' alt='' class='left' />";
echo "<img src='img/bg-th-right.gif' width='7' height='7' alt='' class='right' />";


echo "<table class='listing' cellpadding='0' cellspacing='0'>";
echo "<tr><th class='first'><input type='checkbox' onclick='checkAllFields(1);' id='checkitAll' /></th><th>SIRE Container</th><th>RFC #</th><th>Application</th><th>Component</th><th>Version</th><th>Zip File Name</th><th>Dev Release Date</th><th>Prod Install Date</th><th>Modify</th><th class='last'>Delete</th></tr>";

$i="0";
while($row = mysql_fetch_array($result)){
if ($i == 0){ echo "<tr>"; }else{ echo "<tr class='bg'>";}
 echo "<td><input type='checkbox' id='CheckAll".$row['id']."' name='CheckAll[]'   value='".$row['id']."' onclick='checkAllFields(2);doInputs(this);'></td>";
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
if ($i == 0){ $i="1"; } else { $i="0"; }
}
echo "</table>";
echo "<label id='Hide_me' style='visibility:hidden;'><input type='submit' id='Submit' name='Modify' alt='Modify' value='Modify' style='border: 0px solid #FFFFFF; background-color:#FFFFFF;background-image: url(img/bg-orange-button.gif); height: 35px; width: 75px;text-align:center;color:#fff;text-transform:uppercase;font-weight:bold;line-height:27px;' /><input type='submit' id='Submit' name='Delete' alt='Delete' value='Delete' style='border: 0px solid #FFFFFF; background-color:#FFFFFF;background-image: url(img/bg-orange-button.gif); height: 35px; width: 75px;text-align:center;color:#fff;text-transform:uppercase;font-weight:bold;line-height:27px;' /></label>";
echo "</form>";
echo "</div>";
echo "</div>";
echo "</div>";

//mysql_close();
}// End Function to print all the releases on the search homepage

?>