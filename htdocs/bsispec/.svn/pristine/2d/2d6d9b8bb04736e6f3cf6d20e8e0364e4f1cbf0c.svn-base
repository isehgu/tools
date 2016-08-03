<?php
	include("include/precise.php");
	$localdb=db_connect("local");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>BSISpec - AMR Adapter Information</title>
	<style type="text/css">
		body { margin:10; font-family:helvetica; font-size:12px; }
		th { font-weight:bold; text-align:left; border-bottom:1px #000 solid; }
		td { border-right:1px #777 solid;border-bottom:1px #777 solid; }
			tr.evenrow { background-color:#EEF; }
			td.separator { border-top:#000 solid 2px; }
		h2 { margin-bottom:10px; }
		a { text-decoration: none; color:#000;}
		a:hover { text-decoration: underline; }
		table { margin-top:10px; }
	</style>
</head>
<body>
<h2>Precise AMR Configuration</h2>
<a href="index-precise.php">Go back to Adapter View</a>
<table cellspacing='0' cellpadding='2' id="listviewtable">
<tr><th width="120">Member</th><th width="100">IRD</th><th width="90">CMTA</th><th width="100">Account</th><th width="110">Default CMTA Flag</th></tr>
<?php
	$i=0;
	$curamr="";
	$sameamr=true;
	$sql="SELECT member, ird, cmta, account, default_cmta_flag FROM precise_amr ORDER BY member, ird, default_cmta_flag DESC, cmta;";
	$result=mysql_query($sql);
	while ($row = mysql_fetch_array($result)){ 
		if ($curamr!=$row['member']) $sameamr=false; else $sameamr=true;
		$curamr=$row['member'];
	?>
			<tr class="<?php if ($i++%2!=0) echo "oddrow"; else echo "evenrow"; ?>">
				<td <?php if (!$sameamr) echo "class='separator'"; ?>><?php echo $row['member']; ?></td>
				<td <?php if (!$sameamr) echo "class='separator'"; ?>><?php echo $row['ird']; ?></td>
				<td <?php if (!$sameamr) echo "class='separator'"; ?>><?php echo $row['cmta']; ?></td>
				<td <?php if (!$sameamr) echo "class='separator'"; ?>><?php echo $row['account']; ?></td>
				<td <?php if (!$sameamr) echo "class='separator'"; ?>><?php echo $row['default_cmta_flag']; ?></td>
			</tr>
		<?php 
	}
?>
</table>

<p><br/></p>

</body></html>