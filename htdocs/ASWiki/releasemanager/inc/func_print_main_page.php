<?php

// Function to print all the releases on the home page
//function print_table($page,$lastpage,$lpm1) {
function print_table() {

include 'inc/db_connect.php';

// Table sorting part
if (!isset($_GET['order'])) { $order=1;}

if ((isset($_GET['sort']) && isset($_GET['order'])) && ($_GET['order'] == '1' || $_GET['order'] == '2') && ($_GET['sort'] == "sire" || $_GET['sort'] == "rfc" || $_GET['sort'] == "application" || $_GET['sort'] == "component" || $_GET['sort'] == "version" || $_GET['sort'] == "zipfile" 	|| $_GET['sort'] == "dev_date" || $_GET['sort'] == "prod_date")){

$app=$_GET['sort'];
$order=$_GET['order'];

if (isset($_GET['clicked']) == "yes"){
if ($order == '1'){
$orderq="ASC";
$order=$order+1;
}else{
$orderq="DESC";
$order="1";
}
}
switch($app){
	case sire:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
		$targetpage = "index.php?sort=$app&order=$order";
	};break;
	case rfc:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
		$targetpage = "index.php?sort=$app&order=$order";
	};break;
	case application:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
		$targetpage = "index.php?sort=$app&order=$order";
	};break;
	case component:
	{
		$query="SELECT * FROM releases ORDER BY $app,application $orderq";
		$targetpage = "index.php?sort=$app&order=$order";
	};break;
	case version:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
		$targetpage = "index.php?sort=$app&order=$order";
	};break;
	case zipfile:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
		$targetpage = "index.php?sort=$app&order=$order";
	};break;
	case dev_date:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
		$targetpage = "index.php?sort=$app&order=$order";
	};break;
	case prod_date:
	{
		$query="SELECT * FROM releases ORDER BY $app $orderq";
		$targetpage = "index.php?sort=$app&order=$order";
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
// End Table sorting part


if (!isset($_GET['pagenum']) || $_GET['pagenum'] != view_all){

// Pagination rediness part
	// How many adjacent pages should be shown on each side?
	$adjacents = 2;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	//$queryn="SELECT * FROM releases ORDER BY prod_date DESC";
	//$resultn=mysql_query($queryn) or die(mysql_error());
	$total_pages = $num_rows;

	/* Setup vars for query. */
	if (!$targetpage){
	$targetpage = "index.php?"; 	//your file name  (the name of this file)
	} else { $targetpage=$targetpage."&"; }
	$limit = 50; 								//how many items to show per page
	$page = $_GET['pagenum'];

	if($page) {
		$start = ($page - 1) * $limit; 			//first item to display on this page
	} else {
		$start = 0; }								//if no page var is given, set start to 0 
	
	/* Get data. */
	$query_pages=$query." LIMIT $start, $limit";
	$result_pages = mysql_query($query_pages);

	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1; 						//last page minus 1

	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class='pagination'>";
		$pagination .= "<a href='".$targetpage."pagenum=view_all'>all</a>";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href='".$targetpage."pagenum=$prev'>� previous</a>";
		else
			$pagination.= "<span class='disabled'>� previous</span>";	
		
		//pages	
		if ($lastpage < 4 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class='current'>$counter</span>";
				else
					$pagination.= "<a href='".$targetpage."pagenum=$counter'>$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class='current'>$counter</span>";
					else
						$pagination.= "<a href='".$targetpage."pagenum=$counter'>$counter</a>";					
				}
				$pagination.= "...";
				//$pagination.= "<a href='".$targetpage."pagenum=$lpm1'>$lpm1</a>";
				$pagination.= "<a href='".$targetpage."pagenum=$lastpage'>$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href='".$targetpage."pagenum=1'>1</a>";
				//$pagination.= "<a href='".$targetpage."pagenum=2'>2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class='current'>$counter</span>";
					else
						$pagination.= "<a href='".$targetpage."pagenum=$counter'>$counter</a>";					
				}
				$pagination.= "...";
				//$pagination.= "<a href='".$targetpage."pagenum=$lpm1'>$lpm1</a>";
				$pagination.= "<a href='".$targetpage."pagenum=$lastpage'>$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href='".$targetpage."pagenum=1'>1</a>";
				//$pagination.= "<a href='".$targetpage."pagenum=2'>2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class='current'>$counter</span>";
					else
						$pagination.= "<a href='".$targetpage."pagenum=$counter'>$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href='".$targetpage."pagenum=$next'>next �</a>";
		else
			$pagination.= "<span class='disabled'>next �</span>";
		$pagination.= "</div>\n";		
	}
