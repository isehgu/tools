<?php

// Function to add a new entry to the database
function print_add_new() {

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Register</h1>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
echo "<form action='confirm_add_new.php' method='post' id='new_entry' name='new_entry' >";
echo "<div class='table'>";
echo "<img src='img/bg-th-left.gif' width='8' height='7' alt='' class='left' />";
echo "<img src='img/bg-th-right.gif' width='7' height='7' alt='' class='right' />";

echo "<table id='dataTable' class='listing' cellpadding='0' cellspacing='0' >";
echo "<tr><th class='first'>&nbsp;</th><th>SIRE Container</th><th>RFC #</th><th>Application</th><th>Component</th><th>Version</th><th>Zip File Name</th><th>Dev Release Date</th><th class='last'>Prod Install Date</th></tr>";

echo "<tr>";
 echo "<td class='first style4'><INPUT type='checkbox' name='chk' /></td>";
 echo "<td class='first style4'><input type='text' class='text' size='15' maxlength='150' name='group_name[][sir]' /></td>";
 echo "<td class='first style4'><input type='text' class='text' size='15' maxlength='150' name='group_name[][rfc]' /></td>";
 echo "<td class='first style1'><input type='text' class='text' size='15' maxlength='150' name='group_name[][app]' /></td>";
 echo "<td class='first style2'><input type='text' class='text' size='15' maxlength='150' name='group_name[][component]' /></td>";
 echo "<td class='first style4'><input type='text' class='text' size='15' maxlength='150' name='group_name[][version]' /></td>";
 echo "<td class='first style3'><input type='text' class='text' size='15' maxlength='150' name='group_name[][zip]' /></td>";
 echo "<td class='first style4'><input type=text size='15' maxlength='150' name='group_name[][dev]' id='1' /><input type='button' name='today' onclick=\"document.getElementById('1').value=(new Date().getFullYear())+'/'+((new Date().getMonth()) + 1)+'/'+(new Date().getDate())\" value='Today' /></td>";
 echo "<td class='first style4'><input type=text size='15' maxlength='150' name='group_name[][prod]' id='2' /><input type='button' name='today' onclick=\"document.getElementById('2').value=(new Date().getFullYear())+'/'+((new Date().getMonth()) + 1)+'/'+(new Date().getDate())\" value='Today' /></td>";
echo "</tr>";

echo "</table>";
echo "<INPUT type='button' value='Add Row' onclick='addRow(\"dataTable\")' />";
echo "<INPUT type='button' value='Delete Row' onclick='deleteRow(\"dataTable\")' />";
echo "<div class='select'>";
echo "<input type='submit' /></form>";
echo "</div>";
echo "<p>&nbsp;</p>";


echo "</div>";
echo "</div>";
echo "</div>";
}// End Function to add a new entry to the database

// Function to confirm the addition of the new entry to the database
function print_confirm_add_new() {

include 'inc/db_connect.php';


if (isset($_POST['group_name'])){
$process_list=$_POST['group_name'];

$i="0";
while (list ($key,$val) = @each ($process_list)) { 
$key_last="";
$key_last=$key;
}

$flag="0";
for($i=0,$j=0;$i<$key_last;$j+=1){

if ($process_list[($i)][sir] == "" && $process_list[($i+1)][rfc] == "" && $process_list[($i+2)][app] == "" && $process_list[($i+3)][component] == "" && $process_list[($i+4)][version] == "" && $process_list[($i+5)][zip] == "" && $process_list[($i+6)][dev] == "" && $process_list[($i+7)][prod] == "" )
$flag="1";

$id_list[$j]=$process_list[$i][id];
$i+=8;
}

if ($flag != "1"){
for($i=0,$j=0;$i<$key_last;$j+=1){

$query="INSERT INTO releases (sire,rfc,application,component,version,zipfile,dev_date,prod_date) VALUES ('".$process_list[($i)][sir]."','".strtoupper($process_list[($i+1)][rfc])."','".$process_list[($i+2)][app]."','".$process_list[($i+3)][component]."','".$process_list[($i+4)][version]."','".$process_list[($i+5)][zip]."','".$process_list[($i+6)][dev]."','".$process_list[($i+7)][prod]."')";
$result=mysql_query($query) or die(mysql_error());

$id_list[$j]=$process_list[$i][id];
$i+=8;
$last_id=mysql_insert_id();
$last=($last_id - $j);
}

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<form action='process.php' method='post'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Register</h1>";
echo "<div class='breadcrumbs'>Number of entries inserted into the database: <b>".$j."</b> rows</div>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
if ($j > 1)
	echo "<h3 style='color:red;'>You inserted the following rows into the database:</h3>";
else
	echo "<h3 style='color:red;'>You inserted the following row into the database:</h3>";
echo "<form action='process.php' method='post'>";
echo "<div class='table'>";
echo "<img src='img/bg-th-left.gif' width='8' height='7' alt='' class='left' />";
echo "<img src='img/bg-th-right.gif' width='7' height='7' alt='' class='right' />";

echo "<table class='listing' cellpadding='0' cellspacing='0'>";
echo "<tr><th class='first'><input type='checkbox' onclick='checkAllFields(1);' id='checkitAll' /></th><th>SIRE Container</th><th>RFC #</th><th>Application</th><th>Component</th><th>Version</th><th>Zip File Name</th><th>Dev Release Date</th><th>Prod Install Date</th><th>Modify</th><th class='last'>Delete</th></tr>";

$k="0";
for ($last; $last<=$last_id; $last++){

$query="SELECT * FROM releases WHERE id='".$last."'";
$result=mysql_query($query) or die(mysql_error());

while($row = mysql_fetch_array($result)){
if ($k == 0){ echo "<tr>"; }else{ echo "<tr class='bg'>";}
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
if ($k == 0){ $k="1"; } else { $k="0"; }
}


}
echo "</table>";
echo "<label id='Hide_me' style='visibility:hidden;'><input type='submit' id='Submit' name='Modify' alt='Modify' value='Modify' style='border: 0px solid #FFFFFF; background-color:#FFFFFF;background-image: url(img/bg-orange-button.gif); height: 35px; width: 75px;text-align:center;color:#fff;text-transform:uppercase;font-weight:bold;line-height:27px;' /><input type='submit' id='Submit' name='Delete' alt='Delete' value='Delete' style='border: 0px solid #FFFFFF; background-color:#FFFFFF;background-image: url(img/bg-orange-button.gif); height: 35px; width: 75px;text-align:center;color:#fff;text-transform:uppercase;font-weight:bold;line-height:27px;' /></label>";
echo "</form>";
echo "</div>";
echo "</div>";
echo "</div>";


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
echo "<h3 style='color:red;'>Nothing to Add. The row CANNOT be completely empty.</h3>";
echo "</div>";
echo "</div>";

}

mysql_close();
}
}// End Function to confirm the addition of the new entry to the database

?>