<?php

//Declare Global
$error_code='0';


function get_sire_data($error_code) {

	global $error_code;
	$url="http://sire.deutsche-boerse.de/cgiplus-bin/sire/sire.com/---".strtoupper(date("j-M-Y"))."%2023:59:59.59---/query_results.slk?SI=&EI=&CM=1&AT=S&AU=&CT=S&SS=Delivered+to+acceptance&SU=&SDDY=1&SDMN=".date("n")."&SDYR=".date("Y")."&EDDY=".date("j")."&EDMN=".date("n")."&EDYR=".date("Y")."&H0=1&N0=&H1=1&N1=&H2=1&N2=&H3=1&N3=&DH0=1&DS0DY=&DS0MN=&DS0YR=&DE0DY=&DE0MN=&DE0YR=&UH0=1&UN0=&US0DY=&US0MN=&US0YR=&UE0DY=&UE0MN=&UE0YR=&UH1=1&UN1=&US1DY=&US1MN=&US1YR=&UE1DY=&UE1MN=&UE1YR=&IH0=1&IN0=&IV0=&IH1=1&IN1=&IV1=&FA=1&F=&RT=X&X=Submit+date&ACTION=VIEW&ENTITY=QUERY&FC=4&FC1=1&FC2=2&FC3=2&ROLE=Superset%3B&VER=1";
	$filename="download/siredump.xml";
	$datestr=date("YmdHis");
	$logfile="download/transfer.log";
	$username=$_POST['login'];
	$password=$_POST['password'];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	$sirpayload = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);

	#check for success
	if ($info['http_code']==200) { 
		$fh = fopen($filename,'w');
		fwrite($fh, $sirpayload);
		fclose($fh);
	} else {
		$error_code='1';
	}

	# write log
	$fh=fopen($logfile,'a');
	fwrite($fh, $datestr."\r\n");
	fwrite($fh, implode("|",$info));
	fwrite($fh, "\r\n\r\n");
	fclose($fh);
	
	return($error_code);
}

function extract_unit($string, $start, $end)
{
	$pos = stripos($string, $start);
	$str = substr($string, $pos);
	$str_two = substr($str, strlen($start));
	$second_pos = stripos($str_two, $end);
	$str_three = substr($str_two, 0, $second_pos);
	$unit = trim($str_three); // remove whitespaces
	return $unit;
}

function insertIntoMySQL($error_code) {

	global $error_code;

if ($error_code != '1'){
	include 'inc/db_connect.php';
	$filename="download/siredump.xml";

	$dom = new DOMDocument();
	$dom->load($filename);

	$xpath = new DOMXPath($dom);
	$array = array();

	$sireId=$xpath->query('//SIRE/ISSUE/ID');
	$sireDate=$xpath->query('//SIRE/ISSUE/Submitdate');
	$max=$sireId->length;

	$upd="0";
	$insertedId=array();
	for ($i=0,$j=0; $i<=($max - 1); $i++){
		$id = $sireId->item($i)->nodeValue;
		$date = $sireDate->item($i)->nodeValue;

		//if (extract_unit($date,'-','-') == date("m")){
		if (substr($date, 0, 4) == date("Y")) {
			$querychk="SELECT sire FROM releases WHERE sire=$id";
			$reschk=mysql_query($querychk);
			$row=mysql_fetch_array($reschk);
			if (!$row){
				$query="INSERT INTO releases (sire,dev_date,new_entry) VALUES ('$id','$date','1')";
				$res=mysql_query($query);
				$insertedId[$j]=mysql_insert_id();
				$upd="1";
				$last_id=$j;
				$j++;
			}
		}
	}

	//Update last update time in the DB
	$queryupd="UPDATE updates SET sire_date_update=NOW() WHERE id='0'";
	$resupd=mysql_query($queryupd);

	if ($upd == "1"){
		for ($k=0;$k<=$last_id;$k++){
			$query="SELECT * from releases WHERE id='$insertedId[$k]'";
			$result[$k]=mysql_query($query) or die(mysql_error());
			$num_rows = ($k + 1);
		}
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
		for ($l=0;$l<=$last_id;$l++){
		$row = mysql_fetch_array($result[$l]);
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
		echo "<h3 style='color:red;'>No new Container to enter into the database.</h3>";
		echo "</div>";
		echo "</div>";
	}
mysql_close();
} else {

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Register</h1>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
echo "<h3 style='color:red;'>Please review your Login/Password.</h3>";
echo "</div>";
echo "</div>";

}

}

?>