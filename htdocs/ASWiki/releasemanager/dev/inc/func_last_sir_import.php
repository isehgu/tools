<?php

// Function to print all the latest Sire container added to the DB
function print_sir_import(){

include 'inc/db_connect.php';

$query="SELECT * from releases WHERE new_entry='1' ORDER BY dev_date DESC";
$result=mysql_query($query) or die(mysql_error());
$num_rows = mysql_num_rows($result);

if ($num_rows != '0'){
echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<form action='process.php' method='post'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Webgister</h1>";
if ($num_rows == 1)
	echo "<div class='breadcrumbs'>There is <b>1</b> new container inserted into the database</div>";
else
	echo "<div class='breadcrumbs'>There are <b>".$num_rows."</b> new containers inserted into the database</div>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
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

} else {

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Register</h1>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
echo "<h3 style='color:red;'>No new container to show.</h3>";
echo "</div>";
echo "</div>";

}

mysql_close();

}// Function to print all the latest Sire container added to the DB

?>