// End Pagination rediness part

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<form action='process.php' name='little_boxes' method='post'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Webgister</h1>";
echo "<div class='breadcrumbs'>The database containes <b>".$num_rows."</b> rows</div>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
echo "<div class='table'>";
echo "<img src='img/bg-th-left.gif' width='8' height='7' alt='' class='left' />";
echo "<img src='img/bg-th-right.gif' width='7' height='7' alt='' class='right' />";


echo "<table class='listing' cellpadding='0' cellspacing='0'>";
echo "<tr><th class='first'><input type='checkbox' onclick='checkAllFields(1);' id='checkitAll' /></th><th><a href='index.php?sort=sire&order=$order&clicked=yes' >SIRE Container</a></th><th><a href='index.php?sort=rfc&order=$order&clicked=yes' >RFC #</a></th><th><a href='index.php?sort=application&order=$order&clicked=yes' >Application</a></th><th><a href='index.php?sort=component&order=$order&clicked=yes' >Component</a></th><th><a href='index.php?sort=version&order=$order&clicked=yes' >Version</a></th><th><a href='index.php?sort=zipfile&order=$order&clicked=yes' >Zip File Name</a></th><th><a href='index.php?sort=dev_date&order=$order&clicked=yes' >Dev Release Date</a></th><th><a href='index.php?sort=prod_date&order=$order&clicked=yes' >Prod Install Date</a></th><th>Modify</th><th class='last'>Delete</th></tr>";

$i="0";
while($row = mysql_fetch_array($result_pages)){
if ($i == 0){ echo "<tr>"; }else{ echo "<tr class='bg'>";}
 echo "<td><input type='checkbox' id='CheckAll".$row['id']."' name='CheckAll[]'   value='".$row['id']."' onclick='checkAllFields(2);doInputs(this);'></td>";
// echo "<td><input type='checkbox' id='CheckAll".$row['id']."' name='CheckAll[]'   value='".$row['id']."' onClick='show_modify_delete(".$row['id'].")'></td>";
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

} else {

// Pagination rediness part
	// How many adjacent pages should be shown on each side?
	$adjacents = 2;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	//$queryn="SELECT * FROM releases ORDER BY prod_date DESC";
	//$resultn=mysql_query($queryn) or die(mysql_error());
	$total_pages = $num_rows;

	/* Setup vars for query. */
	if (!$targetpage){
	$targetpage = "index.php?"; 	//your file name  (the name of this file)
	} else { $targetpagenum=$targetpage."&"; }
	$limit = 50; 								//how many items to show per page
	$page = $_GET['page'];
	if($page) {
		$start = ($page - 1) * $limit; 			//first item to display on this page
	} else {
		$start = 0; }								//if no page var is given, set start to 0 
	
	/* Get data. */
	$query_pages=$query." LIMIT $start, $limit";
	$result_pages = mysql_query($query_pages);
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1; 						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class='pagination'>";
		$pagination .= "<span class='disabled'>all</span>";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href='".$targetpage."pagenum=$prev'>� previous</a>";
		else
			$pagination.= "<span class='disabled'>� previous</span>";	
		
		//pages	
		if ($lastpage < 4 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class='current'>$counter</span>";
				else
					$pagination.= "<a href='".$targetpage."pagenum=$counter'>$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<a href='".$targetpage."pagenum=$counter'>$counter</a>";
					else
						$pagination.= "<a href='".$targetpage."pagenum=$counter'>$counter</a>";					
				}
				$pagination.= "...";
				//$pagination.= "<a href='".$targetpage."pagenum=$lpm1'>$lpm1</a>";
				$pagination.= "<a href='".$targetpage."pagenum=$lastpage'>$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href='".$targetpage."pagenum=1'>1</a>";
				//$pagination.= "<a href='".$targetpage."pagenum=2'>2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class='current'>$counter</span>";
					else
						$pagination.= "<a href='".$targetpage."pagenum=$counter'>$counter</a>";					
				}
				$pagination.= "...";
				//$pagination.= "<a href='".$targetpage."pagenum=$lpm1'>$lpm1</a>";
				$pagination.= "<a href='".$targetpage."pagenum=$lastpage'>$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href='".$targetpage."pagenum=1'>1</a>";
				//$pagination.= "<a href='".$targetpage."pagenum=2'>2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class='current'>$counter</span>";
					else
						$pagination.= "<a href='".$targetpage."pagenum=$counter'>$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href='".$targetpage."pagenum=$next'>next �</a>";
		else
			$pagination.= "<span class='disabled'>next �</span>";
		$pagination.= "</div>\n";		
	}
