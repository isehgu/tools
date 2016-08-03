<?php

// End Process Add/Modify/Delete in bulk
function print_bulk() {
include 'inc/db_connect.php';

if (isset($_POST['Modify'])){

if (isset($_POST['group_name'])){
$process_list=$_POST['group_name'];

$i="0";
while (list ($key,$val) = @each ($process_list)) { 
$key_last="";
$key_last=$key;
}

for($i=0,$j=0;$i<$key_last;$j+=1){
$query="UPDATE releases SET sire='".$process_list[($i+1)][sir]."',rfc='".$process_list[($i+2)][rfc]."',application='".$process_list[($i+3)][app]."',component='".$process_list[($i+4)][component]."',version='".$process_list[($i+5)][version]."',zipfile='".$process_list[($i+6)][zip]."',dev_date='".$process_list[($i+7)][dev]."',prod_date='".$process_list[($i+8)][prod]."',new_entry='0' WHERE id='".$process_list[$i][id]."'";
$result=mysql_query($query) or die(mysql_error());

$id_list[$j]=$process_list[$i][id];
$i+=9;
$last=$j;
}

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<form action='process.php' method='post'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Webgister</h1>";
echo "<div class='breadcrumbs'>The database containes <b>".$num_rows."</b> rows</div>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
if ($num_rows == '1')
	echo "<h3 style='color:red;'>The following entry has been modified successfully!</h3>";
else
	echo "<h3 style='color:red;'>The following entries have been modified successfully!</h3>";
echo "<div class='table'>";
echo "<img src='img/bg-th-left.gif' width='8' height='7' alt='' class='left' />";
echo "<img src='img/bg-th-right.gif' width='7' height='7' alt='' class='right' />";

echo "<table class='listing' cellpadding='0' cellspacing='0'>";
echo "<tr><th class='first'><input type='checkbox' value='All' name='CheckAllTop' onClick=\"checkAll(this.checked)\" /></th><th>SIRE Container</th><th>RFC #</th><th>Application</th><th>Component</th><th>Version</th><th>Zip File Name</th><th>Dev Release Date</th><th>Prod Install Date</th><th>Modify</th><th class='last'>Delete</th></tr>";

$k="0";
for ($i=0,$last; $i<=$last; $i++){

$query="SELECT * FROM releases WHERE id='".$id_list[$i]."'";
$result=mysql_query($query) or die(mysql_error());

while($row = mysql_fetch_array($result)){
if ($k == 0){ echo "<tr>"; }else{ echo "<tr class='bg'>";}
 echo "<td><input type='checkbox' id='CheckAll".$row['id']."' name='CheckAll[]'   value='".$row['id']."' onClick='show_modify_delete(".$row['id'].")'></td>";
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
if ($k == 0){ $k="1"; } else { $k="0"; }
}


}
echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";


}
}// End Modify script
elseif (isset($_POST['Delete'])){

if (isset($_POST['group_name'])){
$process_list=$_POST['group_name'];

$i="0";
while (list ($key,$val) = @each ($process_list)) { 
$key_last="";
$key_last=$key;
}

$res="";
$j="0";
for($i=0;$i<=$key_last;$i++){

$queryshow="SELECT * FROM releases WHERE id='".$process_list[$i][id]."'";
if ($resultshow=mysql_query($queryshow)){

while ($row=mysql_fetch_array($resultshow)){
if ($j == 0){ $res .= "<tr>"; }else{ $res.= "<tr class='bg'>";}
 if ($row['sire'] == "0"){ $res .= "<td></td>";} else {$res .= "<td class='first style4'><a href='https://sire.deutsche-boerse.de/cgiplus-bin/sire/sire.com?SVSYSCHID=".$row['sire']."&ACTION=VIEW&ENTITY=CSyschViewer&ROLE=Superset%3B' target='_blank'>".$row['sire']."</a></td>";}
 if ($row['application'] == "0"){ $res .= "<td></td>";} else {$res .= "<td class='first style1'>".$row['application']."</td>";}
 if ($row['component'] == "0"){ $res .= "<td></td>";} else {$res .= "<td class='first style2'>".$row['component']."</td>";}
 if ($row['version'] == "0"){ $res .= "<td></td>";} else {$res .= "<td class='first style4'>".$row['version']."</td>";}
 if ($row['zipfile'] == "0"){ $res .= "<td></td>";} else {$res .= "<td class='first style3'>".$row['zipfile']."</td>";}
 if ($row['dev_date'] == "0000-00-00"){ $res .= "<td></td>";} else {$res .= "<td class='first style4'>".$row['dev_date']."</td>";}
 if ($row['prod_date'] == "0000-00-00"){ $res .= "<td></td>";} else {$res .= "<td class='first style4'>".$row['prod_date']."</td>";}
$res .= "</tr>";
if ($j == 0){ $j="1"; } else { $j="0"; }
}
$query="DELETE FROM releases WHERE id='".$process_list[$i][id]."'";
$result=mysql_query($query) or die(mysql_error());
}
$last=$i;
}

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<form action='process.php' method='post'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Webgister</h1>";
echo "<div class='breadcrumbs'>The database containes <b>".$num_rows."</b> rows</div>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
if ($num_rows == '1')
	echo "<h3 style='color:red;'>The following entry has been deleted successfully!</h3>";
else
	echo "<h3 style='color:red;'>The following entries have been deleted successfully!</h3>";
echo "<div class='table'>";
echo "<img src='img/bg-th-left.gif' width='8' height='7' alt='' class='left' />";
echo "<img src='img/bg-th-right.gif' width='7' height='7' alt='' class='right' />";

echo "<table class='listing' cellpadding='0' cellspacing='0'>";
echo "<tr><th class='first'>SIRE Container</th><th>Application</th><th>Component</th><th>Version</th><th>Zip File Name</th><th>Dev Release Date</th><th class='last'>Prod Install Date</th></tr>";

echo $res;

echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";

}
}// End Delete script

mysql_close();
}// End Process Add/Modify/Delete in bulk
?>