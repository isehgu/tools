<?php

// Function to process modify or delete
function print_process() {

if (isset($_POST['Modify'])) {

include 'inc/db_connect.php';
$box=$_POST['CheckAll'];

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Webgister</h1>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
echo "<h3 style='color:red;'>Are you sure you want to modify the following entry?</h3>";
echo "<div class='table'>";
echo "<img src='img/bg-th-left.gif' width='8' height='7' alt='' class='left' />";
echo "<img src='img/bg-th-right.gif' width='7' height='7' alt='' class='right' />";

echo "<form action='process_bulk.php' method='post'>";
echo "<table class='listing' cellpadding='0' cellspacing='0'>";
echo "<tr><th class='first'>SIRE Container</th><th>RFC #</th><th>Application</th><th>Component</th><th>Version</th><th>Zip File Name</th><th>Dev Release Date</th><th class='last'>Prod Install Date</th></tr>";

$i="0";
while (list ($key,$val) = @each ($box)) { 

$query="SELECT * FROM releases WHERE id='$val'";
$result=mysql_query($query) or die(mysql_error());

while($row = mysql_fetch_array($result)){
if ($i == 0){ echo "<tr>"; }else{ echo "<tr class='bg'>";}
 echo "<input type='hidden' name='group_name[][id]' value='".$row['id']."' />";
 echo "<td class='first style4'><input type='text' class='text' size='15' maxlength='150' name='group_name[][sir]' value='".$row['sire']."' /></input></td>";
 echo "<td class='first style4'><input type='text' class='text' size='15' maxlength='150' name='group_name[][rfc]' value='".$row['rfc']."' /></input></td>";
 echo "<td class='first style1'><input type='text' class='text' size='15' maxlength='150' name='group_name[][app]' value='".$row['application']."' /></td>";
 echo "<td class='first style2'><input type='text' class='text' size='15' maxlength='150' name='group_name[][component]' value='".$row['component']."' /></td>";
 echo "<td class='first style4'><input type='text' class='text' size='15' maxlength='150' name='group_name[][version]' value='".$row['version']."' /></td>";
 echo "<td class='first style3'><input type='text' class='text' size='15' maxlength='150' name='group_name[][zip]' value='".$row['zipfile']."' /></td>";
 echo "<td class='first style4'><input type='text' size='15' maxlength='150' class='text' name='group_name[][dev]' value='".$row['dev_date']."' /></td>";
 echo "<td class='first style4'><input type='text' size='15' maxlength='150' class='text' name='group_name[][prod]' value='".$row['prod_date']."' /></td>";
echo "</tr>";
if ($i == 0){ $i="1"; } else { $i="0"; }
}
} 

echo "</table>";
echo "<div class='select'>";
echo "<input type='submit' name='Modify' id='Modify' value='Modify' /></form>";
echo "</div>";
echo "<p>&nbsp;</p>";
echo "</div>";
echo "</div>";
echo "</div>";

mysql_close();


} elseif (isset($_POST['Delete'])) {

include 'inc/db_connect.php';
$box=$_POST['CheckAll'];

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Webgister</h1>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
echo "<h3 style='color:red;'>Are you sure you want to delete the following entry?</h3>";
echo "<div class='table'>";
echo "<img src='img/bg-th-left.gif' width='8' height='7' alt='' class='left' />";
echo "<img src='img/bg-th-right.gif' width='7' height='7' alt='' class='right' />";

echo "<form action='process_bulk.php' method='post'>";
echo "<table class='listing' cellpadding='0' cellspacing='0'>";
echo "<tr><th class='first'>SIRE Container</th><th>RFC #</th><th>Application</th><th>Component</th><th>Version</th><th>Zip File Name</th><th>Dev Release Date</th><th class='last'>Prod Install Date</th></tr>";

$i="0";
while (list ($key,$val) = @each ($box)) { 

$query="SELECT * FROM releases WHERE id='$val'";
$result=mysql_query($query) or die(mysql_error());

while($row = mysql_fetch_array($result)){
if ($i == 0){ echo "<tr>"; }else{ echo "<tr class='bg'>";}
 echo "<input type='hidden' name='group_name[][id]' value='".$row['id']."' />";
 if ($row['sire'] == "0"){ echo "<td></td>";} else {echo "<td class='first style4'><a href='https://sire.deutsche-boerse.de/cgiplus-bin/sire/sire.com?SVSYSCHID=".$row['sire']."&ACTION=VIEW&ENTITY=CSyschViewer&ROLE=Superset%3B' target='_blank'>".$row['sire']."</a></td>";}
 if ($row['rfc'] == "0"){ echo "<td></td>";} else {echo "<td class='first style4'><a href='https://ise.service-now.com/nav_to.do?uri=change_request.do?sysparm_query=number=".$row['rfc']."' target='_blank'>".$row['rfc']."</a></td>";}
 if ($row['application'] == "0"){ echo "<td></td>";} else {echo "<td class='first style1'>".$row['application']."</td>";}
 if ($row['component'] == "0"){ echo "<td></td>";} else {echo "<td class='first style2'>".$row['component']."</td>";}
 if ($row['version'] == "0"){ echo "<td></td>";} else {echo "<td class='first style4'>".$row['version']."</td>";}
 if ($row['zipfile'] == "0"){ echo "<td></td>";} else {echo "<td class='first style3'>".$row['zipfile']."</td>";}
 if ($row['dev_date'] == "0000-00-00"){ echo "<td></td>";} else {echo "<td class='first style4'>".$row['dev_date']."</td>";}
 if ($row['prod_date'] == "0000-00-00"){ echo "<td></td>";} else {echo "<td class='first style4'>".$row['prod_date']."</td>";}
echo "</tr>";
if ($i == 0){ $i="1"; } else { $i="0"; }
}
} 

echo "</table>";
echo "<div class='select'>";
echo "<input type='submit' name='Delete' id='Delete' value='Delete' /></form>";
echo "</div>";
echo "<p>&nbsp;</p>";
echo "</div>";
echo "</div>";
echo "</div>";

mysql_close();


} else {

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Register</h1>";
echo "<div class='breadcrumbs'><a href='#'>The database containes <b>0</b> rows</a></div>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
echo "<h3 style='color:red;'>Nothing to Process.</h3>";
echo "</div>";
echo "</div>";

}

}// Function to process modify or delete

?>
