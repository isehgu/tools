<?php

// Function to print all the latest releases
function print_latest() {

include 'inc/db_connect.php';

$query="SELECT DISTINCT(application) from releases";
$result=mysql_query($query) or die(mysql_error());
$num_rows = mysql_num_rows($result);

$i="0";
$printres="";
$cnt="0";
while ($apps = mysql_fetch_array($result)){
	$queryapp="SELECT * FROM releases WHERE application='".$apps['application']."' ORDER BY prod_date DESC LIMIT 1";
	$resultapp=mysql_query($queryapp) or die(mysql_error());
	$appres=mysql_fetch_array($resultapp);

	if ($appres['application'] == "IORS"){
		for($j=0; $j<5; $j++){
			switch($j){
				case 0:
				{
					$queryapp="SELECT * FROM releases WHERE application='IORS' and component='DCA' ORDER BY prod_date DESC LIMIT 1";
				};break;
				case 1:
				{
					$queryapp="SELECT * FROM releases WHERE application='IORS' and component='IRC' ORDER BY prod_date DESC LIMIT 1";
				};break;
				case 2:
				{
					$queryapp="SELECT * FROM releases WHERE application='IORS' and component='LDC' ORDER BY prod_date DESC LIMIT 1";
				};break;
				case 3:
				{
					$queryapp="SELECT * FROM releases WHERE application='IORS' and component='MOE' ORDER BY prod_date DESC LIMIT 1";
				};break;
				case 4:
				{
					$queryapp="SELECT * FROM releases WHERE application='IORS' and component='ORA' ORDER BY prod_date DESC LIMIT 1";
				};break;
			}

		$resultapp=mysql_query($queryapp) or die(mysql_error());
		$appres=mysql_fetch_array($resultapp);

		if ($i == 0){ $printres .= "<tr>"; }else{ $printres .= "<tr class='bg'>";}
		if ($appres['sire'] == "0"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style4'><a href='https://sire.deutsche-boerse.de/cgiplus-bin/sire/sire.com?SVSYSCHID=".$appres['sire']."&ACTION=VIEW&ENTITY=CSyschViewer&ROLE=Superset%3B' target='_blank'>".$appres['sire']."</a></td>";}
		if ($appres['rfc'] == "0"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style4'><a href='https://ise.service-now.com/nav_to.do?uri=change_request.do?sysparm_query=number=".$appres['rfc']."' target='_blank'>".$appres['rfc']."</a></td>";}
		if ($appres['application'] == "0"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style1'>".$appres['application']."</td>";}
		if ($appres['component'] == "0"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style2'>".$appres['component']."</td>";}
		if ($appres['version'] == "0"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style4'>".$appres['version']."</td>";}
		if ($appres['zipfile'] == "0"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style3'>".$appres['zipfile']."</td>";}
		if ($appres['dev_date'] == "0000-00-00"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style4'>".$appres['dev_date']."</td>";}
		if ($appres['prod_date'] == "0000-00-00"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style4'>".$appres['prod_date']."</td>";}
		$printres .= "</tr>";
		if ($i == 0){ $i="1"; } else { $i="0"; }
		$cnt=$cnt+1;
		}
	}else{
		if ($appres['application'] != ""){
			if ($i == 0){ $printres .= "<tr>"; }else{ $printres .= "<tr class='bg'>";}
			if ($appres['sire'] == "0"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style4'><a href='https://sire.deutsche-boerse.de/cgiplus-bin/sire/sire.com?SVSYSCHID=".$appres['sire']."&ACTION=VIEW&ENTITY=CSyschViewer&ROLE=Superset%3B' target='_blank'>".$appres['sire']."</a></td>";}
			if ($appres['rfc'] == "0"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style4'><a href='https://ise.service-now.com/nav_to.do?uri=change_request.do?sysparm_query=number=".$appres['rfc']."' target='_blank'>".$appres['rfc']."</a></td>";}
			if ($appres['application'] == "0"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style1'>".$appres['application']."</td>";}
			if ($appres['component'] == "0"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style2'>".$appres['component']."</td>";}
			if 	($appres['version'] == "0"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style4'>".$appres['version']."</td>";}
			if ($appres['zipfile'] == "0"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style3'>".$appres['zipfile']."</td>";}
			if ($appres['dev_date'] == "0000-00-00"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style4'>".$appres['dev_date']."</td>";}
			if ($appres['prod_date'] == "0000-00-00"){ $printres .= "<td></td>";} else {$printres .= "<td class='first style4'>".$appres['prod_date']."</td>";}
			$printres .= "</tr>";
			if ($i == 0){ $i="1"; } else { $i="0"; }
			$cnt=$cnt+1;
		}
	}
}

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<form action='process.php' method='post'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Webgister</h1>";
echo "<div class='breadcrumbs'>This query containes <b>".$cnt."</b> rows</div>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
echo "<div class='table'>";
echo "<img src='img/bg-th-left.gif' width='8' height='7' alt='' class='left' />";
echo "<img src='img/bg-th-right.gif' width='7' height='7' alt='' class='right' />";

echo "<table class='listing' cellpadding='0' cellspacing='0'>";
echo "<tr><th class='first'>SIRE Container</th><th>RFC #</th><th>Application</th><th>Component</th><th>Version</th><th>Zip File Name</th><th>Dev Release Date</th><th class='last'>Prod Install Date</th></tr>";

echo $printres;

echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";

mysql_close();

}// End Function to print all the latest releases

?>