// End Pagination rediness part

echo "<div id='middle'>";
echo "<div id='center-column'>";
echo "<div class='top-bar'>";
echo "<a href='add_new.php' class='button'>ADD NEW</a>";
echo "<h1>Release Webgister</h1>";
echo "<div class='breadcrumbs'>The database containes <b>".$num_rows."</b> rows</div>";
echo "</div><br />";
echo "<div class='select-bar-home'>";
echo "</div>";
echo "<form action='process.php' method='post'>";
echo "<div class='table'>";
echo "<img src='img/bg-th-left.gif' width='8' height='7' alt='' class='left' />";
echo "<img src='img/bg-th-right.gif' width='7' height='7' alt='' class='right' />";


echo "<table class='listing' cellpadding='0' cellspacing='0'>";
echo "<tr><th class='first'><input type='checkbox' onclick='checkAllFields(1);' id='checkitAll' /></th><th><a href='index.php?sort=sire&order=$order&clicked=yes' >SIRE Container</a></th><th><a href='index.php?sort=rfc&order=$order&clicked=yes' >RFC #</a></th><th><a href='index.php?sort=application&order=$order&clicked=yes' >Application</a></th><th><a href='index.php?sort=component&order=$order&clicked=yes' >Component</a></th><th><a href='index.php?sort=version&order=$order&clicked=yes' >Version</a></th><th><a href='index.php?sort=zipfile&order=$order&clicked=yes' >Zip File Name</a></th><th><a href='index.php?sort=dev_date&order=$order&clicked=yes' >Dev Release Date</a></th><th><a href='index.php?sort=prod_date&order=$order&clicked=yes' >Prod Install Date</a></th><th>Modify</th><th class='last'>Delete</th></tr>";

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

}
echo "</table>";
echo "<label id='Hide_me' style='visibility:hidden;'><input type='submit' id='Submit' name='Modify' alt='Modify' value='Modify' style='border: 0px solid #FFFFFF; background-color:#FFFFFF;background-image: url(img/bg-orange-button.gif); height: 35px; width: 75px;text-align:center;color:#fff;text-transform:uppercase;font-weight:bold;line-height:27px;' /><input type='submit' id='Submit' name='Delete' alt='Delete' value='Delete' style='border: 0px solid #FFFFFF; background-color:#FFFFFF;background-image: url(img/bg-orange-button.gif); height: 35px; width: 75px;text-align:center;color:#fff;text-transform:uppercase;font-weight:bold;line-height:27px;' /></label>";
echo "</form>";
echo "<div class='selectpage'>";
echo $pagination;
echo "</div>";
echo "<p>&nbsp;</p>";
echo "</div>";
echo "</div>";
echo "</div>";

mysql_close();
return($num_rows);
}// End Function to print all the releases on the home page

?>